# Seehafen — Plugin-Based WordPress Build (Plan)

**Status:** Draft for approval · **Owner:** Night-Mule-9000 · **Date:** 2026-08-07

## Goal
Rebuild the Seehafen & Partner Immobilien AG site (source: `cng13m/seehafen-2` SPA) in WordPress **using plugins as the primary build method** — minimal custom code. Same 1:1 design/content/animation requirements as the earlier build, but plugin-first.

## Why this approach
Previous build was custom-code-first (custom theme + CPT plugin + AJAX handlers). Plugin-first = client can self-manage everything visually, faster rebuild, less code to own. Trade-off accepted: design fidelity must be enforced via CSS, not code templates.

## Plugin stack (all free unless noted)
| Role | Plugin |
|---|---|
| Content types (services/references/offers/team) | **Custom Post Type UI** (CPT UI) |
| Custom fields | **ACF** (free) |
| Page composition / design blocks | **Gutenberg + GenerateBlocks** (free block plugin — seedable, keeps design via CSS) |
| SEO | **Rank Math** |
| Contact form | **Contact Form 7** |
| Media/animations | Core blocks + GenerateBlocks + theme CSS (SPA main.css verbatim) |

> Note: Elementor was considered; it's GUI-drag based (heavy to automate, DB-bound design, paid tier for theme builder). GenerateBlocks + Gutenberg keeps content seedable/versionable while still being a plugin-driven build.

## Minimal custom code (deliberately small)
- Child theme `seehafen` of twentytwentyfive: `style.css`, `functions.php` (enqueue SPA CSS + block styles), `assets/css/main.css` (verbatim from SPA) — WPCS compliant
- **No** custom CPT code, **no** custom AJAX, **no** custom form handler — all plugin territory

## Content scope (1:1 from SPA)
- 14 routes/pages, 8 services, 28 references, 3 offers, 3 team members
- Home hero, carousel, load-more references, contact form, legal pages
- All images migrated 1:1 from SPA assets

## Deliverables
1. Local working build (podman: mysql:8.4 + WP 8.3, port 8080)
2. All plugins installed/configured via wp-cli
3. Pages composed with blocks, content imported 1:1
4. All routes 200, screenshot diff vs live SPA
5. Pushed to GitHub `manshu07/seehafen-wp` (plugin-build branch or v2)

## Verification gates
- All 14 routes green, zero PHP notices
- Design/animation 1:1 (screenshot compare)
- Contact form validated (CF7)
- phpcs on the small custom code (WPCS)

## Open questions for approval
1. **Scope confirm:** rebuild **Seehafen** (same client) with plugins — correct?
2. **Page builder choice:** OK with Gutenberg + GenerateBlocks (vs Elementor GUI)?
3. **Repo:** reuse `manshu07/seehafen-wp` (old code archived) — OK?
