# Seehafen WP — Status, Pending & Copy Gaps

**Date:** 2026-08-05 · **Status:** Core build complete, local verified

## ✅ Done

- Local stack: WordPress 8.3 + MySQL 8.4 (podman, rootless) — http://localhost:8080
- Theme `seehafen` as **child of twentytwentyfive** (WP default parent) — 1:1 design + animation port
- Plugin `seehafen-cpt`: service (8), reference (28), offer (3), team_member (3) + taxonomies + meta boxes
- 9 pages, menus (primary dropdowns + footer), Customizer site settings, Rank Math active, manual SEO meta
- Contact form (AJAX, nonce, honeypot, sanitization) → wp_mail
- All 13 routes 200, zero PHP notices, all content markers verified in browser
- JS verified: nav dropdowns, offer carousel (real avif), references load-more (9→18→28), contact validation
- `/immobilien` → 301 Homegate
- Repo: github.com/manshu07/seehafen-wp (`main`)

## ⏳ Pending

| # | Item | Why blocked / needed |
|---|---|---|
| 1 | **phpcs WordPress-standard scan** | Code written to WPCS line-by-line, but the automated gate couldn't run in the container (no unzip; WPCS download 404'd). Run `phpcs --standard=WordPress` on a PHP host or install phpcs+WPCS properly. |
| 2 | **Contact form email delivery** | wp_mail flow verified (validation + mail attempt), but no SMTP on local box. Production needs SMTP/transactional provider configured. |
| 3 | **Production deployment** | Hosting target not decided. Local only. Needs PHP host + DB + domain (seehafen-immobilien.ch?) + SMTP + Rank Math final config. |
| 4 | **Rank Math setup wizard** | Plugin active + per-page manual meta wired, but Rank Math's own setup/import not run yet. |
| 5 | **Pixel screenshot diff vs live site** | Verified structure + computed styles match; a full screenshot comparison against the live SPA is the final visual proof. |
| 6 | **Admin editing flow test** | Content editable in admin (CPT/meta boxes), but end-to-end "edit in admin → see on front" not manually exercised yet. |
| 7 | **Tidy dev scripts** | setup/*.php contain hardcoded `/var/www/html/...` container paths — fine for one-time seed, should be documented as such. |

## 🔍 Gaps found while copying (all fixed)

1. **Service URLs 404** — CPT rewrite rules weren't generated during migration → fixed with `wp rewrite flush`.
2. **`/immobilien` redirect wrong** — `redirect_canonical` (priority 10) hijacked the request first, and `wp_safe_redirect` blocked the external Homegate URL → fixed: handler at priority 5 + `wp_redirect`.
3. **Images imported 0** — two bugs: `wp eval-file` runs in **function scope** so `global $asset_base` was empty; and wp-admin includes (`image.php`, `file.php`, `media.php`) aren't loaded in CLI context → fixed with `define()` + explicit requires; re-imported all 44 assets.
4. **Reference image mapping wrong** — PHP array keys collide on duplicate titles (`3.5-Zimmer-Wohnung` ×4, `Wohnliegenschaft` ×6) → fixed by keying on `menu_order` (deterministic SPA order).
5. **Home card content gap** — the SPA uses *different* text+image on home cards vs service detail pages; initially mapped the wrong field → added `_seehafen_home_text` + `_seehafen_home_image` meta.
6. **Carousel markup contract** — SPA renders ONE stage (active offer), not N slides; first PHP build rendered all slides (would break CSS) → single stage + JSON data + JS swap.
7. **Blank homepage** — host-created files were 0600, container couldn't read → chmod 644.
8. **Podman + SELinux** — bind mounts need `:Z` suffix or containers get Permission denied (MySQL + WP both failed initially).
9. **Theme mods lost** — `set_theme_mod` ran before the theme was active → wrote to the OLD theme; menu locations + contact settings were empty → re-applied after activation.
10. **WP 6.7 textdomain notice** — meta box class called `__()` in constructor (plugins_loaded, too early) → lazy `get_fields()`.
11. **Child-theme transient fatal** — path refactor while the old child was active broke boot → recovered via direct DB update of `stylesheet`/`template` options.

---

*Night-Mule-9000 🌙🐴💤 — honest status, no sugar-coating.*
