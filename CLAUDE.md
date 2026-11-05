# CLAUDE.md — The Travel Network Group

## Project Overview

Bespoke WordPress theme for **The Travel Network Group (TTNG)**, built by **SPS Marketing** (https://spsmarketing.co.uk/). Text domain: `ttng-main-site-theme`; PHP prefix: `ttng_` / `TTNG_`. It replaces the previous Elementor build (the "staging" site referred to in these docs and in code comments). There is no build system — CSS and JS are authored and served directly (no Sass, no bundler, no npm).

**Public repo:** https://github.com/SPS-Marketing-Repository/ttng-main-site-theme. The repo is public so that sites can receive updates, so never commit credentials, client data or internal URLs. Proprietary licence (`LICENSE`).

**Requirements:** WordPress 6.1+, PHP 8.0+, ACF Pro (required — the theme will not function without it), ACF Extended (Add Module pop-up, thumbnails, copy/paste). Contact Form 7 for the WordPress forms; the brief specifies ClickUp embeds for brochures and Speak to Our Team.

**Local development** runs via LocalWP at `http://ttng-main-site.local/`. The site root relative to this theme is three levels up: `app/public/`.

---

## Architecture

### `functions.php` — Module Loader

`functions.php` only defines two constants (`TTNG_THEME_DIR`, `TTNG_THEME_URI`) and then `require_once`s every file in `/inc/`. Add new functionality by creating a new file in `/inc/` and adding its name (without `.php`) to the `$modules` array.

### ACF Flexible Content Page Builder

The page builder field (`page_builder`, field group `group_677bab909515c.json`) is used on **pages** and **reusable sections**. Layouts map directly to files:

- **PHP template:** `template-parts/modules/{layout_name}.php`
- **CSS:** `assets/css/modules/{layout_name}.css` (lazy-enqueued only when the module is on the page)
- **Thumbnail:** `assets/images/modules/{layout_name}.svg` (drawn by `bin/module-thumbnails.py`; a .jpg/.png/.webp with the same name takes priority)

The dispatcher is `template-parts/modules/page-builder.php`, called through `ttng_render_page_builder( $post_id )`.

| Layout | Purpose |
|---|---|
| `home_banner` | Left: label / **H1** / content / buttons. Right: fading image slider (gallery). Once per page. |
| `page_header` | Inner page hero: optional logo, label, **H1** (falls back to page title), subheading, buttons, background image with size/position (falls back to featured image). Once per page. |
| `jump_nav` | Links to `#section-id` anchors or other pages |
| `tabs` | Intro + tabbed panels with optional image (`.ttng-tabs`, stacked without JS) |
| `timeline` | Intro + timeline or numbered steps (`timeline_style`) |
| `comparison_table` | Up to 4 columns, rows of Included / Extra cost / not included |
| `guide_nav` | Series list + prev/next from the `guide_pages` option |
| `people_slider` | `team-member` posts as slider or grid; empty selection shows everyone |
| `logo_strip` | Title + logos with optional links |
| `cta_band` | Centred CTA, optional contact details and background image |
| `brochure_cards` | One row per brochure with its own iframe embed |
| `download` | Centred accent bar + intro + buttons (form page or file URL), then an optional image capped at 450px |
| `global_section` | Outputs a `reusable-section` post's modules |
| `intro_cards` | Centred intro + up to 3 "pod" cards (stat e.g. `1100+`, or content) |
| `card_grid` | Intro + grid of image or logo cards (2/3/4 cols) with buttons |
| `icon_grid` | Intro + grid of icon / title / text items (3/4 cols), optional links |
| `icon_cards` | Left-aligned header + text link, then a grid of icon / title / text / link (3/4 cols). Options: White (pale blue `section-bg--pale`) or Blue (dark navy) background, watermark on/off, card design on/off (off = centred, no cards) |
| `feature_boxes` | Intro + rounded navy boxes (2/3 cols) with icon / title / content; per-box `box_width` builds custom rows (e.g. 3 / 2 / 1) |
| `boxed_list` | Intro + rounded panel of up to 4 joined columns, each with its own colour, title and subheadings with ticked points (one per line of text) |
| `split_content` | Text one side, image or video (oEmbed) the other; optional desktop minimum height (`section_min_height`) |
| `full_width_content` | Nested flexible: heading / content / image / video / buttons |
| `faq` | Intro + accordion (native `<details>`), first-open / one-at-a-time toggles, optional FAQPage JSON-LD |
| `faq_links` | Sidebar (label, quick links, text, link; below the questions at 992px and under) + title, intro and ruled accordion. Same `faq_*` options and `.faq-item` animation as `faq`; both use the `faq-list` snippet |
| `testimonials` | Relationship to `testimonial` CPT; empty selection shows the latest five. Optional boxed tick list and line of text below |
| `quote` | Single quote in a navy panel (big faded quote mark, brand-shape watermark) beside a photo, left or right |
| `latest_news` | Latest posts, optional category filter + count |
| `contact_form` | Text + general or recruitment phone/email (from Site Settings) + CF7 shortcode or iframe embed |
| `become_member` | Background image under a white or dark blue layer at 80% (`section_background`). Left: text, Site Settings phone/email, text links. Right: CF7 shortcode in a white card; two-column form rows use `<div class="ttng-form-row">` in the CF7 form code |
| `contact_cards` | Full-width navy or light panels with title, text, phone and email |

**ACF Extended** is configured on the `page_builder` field: pop-up picker (`acfe_flexible_modal`, 4 columns, categories from each layout's `acfe_flexible_category`), `acfe_flexible_layouts_thumbnails`, and copy/paste via `acfe_flexible_add_actions: ["copy"]`. Thumbnails are served by `ttng_acfe_module_thumbnail()` in `inc/acf.php`, not stored in the field group, because media IDs differ between sites.

**Shared sub fields** (same names in every layout, so the shared snippets can read them):
`section_label`, `section_title`, `section_content`, `section_ctas` (repeater: `cta_link`, `cta_style`), `section_background` (white / light / navy-primary / navy / gradient; `navy-primary` is flat `#1B2D70`, labelled "Navy"; `navy` is `#0F193D`, labelled "Dark navy"), `section_id`.

**Repeaters:** if a template reads layout-level sub fields *after* checking a repeater, read the repeater as an array (`get_sub_field( 'faqs' )`) instead of `have_rows()` — an early `have_rows()` check starts the repeater loop and later `get_sub_field()` calls read the repeater row (see `faq.php`).

**Shared snippets** (`template-parts/snippets/`): `section-header.php`, `ctas.php`, `faq-list.php`, `contact-details.php`, `news-card.php`, `category-links.php`, `share-links.php`, `social-links.php`.

**Helpers** (`inc/helpers.php`): `ttng_module_attrs()` (section class/id from Settings tab), `ttng_render_page_builder()`, `ttng_get_contact_details()`, `ttng_the_button()`, `ttng_tel_href()`, `ttng_icon()`, `ttng_kses_iframe()`, trimmed excerpt/content.

### ACF JSON is generated

The page builder, Theme Settings and CPT field groups were generated by a script with deterministic (hash-based) keys. Editing in the ACF admin and re-saving is fine — ACF will write back to `/acf-json/`. **Never hand-edit keys.**

**To create a new module:**
1. Add a new layout to `page_builder` (ACF admin), reusing the shared sub field names above where relevant.
2. Create `template-parts/modules/{layout_name}.php` (copy `BLANK.php`) and start the section with `<section<?php echo ttng_module_attrs( 'slug' ); ?>>`.
3. Create `assets/css/modules/{layout_name}.css` (copy `BLANK.css`).
4. Add a drawing function to `bin/module-thumbnails.py` and run `python -I bin/module-thumbnails.py assets/images/modules`. Give the layout a category.
5. Sync ACF JSON: WP Admin → Custom Fields → Field Groups → Sync available.

---

## CSS Structure

| File | Purpose |
|------|---------|
| `assets/css/core/base.css` | Neutral reset + fallback tokens — **no brand values here** |
| `assets/css/core/additions.css` | **All brand tokens** (colours, gradients, fonts, type scale, spacing, radii), buttons, section backgrounds, utilities, CF7 form styling |
| `assets/css/core/header.css` | Top bar + header, sticky state (`body.header-is-stuck`), mega panels, off-canvas menu |
| `assets/css/core/footer.css` | Footer, social links, accreditations, sub footer |
| `assets/css/core/insights.css` | Single post, listings, category links, pagination, share links (loaded by `inc/insights.php`) |
| `assets/css/core/cookie-consent.css` | Cookiebot/CookieScript tweaks |
| `assets/css/bootstrap-custom.min.css` | Bootstrap **grid only** — utilities like `text-center`, `justify-content-center`, `order-lg-*`, `stretched-link` are defined in additions.css |
| `assets/css/modules/*.css` | One per page builder layout |

- Brand tokens: `--navy #1B2D70`, `--navy-dark #0F193D`, `--blue #009ADF` (large text/icons only — 3:1 on white), `--blue-dark #3153CC` (small text/links), `--blue-light #B1BCE7` (small accent text on navy), `--grey-light #F3F5F8`, `--blue-tint #F2F8FE` (pale section background), `--text #333`.
- Font: Montserrat (Google Fonts, 400/500/600/700) — enqueued in `inc/assets.php`.
- Dark sections get `.is-dark` (from `ttng_module_attrs()`), which flips text to white. Cards inside dark sections set their own text colours explicitly.
- Header height tokens: `--top-bar-height`, `--header-height`, `--header-total` (header.css). Desktop nav switches to the hamburger at **1280px**.
- Module CSS breakpoints: **1536, 1366, 1280, 992, 768, 600px** (desktop-first, follow `BLANK.css`).
- Never use `!important` without flagging it.

---

## JavaScript

All custom JS lives in `assets/js/main.js` (jQuery, loaded in footer):
- Mobile off-canvas menu (`#trigger`, `#slide-div`) with inert/aria handling
- Submenu toggles (`.nav-toggle`): mega panels on desktop, accordions on mobile, Escape and click-away to close
- Tabs (`.ttng-tabs`) with arrow/Home/End keys; people slider arrows (`.people-slider--slider`)
- Sticky header (`body.header-is-stuck`), stat count-up
- Scroll reveal: `ttng_reveal( selector, variant, stagger )` adds `.reveal` plus an optional variant (`from-left`, `from-right`, `scale`, `fade`; styles in additions.css). Add new elements there. Skips the hero and anything on screen at load; carousel items only fade, and those starting off-screen are skipped. Page Header animates on load in `page_header.css`
- Fade slider `.ttng-slider` (home banner) — autoplay, dots, pause button, pauses on hover/focus, starts paused for reduced-motion users
- Smooth anchor scrolling offset by the fixed header
- Simple accordion (`.accordion-header` / `.accordion-content`)
- FAQ open/close animation (`.faq-item`) — animates `.faq-item__panel`, takes over the `name` exclusive behaviour, off for reduced-motion users
- Adaptive content (`.show-900` / `.hide-900`), YouTube nocookie swap

There is **no Slick or Bootstrap JS** in this theme.

**Never add inline JS — always add to `assets/js/main.js`.**

---

## ACF Field Groups (`/acf-json/`)

| File | Contents |
|------|---------|
| `group_64525a8b8885f.json` | Theme Settings (tabs: Logos, Contact, Header, Footer, Guide, Social) |
| `group_677bab909515c.json` | Page Builder (29 layouts — pages + reusable sections) |
| `group_6881f94ae87b3.json` | Humans.txt |
| `group_ttng_testimonial_details.json` | Testimonial: `testimonial_quote`, `testimonial_role` |
| `group_ttng_team_member.json` | Team member: `team_role`, `team_link`, `team_text` |
| `group_ttng_post_details.json` | Events category posts: `event_date` (Ymd) |
| `group_ttng_category_details.json` | Category: `category_intro` |
| `group_ttng_seo.json` | Pages and posts: `seo_title`, `seo_description`, `seo_image`, `seo_noindex`. Hidden by `inc/seo.php` when an SEO plugin is active. Importer writes by key — don't change keys |
| `group_ttng_tracking.json` | Site Settings: `gtm_container_id` |
| `group_ttng_mega_menu.json` | Menu items: `mega_image`, `mega_heading`. Importer writes by key — don't change keys |

After pulling changes always go to **Custom Fields → Field Groups → Sync available**.

---

## ACF Options Pages

Registered in `inc/acf.php`:
- **Site Settings** (`theme-general-settings`) — logos, `contact_email`, `contact_telephone`, `recruitment_telephone`, `recruitment_telephone_alt`, `recruitment_email`, `contact_address`, `top_bar_tagline`, `top_bar_link`, `header_cta`, `footer_tagline`, `footer_accreditations`, `copyright_text`, `company_registration`, `guide_pages`, social URLs
- **Humans.txt** (`site-text-files`)

Access options page fields with `get_field( 'field_name', 'option' )`.

---

## Navigation Menus

Registered in `inc/theme-setup.php`: `primary`, `mobile`, `footer_one`, `footer_two`, `footer_three`, `legal`.
Footer column headings are the **menu names** (`wp_get_nav_menu_name()`), e.g. "Membership Options".
Primary and mobile use `TTNG_Mega_Menu_Walker` (`inc/nav-walker.php`): a top-level item with 6+ children or any grandchildren becomes a mega panel.

---

## Custom Post Types

Registered in PHP in `inc/post-types.php`:
- `testimonial` — admin only (no front-end URLs). Title = person's name, featured image = photo.
- `team-member` — admin only. Title = person's name, featured image = photo; order with Page Attributes.
- `reusable-section` — admin only. Built with the page builder; placed with the `global_section` module.

Built-in `post` type is relabelled to **Insights** via `inc/post-labels.php`. Member stories are posts in the `member-stories` category, keeping their URLs. Permalinks are `/%postname%/`, matching the old site. Templates: `home.php`, `archive.php`, `category-events.php`, `search.php`, `single.php`, `page-templates/template-sitemap.php`.

---

## Key `inc/` Files

| File | What it does |
|------|-------------|
| `assets.php` | Enqueues styles/scripts; Montserrat from Google Fonts |
| `post-types.php` | Testimonial, Team Member and Reusable Section CPTs |
| `insights.php` | Insights CSS, Events upcoming/past queries |
| `nav-walker.php` | Mega menu walker |
| `helpers.php` | Module/button/icon helpers, excerpts, `ttng_kses_iframe()` |
| `lazy-load.php` | Lazy-loads attachment images unless `'loading' => 'eager'` is passed (home banner first slide) |
| `gutenberg.php` | Disables Gutenberg for all post types |
| `security.php` | Strips WP version, disables XML-RPC |
| `media.php` | SVG sanitisation, WebP support, email obfuscation |
| `acf-json.php` | ACF JSON save/load to `/acf-json/` |
| `breadcrumbs.php` | `ttng_breadcrumbs()` / `ttng_get_breadcrumbs()` — trail follows the Primary menu, falls back to page ancestors |
| `seo.php` | Meta description, OG/Twitter, JSON-LD graph — off when an SEO plugin is active |
| `redirects.php` | `ttng_get_redirect_map()` — 301s on 404 only; extend via `ttng_redirect_map` filter |
| `forms.php` | CF7 honeypot (`ttng_website`) + signed time trap (`ttng_form_ts`) via `wpcf7_spam` |
| `mega-menu.php` | `ttng_primary_nav()` — desktop mega panels (menu item Description + "Mega menu" image/heading fields) |
| `tracking.php` | GTM + Consent Mode v2 defaults; container ID in Site Settings > Tracking (`gtm_container_id`) |
| `updater.php` | wp-admin theme updates from GitHub Releases via the `Update URI` header. Off when the theme folder contains `.git` (`ttng_updater_enabled` filter) |

---

## Known Issues / To Do

- Design is a clone of the staging site. Decorative images live in `assets/images/staging/`. The header scrolls away, then returns as a compact sticky bar.
- `#009ADF` link text on white is ~3:1 contrast (staging styling) — below WCAG AA for body-size text. Flag to client.
- Content import tool: `wp-content/mu-plugins/ttng-content-import.php` (Tools → TTNG Import). Dev only — delete before launch.
- Contact Form 7 6.2 throws a PHP fatal when a logged-out request hits `GET /wp-json/contact-form-7/v1/contact-forms` (plugin bug: a static closure uses `$this`, `includes/rest-api.php:33`). Not theme code; update the plugin when a fix ships.
- Brand gradient button (`#3153CC → #009ADF`) with white 15px text is ~3:1 at the light end — below WCAG AA 4.5:1. Matches the live site; confirm with client or darken the end stop.
- Pushing a tag did not start the release workflow during the 2.6.0 release (three attempts, cause not found; manual runs work and no ruleset applies). Start it with `gh workflow run release.yml --ref vx.y.z` if no run appears.
- Page builder module CSS is enqueued mid-page, so it prints in the footer (late styles) — possible flash of unstyled content / CLS. Consider pre-scanning `page_builder` rows on `wp_enqueue_scripts`.

---

## Releases

Sites update from wp-admin through GitHub Releases (`inc/updater.php`). Full steps are in README.md under "Releasing an Update". In short: bump `Version` in `style.css` and the README, move `[Unreleased]` in `CHANGELOG.md` under the new version, merge to `main`, push a `vx.y.z` tag, then confirm a run started (see Known Issues). `.github/workflows/release.yml` then builds `ttng-main-site-theme.zip` and publishes the release.

- Never upload a GitHub source zip to a site: only the built asset has the right folder name.
- An update replaces the whole theme folder, so field group changes made in the ACF admin on a live site are lost at the next update. Make them locally and release them.
- New dev-only root files need an `export-ignore` line in `.gitattributes`.

---

## Branding

- This is an **SPS Marketing** white-label build. Never add references to any other agency in code, comments, docs, credits or robots.txt.
- Sub footer credit: "Website by SPS Marketing" (`template-parts/footer/site-footer.php`).
- Auto-update opt-out constant is `TTNG_DISABLE_AUTO_UPDATES` (`inc/security.php`). It stops automatic updates only; manual updates from wp-admin still work.

---

## Coding Conventions

- Follow WordPress coding standards — always escape output, sanitise input, nonces where appropriate
- Use `get_field()` / `the_field()` for ACF — pass `'option'` for options page fields
- Use `get_theme_file_path()` / `get_theme_file_uri()` — not `get_template_directory()` — for child-theme override support
- New PHP helper functions go in `inc/helpers.php` prefixed `ttng_`; translatable strings use the `ttng-main-site-theme` text domain
- Embeds/iframes: always `ttng_kses_iframe()`

---

## Agents to Use on This Project

| Task | Agent |
|------|-------|
| Writing or reviewing PHP/CSS/JS | `Use the wp-developer agent` |
| Updating DESIGN.md / README.md | `Run themesetup` (both are already populated for TTNG — update, don't regenerate) |
| GDPR/cookie/accessibility audit | `Use the compliance-checker agent` |

---

## CHANGELOG

Keep `CHANGELOG.md` updated using [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) format with Semantic Versioning.
