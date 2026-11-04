# DESIGN.md — The Travel Network Group

> Design system reference for anyone (or any AI agent) building UI on this theme.
> All values live as CSS custom properties in `assets/css/core/additions.css` —
> reference the tokens, never hard-code the hex/pixel values below.
> Values marked `[ASSUMED]` were inferred rather than taken from a confirmed source.

---

## 1. Visual Theme & Atmosphere

- **Mood:** Professional, trustworthy and supportive — a large network that still feels personal
- **Design philosophy:** Clean, content-first, with confident navy blocks and bright blue accents
- **Density:** Spacious — generous section padding, cards with breathing room
- **Personality:** Corporate-but-friendly membership organisation
- **Signature details:** "Pod" shapes (rounded on three corners, square bottom-left), navy-to-blue gradients, pill-shaped buttons

---

## 2. Colour Palette & Roles

| Token | Hex | Role |
|---|---|---|
| `--navy` | `#1B2D70` | Primary — header, footer, headings, navy sections |
| `--navy-dark` | `#0F193D` | "Dark navy" section background, sticky header |
| `--navy-mid` | `#2944A3` | Gradient stop |
| `--navy-hover` | `#22378A` | Hover/open state for navy panels |
| `--navy-button` | `#0E3178` | Solid navy buttons (`.btn--outline`) |
| `--footer-bg` | `#090F24` | Footer |
| `--blue` | `#009ADF` | Accent — icons, stat numbers, large text only (3:1 on white) |
| `--blue-dark` | `#3153CC` | Accessible blue for small text, links, labels, focus rings (6.2:1 on white) |
| `--blue-light` | `#B1BCE7` | Small accent text on navy (labels, hover) |
| `--grey-light` | `#F3F5F8` | Light section background |
| `--grey` | `#E7E8EB` | Borders, dividers |
| `--grey-text` | `#5A6380` | Muted text — dates, captions, roles |
| `--text` | `#333333` | Body copy |
| `--white` | `#FFFFFF` | Page and card background |
| Error | `#C62828` | Form validation `[ASSUMED]` |

### Gradients

| Token | Value | Used on |
|---|---|---|
| `--gradient-btn` | `127deg, #3153CC → #009ADF` | Gradient buttons (hero, form submit, brochure pill) |
| `--gradient-section` | `0deg, #1B2D70 → #3354CC` | "Blue gradient" section background |
| `--gradient-testimonials` | `0deg, #162255 → #3354CC` | Testimonials |
| `--gradient-hero` | `165deg, #17265E 42% → #3153CC` | Home banner |
| `--gradient-pod-1/2/3` | navy → blue variants | Intro card pods (cycle 1, 2, 3) |

---

## 3. Typography Rules

Font: **Montserrat** (Google Fonts, weights 400 / 500 / 600 / 700) via `--content` and `--header`.

| Role | Weight | Size (mobile → desktop) | Line height |
|---|---|---|---|
| H1 (`--fs-h1`) | 600 | 34 → 52px | 1.2 |
| H2 and section title (`.section-title`) | 400 | 28 → 38px | 1.2 (section title 1.1) |
| H3 (`--fs-h3`) | 600 | 20 → 24px | 1.2 |
| H4 (`--fs-h4`) | 600 | 18px | 1.2 |
| Lead (`--fs-lead`) | 500 | 17 → 20px | 1.45 |
| Body (`--fs-body`) | 500 | 16px | 1.45 (23px) |
| Small / caption (`--fs-small`) | 500 | 14px | 1.45 |
| Section label (`.section-label`) | 600, uppercase; last words in `--blue` | 15px | 1 |
| Stat number (`--fs-stat`) | 700 | 40 → 51px | 1 |
| Button | 500, uppercase | 14px | 1 |
| Navigation | 400, uppercase | 16px | 23px |

- Headings are navy; on dark sections (`.is-dark`) all text flips to white.
- One H1 per page — the Home Banner or Page Header title.

---

## 4. Component Stylings

### Buttons (`ttng_the_button()`)

```css
/* .btn (all buttons) */
border-radius: 100px;
padding: 16px 32px 15px;
font: 500 14px/1 Montserrat;
text-transform: uppercase;

/* .btn--primary */        background: var(--blue); color: #fff;          hover → --blue-dark
/* .btn--gradient */       127deg, #3153CC → #009ADF → #3153CC at 200% width; slides on hover (hero, forms)
/* .btn--outline */        background: var(--navy-button); color: #fff;   hover → --navy
/* .btn--outline-light */  border: 2px solid #fff; color: #fff;           hover → white fill
```

Hover lifts the button 2px. Inside `.is-dark` sections, `.btn--outline` automatically renders as outline-light. Home Banner primary buttons use the gradient.

### Cards

| Card | Background | Radius | Shadow | Hover |
|---|---|---|---|---|
| Intro pod | Navy gradients | `25px 25px 25px 0` (`--radius-pod`) | none | lift 4px (if linked) |
| Grid card | White | 25px | `--shadow-card` | `--shadow-hover`, image zoom |
| News card | White | 25px | `--shadow-card` | `--shadow-hover`, image zoom |
| Testimonial | One-at-a-time slider on `--gradient-testimonials` | — | none | — |
| People | Navy body, 302px photo | 25px (photo top, body bottom) | none | — |
| Feature box | Navy, blue, dark navy or light grey per box | 15px | none | — |
| Contact card | Navy with brand-shape watermark, or light | 20px | none | — |
| Contact form panel | White | `--radius-pod` | deep navy shadow | — |
| Quote panel | `--navy` + brand-shape watermark (5%) | 25px | none | — |
| FAQ bar | `--navy` (`--navy-hover` when hovered/open), answers on white | 15px | none | background darkens, plus → minus |

On light-grey sections the card shadows are dropped.

### Forms & Inputs (Contact Form 7)

- Input background: white; border `1px solid #C9CFDF`; radius 12px; padding 14px 18px
- Focus: border `--blue-dark` plus a 3px `rgba(49, 83, 204, .2)` ring
- Labels above fields, 14px / 600, navy
- Submit uses the button gradient
- Errors: inline red message below the field

### Navigation

- Top bar: white, 55px tall (46px ≤768px): star tagline, email and phone with dividers, gradient "Request a Brochure" pill
- Header: 165px tall (110px ≤1280px, 96px ≤768px), white logo and uppercase links. Transparent over the hero on the home page, navy underlay on inner pages
- Sticky state (`body.header-is-stuck`): the header scrolls away, then slides back pinned to the top as a 90px dark navy bar with a smaller logo
- Mega panels (`inc/mega-menu.php`): overview, child links, and an image + navy card preview that swaps on hover/focus
- Hamburger + off-canvas menu at **≤1280px**

---

## 5. Layout Principles

### Spacing

| Token | Value | Usage |
|---|---|---|
| `--section-space` | 50 → 70px (fluid) | Top/bottom padding of every module |
| `--stack-space` | 35px | Section header → grid gap |
| Card padding | 26–44px | Inside cards |
| Grid row gap | 24–30px | Between card rows |

Two consecutive white (or light-grey) sections drop the second one's top padding so they read as one block.

### Grid

- Bootstrap grid only (`bootstrap-custom.min.css`) — `.row` / `.col-*`
- Utilities not in that build are defined in `additions.css`: `text-center`, `text-start`, `justify-content-center`, `align-items-center`, `order-lg-1/2`, `stretched-link`
- Containers (`base.css`): 1528px content + 20px gutters, 1568px maximum. The header container is the same

---

## 6. Depth & Elevation

| Level | Usage | CSS |
|---|---|---|
| Flat | Cards on light grey | `box-shadow: none` |
| Raised | Cards | `--shadow-card: 0 10px 30px rgba(15,25,61,.08)` |
| Hover | Cards on hover | `--shadow-hover: 0 16px 40px rgba(15,25,61,.16)` |
| Overlay | Dropdowns, scrolled header | `0 16px 32px rgba(15,25,61,.18)` / `0 8px 24px rgba(15,25,61,.3)` |

### Motion

Content animates once as it scrolls into view, using `ttng_reveal()` in `main.js`. Keep it calm: one soft movement per element, nothing that loops or repeats on scroll.

| Variant | Movement | Used on |
|---|---|---|
| Default | Fade up 30px, .7s | Headings, text, panels. Cards and row items are staggered by .12s |
| `from-left` / `from-right` | Slide 40px, .9s (fade up when stacked, ≤992px) | Split Content text and media |
| `scale` | Fade in from 96%, .9s | Images |
| `fade` | Fade only, .7s | Carousel items, full-bleed images |

Page Headers animate on load, with a 9s settle on a full-size background image. Hover: cards lift 4px, images inside them zoom to 105%, button icons nudge 3px. All of it is off for reduced-motion users.

---

## 7. Do's and Don'ts

### Do
- Use the tokens in `additions.css` — never hard-code colours, radii or font sizes.
- Use `--blue-dark` (not `--blue`) for small text and links on white.
- Use the pod radius (`--radius-pod`) for feature panels and imagery.
- Keep one primary button per group; pair it with an outline button.
- Use inline SVG icons (`ttng_icon()` / `social-links.php` pattern).

### Don't
- Don't put small white text on `--blue` (#009ADF) — it fails contrast.
- Don't add a second H1 — the Home Banner or Page Header owns it.
- Don't add slider or icon-font libraries (Slick, AOS, Font Awesome) — use `.ttng-slider` and inline SVG.
- Don't use `!important` without a comment explaining why.

---

## 8. Responsive Behaviour

| Breakpoint | Change |
|---|---|
| ≤1366px | Nav links tighten |
| ≤1280px | Hamburger menu; header 110px; card padding reduces |
| ≤992px | Two-column modules stack (media below text) |
| ≤768px | Header 96px, top bar 46px; top bar centres, email shows icon only; buttons stretch |
| ≤600px | Smaller card padding |

- Module CSS breakpoints: 1536, 1366, 1280, 992, 768, 600 (desktop-first)
- Type is fluid via `clamp()`, so there are no per-breakpoint font overrides
- Touch targets: 44px minimum

---

## 9. Agent Prompt Guide

**Brand colours (copy-paste ready):**
```
Primary (navy):   #1B2D70
Dark navy:        #0F193D
Accent blue:      #009ADF  (large text / icons only)
Accessible blue:  #3153CC
Light grey bg:    #F3F5F8
Text:             #333333
```

**Suggested prompts:**

- *New module:* "Build a new page builder module for the TTNG theme. Copy `BLANK.php` / `BLANK.css`, open the section with `ttng_module_attrs()`, reuse the `section-header` and `ctas` snippets, and use the tokens from `additions.css` (Montserrat, `--navy`, `--radius-pod`, `--section-space`)."
- *New card:* "White card, 25px radius, `--shadow-card`, navy H3, 16px body text, primary pill button pinned to the bottom."
- *New form:* "Style it with the existing CF7 rules in `additions.css` — 12px-radius inputs, navy labels above fields, gradient pill submit."

---

## Source Notes

- **Colours, gradients, font sizes:** taken from the Elementor global kit and page CSS of the previous TTNG site, and measured from its staging copy at 1440px (2.4.0).
- **Fluid type ranges, spacing scale, shadows, form styling:** derived for this build — `[ASSUMED]` until confirmed against final designs.
- **Typography:** Montserrat via Google Fonts (`inc/assets.php`). The old site also loaded Roboto and Poppins; both were dropped.
