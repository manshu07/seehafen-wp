# Seehafen & Partner Immobilien AG — WordPress (Plugin Build v2)

WordPress build of the Seehafen & Partner Immobilien AG site — **plugin-first approach** (v2, replaces the v1 custom-code build). Design + behavior match the reference React SPA **1:1** (page-by-page, pixel-measured).

Reference SPA: https://seehafen-2.shefkiu-genc.workers.dev

## Stack
- **WordPress** + child theme `seehafen` (parent: twentytwentyfive)
- **Plugins:** Elementor (page composition), Custom Post Type UI (CPTs), ACF (fields), Rank Math (SEO), Contact Form 7 (contact)
- Theme carries the original SPA design CSS verbatim (`assets/css/main.css`) + ported SPA JS (`assets/js/main.js` — menu, dropdowns, scroll-reveal, offer carousel, load-more)
- Shortcodes in `inc/shortcodes.php` render each section with the SPA's exact markup, driven by WP data (CPTs/ACF/options)

## Local dev (podman, rootless)
```bash
podman network create wpnet
podman run -d --name seehafen-db --network wpnet -p 127.0.0.1:3307:3306 \
  -e MYSQL_ROOT_PASSWORD=rootpw2026 -e MYSQL_DATABASE=seehafen -e MYSQL_USER=wp -e MYSQL_PASSWORD=wppass2026 \
  -v ~/wordpress-dev/mysql-data:/var/lib/mysql:Z docker.io/library/mysql:8.4
podman run -d --name seehafen-wp --network wpnet -p 127.0.0.1:8080:80 \
  -e WORDPRESS_DB_HOST=seehafen-db:3306 -e WORDPRESS_DB_USER=wp -e WORDPRESS_DB_PASSWORD=wppass2026 \
  -e WORDPRESS_DB_NAME=seehafen \
  -v ~/wordpress-dev/wp-content:/var/www/html/wp-content:Z docker.io/library/wordpress:php8.3-apache
```
WP admin: `http://localhost:8080/wp-admin` (admin / Admin2026!)

## One-time setup (order matters)
1. `wp core install` + activate theme + install/activate plugins:
   `wp plugin install elementor custom-post-type-ui advanced-custom-fields seo-by-rank-math contact-form-7 --activate`
2. `setup/seed-cptui.php` — creates CPTs (service, reference, offer, team_member) via CPT UI option
3. `setup/seed-acf.php` — creates ACF field groups
4. `setup/seed-options.php` — stores additional services, values, process data in options
5. Stage assets: copy SPA `public/assets/` → `wp-content/assets/` (JSON paths are `/assets/...`)
6. `setup/seed-data.json` → `/tmp/seed-data.json` in container
7. `setup/seed-content.php` — imports pages, services, references, offers, team, menu, CF7 form
8. `setup/seed-elementor-v2.php` — builds Elementor page data (HTML widgets calling the theme shortcodes)
9. `wp elementor flush-css && wp cache flush` — clear Elementor caches after seeding
10. `setup/fix-menu.php` — menu hierarchy (dropdowns); `setup/fix-cf7.php` — German CF7 form

## Content
- 9 pages, 4 services (detail pages at `/dienstleistungen/{slug}`), 28 references, 3 offers, 3 team members
- All copy 1:1 from source SPA (`cng13m/seehafen-2`)

## Fidelity verification (2026-08-08)
- **Header/menu behavior** (dropdown hover/click, mobile full-screen menu, Menu⇄X swap, Escape, scroll lock): matches at desktop/tablet/mobile — pixel diff ≤1.3%
- **Hover/focus/link states** across all 20 components × 10 pages × 3 viewports: match (58/60 identical; 2 artifacts = no touch-hover in the reference itself)
- **Page diffs** (full-page pixel compare, reveal-settled): home 0-11%, referenzen ≤3.4%, kontakt 6.8% desktop, firma 15.5% tablet / 19.9% mobile, legal pages desktop 0%
- Key gotchas handled: Elementor global CSS flattening (img heights, fonts), CF7's single-`<p>` form markup, baked `do_shortcode` in Elementor HTML widgets (re-seed after shortcode changes)

## Verify
- All routes: `/`, `/firma/`, `/dienstleistungen/`, `/dienstleistungen/immobilienverkauf/`, `/angebote/`, `/referenzen/`, `/kontakt/`, `/impressum/`, `/datenschutz/`, `/agb/`
- Contact form: German messages, Thema select, consent checkbox
