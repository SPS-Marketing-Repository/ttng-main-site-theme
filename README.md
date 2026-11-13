# The Travel Network Group — WordPress Theme

**Version:** 2.7.0  
**Agency:** [SPS Marketing](https://spsmarketing.co.uk/)  
**Text Domain:** `ttng-main-site-theme`

---

## Overview

Bespoke WordPress theme for **The Travel Network Group (TTNG)** — the UK's biggest independent travel network — built by [SPS Marketing](https://spsmarketing.co.uk/).

It replaces the previous Elementor build with:

- An ACF Flexible Content page builder (29 modules) with ACF Extended's visual picker and copy/paste
- Testimonial, Team Member and Reusable Section custom post types
- A top bar and header with a compact sticky state, a plugin-free mega menu and a fully options-driven footer
- A single source of truth for brand tokens (`assets/css/core/additions.css`)
- Clean, secure and performance-focused PHP, HTML, CSS and jQuery — no build step
- Updates from wp-admin, delivered through GitHub Releases

The source is public so that sites can receive updates. It is not open source: see [License](#license).

See the full release history in [CHANGELOG.md](CHANGELOG.md) and the design system in [DESIGN.md](DESIGN.md).

---

## Requirements

- **WordPress:** 6.1+
- **PHP:** 8.0+
- **ACF Pro:** required — the theme will not function without it
- **ACF Extended:** for the Add Module pop-up, thumbnails and copy/paste (without it, the normal ACF dropdown is used)
- **Contact Form 7:** for the membership / contact form (or use an iframe embed, e.g. ClickUp)
- **Database:** MySQL 5.7+ or MariaDB equivalent

---

## Theme Structure

| Path | Contents |
|---|---|
| `/acf-json/` | ACF Local JSON field groups (page builder, Theme Settings, CPT fields) |
| `/assets/css/core/` | `base.css` (reset), `additions.css` (brand tokens), `header.css`, `footer.css`, `insights.css`, `cookie-consent.css` |
| `/assets/css/modules/` | One stylesheet per page builder module, loaded only when used |
| `/assets/images/` | Theme graphics (`brand-shape.svg` watermark), `icons/` and `staging/` background images |
| `/assets/images/modules/` | Module thumbnails for the Add Module picker, drawn by `bin/module-thumbnails.py` |
| `/assets/images/icon-library/` | The icon picker's set of 48 icons, built by `bin/build-icon-library.py` |
| `/assets/js/main.js` | All front-end jQuery (menus, sliders, tabs, sticky header, FAQ animation, count-up, scroll reveal) |
| `/assets/js/admin-icons.js` | Icon previews in the icon picker dropdown (wp-admin only) |
| `/page-templates/` | HTML Sitemap page template |
| `/inc/` | Theme setup, assets, ACF config, post types, helpers, security, media, SEO, redirects, forms, tracking, updater |
| `/template-parts/header/`, `/footer/` | Site header and footer |
| `/template-parts/modules/` | One PHP template per page builder module |
| `/template-parts/snippets/` | Shared partials (section header, buttons, news card, social links) |
| `/.github/workflows/release.yml` | Builds the theme zip and publishes a release when a version tag is pushed |

---

## Page Builder Modules

| Module | Use it for |
|---|---|
| Home Banner | Page H1 with content and buttons, plus a fading image slider |
| Page Header | Inner page H1 and subheading, with an optional linked image panel, buttons and background image (size and position selectable) |
| Jump Links | Links to sections on the page (via their Section ID) or to other pages |
| Tabs | Tabbed panels of text with an optional image, with tabs along the top or coloured down the side |
| Timeline / Steps | Company history as a timeline or story cards, or a process as numbered steps |
| Comparison Table | Features against up to 4 columns: Included, Extra cost or not included |
| Guide Navigation | Series list and previous/next for the pages in Site Settings > Guide |
| People | Team Members as a slider or grid |
| Logo Strip | Row of partner or accreditation logos, optionally linked |
| CTA Band | Centred call to action, with optional contact details and background photo |
| Brochure Cards | One row per brochure, each with its own ClickUp form embed |
| Download | Centred intro and download button, with an optional image below |
| Reusable Section | Shows a section from Reusable Sections, so one edit updates every page |
| Intro + 3 Cards | Intro text with up to three stat or content "pods" (e.g. 1100+ Members) |
| Card Grid | 2/3/4-column cards with image or logo, text and a button |
| Icon Grid | Services or features as icon + title + text, optionally linked |
| Icon Cards | Left-aligned header and text link, then 3 or 4 columns of icon, title, text and link, as cards or plain |
| Feature Boxes | Rounded boxes or cards with icon, title, content, logos and a button, in custom rows with per-box width and colour |
| Boxed List | Rounded panel of up to 4 coloured columns, each with subheadings and ticked points |
| Split Content | Text alongside an image, video or Google map, either side, with an optional minimum height |
| Full Width Content | Free-form stack of headings, text, images, video and buttons |
| FAQs | Question/answer accordion, with optional FAQ structured data |
| FAQs with Quick Links | Sidebar of quick links beside a title, intro and questions that slide open and shut, styled like the FAQs module |
| Testimonials | Slider of chosen testimonials, or the latest five, with an optional tick list and line of text below |
| Quote | A single highlighted quote (e.g. from the CEO) beside a photo |
| Latest News | Latest posts, optionally filtered by category |
| Featured Story | One story in a card with image, ribbon, date, text and button: pick a post or enter it manually |
| Become a Member / Contact Form | Text, phone/email and links beside a Contact Form 7 form, with an optional background image under a white or dark blue layer |
| Contact Cards | Full-width navy or light panels with a title, text, phone and email |

Every module except Reusable Section has a **Settings** tab with an optional anchor ID and a background. All of them offer White and Dark navy (Icon Cards labels these White and Blue, and its White is pale blue). Most also offer Light grey, Navy and Blue gradient, and Testimonials and Page Header keep their own default look.

Modules with a brand shape watermark have a **Watermark** switch, on by default.

Icon Cards, Icon Grid, Feature Boxes and Intro Cards can use the **icon picker**: choose from the set of 48, which change colour to suit the background, or upload your own.

Copy a module with the copy icon on its bar, then use the arrow beside **Add Module** on any page to paste it. The private **Module Library** page shows the new modules with example content.

---

## Content Model

- **Site Settings** (ACF options page): logos, general and recruitment contact details, address, top bar tagline and link, header button, footer tagline, accreditation logos, copyright, company registration, guide pages, social links.
- **Testimonials** (`testimonial`): title = person's name, featured image = photo, plus quote and role/company. No front-end URLs.
- **Team Members** (`team-member`): title = person's name, featured image = photo, plus role, bio and link. No front-end URLs.
- **Reusable Sections** (`reusable-section`): built with the page builder and placed with the Reusable Section module. No front-end URLs.
- **Insights**: the built-in Posts type, relabelled, written in the normal editor. Categories: Events (with an Event Date field, split into Upcoming and Past), Advice & Insights, Member Stories, Charity, Marketing Campaigns. Each category has an Intro Paragraph field.
- **Menus**: Primary, Mobile, Footer Column 1–3 (the menu name is used as the column heading) and Legal (sub footer). A top-level item with 6+ children, or any grandchildren, becomes a mega panel automatically.

---

## Setting Up a New Environment

1. Activate ACF Pro, ACF Extended and this theme.
2. **Custom Fields → Field Groups → Sync available** to import the field groups from `/acf-json/`.
3. **Settings → Permalinks**: choose Post name (`/%postname%/`), matching the old site's post URLs.
4. **Settings → Reading**: set the Home page and the Posts page (Insights, slug `news-events`).
5. Create the Insights categories, using the slug `events` for Events.
6. Create and assign the menus (Appearance → Menus).
7. Fill in **Site Settings**.
8. Install Contact Form 7 and add the form shortcode to the Contact Form module.

ACF Local JSON keeps field definitions version-controlled — always commit `/acf-json/` after changing fields.

If the site already has an earlier copy of the theme without the updater, upload `ttng-main-site-theme.zip` from the [latest release](https://github.com/SPS-Marketing-Repository/ttng-main-site-theme/releases/latest) once (Appearance → Themes → Add New → Upload Theme, then replace the current theme). Updates come through wp-admin from then on.

---

## Updating a Site

New versions appear in **Dashboard → Updates** and **Appearance → Themes** like any other theme, within about 15 hours of release (**Check again** on Dashboard → Updates finds them straight away). Auto-updates can be turned on per site from the theme's details.

After updating:

1. **Custom Fields → Field Groups → Sync available**, if the release changed any fields. Page content can't use new fields until this is done.
2. Clear any page or server cache.

An update replaces the whole theme folder, so **don't change files or save field groups in the ACF admin on a live site**: those changes would be lost at the next update. Make them in a development copy and release them.

Sites can opt out of automatic theme and plugin updates with `define( 'TTNG_DISABLE_AUTO_UPDATES', true );` in `wp-config.php`. Manual updates still work.

A theme folder containing `.git` isn't offered updates, because updating would delete the repository. Use `git pull` there. No GitHub token or site configuration is needed. If a site can't reach GitHub, the failure is written to the PHP error log and the site carries on as normal.

---

## Releasing an Update

Work on `develop` or a feature branch, then:

1. Set the new version in `style.css` and in this README's header ([Semantic Versioning](https://semver.org/)).
2. Move the `[Unreleased]` entries in `CHANGELOG.md` under a new `## [x.y.z] - YYYY-MM-DD` heading. The release notes are taken from this section.
3. Merge to `main`, then tag and push:

```
git tag vx.y.z
git push origin main vx.y.z
```

The **Release theme** workflow then checks the tag matches `style.css`, lints the PHP, validates the ACF JSON, builds `ttng-main-site-theme.zip` and publishes the release. Check it passed under the repository's **Actions** tab. If no run appears within a couple of minutes, start it yourself: `gh workflow run release.yml --ref vx.y.z`, or **Actions → Release theme → Run workflow** and choose the tag. If the tag's own run then starts as well, it fails harmlessly because the release already exists. A failed run publishes nothing, so no site is offered that version. Fix the problem and release it under the next version number.

Don't delete the zip from a release, and don't upload a GitHub source zip to a site: it unpacks to a different folder name and installs as a second theme.

---

## Security & Performance

- All output escaped; embeds sanitised with `ttng_kses_iframe()` (iframes only, scripts stripped)
- Module CSS is loaded only for modules present on the page
- Images lazy-load by default; the home banner's first slide loads eagerly as the LCP image
- Accessible slider (pause control, pauses on hover/focus, respects reduced motion)
- No slider or icon-font libraries — inline SVG icons and a small jQuery slider

---

## Notes

Any refactors or new modules should:
- Reuse the shared module fields and snippets (see CLAUDE.md).
- Reference tokens from `additions.css` rather than hard-coded values.
- Follow WordPress coding standards (escaping, sanitisation, nonces, capability checks).

---

## License

**Proprietary — All Rights Reserved.** See [LICENSE](LICENSE).  
Developed by **SPS Marketing** for The Travel Network Group. The source is public so that sites can receive updates; this does not grant a licence.  
Do not distribute or reuse without written permission.

---

© SPS Marketing. All Rights Reserved.
