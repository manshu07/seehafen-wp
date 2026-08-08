# Seehafen & Partner Immobilien AG — WordPress (Plugin Build v2)

WordPress build of the Seehafen & Partner Immobilien AG site — **plugin-first approach** (v2, replaces the v1 custom-code build).

## Stack
- **WordPress** + child theme `seehafen` (parent: twentytwentyfive)
- **Plugins:** Elementor (page composition), Custom Post Type UI (CPTs), ACF (fields), Rank Math (SEO), Contact Form 7 (contact)
- Theme carries the original SPA design CSS verbatim (`assets/css/main.css`)

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
1. `wp core install` (as above) + activate theme + install/activate plugins:
   `wp plugin install elementor custom-post-type-ui advanced-custom-fields seo-by-rank-math contact-form-7 --activate`
2. `setup/seed-cptui.php` — creates CPTs (service, reference, offer, team_member) via CPT UI option
3. `setup/seed-acf.php` — creates ACF field groups
4. Stage assets: copy SPA `public/assets/` → `wp-content/assets/` (JSON paths are `/assets/...`)
5. `setup/seed-data.json` → `/tmp/seed-data.json` in container
6. `setup/seed-content.php` — imports pages, services, references, offers, team, menu, CF7 form
7. `setup/seed-elementor.php` — builds Elementor page data for all pages
8. `wp elementor flush-css && wp cache flush` — clear Elementor caches after seeding
9. `setup/fix-menu.php` — menu hierarchy (dropdowns); `setup/fix-cf7.php` — German CF7 form

## Content
- 9 pages, 4 services (detail pages at `/dienstleistungen/{slug}`), 28 references, 3 offers, 3 team members
- All copy 1:1 from source SPA (`cng13m/seehafen-2`)

## Verify
- All routes: `/`, `/firma/`, `/dienstleistungen/`, `/dienstleistungen/immobilienverkauf/`, `/angebote/`, `/referenzen/`, `/kontakt/`, `/impressum/`, `/datenschutz/`, `/agb/`
- Contact form: German messages, Thema select, consent checkbox
