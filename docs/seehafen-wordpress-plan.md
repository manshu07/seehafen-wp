# Seehafen-2 → WordPress Migration Plan

**Date:** 2026-08-05
**Author:** Night-Mule-9000 🌙🐴💤
**Source repo:** github.com/cng13m/seehafen-2 (React/Vite SPA)
**Live URL:** https://seehafen-2.shefkiu-genc.workers.dev
**Target:** 100% dynamic WordPress site, WordPress Coding Standards (mandatory per Himanshu)

---

## 1. What exists today (site inventory)

React SPA, single `main.jsx` (1261 lines), German (de_CH), Swiss real-estate company "Seehafen & Partner Immobilien AG". Deployed on Cloudflare Workers.

### Routes (14)
| Route | Page | Content |
|---|---|---|
| `/` | Home | Hero, 4 service cards, offers carousel (3), 3 sold references, CTA strip |
| `/firma` | Firma | Über uns, Team (3), Werte (4) + Prozess (4) — anchors #uber-uns #team #werte |
| `/dienstleistungen` | Services overview | 4 primary cards + 4 additional service blocks + process + CTA |
| `/dienstleistungen/immobilienverkauf` | Service detail | heading/copy/points, CTA |
| `/dienstleistungen/immobilienbewertung` | Service detail | same pattern |
| `/dienstleistungen/stockwerkeigentum` | Service detail | same pattern |
| `/dienstleistungen/mietliegenschaften` | Service detail | same pattern |
| `/angebote` | Offers overview | 2 cards (Homegate link, Referenzen) |
| `/referenzen` | References | 28 tiles (6 verkauft, 14 vermietet, 8 verwaltung), "Mehr anzeigen" |
| `/kontakt` | Contact | info panel, 2 locations, form (name/email/phone/subject/message/honeypot/consent) |
| `/impressum` | Legal | company info |
| `/datenschutz` | Privacy | DSG content |
| `/agb` | Terms | 7 clauses |
| 404 | Not found | German 404 |

### Data entities (currently hardcoded in JS)
- **Services:** 8 total — 4 primary (with detail pages) + 4 additional
- **Offers:** 3 showcase items (title, image, location, price, rooms, area)
- **References:** 28 (title, location, type, detail, image)
- **Team:** 3 members (initials, name, role, bio)
- **Process:** 4 steps
- **Values:** 4
- **Nav:** 3 dropdown groups (Über uns, Dienstleistungen, Angebote)
- **Contact:** 2 phones, email, 2 addresses, opening hours
- **SEO:** per-route title/description, og tags, canonical → https://seehafen-immobilien.ch
- **Redirect:** `/immobilien` → Homegate profile (301)

### Features
- Contact form POST `/api/contact` → Cloudflare Email Workers → info@seehafen-immobilien.ch
- Scroll-reveal animations (respects prefers-reduced-motion)
- Mobile hamburger menu, dropdown nav with ARIA
- External links: Homegate profile https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag

### Design tokens
- Colors: ink `#071f42`, gold `#0d7fe8`, gold-soft `#93d0ff`, cream `#f4f7fb`, muted `#56677d`, line `#dbe4ee`
- Font: Helvetica Neue / Arial (system stack)
- Content width 1300px, radius 4/7px

### Assets (public/assets)
- Logos, heroes, 3 offer images (.avif), ~28 reference images (jpg/png) → all go to WP media library

---

## 2. WordPress architecture proposal

**Approach: Classic custom theme** (best match for pixel-perfect replication of existing design + full WP best practices) with **CPTs in a small custom plugin** (best practice: content types in plugin, presentation in theme).

### Components
| Piece | Solution |
|---|---|
| Theme | `seehafen` custom classic theme (template hierarchy: header/footer/index/page/single/single-{cpt}/archive/404/search) |
| Custom post types (plugin) | `service` (8, taxonomy `service_type` primary/additional), `reference` (28, taxonomy `reference_type` sold/rented/management), `offer` (3), `team_member` (3) |
| Pages | 9 WP pages (Start, Firma, Dienstleistungen, 4× service can be CPT singles, Angebote, Referenzen, Kontakt, Impressum, Datenschutz, AGB) |
| Menus | WP nav menus (3 dropdowns) |
| Editable site settings | Customizer: phones, email, addresses, opening hours, Homegate URL, footer text, hero content |
| Contact form | Custom lightweight handler (wp_mail + nonces + honeypot + sanitization) — no plugin bloat; SMTP via wp_mail filter |
| SEO | Per-page/CPT meta (custom fields) — title/description/canonical/og — or Rank Math if preferred |
| Animations | Small vanilla JS scroll-reveal (respects prefers-reduced-motion) |
| Images | WP media library (import all ~35 assets) |

### 100% dynamic mapping (nothing hardcoded)
| Hardcoded today | Becomes |
|---|---|
| Services in JS array | `service` CPT + `service_type` taxonomy, editable in admin |
| References in JS array | `reference` CPT + `reference_type` taxonomy |
| Offers in JS array | `offer` CPT |
| Team in JS array | `team_member` CPT |
| Process/values | Options/Customizer or ACF repeater fields |
| Phone/email/address/hours | Customizer settings |
| Nav labels/links | WP Menus |
| Legal pages | WP pages with editor content |
| SEO titles | Per-post meta fields |

---

## 3. WordPress Coding Standards checklist (mandatory)

- PHP: Yoda conditions, `elseif`, long array syntax, tabs, one class/file, `$wpdb->prepare()`, escaping on output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`)
- Sanitize every input: `sanitize_text_field`, `absint`, `sanitize_email`, `sanitize_textarea_field`
- Nonces on all forms, `current_user_can()` capability checks
- Proper enqueueing: `wp_enqueue_style/script` in `wp_enqueue_scripts`, version + `defer` for JS
- Text domain `seehafen` on all `__()`/`_e()` strings (translation-ready, site is de_CH)
- JS: const/let, single quotes, semicolons, `===`, no ASI
- CSS: tabs, lowercase hyphens, hex colors, grouped property order, no magic numbers
- HTML: quoted attributes, lowercase, proper self-closing
- Accessibility: WCAG 2.2 AA — skip link, ARIA on nav/dropdowns, keyboard support, alt text, form labels
- Verification: `phpcs --standard=WordPress` before "done"

---

## 4. Execution phases

### Phase 0 — Environment (in progress)
- [x] MySQL 8.4 + WordPress PHP 8.3 images pulled (podman, rootless)
- [ ] Spin up stack, install WP, wp-cli
- [ ] Create DB, install WP core, set up admin

### Phase 1 — Foundation (repo arrives)
- [ ] Create `seehafen` theme skeleton (style.css, functions.php, template hierarchy)
- [ ] Create `seehafen-cpt` plugin (CPTs + taxonomies + meta)
- [ ] Import all assets to media library
- [ ] Register menus, sidebars/widgets

### Phase 2 — Content & templates
- [ ] Create 9 pages + seed content (migrated from JS)
- [ ] Home template (hero, services, offers carousel, references, CTA)
- [ ] Firma template (about, team, values, process)
- [ ] Services overview + single-service templates
- [ ] Angebote + Referenzen templates (28 tiles, load-more)
- [ ] Kontakt template + form handler (wp_mail, nonce, honeypot)
- [ ] Legal templates (Impressum/Datenschutz/AGB)
- [ ] 404, search, header/footer with dynamic menus + Customizer values
- [ ] Redirect `/immobilien` → Homegate

### Phase 3 — Best practices & polish
- [ ] SEO meta per page (title/description/canonical/og)
- [ ] Scroll-reveal JS (reduced-motion aware)
- [ ] phpcs WordPress-standard pass → fix all violations
- [ ] Accessibility pass (WCAG AA)
- [ ] Translation-ready audit (text domain everywhere)

### Phase 4 — Verify & deliver
- [ ] Local smoke test: all 14 routes, form submit, menus, images
- [ ] Screenshot comparison vs live site
- [ ] Push theme + plugin to repo, report to Himanshu

---

## 5. What I need from you before starting

1. **Production hosting target** — where will WP run in production? (new PHP host / existing VPS 172.81.57.2 / same domain seehafen-immobilien.ch?) Only affects later phases; local dev proceeds regardless.
2. **Email for contact form** — is `info@seehafen-immobilien.ch` OK as recipient, and is there SMTP available, or use wp_mail (server mail)?
3. **New repo for WP code** — theme + plugin repo location: under `manshu07`, `cng13m`, or new org? (client's repo is cng13m/seehafen-2 — I won't pollute it)
4. **Theme approach sign-off** — classic custom theme (my recommendation, exact design match) vs block/FSE theme. Say "go" and I proceed.
5. **SEO plugin** — manual meta fields (lightweight) vs Rank Math? (my rec: manual, keeps it lean — you care about lightweight)
6. **Content changes?** — migrate content 1:1 from current site, or any copy/pages to add/remove?
7. **Multilingual?** — site is German only today; confirm no second language needed (theme will be translation-ready anyway).
8. **Who is cng13m** — client or your account? (affects repo/push decisions)

**Minimum to start Phase 0/1 right now: nothing — I can build the full local WP with content migrated 1:1. Items 3–5 decide repo/plugin choices early.**

---

*Prepared by Night-Mule-9000 — next step: your answers → spin up local WP → build.*
