# Seehafen & Partner Immobilien AG — WordPress (Plugin Build v2)

WordPress build of the Seehafen & Partner Immobilien AG site — **plugin-first approach**. Design + behavior match the reference React SPA 1:1 (page-by-page, pixel-measured).

- Reference SPA (what the site should look like): https://seehafen-2.shefkiu-genc.workers.dev
- Source SPA code (needed for images): https://github.com/cng13m/seehafen-2

## Stack
- **WordPress** + child theme `seehafen` (parent: twentytwentyfive)
- **Page/content:** Elementor (page composition), Custom Post Type UI (CPTs), ACF (fields), Rank Math (SEO), Contact Form 7 (contact)
- **Business care (360°):**
  - **FluentSMTP** — reliable form mail delivery (needs real SMTP credentials in production)
  - **Flamingo** — stores every CF7 form submission in the DB (never lose an inquiry)
  - **UpdraftPlus** — scheduled offsite backups (weekly, keep 4; add Google Drive/Dropbox in production)
  - **All-in-One WP Security** — firewall + hardening (XML-RPC off)
  - **Complianz** — Swiss/GDPR cookie consent banner (country=CH)
  - **WP Super Cache** — page caching (enabled)
  - **Redirection** — free URL management
  - **Limit Login Attempts** — brute-force protection (4 attempts / 20 min lockout)
- Theme carries the SPA design CSS verbatim (`assets/css/main.css`) + ported SPA JS (`assets/js/main.js` — menu, dropdowns, scroll-reveal, offer carousel, load-more)
- Shortcodes in `inc/shortcodes.php` render each section with the SPA's exact markup, driven by WP data (CPTs/ACF/options)

---

## Prerequisites (install once)

```bash
# 1. Podman (container engine — works like Docker, no root needed on Fedora)
sudo dnf install podman          # Fedora
#   or:  brew install podman      # macOS

# 2. Git
sudo dnf install git

# 3. Clone this repo AND the source SPA (for the images)
git clone https://github.com/manshu07/seehafen-wp.git
git clone https://github.com/cng13m/seehafen-2.git
cd seehafen-wp
```

---

## Step 1 — Start WordPress + MySQL containers

```bash
podman network create wpnet 2>/dev/null; true

podman run -d --name seehafen-db --network wpnet -p 127.0.0.1:3307:3306 \
  -e MYSQL_ROOT_PASSWORD=rootpw2026 -e MYSQL_DATABASE=seehafen \
  -e MYSQL_USER=wp -e MYSQL_PASSWORD=wppass2026 \
  -v ~/wordpress-dev/mysql-data:/var/lib/mysql:Z \
  docker.io/library/mysql:8.4

podman run -d --name seehafen-wp --network wpnet -p 127.0.0.1:8080:80 \
  -e WORDPRESS_DB_HOST=seehafen-db:3306 -e WORDPRESS_DB_USER=wp \
  -e WORDPRESS_DB_PASSWORD=wppass2026 -e WORDPRESS_DB_NAME=seehafen \
  -v ~/wordpress-dev/wp-content:/var/www/html/wp-content:Z \
  docker.io/library/wordpress:php8.3-apache
```

> The WordPress core (wp-admin, wp-includes, etc.) comes **inside the container image** — you don't need to download it.
> The `wp-content` folder is mounted from the host so your theme files are editable from the repo.

**Verify containers are running:**
```bash
podman ps --format "table {{.Names}}\t{{.Status}}"
# seehafen-db  Up ...   and   seehafen-wp  Up ...
```

---

## Step 2 — Copy this repo's theme into the site

```bash
# Copy the theme from the repo into the mounted wp-content
mkdir -p ~/wordpress-dev/wp-content/themes
cp -r themes/seehafen ~/wordpress-dev/wp-content/themes/
chmod -R a+rX ~/wordpress-dev/wp-content/themes/seehafen
```

---

## Step 3 — Install WordPress core (one time)

```bash
podman exec seehafen-wp bash -lc '
  wp core install \
    --url=http://localhost:8080 \
    --title="Seehafen & Partner Immobilien AG" \
    --admin_user=admin \
    --admin_password=Admin2026! \
    --admin_email=admin@seehafen.local \
    --skip-email --allow-root
'
```

**Expected output:** `Success: WordPress installed successfully.`

---

## Step 4 — Activate theme + set pretty URLs

```bash
podman exec seehafen-wp bash -lc '
  wp theme activate seehafen --allow-root &&
  wp rewrite structure "/%postname%/" --hard --allow-root &&
  wp option update blogdescription "Persönliche Immobiliendienstleistungen mit Weitblick" --allow-root
'
```

**Expected:** `Success: Switched to 'seehafen' theme.` and `Success: Rewrite structure set.`

---

## Step 5 — Install + activate plugins

```bash
podman exec seehafen-wp bash -lc '
  wp plugin install elementor custom-post-type-ui advanced-custom-fields seo-by-rank-math contact-form-7 --activate --allow-root &&
  wp plugin install fluent-smtp flamingo updraftplus all-in-one-wp-security-and-firewall complianz-gdpr wp-super-cache redirection limit-login-attempts-reloaded --activate --allow-root
'
```

**Expected:** `Success: Installed 13 of 13 plugins.`

**Post-install config (one time):**
```bash
podman exec seehafen-wp bash -lc '
  # WP Super Cache on
  sed -i "s/\\\$cache_enabled = false;/\\\$cache_enabled = true;/" /var/www/html/wp-content/wp-cache-config.php
  # UpdraftPlus: weekly, keep 4
  wp option update updraft_interval weekly --allow-root &&
  wp option update updraft_interval_database weekly --allow-root &&
  wp option update updraft_retain 4 --allow-root
  # Complianz: Switzerland
  wp option update complianz_options_settings "{\"country\":\"CH\",\"language\":\"de\"}" --format=json --allow-root
  # Limit Login: 4 attempts / 20 min
  wp option update limit_login_retries 4 --allow-root &&
  wp option update limit_login_lockout_duration 1200 --allow-root
  # All-in-One: firewall + XML-RPC off
  wp option update aiowps_enable_firewall 1 --allow-root &&
  wp option update aiowps_disable_xmlrpc 1 --allow-root
'
```

> **Production to-dos:** add real SMTP credentials in FluentSMTP (FluentSMTP → Settings), connect Google Drive/Dropbox in UpdraftPlus, and run the Complianz wizard once on the live domain.

---

## Step 6 — Copy the setup scripts into the container

```bash
podman unshare chmod -R a+rX ~/wordpress-dev/wp-content 2>/dev/null
cp setup/*.php setup/*.js setup/*.json ~/wordpress-dev/wp-content/ 2>/dev/null
```

(The setup scripts run from inside the container at `/var/www/html/wp-content/`.)

---

## Step 7 — Run the seed scripts (in this order!)

```bash
# 7a. Create content types (CPTs) + fields + options
podman exec seehafen-wp bash -lc '
  wp eval-file /var/www/html/wp-content/seed-cptui.php --allow-root &&
  wp eval-file /var/www/html/wp-content/seed-acf.php --allow-root &&
  wp eval-file /var/www/html/wp-content/seed-options.php --allow-root
'

# 7b. Stage the SPA images (from the source repo clone)
mkdir -p ~/wordpress-dev/wp-content/assets-import
cp ../seehafen-2/public/assets/* ~/wordpress-dev/wp-content/assets-import/
podman unshare chmod -R a+rX ~/wordpress-dev/wp-content/assets-import

# 7c. Import pages, services, references, offers, team, menu + CF7 form
podman exec seehafen-wp bash -lc '
  cp /var/www/html/wp-content/seed-data.json /tmp/seed-data.json &&
  wp eval-file /var/www/html/wp-content/seed-content.php --allow-root
'
# Expected: "Pages: 9 | Services: 4 | Refs: 28 | Offers: 3 | Team: 3 | Menu: <id>"

# 7d. Build the Elementor page data
podman exec seehafen-wp bash -lc 'wp eval-file /var/www/html/wp-content/seed-elementor-v2.php --allow-root'
# Expected: "Built v2 Elementor pages: home, firma, ..."

# 7e. Menu hierarchy + German contact form
podman exec seehafen-wp bash -lc '
  wp eval-file /var/www/html/wp-content/fix-menu.php --allow-root &&
  wp eval-file /var/www/html/wp-content/fix-menu2.php --allow-root &&
  wp eval-file /var/www/html/wp-content/fix-cf7.php --allow-root
'
```

---

## Step 8 — Clear Elementor caches (required after seeding)

```bash
podman exec seehafen-wp bash -lc '
  wp elementor flush-css --allow-root &&
  wp cache flush --allow-root &&
  wp transient delete --all --allow-root
'
```

---

## Step 9 — Verify it worked

```bash
# All 10 routes must return 200
for page in "" "firma/" "dienstleistungen/" "dienstleistungen/immobilienverkauf/" \
            "angebote/" "referenzen/" "kontakt/" "impressum/" "datenschutz/" "agb/"; do
  echo -n "/$page -> "
  curl -s -o /dev/null -w "%{http_code}\n" "http://localhost:8080/$page"
done

# WP admin works
# http://localhost:8080/wp-admin   (admin / Admin2026!)
```

**Checklist for the final look:**
- [ ] Header: logo, 3 dropdowns (Über uns / Dienstleistungen / Angebote), blue "Kostenlose Bewertung" button
- [ ] Homepage: hero, 4 service cards, offers showcase, 3 reference tiles, CTA strip
- [ ] Mobile (<900px): gold hamburger opens full-screen menu with Menu⇄X swap
- [ ] Kontakt: German form (Name*, E-Mail*, Telefon, Thema select, Nachricht*, consent checkbox, "Nachricht senden →")
- [ ] Footer: 3 columns + icons in "Direkter Kontakt"

---

## Troubleshooting

| Symptom | Fix |
|---|---|
| Blank/white page | `podman unshare chmod -R a+rX ~/wordpress-dev/wp-content/themes/seehafen` (host files are 0600 by default) |
| Elementor not rendering styles | Re-run Step 8 (cache flush) |
| Images missing | Check `~/wordpress-dev/wp-content/assets-import/` has the SPA assets, re-run Step 7b+7c |
| Port 8080 busy | Change `-p 127.0.0.1:8080:80` to another port and use it in the URLs |

## Content
- 9 pages, 4 services (detail pages at `/dienstleistungen/{slug}`), 28 references, 3 offers, 3 team members
- All copy 1:1 from source SPA (`cng13m/seehafen-2`)

## Fidelity verification (2026-08-08)
- **Header/menu behavior** (dropdown hover/click, mobile full-screen menu, Menu⇄X swap, Escape, scroll lock): matches at desktop/tablet/mobile — pixel diff ≤1.3%
- **Hover/focus/link states** across all 20 components × 10 pages × 3 viewports: match
- **Page diffs** (full-page pixel compare, reveal-settled): home 0-11%, referenzen ≤3.4%, kontakt 6.8% desktop, firma 15.5% tablet / 19.9% mobile, legal pages desktop 0%
- Key gotchas handled: Elementor global CSS flattening (img heights, fonts), CF7's single-`<p>` form markup, baked `do_shortcode` in Elementor HTML widgets (re-seed after shortcode changes)
