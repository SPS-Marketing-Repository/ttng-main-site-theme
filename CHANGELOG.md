# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed
- Timeline (vertical) style: an item's Image / logo now shows as a photo across the top of its card. Before, only the Story style output it. The field's help text, which wrongly said it showed on Steps, now lists where it appears. Sync the field groups after updating.

### Changed
- Jump links and other in-page anchor links start scrolling straight away. They use the browser's smooth scroll, which sets its speed by distance, in place of a fixed 1-second animation that lagged before moving. Reduced-motion users jump straight to the section. The link updates the address bar, and focus moves to the section for keyboard and screen reader users.
- Home Banner: the Label field's instructions now say it is the first line of the H1 and explain how to highlight words with the brand gradient using *asterisks*. Sync the field groups after updating.

## [2.7.0] - 2026-11-06

### Added
- Icon picker on Icon Cards, Icon Grid, Feature Boxes and Intro Cards: choose from a set of 48 icons, with previews in the dropdown, or upload your own. Picked icons change colour to suit their background. Existing uploaded icons are unchanged. Sync the field groups after updating.
- New Featured Story module (`featured_story`): one story in a card with an image, corner ribbon, date, title, text and button. Pick an Insights post, which fills everything in, or enter it all manually. Options for the background colour, watermark and ribbon text. Sync the field groups after updating.
- New Become a Member / Contact Form module (`become_member`): title, content, phone and email from Site Settings and text links on the left, and a Contact Form 7 form in a card on the right, with an optional heading. With a background image: a dark blue or white layer at 80% and a white card. Without one: a solid white or dark blue background and a light grey card. Sync the field groups after updating.
- New FAQs with Quick Links module (`faq_links`): a sidebar of label, quick links, text and a link beside the title, intro and ruled questions, which slide open and shut. On smaller screens the sidebar moves below the questions. Same first-open, one-at-a-time and schema options as FAQs. Sync the field groups after updating.
- New Icon Cards module (`icon_cards`): left-aligned header with an optional text link, then 3 or 4 columns of uploaded icon, title, text and link. Options for a White (pale blue) or Blue (dark navy) background, the brand shape watermark, and the card design; with cards off, items sit on the background and are centred. Sync the field groups after updating.
- Scroll animation across the site, extending the existing card fade-up: section headings and text blocks fade up; logos, feature boxes, boxed list columns, contact cards and timeline items fade up in turn; Split Content text and media slide in from their own side on desktop; images fade in while growing slightly into place; quote panels, testimonials and calls to action fade up. Runs once per element. Content on screen at page load is never hidden, carousel cards that start off-screen stay visible, and reduced-motion users, no-JS visitors and print see everything straight away.
- Page Header: text and image fade up on load, and a full-size background image settles with a slow zoom.
- Button icons nudge along on hover.

### Changed
- Tabs, Side layout: redesigned, with coloured tabs joined to a white card and a notch on the active tab. Adds an optional text link opposite the title. Up and down arrow keys now move between its tabs. Removes the uppercase text and arrows from the tabs.
- Tabs, both layouts: adds a watermark toggle, on by default including for existing sections, and a Pale blue background option. Sync the field groups after updating.
- Watermark switch on every module with a watermark, on by default including for existing sections. On modules where only the Blue gradient has one, it shows when that background is chosen. Intro Cards also turns off the light streaks on its cards, and Quote and Contact Cards the shape in their panels and cards. Sync the field groups after updating.
- White and Dark navy backgrounds now work on every module. Home Banner gets a Settings tab with Background, Section ID and Watermark, and its header turns navy on White. FAQs with Quick Links adds Dark navy. Testimonials, Page Header and Contact Form had a Background setting that did nothing; each now offers its existing look (the default), White and Dark navy. Page Header's choice applies when it has no photo. Existing sections look the same.
- Page builder thumbnails: redrawn for Home Banner, Tabs, Card Grid, Testimonials, People Slider, Latest News, Contact Form, CTA Band, Brochure Cards and Boxed List to match the current designs. Feature Boxes and Contact Cards are now drawn by `bin/module-thumbnails.py`.

### Fixed
- Contact Form: form messages, such as validation errors, were white text on a white box.
- Icon Grid: long title words such as "Documentation" no longer break mid-word, at any width.

## [2.6.1] - 2026-11-04

### Changed
- Icon Grid: a part-filled last row is now centred.

### Fixed
- SVGs with only a `viewBox` rendered about 1px tall, as seen in Logo Strip, because they were output with `width="1" height="1"`. They now get real dimensions from the `viewBox` (`ttng_svg_image_dimensions()` in `inc/media.php`).

## [2.6.0] - 2026-11-04

First public release. Sites now update from wp-admin. Sync the field groups after updating (Custom Fields → Field Groups → Sync available).

### Added
- Theme updates in wp-admin from GitHub Releases (`inc/updater.php`, `Update URI` header): update notice, one-click update, auto-update toggle, "Check again" and a version details pop-up. Off when the theme folder is a git checkout, so an update can't delete `.git` from a development copy.
- `.github/workflows/release.yml`: pushing a version tag checks it matches `style.css`, lints the PHP, validates the ACF JSON, builds `ttng-main-site-theme.zip` and publishes the release with notes from this changelog.
- `.gitattributes` keeps development files out of the release zip.
- `LICENSE`.
- Contact Cards module (`contact_cards`): full-width rounded panels with a title, text, phone and email (navy with brand-shape watermark, or light). Matches the staging Contact Us "General Enquiries" / "Joining Enquiries".
- Split Content: "Google map" media type (`media_type = map`, `media_map` address) for the staging Contact Us "Visit Us" row.
- Feature Boxes: per-box colour (`box_colour`: navy, blue, dark navy, light grey), logos under the content (`box_images`), a button (`box_link`), and text + image below the boxes (`footer_text`, `footer_image`). Covers the staging TTA / Worldchoice / Global Travel Group link boxes, the navy + blue membership pairs and Managed Services "key differences".
- Every module's Background setting now offers the same five options: White, Light grey, Navy (#1B2D70, `navy-primary`), Dark navy (#0F193D, `navy`) and Blue gradient. Stored values are unchanged.
- Tabs: Tab layout setting (`tabs_layout`: top or side) and a per-tab colour (`tab_colour`: navy, pink, teal, black, blue). Side = 360px coloured uppercase tab buttons with an arrow down the left, content beside them without a card, stacking on tablet. Matches the staging Membership Types "Vision across our Propositions". Sync the field group after pulling.
- Timeline: "Story" style (`timeline_style = story`) and an Image / logo field per item (`item_image`). Blue year tab, white logo strip, navy / blue-navy cards with a pointed bottom and a dotted line underneath, five per view with arrows when there are more. Matches the staging About "From Humble Beginnings". Sync the field group after pulling.
- Feature Boxes: Box style setting (`box_style`: boxes or cards) and 4 / 5 column options. Cards = centred white icon on top, alternating navy / blue cards (staging About "The Principles We Live By").
- Page Header: Background Image, Background Size (cover, contain, original size) and Background Position (9 positions) fields on the Settings tab. The image overrides the featured image; size and position apply to whichever is used. Defaults to cover / centre, so existing pages are unchanged. Sync the field group after pulling.
- Feature Boxes: per-box Width setting (`box_width`: one third, half, wide, two thirds, full) for custom rows such as the staging 3 / 2 / 1 "How It Works" grid. Boxes left on Default still follow Columns.
- Boxed List module (`boxed_list`): section header, then a rounded panel of up to 4 joined columns with their own colour (navy, deep navy, dark navy, black, light grey, white). Each column has a title, then subheadings with ticked points; each line of text is one point. Matches the staging "Why Choose Us" panel.
- Download module (`download`): centred accent bar, section header and buttons with an arrow, then an optional image capped at 450px wide and tall (320px on small screens). Matches the staging "Brochure Download" section. Buttons link to a form page or a file URL.
- Testimonials: optional boxed tick list (`benefits_list`, one point per line) and a line of text (`benefits_text`) between the testimonials and the buttons, matching the staging Independent Travel Experts section. Pages that leave them empty are unchanged.
- `navy-primary` section background: flat `#1B2D70`, labelled "Navy". Only offered on Boxed List for now.
- Split Content: Minimum height setting (`section_min_height`: auto, 400, 500, 600 or 700px) on the Settings tab. Centres the content vertically and applies on desktop only. Defaults to auto, so existing sections are unchanged. Sync the field group after pulling.

### Changed
- **Requires WordPress 6.1** (was 6.0), the first version with `Update URI` support for themes.
- `style.css` version brought up to date; it had stayed at 2.2.0 since that release.
- Licence wording: the source is public so that sites can receive updates, and all rights stay reserved.
- Module thumbnail URLs are no longer written to `acf-json/` when the page builder field group is saved. They are supplied at runtime by `ttng_acfe_module_thumbnail()`, so the picker is unchanged.
- README, DESIGN, CLAUDE and llm-instructions brought in line with the current code: 26 modules, header, buttons, spacing and the release process.
- People: cards restyled to the staging profile slider: 302px photo with 25px top corners, navy body with a white name, light-blue text and a solid blue button.
- Full Width Content: the image panel is white on navy sections (staging "Institute of Customer Service" logo block).
- Feature Boxes: "Navy" background relabelled "Dark navy" (still `#0F193D`, same stored value). On this module it now also shows the staging brand shape watermark at 3% with flat `#1B2D70` boxes.
- Quote module matched to the staging Vision page: panel capped at 1320px and 500px tall, photo about a third of the width instead of over half, quote mark fully visible behind the first lines, role in white. Image `sizes` hint added for the narrower column.
- FAQs: answers sit on a white background with dark text. Open and close animate height and fade in all modern browsers through `assets/js/main.js`, including the item that closes when "one open at a time" is on, and a second click mid-animation reverses it. Without JS, or for reduced-motion users, the native instant behaviour remains.
- SEO, Tracking and Mega menu field groups moved from PHP registration to `acf-json/` so they can be synced and edited in the ACF admin. Keys are unchanged, so saved values and the content importer are unaffected. The SEO group is still hidden while an SEO plugin is active.

## [2.5.1] - 2026-11-04

### Added
- Page Header: "Image link" field (`header_logo_link`, shown once an image is set) — makes the image a link.

### Changed
- Page Header: the image ("Logo", now "Image") sits in a white 20px-radius panel to the right of the text, with the staging navy gradient behind it when there's no featured image — matches the staging Travel Trust Association page. Stacks above the text on tablet/mobile.
- Contact form module background photo is fixed (parallax) on scroll. Falls back to normal scrolling on touch devices and for reduced-motion users.

## [2.5.0] - 2026-11-04

### Added
- Sticky header: once the top bar and header scroll away, the header slides back in pinned to the top as a 90px navy bar with a 100px logo (`body.header-is-stuck`). Holds its space on inner pages so nothing jumps.
- Stat pod numbers count up when scrolled into view (screen readers get the final figure).
- Cards (stat pods, membership/grid cards, news cards, icon items, brochure cards, people cards, FAQs) fade in and up on scroll, staggered along each row. Off for reduced-motion users and without JS.
- Staging background images committed in `assets/images/staging/`.

### Changed
- Sub footer in two rows: copyright | registration, then legal links | credit.

## [2.4.0] - 2026-11-04

Restyled to clone the staging (Elementor) site, measured from it at 1440px.

### Added
- `inc/mega-menu.php` — desktop nav renders staging-style mega panels: overview (parent title + menu description), child "tabs", and an image + navy card preview that swaps on hover/focus. "Mega menu" fields (preview image, heading) on menu items; image falls back to the page's featured image.
- Testimonials module is now a one-at-a-time slider (round photo, italic name/business, dots, autoplay that pauses on hover/focus and for reduced motion).
- `ttng_format_label()` (two-tone "The Travel *Network Group*" labels) and `ttng_format_highlight()` (gradient "UK's biggest") helpers.
- Theme icons in `assets/images/icons/` (staging service icons + stat star).
- Importer steps 5 (download staging background images into `assets/images/staging/`, fill empty logos/socials/accreditation/menu previews) and 6 (rebuild the home page as staging, backing up the old one).

### Changed
- Header: white top bar (star tagline, dividers, gradient "Request a Brochure" pill); 165px header with 153px logo, uppercase nav; transparent over the home hero, navy underlay on inner pages. No longer fixed (staging scrolls away).
- Containers: 1528px content + 20px gutters (staging Elementor boxed width).
- Section labels 15px/600 uppercase navy + blue; titles 38px/400; 70px section spacing; navy sections are #0F193D.
- Buttons: 14px/500 pills — primary solid #009ADF, "outline" = solid navy #0E3178; gradient kept for the hero and form submit.
- Home banner: H1 = label + title, horizontal slide with Ken Burns zoom, pill pager; navy underlay with rounded bottom.
- Stat pods, membership cards (white logo top / navy body), icon grid (94px circles), Vision section, news cards, join form (stacked white fields, gradient submit bar), footer (#090F24, blue headings, social circles, ICS logo) and white sub footer — all matched to staging.
- Page header uses the page's featured image with a 25% navy wash.
- Links in copy are brand blue, underlined on hover.

## [2.3.0] - 2026-11-04

Sections 4–8 of the build checklist: forms, content population, SEO and redirects.

### Added
- `inc/breadcrumbs.php` — breadcrumb trail that follows the Primary menu (the brief's URLs are mostly flat), falling back to page ancestors. Shown in the page header, Insights listing header and single Insight header.
- `inc/seo.php` — SEO fields on pages and Insights (title, meta description, share image, noindex), meta description, Open Graph and Twitter tags, and one JSON-LD graph (Organization, WebSite, BreadcrumbList, BlogPosting). Switches itself off if Rank Math, Yoast or AIOSEO is active. Field group registered in PHP with fixed keys.
- `inc/redirects.php` — 301s on 404 from old staging/sitemap URLs (`-2` legal slugs, `/risk/`, `/testimonials/`, `/news-events/`, sitemap-image names) to the brief's URLs. Extend with the `ttng_redirect_map` filter.
- `inc/forms.php` — honeypot + signed 3-second time trap on every Contact Form 7 form, logged to CF7's spam log. No CAPTCHA.
- `inc/tracking.php` — Google Tag Manager with Consent Mode v2 defaults (all denied until the cookie banner updates consent). Container ID in Site Settings > Tracking; nothing output until set or for logged-in users.
- `form_submit` dataLayer event on CF7 success in `assets/js/main.js` (form ID, name, path — no field values).
- Breadcrumb and honeypot styles in `additions.css`.
- `wp-content/mu-plugins/ttng-content-import.php` (outside the theme) — Tools > TTNG Import: creates every brief page, imports first-pass content from staging/live, imports Insights posts and repoints menu custom links. Delete before launch.

## [2.2.0] - 2026-11-04

Brings the theme in line with the TTNG build brief, ready for staff to move the content across.

### Added
- ACF Extended on `page_builder`: Add Module pop-up (4 columns, categories), module thumbnails and copy/paste of modules between pages.
- `bin/module-thumbnails.py` and 22 SVG wireframes in `assets/images/modules/`, served by `ttng_acfe_module_thumbnail()`. A .jpg/.png/.webp with the same name takes priority.
- Modules: `page_header`, `jump_nav`, `comparison_table`, `tabs`, `timeline` (timeline or steps), `people_slider` (slider or grid), `logo_strip`, `cta_band` (optional background image), `brochure_cards`, `guide_nav`, `global_section`.
- `team-member` post type with placeholder silhouette, and `reusable-section` post type: build a section once and use it on any page.
- Mega menu: `TTNG_Mega_Menu_Walker` (`inc/nav-walker.php`) with keyboard/touch toggle buttons, Escape to close, and accordion submenus on mobile. No plugin.
- Insights templates: `home.php`, `archive.php`, `category-events.php` (Upcoming and Past via `event_date`), `search.php`, a full single post, HTML sitemap page template, pagination, category links, plain share links with no trackers, and category intro paragraphs.
- Site Settings: recruitment phone (x2) and email, top bar tagline, company registration line, guide pages.
- Contact Form and CTA Band can show general or recruitment contact details via `ttng_get_contact_details()`.
- Brand-shape watermark on all gradient sections.

### Changed
- Posts are labelled "Insights" in the admin (were "News").
- Testimonials module shows the latest two when none are chosen.
- Page builder renders through `ttng_render_page_builder()`, which keeps module variables local and limits nesting to one level.
- `index.php` is now a generic post list rather than the page builder.
- 404 page uses the brand button classes.

### Removed
- `case-study` post type and its field group. Member stories are posts in the Member Stories category. No case studies existed.

## [2.1.0] - 2026-10-07

The Travel Network Group build — converts the previous Elementor site into
the ACF Flexible Content page builder.

### Added
- Eleven page builder layouts with matching templates and CSS: `home_banner`, `intro_cards`, `card_grid`, `icon_grid`, `split_content`, `full_width_content` (nested flexible content), `faq`, `testimonials`, `quote`, `latest_news`, `contact_form`.
- `quote` module matching the CEO quote on the old Vision page: navy 25px-radius panel, large faded quote mark, brand-shape watermark, name/role, photo left or right; stacks on tablet/mobile. Name is bold so the brand blue passes WCAG AA as large text on navy.
- `assets/images/brand-shape.svg` (TTNG brand shape, used as a 5% watermark).
- `faq` module: native `<details>` accordion (no JS) matching the old Why Join page — navy 15px-radius bars, plus/minus icon, smooth open where supported. Toggles for first-open, one-open-at-a-time (`name` attribute) and optional FAQPage JSON-LD (off by default to avoid duplicating SEO plugin schema).
- `--navy-hover` colour token.
- Shared snippets `section-header.php`, `ctas.php` and `news-card.php` in `template-parts/snippets/`.
- Helpers `ttng_module_attrs()`, `ttng_the_button()`, `ttng_tel_href()` and `ttng_icon()` in `inc/helpers.php`.
- `inc/post-types.php` — `testimonial` (admin only) and `case-study` (public, `/case-studies/`) custom post types, plus their ACF field groups.
- Fixed top bar (email / phone / link) above a fixed navy header with dropdowns, optional header CTA and scroll shadow.
- Footer with tagline, address, contact links, three menu columns titled by menu name, socials, accreditation logos and a legal menu in the sub footer.
- Theme Settings fields: `contact_address`, `top_bar_link`, `header_cta`, `footer_tagline`, `footer_accreditations`; settings split into tabs.
- Menu locations `footer_one`, `footer_two`, `footer_three`.
- Accessible jQuery fade slider (`.ttng-slider`) with dots, pause button, hover/focus pause and reduced-motion support.
- Utility classes missing from the grid-only Bootstrap build (`text-center`, `justify-content-center`, `align-items-center`, `order-lg-*`, `stretched-link`).

### Changed
- All brand tokens (TTNG colours, gradients, Montserrat, fluid type scale, spacing, radii), buttons, section backgrounds and CF7 form styles now live in `additions.css`.
- Font switched from Cormorant Garamond to Montserrat.
- `page_builder` field group now also applies to the `case-study` post type.
- Anchor smooth-scroll now offsets by the fixed header height.
- `lazy-load.php` respects an explicit `loading="eager"` so the hero image (LCP) isn't lazy-loaded.
- CLAUDE.md rewritten for TTNG.
- Rebranded the theme for SPS Marketing: text domain `ttng-main-site-theme`, `@package` `TTNG_Main_Site_Theme`, function/constant prefixes `ttng_`/`TTNG_`, CSS `ttng-slider`, image sizes `ttng-card`/`ttng-featured`, Theme Settings field keys `field_ttng_*`.
- Sub footer credit, robots.txt, editor stylesheet header and all docs (README, DESIGN, CLAUDE.md, llm-instructions) now reference SPS Marketing.
- The auto-update opt-out constant is now `TTNG_DISABLE_AUTO_UPDATES` — update `wp-config.php` on any environment that defines the old name.

### Removed
- `footer` menu location (replaced by the three footer column locations).
- Placeholder dark-mode token block and "Guggenheim Sans" placeholders from `additions.css`.

### Security
- `ttng_kses_iframe()` was allowing bare `<script>` tags — `'script' => false` in a `wp_kses()` allowlist still counts as allowed because kses checks `isset()`. Removed the key so script tags are stripped.

---

## [2.0.0] - 2026-08-05

Ground-up cleanup and hardening pass on the base theme: removes leftover content
from earlier projects and fixes several real bugs found along the way.

### Added
- `template-parts/snippets/social-links.php` — shared inline-SVG social icon renderer used by both header and footer.
- `assets/css/core/cookie-consent.css` — Cookiebot/CookieScript vendor styles split out of `base.css` into their own file, with a matching enqueue in `inc/assets.php`.
- `ttng-card` (600×400, 3:2) and `ttng-featured` (1600×900, 16:9) image sizes via `add_image_size()`.
- Root `page.php` and `single.php` — restores the theme actually reaching WordPress's dedicated single-post/page templates instead of silently falling through to `index.php` for everything.
- Generic Theme Settings ACF fields (`contact_email`, `contact_telephone`, `facebook_url`, `instagram_url`, `linkedin_url`, `youtube_url`, `twitter_url`, `pinterest_url`) matching what the header/footer code actually calls.
- `.gitignore`.

### Changed
- Rewrote `site-header.php`/`site-footer.php`: BEM-ish class names, `wp_body_open()`, `language_attributes()` (was a hardcoded `lang="en"`), an explicit `<meta charset>`, `inert`/`aria-hidden` off-canvas menu, fully escaped output.
- Rebuilt `header.css`/`footer.css` to match the new markup.
- Fixed a `--semi` CSS custom-property collision between `base.css` (font-weight `600`) and `additions.css` (a font-family string) by renaming the base tokens to `--fw-semi`/`--fw-bold`.
- Restored fallback colour tokens (`--black`, `--white`, `--offwhite`, `--orange`) in `base.css` that had gone undefined, and darkened the default `--orange` (`#C47714` → `#A35F0E`) — the old value measured 3.50:1 contrast against white/off-white, below WCAG AA's 4.5:1 minimum for text.
- Simplified `inc/assets.php` — dropped FontAwesome/Slick/AOS/`bootstrap.bundle.min.js` enqueues; `base.css` now loads before `additions.css` so brand colour overrides apply correctly.
- Cleaned `assets/js/main.js` down to genuinely reusable behaviour (passive listeners, YouTube privacy, smooth scroll, accessible off-canvas toggle with `inert`/`aria-hidden`/Escape handling, simple accordion).
- Reset `DESIGN.md`, `CLAUDE.md`, `style.css`, `README.md`, and `robots.txt` back to generic base-theme content — all had been overwritten with content from earlier projects (including an unrelated domain in `robots.txt`'s sitemap). `DESIGN.md` is now the blank template the `themesetup` agent fills in per client.
- Rebuilt the Theme Settings and Page Builder ACF field groups (`acf-json/`) to match the theme's actual code.
- Simplified `inc/acf-defaults.php` to an empty layouts array — was referencing flexible-content layouts and a post type that don't exist in this theme.
- Brought `style.css`'s theme header in line with WordPress's recognised fields (dropped non-standard `Developer:`/`Web Designer:` fields; added `Requires at least`/`Requires PHP`).

### Removed
- FontAwesome kit (full webfonts/svgs/less/scss/sprites), Bootstrap 3 glyphicons, Slick Carousel, and AOS — replaced by hand-written inline SVG icons; not a base-theme dependency going forward.
- All legacy page-builder modules from earlier projects (PHP + CSS) and their `page_builder` ACF scaffolding.
- 15 unused Page Builder layouts and several unused Theme Settings fields from `acf-json/` — none were used by any code in this theme.
- `Team` and `Case Studies` ACF post-type registrations — not universal enough for a base theme.
- Stray/duplicate CSS: `JUST+GRIDSbootstrap-custom.min.css`, `old_bootstrap-custom.min.css`, `bootstrap.min.css`, `colour-scheme.css`.
- A booking-widget enqueue left over from an earlier project.
- Dead files: `templates/` folder (non-functional — WordPress's template hierarchy doesn't look there for a classic theme; also contained an orphaned `single-campaign.php` pointing at a non-existent template part and unregistered post type), `search-filter/results.php` (orphaned `whats-on` listing code, nothing `include`d it), `template-parts/snippets/content-block-intro.php` (unreferenced).

### Fixed
- `inc/header.php` had an unresolved git merge conflict left in it — literally invalid PHP, a fatal parse error on every page load.
- `inc/assets.php` had the same issue in two smaller spots.
- Missing `id="main"` on the 404 template — every other template's skip link targets `#main`; on 404 that target didn't exist, so the skip link silently failed there.
- Unescaped `echo home_url()` on the 404 template.

### Security
- `ABSPATH` guards added across `inc/` and module template files; several functions renamed into the `ttng_` namespace to avoid collisions with plugins/other themes.
- Site Settings options page capability raised from `edit_posts` to `edit_theme_options`.
- humans.txt content sanitised with `wp_strip_all_tags()`/`sanitize_textarea_field()` before being written to the filesystem.
- Escaped remaining unescaped output in header/footer (copyright text, credit link, contact email/phone) and the 404 template.

---

## [1.0.0] - 2025-10-01

### Added
- Initial stable release.
