# Seehafen & Partner Immobilien AG — WordPress

WordPress migration of the Seehafen & Partner Immobilien AG website (seehafen-immobilien.ch), ported 1:1 from the original React SPA ([cng13m/seehafen-2](https://github.com/cng13m/seehafen-2)).

**Design and animations unchanged. All content is 100% dynamic** — editable from the WordPress admin.

## Stack

| Piece | Tech |
|---|---|
| Theme | `themes/seehafen` — **child theme** of the WordPress default (`twentytwentyfive` parent). 1:1 site port, WP Coding Standards compliant |
| Content types | `plugins/seehafen-cpt` — CPTs: service (8), reference (28), offer (3), team_member (3) + taxonomies |
| SEO | Rank Math plugin + manual per-page meta (title/description/canonical/OG) |
| Forms | **Contact Form 7** (German messages, honeypot, consent acceptance, design-matched CSS) → mail |
| Menus | WP nav menus (primary dropdowns + footer) |
| Site settings | Appearance → Customize (contact info, addresses, hours, Homegate URL, hero, CTA, process, values) |
| Images | WP media library (all 44 assets imported) |
| Standards | WordPress Coding Standards (PHP/JS/CSS/HTML), WCAG 2.2 AA, translation-ready (de_CH) |

## Pages / routes

`/` (Start) · `/firma/` · `/dienstleistungen/` · `/dienstleistungen/{service}/` (×4) · `/angebote/` · `/referenzen/` · `/kontakt/` · `/impressum/` · `/datenschutz/` · `/agb/` · 404 · `/immobilien` → 301 Homegate

## Local setup (podman, rootless)

```bash
mkdir -p wp-content mysql-data && chmod 777 wp-content mysql-data

podman network create wpnet

podman run -d --name seehafen-db --network wpnet \
  -e MYSQL_ROOT_PASSWORD='seehafen_root_2026' \
  -e MYSQL_DATABASE=seehafen -e MYSQL_USER=wp -e MYSQL_PASSWORD='seehafen_wp_2026' \
  -p 3307:3306 -v ~/wordpress-dev/mysql-data:/var/lib/mysql:Z \
  docker.io/library/mysql:8.4

podman run -d --name seehafen-wp --network wpnet \
  -e WORDPRESS_DB_HOST=seehafen-db:3306 -e WORDPRESS_DB_USER=wp \
  -e WORDPRESS_DB_PASSWORD='seehafen_wp_2026' -e WORDPRESS_DB_NAME=seehafen \
  -p 8080:80 -v ~/wordpress-dev/wp-content:/var/www/html/wp-content:Z \
  docker.io/library/wordpress:php8.3-apache
```

Then install WP, activate `seehafen-cpt` + `seehafen` theme, run:

```bash
wp eval-file /var/www/html/wp-content/migrate-seehafen.php
wp rewrite flush
```

> Note: the setup scripts write theme mods + menu locations, so run them **after** the `seehafen` theme is active.

## Local admin

- URL: http://localhost:8080
- Admin: `admin` / `SeehafenAdmin2026!` (local dev only)

## Structure

```
themes/seehafen/        child theme of the WP default (twentytwentyfive) — all site templates/code
plugins/seehafen-cpt/   CPT + taxonomy + meta box plugin
setup/                  one-time content seed scripts (1:1 SPA data)
docs/                   planning doc
```

## Child theme concept

- **Parent = the WordPress default theme `twentytwentyfive`** (ships with every WP install, receives core updates).
- `seehafen` is the **child theme** — all site code lives here (`Template: twentytwentyfive` in style.css).
- Child templates are checked first; anything the child doesn't provide falls back to the parent.
- Child `functions.php` loads **in addition to** the parent's — parent updates never clobber the site.
- Theme asset paths use `get_stylesheet_directory_uri()` (child-relative).

## WP Coding Standards

All PHP/JS/CSS/HTML follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/). Verify with PHP_CodeSniffer:

```bash
phpcs --standard=WordPress themes/seehafen plugins/seehafen-cpt
```

## Credits

Built by Night-Mule-9000 🌙🐴💤 for Seehafen & Partner Immobilien AG · German (de_CH) · © 2026
