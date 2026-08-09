# Migration Playbook — Updating the Client's LIVE Site to the New Seehafen Design

**Goal:** take the client's existing hosted WordPress site and update it to the new design
(v2 plugin build, repo `manshu07/seehafen-wp`) **without losing content and without breaking the live site**.

**Golden rule: the live site stays untouched until the new build is fully tested on a staging copy.**
Every step below is reversible; the backup at step 1 is your rollback plan.

---

## Phase 0 — Discovery (what does the client's site actually have?)

Get these from the client / current hosting before touching anything:

| Question | Why it matters |
|---|---|
| Hosting provider + control panel access (FTP/SSH) | Where we upload |
| Domain + DNS provider | Where we switch traffic |
| WordPress version + active plugins | Compatibility (new build needs WP ≥ 6.0, PHP ≥ 8.1) |
| Content inventory: pages, posts, CPTs, media size | What must be preserved |
| Users / roles | Who should keep admin access |
| Current theme | If it's an old Seehafen site, we can copy some content |
| SSL cert setup (Let's Encrypt / Cloudflare?) | New site needs HTTPS |

---

## Phase 1 — Full backup (non-negotiable, before ANY change)

On the **live site**:

1. **Files:** download the whole site (public_html or www root) — or via SSH:
   ```bash
   tar czf ~/seehafen-live-files.tar.gz /var/www/html
   ```
2. **Database:** export everything:
   ```bash
   mysqldump -u USER -p DBNAME > seehafen-live-db.sql
   ```
   (or WP admin → Tools → Export → All content — but DB dump is the complete backup)
3. **Media:** wp-content/uploads (the images) — often the biggest piece.
4. Store all three **offsite** (Google Drive / Dropbox / GitHub private repo). Keep them until 1 month after the new site is live and stable.

---

## Phase 2 — Prepare the new build (already done ✅)

The new design is ready and verified locally:
- Repo: `github.com/manshu07/seehafen-wp` — theme + setup scripts + README (10 steps)
- Verified: matches the reference design (home diff 4.1%), content 10 pages / 4 services / 28 refs / 3 offers / 3 team / 39 images, zero broken images, German form working, 13 plugins incl. security/backup/cookie/SMTP
- Local test URL: `localhost:8080` (podman stack)

**Rebuild it on the server exactly per the README** (fresh WordPress + theme + plugins + seeds).

---

## Phase 3 — Choose the migration path

### Path A — Fresh WordPress + design, then bring client content in (RECOMMENDED ✅)
Cleanest separation: the design pages are built exactly as tested; the client's own content
is migrated into the new site.

1. Install fresh WordPress on the server (or a staging subdomain)
2. Follow the README steps 1–10 (theme, plugins, seeds) → new site ready with design content
3. **Migrate the client's content that is NOT part of the design:**
   - Blog/news posts → WP Tools → Export (old site) → Tools → Import (new site)
   - Extra pages (if any, with slugs not used by the design: home, firma, dienstleistungen, angebote, referenzen, kontakt, impressum, datenschutz, agb) → export/import or recreate
   - Media → copy `wp-content/uploads` from old to new
   - Users → recreate admins/editors (or WP import users plugin)
4. The **design pages replace** the client's old versions of those pages (that's the point of the redesign — the new structure + content is the new home/firma/etc.)

### Path B — In-place upgrade on the live site (faster, riskier ⚠️)
Apply the design directly to the existing site:

1. Back up (Phase 1) — **mandatory**, even more so here
2. Upload the `seehafen` theme + activate it
3. Install the 13 plugins (README step 6) + post-config
4. Run the seeds (README step 8) — **CAUTION:**
   - Pages with the same slugs as the design (home, firma, kontakt, …) will be **re-used or overwritten** by the seed
   - The client's existing content on those slugs is replaced by the new design content
   - Other content (posts, custom pages) stays untouched
5. Run on a staging copy FIRST (duplicate the live site to a test URL), never directly on live

> **Which path?** Path A when the client wants the new design + structure everywhere.
> Path B when the client's site is mostly just the pages the design replaces (quick swap).
> For an "existing project" with lots of content, **Path A is safer**.

---

## Phase 4 — Pre-launch checklist (on the staging copy)

- [ ] All 10 routes return 200 (README step 10)
- [ ] Zero broken images
- [ ] German contact form works + **FluentSMTP configured with REAL SMTP credentials** (client's mail provider — the form must actually deliver to info@seehafen-immobilien.ch)
- [ ] **Flamingo** stores submissions (test with a real submit)
- [ ] Complianz cookie banner shows (wizard run on the final domain)
- [ ] Rank Math wizard done (sitemap, titles)
- [ ] UpdraftPlus connected to offsite storage (Drive/Dropbox) + run one backup
- [ ] Permalinks = `/%postname%/` (README step 5)
- [ ] Mobile + tablet look right (3 viewports)
- [ ] All plugins updated, WP core updated
- [ ] SSL works on the staging URL

---

## Phase 5 — Go live (the actual switch)

1. **Final backup** of the live site (Phase 1 again — one more snapshot the day of the switch)
2. **Freeze client edits:** tell the client to not change content during the switch window
3. **Point the domain to the new build:**
   - Same hosting: swap the docroot (old → backup dir, new → live dir) — old site stays on disk as `seehafen-old/`
   - Or: repoint DNS A record (hosting provider's panel) → new server (allow 1–24h propagation)
4. **HTTPS:** Cloudflare / Let's Encrypt for the new site (SSL must cover the domain)
5. **Set permalinks + flush** (`wp rewrite structure '/%postname%/' --hard` + cache flush)
6. **Verify on the live domain:** routes 200, images, form, mobile, console errors
7. **Redirects:** if old URLs differ from new, add 301 redirects (Redirection plugin — already installed) so old links/Google don't break

---

## Phase 6 — Post-launch (first 7 days)

- [ ] Monitor uptime + errors (daily check first week)
- [ ] Verify Google Search Console (resubmit sitemap) — Rank Math handles the sitemap
- [ ] Confirm form mail arrives (test submission through the real form)
- [ ] Keep the old-site backup for 1 month, then archive
- [ ] Schedule UpdraftPlus backups (weekly, offsite) — already configured
- [ ] Update the client: new login details, where to add references/services, how the form mail works

---

## Rollback plan (if anything breaks)

1. Restore the Phase-5 backup: DB (`mysql < seehafen-live-db.sql`) + files (untar old docroot)
2. Point the domain back / swap the docroot back
3. Site is exactly as it was before the switch — zero data loss
4. Investigate the failure on staging, fix, re-run the launch

---

## What I need from you to tailor this

1. Client's **hosting provider + access** (FTP/SSH/cPanel?)
2. **Domain(s)** to update
3. Is the client's current site **WordPress** already, and does it have content to keep (posts, pages beyond the design's 9)?
4. Do we have a **staging URL** available (subdomain like `staging.example.ch`)?
5. SMTP provider for the client's mail (or should the form use the hosting's mail service?)

Send me these and I'll turn this playbook into the exact step-by-step commands for their server.
