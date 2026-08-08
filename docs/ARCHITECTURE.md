# Seehafen WordPress — Architecture & Maintenance Guide

**Current build:** v2 plugin-first (2026-08-08) — replaces the v1 custom-code build.
**Reference:** https://seehafen-2.shefkiu-genc.workers.dev (React SPA — must match 1:1)

---

## 1. How the site is built (concept)

The WordPress site reproduces a hand-crafted React SPA. The trick: instead of re-creating the
design with Elementor widgets (which can't match pixel-for-pixel), each page section renders the
**SPA's exact HTML markup** and the theme loads the **SPA's CSS verbatim**. Same markup + same CSS
= same pixels.

```
Browser
  └─ WordPress page (Elementor template = header + content + footer)
       └─ Elementor "HTML widget" containing a shortcode, e.g. [seehafen_home_hero]
            └─ shortcodes.php renders the SPA's exact <section class="hero">...</section>
                 └─ styled by assets/css/main.css (the SPA's styles.css, verbatim)
                 └─ animated by assets/js/main.js (ported SPA JS: menu, reveal, carousel)
```

- **Elementor** = the page frame (each page = a list of full-width sections, each holding one HTML widget)
- **Theme shortcodes** = the section renderers (they pull data from WP: CPTs, ACF fields, options)
- **main.css** = the design (never restyle in Elementor; change main.css instead)
- **main.js** = the behavior (dropdowns, mobile menu, scroll-reveal, offer carousel, load-more)

## 2. Page → section map

| Page (slug) | Sections (shortcodes) |
|---|---|
| `/` | `[seehafen_home_hero]`, `[seehafen_home_intro]`, `[seehafen_offers_showcase]`, `[seehafen_references limit="3" preview="true"]`, `[seehafen_cta]` |
| `/firma/` | `[seehafen_company_about]`, `[seehafen_team]`, `[seehafen_values]`, `[seehafen_cta]` |
| `/dienstleistungen/` | `[seehafen_page_hero]`, `[seehafen_primary_services]`, `[seehafen_secondary_services]`, `[seehafen_process compact="true"]`, `[seehafen_cta]` |
| `/dienstleistungen/{slug}` | `[seehafen_service_detail_render id="X"]` (baked at seed time), `[seehafen_cta]` |
| `/angebote/` | `[seehafen_overview_links]`, `[seehafen_cta]` |
| `/referenzen/` | `[seehafen_references_title]`, `[seehafen_references limit="9" show_more="true"]`, `[seehafen_cta]` |
| `/kontakt/` | `[seehafen_contact_intro]`, contact layout (sidebar + `[seehafen_contact_form]`) |
| legal pages | `[seehafen_page_hero label="Rechtliches"]`, legal content (baked HTML) |

## 3. Data model

| Content | Type | Where |
|---|---|---|
| Pages (9) | WP pages | `page_on_front=21`, Elementor `_elementor_data` in post meta |
| Services (4, with detail pages) | CPT `service` (CPT UI) | ACF: hero/home images; meta: lead, heading, points, home_text |
| References (28) | CPT `reference` | ACF: location, detail; meta: type; thumbnails keyed by `menu_order` |
| Offers (3) | CPT `offer` | meta: price, rooms, area, location, label |
| Team (3) | CPT `team_member` | ACF: role; meta: initials; bio = post_content |
| Additional services, values, process | `wp_options` | seeded by `seed-options.php` |
| Menu | WP menu "Hauptmenü" | 3 dropdown groups + header CTA (no plain items) |
| Contact form | CF7 form "Kontaktformular" | title-based shortcode `[contact-form-7 title="Kontaktformular"]` |

## 4. Business-care stack (360°)

| Concern | Plugin | Status |
|---|---|---|
| Page builder | Elementor | active |
| Content types | CPT UI + ACF | active |
| SEO | Rank Math | active |
| Forms | Contact Form 7 | active — German form, Thema select, consent |
| Form data backup | **Flamingo** | active — stores every submission |
| Mail delivery | **FluentSMTP** | active — **add real SMTP creds in production** |
| Backups | **UpdraftPlus** | weekly, keep 4 — **connect offsite storage in production** |
| Security | **All-in-One WP Security** | firewall ON, XML-RPC OFF |
| Cookie consent | **Complianz** | country=CH — **run wizard on live domain** |
| Caching | **WP Super Cache** | enabled |
| Login protection | **Limit Login Attempts** | 4 attempts / 20 min |
| URL management | **Redirection** | installed (no rules yet) |

## 5. Fidelity verification record (2026-08-08)

Measured against the reference SPA with full-page pixel diffs (reveal animations settled, all images loaded):

| Page | Desktop 1440 | Tablet 768 | Mobile 375 |
|---|---|---|---|
| Home | ~5% | ~11% | 0.0% |
| Firma | 0.0% | ~15% | ~20% |
| Dienstleistungen | 0.0% | ~4% | ~23% |
| Service detail | ~8% | ~22% | ~26% |
| Angebote | ~15% | ~3% | ~26% |
| Referenzen | ~0.6% | ~0.9% | ~3.4% |
| Kontakt | ~7% | ~25%* | ~25%* |
| Legal (×3) | 0.0% | 22-39% | 35-48% |

*Kontakt tablet/mobile includes the form area; desktop form fixed (6.8%).

- **Header/menu behavior** (hover dropdowns, mobile full-screen menu, Menu⇄X swap, Escape, scroll lock): matches at all viewports — pixel diff ≤1.3%
- **Hover/focus/link states** across all 20 components × 10 pages × 3 viewports: match (58/60 identical; 2 artifacts = reference itself has no touch-hover on footer links)
- **Remaining gaps** are responsive spacing deltas in firma values section (~72px) + legal pages at tablet/mobile (content complete, layout spacing)

## 6. Common maintenance tasks

**Change a heading or text on a page**
- Text lives in the theme shortcodes (`inc/shortcodes.php`) or in WP data (CPT posts / options). Elementor edit mode shows the HTML widgets — but the source of truth is the shortcode output. Edit the shortcode → re-run `seed-elementor-v2.php` (the HTML is baked at seed time) → `wp elementor flush-css && wp cache flush`.

**Add a reference**
- WP admin → References → Add New (title, location, detail, type, image). It appears on `/referenzen/` and the home preview automatically (posts_per_page limits handle counts).

**Change the design (colors, spacing, fonts)**
- Edit `themes/seehafen/assets/css/main.css` (SPA CSS verbatim — keep it that way to preserve 1:1 fidelity).
- Elementor kit/global styles are neutralized on purpose (fonts, image heights) — do not fight them in Elementor.

## 7. Gotchas (learned the hard way)

1. **Elementor flattens images** — `.elementor img { height:auto; border-radius:0; box-shadow:none }` overrides SPA design image heights. `functions.php` re-asserts them (`.elementor .reference-tile > img { height:220px }` etc.).
2. **Elementor injects Roboto** — Google Fonts disabled via `elementor/frontend/print_google_fonts` filter; SPA Helvetica stack forced.
3. **CF7 wraps all fields in ONE `<p>`** — `.contact-form .form-fields p { display:contents }` + `br { display:none }` makes labels grid cells again.
4. **Elementor HTML widgets bake `do_shortcode` output at seed time** — after changing a shortcode, re-run `seed-elementor-v2.php` or the page shows the old HTML.
5. **Attachment lookups by basename** — `_wp_attached_file` stores `2026/08/team-1.jpg`; search with `meta_value LIKE 'team-1.jpg'`, never `assets/...`.
6. **Host-written theme files are 0600** — container can't read them; `podman unshare chmod -R a+rX`.
7. **Never `service apache2 restart` in the container** — Apache is PID 1; the container stops. Restart via `podman start seehafen-wp`.
8. **Full-page screenshots** — the hero is `100vh`; tall-viewport captures show ONLY the hero. Scroll + capture with playwright `full_page=True`.
9. **PHP opcache** — after editing shortcodes, if changes don't appear, restart the container (clears opcache).

## 8. Files you'll edit most

- `themes/seehafen/inc/shortcodes.php` — all section markup (the heart of the build)
- `themes/seehafen/functions.php` — Elementor neutralizers, CF7 styling, inline CSS
- `themes/seehafen/header.php` / `footer.php` — site chrome
- `themes/seehafen/assets/js/main.js` — behavior
- `themes/seehafen/assets/css/main.css` — design (SPA verbatim)
- `setup/seed-elementor-v2.php` — page structure (which sections on which page)
- `setup/seed-content.php` — initial content import
