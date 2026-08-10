# Elementor Guide — Adding & Copying Pages (no code needed)

This is what YOU (or the client) can do from the WordPress admin, **without writing any code**.

---

## 1. Copy / duplicate a whole page

**Method A — Duplicate in the editor (recommended):**
1. Pages → open the page → **Edit with Elementor**
2. Top bar → **≡ (hamburger menu)** → **Duplicate** (copies the entire page structure)
3. Pages list → open the copy → rename it

**Method B — Save as Template (reuse a page layout later):**
1. In the Elementor editor → **≡ → Save as Template** (name it, e.g. "Legal page layout")
2. Any page → **Add Template** → insert it → it appears with the same sections

> A duplicated page keeps all its sections — the hero, offers, references, form, everything — because
> the sections are saved inside the page itself.

---

## 2. Add new content (no Elementor needed)

The site is data-driven — new items appear on the right pages automatically:

| I want to add… | Go to | It appears on |
|---|---|---|
| A new **reference** (sold/rented property) | References → Add | `/referenzen/` + home preview |
| A new **offer** (rental) | Offers → Add | Home showcase + `/angebote/` |
| A new **service** | Services → Add | `/dienstleistungen/` |
| A new **team member** | Team Members → Add | `/firma/` |
| A **news/post** | Posts → Add | Standard blog |

Fill the fields (title, image, location, price…) → Publish → done. No Elementor, no code.

---

## 3. Add/edit normal content on a page (Elementor widgets)

In **Edit with Elementor**, you can drag any normal widget onto the canvas:
- **Heading**, **Text Editor** (paragraphs), **Image**, **Button**, **Divider**, **Spacer**, **Icon**, **Google Maps**, **Tabs**, **Accordion**, **Icon Box**, **Image Carousel**, **Video** …

These work exactly like any Elementor site — no code. **But note:** the special design sections below
are NOT built from these widgets (they're rendered from theme shortcodes so they match the reference design 1:1).

---

## 4. Insert a special design section anywhere (copy-paste a shortcode — still not "code")

The design sections (hero, offers, references grid…) are inserted with the **Shortcode widget** or **HTML widget**
(Elementor's own widgets — drag it in, paste one line, done):

| Section | Shortcode to paste |
|---|---|
| Home hero (big title + image) | `[seehafen_home_hero]` |
| Intro + 4 service cards | `[seehafen_home_intro]` |
| Offers carousel (3 offers) | `[seehafen_offers_showcase]` |
| References grid (home: 3 tiles) | `[seehafen_references limit="3" preview="true"]` |
| References grid (full: 9 + "Mehr anzeigen") | `[seehafen_references limit="9" show_more="true"]` |
| CTA strip (blue "Jetzt kontaktieren" band) | `[seehafen_cta]` |
| Page hero (title banner with image) | `[seehafen_page_hero label="Rechtliches"]` |
| Firma — Über uns | `[seehafen_company_about]` |
| Firma — Team grid | `[seehafen_team]` |
| Firma — Werte & Prozess | `[seehafen_values]` |
| Dienstleistungen — 4 primary cards | `[seehafen_primary_services]` |
| Dienstleistungen — 4 extra blocks | `[seehafen_secondary_services]` |
| Dienstleistungen — process strip | `[seehafen_process compact="true"]` |
| Angebote — overview cards | `[seehafen_overview_links]` |
| Kontakt — info + form | `[seehafen_contact_intro]` + `[seehafen_contact_form]` |

**How to paste one:**
1. Edit with Elementor the page you want
2. Drag the **Shortcode** widget (under General) onto the canvas
3. Paste e.g. `[seehafen_cta]` into the field
4. Update → the blue CTA band appears, styled exactly like the reference

---

## 5. What still needs me (or a developer) — honest list

- **Changing the DESIGN** (colors, fonts, spacing) — that's the theme CSS (`main.css`). Elementor's global
  styles are intentionally turned off so the site matches the reference. Ask me for design changes.
- **Changing the TEXT inside the special sections** — e.g. the hero heading or a service card text lives in
  the theme options (WP admin → options) or the shortcodes. Ask me; it's a 2-minute change.
- **New design sections** (a new layout the reference doesn't have) — I build those once, then you can
  reuse them with a shortcode forever.

---

## TL;DR

- **Copy a page:** ✅ Elementor Duplicate / Save as Template
- **Add content** (reference/offer/service/team/post): ✅ admin menus, no code
- **Add normal blocks** (heading/image/button/text…): ✅ drag & drop
- **Add a special design section:** ✅ paste one shortcode line into the Shortcode widget
- **Change the design / section texts:** → ask me (theme-level, keeps the 1:1 match)
