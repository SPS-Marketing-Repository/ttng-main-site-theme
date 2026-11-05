"""Generates ACF Extended module thumbnails (400x250 SVG wireframes).

One drawing function per page_builder layout, named after the layout.
Add one when you add a module, then regenerate (from the theme folder):

    python -I bin/module-thumbnails.py assets/images/modules

inc/acf.php's ttng_acfe_module_thumbnail() serves them. A .jpg/.png/.webp
with the same name (e.g. a real screenshot) takes priority over the .svg.

Colours follow the brand tokens in assets/css/core/additions.css.
"""
import os
import sys

W, H = 400, 250
NAVY = '#1B2D70'
NAVY_DARK = '#0F193D'
BLUE = '#009ADF'
BLUE_DARK = '#3153CC'
BLUE_LIGHT = '#B1BCE7'
GREY = '#F3F5F8'
LINE = '#D5DAE6'
IMG = '#D9DEEA'
IMG_DARK = '#B4BCD0'
WHITE = '#FFFFFF'


class Svg:
    def __init__(self, bg=WHITE):
        self.parts = [f'<rect width="{W}" height="{H}" fill="{bg}"/>']

    def rect(self, x, y, w, h, fill, r=0, stroke=None, dash=False):
        extra = f' stroke="{stroke}" stroke-width="1.5"' if stroke else ''
        extra += ' stroke-dasharray="6 4"' if dash else ''
        self.parts.append(f'<rect x="{x}" y="{y}" width="{w}" height="{h}" rx="{r}" fill="{fill}"{extra}/>')

    def circle(self, cx, cy, r, fill, stroke=None):
        extra = f' stroke="{stroke}" stroke-width="1.5"' if stroke else ''
        self.parts.append(f'<circle cx="{cx}" cy="{cy}" r="{r}" fill="{fill}"{extra}/>')

    def path(self, d, fill='none', stroke=None, width=2):
        s = f' stroke="{stroke}" stroke-width="{width}" stroke-linecap="round" stroke-linejoin="round"' if stroke else ''
        self.parts.append(f'<path d="{d}" fill="{fill}"{s}/>')

    def image(self, x, y, w, h, r=8):
        """Photo placeholder: rounded box with a sun and two hills, clipped."""
        cid = f'c{len(self.parts)}'
        self.parts.append(f'<clipPath id="{cid}"><rect x="{x}" y="{y}" width="{w}" height="{h}" rx="{r}"/></clipPath>')
        self.parts.append(f'<g clip-path="url(#{cid})">')
        self.rect(x, y, w, h, IMG)
        s = min(w, h)
        self.circle(x + w * 0.72, y + h * 0.3, s * 0.09, '#EEF1F7')
        self.path(f'M{x} {y + h} L{x + w * 0.32} {y + h * 0.5} L{x + w * 0.58} {y + h} Z', fill=IMG_DARK)
        self.path(f'M{x + w * 0.38} {y + h} L{x + w * 0.7} {y + h * 0.58} L{x + w} {y + h * 0.9} L{x + w} {y + h} Z',
                  fill=IMG_DARK)
        self.parts.append('</g>')

    def lines(self, x, y, w, n=3, gap=9, h=4, fill=LINE, last=0.6, align='left'):
        for i in range(n):
            lw = w * (last if i == n - 1 and n > 1 else 1)
            lx = x + (w - lw) / 2 if align == 'center' else x
            self.rect(lx, y + i * gap, lw, h, fill, r=h / 2)

    def heading(self, x, y, w, fill=NAVY, h=9):
        self.rect(x, y, w, h, fill, r=h / 2)

    def label(self, x, y, w=40, fill=BLUE_DARK):
        self.rect(x, y, w, 3, fill, r=1.5)

    def button(self, x, y, w=60, h=15, fill=BLUE_DARK):
        self.rect(x, y, w, h, fill, r=h / 2)

    def avatar(self, cx, cy, r, fill=IMG):
        self.circle(cx, cy, r, fill)
        self.circle(cx, cy - r * 0.2, r * 0.38, IMG_DARK)
        self.path(f'M{cx - r * 0.62} {cy + r * 0.72} Q{cx} {cy + r * 0.05} {cx + r * 0.62} {cy + r * 0.72} Z', fill=IMG_DARK)

    def tick(self, cx, cy, colour=BLUE_DARK):
        self.path(f'M{cx - 4} {cy} L{cx - 1} {cy + 3} L{cx + 4} {cy - 3}', stroke=colour, width=2)

    def intro(self, y=22, w=160, fill=NAVY, text=LINE, label=BLUE_DARK):
        """Centred section header: label, title, one line of copy."""
        self.label((W - 40) / 2, y, 40, label)
        self.heading((W - w) / 2, y + 10, w, fill, h=11)
        self.lines((W - 220) / 2, y + 30, 220, n=1, fill=text)

    def svg(self):
        body = ''.join(self.parts)
        return f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" width="{W}" height="{H}">{body}</svg>\n'


# --- One function per module -------------------------------------------------

def home_banner():
    s = Svg(NAVY)
    s.label(26, 70, 44, BLUE_LIGHT)
    s.heading(26, 82, 150, WHITE, h=14)
    s.heading(26, 102, 110, WHITE, h=14)
    s.lines(26, 128, 150, n=3, fill=BLUE_LIGHT)
    s.button(26, 164, 74, 18, BLUE)
    s.image(210, 34, 166, 182, r=14)
    for i in range(3):
        s.circle(278 + i * 12, 202, 3, WHITE if i == 0 else BLUE_LIGHT)
    return s


def page_header():
    s = Svg(NAVY)
    s.label(40, 84, 44, BLUE_LIGHT)
    s.heading(40, 96, 220, WHITE, h=18)
    s.lines(40, 128, 260, n=2, fill=BLUE_LIGHT, gap=10)
    s.button(40, 158, 74, 16, BLUE)
    return s


def full_width_content():
    s = Svg()
    s.intro(22, 170)
    s.lines(60, 66, 280, n=3, gap=10, align='center')
    s.image(50, 104, 300, 124)
    return s


def split_content():
    s = Svg()
    s.image(26, 36, 166, 178)
    s.label(214, 66)
    s.heading(214, 78, 150, h=12)
    s.lines(214, 102, 160, n=4)
    s.button(214, 150, 70, 16)
    return s


def jump_nav():
    s = Svg(GREY)
    s.heading(140, 66, 120, h=10)
    for row in range(2):
        for col in range(3):
            x = 34 + col * 114
            y = 98 + row * 44
            s.rect(x, y, 104, 34, WHITE, r=17, stroke=LINE)
            s.rect(x + 16, y + 15, 58, 4, BLUE_DARK, r=2)
            s.path(f'M{x + 82} {y + 14} L{x + 87} {y + 19} L{x + 92} {y + 14}', stroke=BLUE_DARK, width=1.6)
    return s


def tabs():
    s = Svg()
    s.intro(18, 150)
    for i in range(4):
        x = 30 + i * 86
        s.rect(x, 62, 82, 22, NAVY if i == 0 else GREY, r=11)
        s.rect(x + 18, 71, 46, 4, WHITE if i == 0 else IMG_DARK, r=2)
    s.rect(30, 96, 340, 130, WHITE, r=10, stroke=LINE)
    s.heading(46, 112, 130, h=9)
    s.lines(46, 132, 150, n=5)
    s.image(226, 110, 128, 102)
    return s


def timeline():
    s = Svg()
    s.intro(14, 150)
    s.rect(198, 58, 3, 180, LINE)
    for i in range(4):
        y = 66 + i * 44
        s.circle(199.5, y + 12, 6, BLUE_DARK)
        x = 42 if i % 2 == 0 else 222
        s.rect(x, y, 136, 36, GREY, r=6)
        s.heading(x + 10, y + 7, 34, BLUE_DARK, h=6)
        s.lines(x + 10, y + 19, 112, n=2, gap=7, h=3, fill=IMG_DARK)
    return s


def comparison_table():
    s = Svg()
    s.intro(14, 170)
    s.rect(24, 54, 352, 26, NAVY, r=6)
    for i in range(4):
        s.rect(170 + i * 52, 65, 34, 4, WHITE, r=2)
    for r in range(6):
        y = 80 + r * 26
        s.rect(24, y, 352, 26, GREY if r % 2 else WHITE)
        s.rect(36, y + 11, 100 if r % 3 else 120, 4, IMG_DARK, r=2)
        for c in range(4):
            if (r + c) % 5:
                s.tick(187 + c * 52, y + 13, BLUE_DARK if (r + c) % 3 else BLUE)
    return s


def boxed_list():
    s = Svg(NAVY)
    s.intro(14, 170, WHITE, BLUE_LIGHT, BLUE)
    for c, (x, bg) in enumerate(((44, '#132257'), (200, NAVY_DARK))):
        s.rect(x, 62, 156, 148, bg)
        s.heading(x + 38, 74, 80, WHITE, h=6)
        for r in range(3):
            y = 92 + r * 38
            s.rect(x + 12, y, 54, 4, WHITE, r=2)
            s.circle(x + 16, y + 15, 4, BLUE)
            s.lines(x + 26, y + 10, 118, n=2, gap=8, fill=BLUE_LIGHT)
    return s


def faq():
    s = Svg()
    s.intro(16, 170)
    for i in range(4):
        y = 62 + i * 44
        s.rect(50, y, 300, 34, NAVY, r=8)
        s.rect(66, y + 15, 170 - i * 12, 4, WHITE, r=2)
        s.path(f'M326 {y + 17} L336 {y + 17} M331 {y + 12} L331 {y + 22}', stroke=WHITE, width=2)
    return s


def faq_links():
    s = Svg()
    s.label(24, 34, 44, NAVY)
    for i in range(3):
        s.rect(24, 50 + i * 16, 70 if i != 1 else 84, 4, BLUE_DARK, r=2)
    s.lines(24, 106, 96, n=2, gap=8, h=3)
    s.rect(24, 128, 52, 4, BLUE_DARK, r=2)
    s.heading(136, 30, 170, h=11)
    s.lines(136, 52, 240, n=2, gap=9)
    for i in range(4):
        y = 84 + i * 36
        s.rect(136, y + 8, 180 - i * 18, 5, NAVY, r=2.5)
        s.path(f'M362 {y + 10} L372 {y + 10}' + ('' if i == 0 else f' M367 {y + 5} L367 {y + 15}'), stroke=BLUE, width=2)
        if i == 0:
            continue
        s.rect(136, y - 6, 240, 1, LINE)
    s.rect(136, 220, 240, 1, LINE)
    return s


def guide_nav():
    s = Svg(GREY)
    s.heading(30, 26, 120, h=10)
    for i in range(7):
        col = i % 2
        row = i // 2
        x, y = 30 + col * 174, 50 + row * 32
        s.rect(x, y, 166, 26, WHITE, r=6, stroke=NAVY if i == 2 else LINE)
        s.circle(x + 14, y + 13, 7, NAVY if i == 2 else BLUE_LIGHT)
        s.rect(x + 28, y + 11, 96, 4, NAVY if i == 2 else IMG_DARK, r=2)
    s.rect(30, 190, 166, 38, WHITE, r=8, stroke=LINE)
    s.rect(204, 190, 166, 38, WHITE, r=8, stroke=LINE)
    s.path('M44 209 L52 201 M44 209 L52 217', stroke=BLUE_DARK, width=2)
    s.path('M356 209 L348 201 M356 209 L348 217', stroke=BLUE_DARK, width=2)
    s.rect(64, 206, 80, 5, NAVY, r=2.5)
    s.rect(256, 206, 80, 5, NAVY, r=2.5)
    return s


def intro_cards():
    s = Svg()
    s.intro(22, 160)
    for i in range(3):
        x = 30 + i * 117
        s.rect(x, 78, 106, 140, NAVY if i != 2 else BLUE_DARK, r=14)
        s.path(f'M{x + 18} 100 l4 8 8 1 -6 6 2 8 -8 -4 -8 4 2 -8 -6 -6 8 -1 z', fill=BLUE)
        s.heading(x + 14, 130, 64, WHITE, h=16)
        s.lines(x + 14, 158, 76, n=2, fill=BLUE_LIGHT, gap=9)
    return s


def card_grid():
    s = Svg()
    s.intro(16, 170)
    for i in range(4):
        x = 20 + i * 92
        s.rect(x, 62, 84, 164, WHITE, r=10, stroke=LINE)
        s.image(x + 12, 72, 60, 34, r=4)
        s.heading(x + 10, 118, 54, h=6)
        s.lines(x + 10, 134, 64, n=3, gap=8, h=3)
        s.button(x + 10, 196, 54, 14)
    return s


def icon_grid():
    s = Svg(NAVY)
    s.intro(14, 170, fill=WHITE, text=BLUE_LIGHT, label=BLUE_LIGHT)
    for r in range(2):
        for c in range(4):
            x = 24 + c * 90
            y = 62 + r * 88
            s.circle(x + 36, y + 16, 13, BLUE)
            s.heading(x + 10, y + 38, 52, WHITE, h=6)
            s.lines(x + 6, y + 52, 60, n=2, gap=7, h=3, fill=BLUE_LIGHT, align='center')
    return s


def icon_cards():
    s = Svg('#F2F8FE')
    s.label(24, 20)
    s.heading(24, 30, 170, h=11)
    s.rect(316, 34, 60, 4, NAVY, r=2)
    for r in range(2):
        for c in range(3):
            x = 24 + c * 120
            y = 56 + r * 90
            s.rect(x, y, 112, 82, WHITE, r=8, stroke=LINE)
            s.circle(x + 18, y + 16, 7, BLUE)
            s.heading(x + 11, y + 30, 70, h=6)
            s.lines(x + 11, y + 43, 90, n=2, gap=7, h=3)
            s.rect(x + 11, y + 65, 34, 4, BLUE_DARK, r=2)
    return s


def testimonials():
    s = Svg(GREY)
    s.intro(16, 150)
    for i in range(2):
        x = 26 + i * 180
        s.rect(x, 64, 168, 160, WHITE, r=14)
        s.path(f'M{x + 18} {96} q0 -14 12 -16 l0 5 q-6 2 -6 7 l6 0 0 12 -12 0 z', fill=BLUE)
        s.path(f'M{x + 38} {96} q0 -14 12 -16 l0 5 q-6 2 -6 7 l6 0 0 12 -12 0 z', fill=BLUE)
        s.lines(x + 18, 120, 132, n=3)
        s.avatar(x + 32, 192, 14)
        s.heading(x + 54, 184, 70, h=6)
        s.lines(x + 54, 196, 84, n=1, h=3)
    return s


def quote():
    s = Svg()
    s.rect(24, 40, 230, 170, NAVY, r=18)
    s.path('M44 96 q0 -24 20 -28 l0 8 q-10 3 -10 12 l10 0 0 20 -20 0 z', fill=BLUE)
    s.path('M78 96 q0 -24 20 -28 l0 8 q-10 3 -10 12 l10 0 0 20 -20 0 z', fill=BLUE)
    s.lines(44, 126, 180, n=3, fill=WHITE, gap=11)
    s.heading(44, 176, 70, BLUE, h=7)
    s.image(266, 40, 110, 170, r=18)
    return s


def people_slider():
    s = Svg()
    s.intro(16, 150)
    for i in range(4):
        x = 34 + i * 86
        s.rect(x, 64, 78, 138, WHITE, r=10, stroke=LINE)
        s.avatar(x + 39, 100, 26)
        s.heading(x + 12, 138, 54, h=6)
        s.lines(x + 12, 152, 54, n=2, gap=8, h=3)
    s.path('M20 133 L12 125 M20 117 L12 125', stroke=NAVY, width=2)
    s.path('M380 133 L388 125 M380 117 L388 125', stroke=NAVY, width=2)
    for i in range(3):
        s.circle(188 + i * 12, 220, 3, BLUE_DARK if i == 0 else LINE)
    return s


def logo_strip():
    s = Svg()
    s.heading(140, 70, 120, h=10)
    for i in range(5):
        x = 22 + i * 73
        s.rect(x, 104, 64, 42, GREY, r=6)
        s.circle(x + 18, 125, 8, IMG_DARK)
        s.rect(x + 30, 122, 24, 6, IMG_DARK, r=3)
    return s


def latest_news():
    s = Svg()
    s.intro(16, 170)
    for i in range(3):
        x = 22 + i * 122
        s.rect(x, 62, 112, 166, WHITE, r=12, stroke=LINE)
        s.image(x, 62, 112, 66, r=12)
        s.lines(x + 10, 140, 44, n=1, h=3, fill=IMG_DARK)
        s.heading(x + 10, 152, 80, h=7)
        s.lines(x + 10, 170, 92, n=2, gap=8, h=3)
        s.lines(x + 10, 206, 50, n=1, h=4, fill=BLUE_DARK)
    return s


def featured_story():
    s = Svg(NAVY_DARK)
    s.rect(24, 45, 352, 160, WHITE, r=10)
    s.image(24, 45, 148, 160, r=10)
    s.path('M24 72 L51 45 L84 45 L24 105 Z', fill='#D6346C')
    s.lines(192, 76, 50, n=1, h=3)
    s.heading(192, 90, 150, h=10)
    s.lines(192, 112, 164, n=3, gap=8, h=3)
    s.button(192, 148, 56, 15, BLUE)
    return s


def contact_form():
    s = Svg(BLUE_DARK)
    s.label(26, 60, 40, BLUE_LIGHT)
    s.heading(26, 72, 120, WHITE, h=12)
    s.lines(26, 96, 130, n=3, fill=BLUE_LIGHT)
    for i in range(2):
        s.circle(32, 142 + i * 22, 6, BLUE)
        s.rect(46, 140 + i * 22, 90, 4, WHITE, r=2)
    s.rect(180, 28, 196, 194, WHITE, r=14)
    for i in range(4):
        s.rect(194, 44 + i * 30, 168, 22, GREY, r=6, stroke=LINE)
    s.button(194, 174, 92, 22)
    return s


def become_member():
    s = Svg()
    s.image(0, 0, W, H, r=0)
    s.parts.append(f'<rect width="{W}" height="{H}" fill="{WHITE}" fill-opacity="0.8"/>')
    s.heading(24, 44, 110, h=12)
    s.lines(24, 66, 120, n=2)
    for i in range(3):
        s.rect(24, 92 + i * 13, 88, 5, NAVY, r=2.5)
    s.rect(24, 138, 80, 4, BLUE_DARK, r=2)
    s.rect(168, 28, 208, 194, WHITE, r=10)
    for r in range(2):
        for c in range(2):
            s.rect(182 + c * 94, 42 + r * 26, 86, 18, WHITE, r=4, stroke=LINE)
    s.rect(182, 94, 180, 18, WHITE, r=4, stroke=LINE)
    s.rect(182, 120, 180, 38, WHITE, r=4, stroke=LINE)
    s.rect(182, 166, 180, 18, WHITE, r=4, stroke=LINE)
    s.button(182, 194, 92, 16)
    return s


def cta_band():
    s = Svg(NAVY_DARK)
    s.image(0, 0, W, H, r=0)
    s.rect(0, 0, W, H, NAVY)
    s.path('M300 0 L400 0 L400 250 L220 250 Z', fill=BLUE_DARK)
    s.label(160, 76, 80, BLUE_LIGHT)
    s.heading(90, 90, 220, WHITE, h=15)
    s.lines(90, 118, 220, n=2, fill=BLUE_LIGHT, gap=10, align='center')
    s.button(120, 150, 74, 20, BLUE)
    s.rect(206, 150, 74, 20, 'none', r=10, stroke=WHITE)
    return s


def brochure_cards():
    s = Svg()
    s.intro(10, 160)
    for i in range(3):
        y = 50 + i * 66
        s.rect(24, y, 352, 58, WHITE, r=8, stroke=LINE)
        s.image(32, y + 7, 34, 44, r=3)
        s.heading(76, y + 14, 92, h=7)
        s.lines(76, y + 30, 92, n=2, gap=7, h=3)
        for f in range(2):
            s.rect(190 + f * 72, y + 10, 66, 14, GREY, r=4, stroke=LINE)
        s.button(190, y + 32, 66, 14)
    return s


def download():
    s = Svg()
    s.rect(182, 14, 18, 3, NAVY_DARK, r=1.5)
    s.rect(200, 14, 18, 3, BLUE, r=1.5)
    s.intro(24, 150)
    s.button(160, 76, 80, 16, BLUE)
    s.image(145, 106, 110, 130)
    return s


def global_section():
    s = Svg(GREY)
    s.rect(40, 34, 320, 182, WHITE, r=12, stroke=BLUE_DARK, dash=True)
    for i in range(3):
        y = 54 + i * 50
        s.rect(62, y, 276, 40, GREY, r=6)
        s.rect(76, y + 12, 110, 6, NAVY, r=3)
        s.rect(76, y + 24, 170, 4, IMG_DARK, r=2)
    s.circle(342, 46, 20, BLUE_DARK)
    s.path('M333 46 a9 9 0 0 1 15 -6 l3 3 m0 -7 l0 7 -7 0 M351 46 a9 9 0 0 1 -15 6 l-3 -3 m0 7 l0 -7 7 0', stroke=WHITE, width=1.8)
    return s


MODULES = [
    home_banner, page_header, full_width_content, split_content, jump_nav, tabs, timeline, comparison_table, boxed_list, faq, faq_links,
    guide_nav, intro_cards, card_grid, icon_grid, icon_cards, testimonials, quote, people_slider, logo_strip, latest_news, featured_story,
    contact_form, become_member, cta_band, brochure_cards, download, global_section,
]

if __name__ == '__main__':
    out = sys.argv[1]
    os.makedirs(out, exist_ok=True)
    for fn in MODULES:
        with open(os.path.join(out, fn.__name__ + '.svg'), 'w', encoding='utf-8', newline='\n') as f:
            f.write(fn().svg())
    print(len(MODULES), 'thumbnails written to', out)
