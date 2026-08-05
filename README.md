# Seehafen & Partner Immobilien AG — WordPress

WordPress migration of the Seehafen & Partner Immobilien AG website (seehafen-immobilien.ch), ported 1:1 from the original React SPA ([cng13m/seehafen-2](https://github.com/cng13m/seehafen-2)).

**Design and animations unchanged. All content is 100% dynamic** — editable from the WordPress admin.

## Stack

| Piece | Tech |
|---|---|
| Theme | `themes/seehafen` — custom **parent** theme (base design/framework), WP Coding Standards compliant |
| Child theme | `themes/seehafen-child` — development/customization layer on top of the parent; template overrides live here |
| Content types | `plugins/seehafen-cpt` — CPTs: service (8), reference (28), offer (3), team_member (3) + taxonomies |
| SEO | Rank Math plugin + manual per-page meta (title/description/canonical/OG) |
| Forms | Custom AJAX contact form → `wp_mail` (nonce + honeypot + sanitization) |
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
themes/seehafen/        parent theme (templates, inc/, assets/css/js/img)
themes/seehafen-child/  child theme — override layer; copy any parent template here to customize
plugins/seehafen-cpt/   CPT + taxonomy + meta box plugin
setup/                  one-time content seed scripts (1:1 SPA data)
docs/                   planning doc
```

## Child theme concept

- `seehafen` is the **parent** (design + framework). `seehafen-child` is the active **child**.
- WordPress checks the child theme's templates first — copy e.g. `front-page.php` from parent into the child to override it.
- Child `functions.php` loads **in addition to** the parent's — add custom hooks there.
- Child `style.css` loads after the parent stylesheet (`seehafen-main` dependency).
- Parent updates never clobber child customizations.

## WP Coding Standards

All PHP/JS/CSS/HTML follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/). Verify with PHP_CodeSniffer:

```bash
phpcs --standard=WordPress themes/seehafen plugins/seehafen-cpt
```

## Credits

Built by Night-Mule-9000 🌙🐴💤 for Seehafen & Partner Immobilien AG · German (de_CH) · © 2026
