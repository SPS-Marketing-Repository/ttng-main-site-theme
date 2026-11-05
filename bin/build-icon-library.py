"""Builds the icon picker's SVGs from the designer's Figma exports.

Each export is a 132x72 preview tile: a pale background, then a 28x28 icon in the
centre with a fixed colour. This removes the tile, crops to the icon, and swaps
the colour for currentColor so the theme's CSS can set it. Clip paths and masks
are removed too: they only trim the artwork, and their ids would clash when
several icons are inline on one page.

From the theme folder:

    python -I bin/build-icon-library.py assets/images/icons/library assets/images/icon-library

In:  {source}/Business advice/preview.svg
Out: {output}/business-advice.svg

inc/icons.php lists whatever is in the output folder, so a new icon only needs
its export adding and this running again.
"""
import glob
import os
import re
import sys
import xml.etree.ElementTree as ET

SVG = '{http://www.w3.org/2000/svg}'
# The icon's square inside the 132x72 tile.
VIEWBOX = '52 22 28 28'
# The exports use 3, heavy to suit the small tile. This matches the line weight
# in the signed-off module designs at 40px. Change it here and re-run to adjust.
STROKE_WIDTH = '2.25'
STROKE_ATTRS = ('stroke-linecap', 'stroke-linejoin')


def icon_paths(root):
    """Yields every drawn path, skipping <defs> and <mask> and looking inside <g>."""
    for child in root:
        tag = child.tag.replace(SVG, '')
        if tag == 'g':
            yield from icon_paths(child)
        elif tag == 'path':
            yield child


def clean_path(path):
    attrs = {'d': path.get('d')}
    if path.get('mask'):
        # Filled shape with an outer stroke (the pound sign in Currency). The export
        # draws this with a mask; a fill plus a stroke looks the same.
        attrs.update({'fill': 'currentColor', 'stroke': 'currentColor', 'stroke-width': '1'})
    elif path.get('stroke'):
        attrs.update({'stroke': 'currentColor', 'stroke-width': STROKE_WIDTH})
        attrs.update({a: path.get(a) for a in STROKE_ATTRS if path.get(a)})
    elif path.get('fill'):
        attrs['fill'] = 'currentColor'
    body = ' '.join(f'{k}="{v}"' for k, v in attrs.items())
    return f'<path {body}/>'


def build(source):
    root = ET.parse(source).getroot()
    paths = [clean_path(p) for p in icon_paths(root)]
    if not paths:
        raise ValueError(f'No paths found in {source}')
    return (
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="{VIEWBOX}" width="28" height="28" fill="none" '
        f'aria-hidden="true" focusable="false">{"".join(paths)}</svg>\n'
    )


if __name__ == '__main__':
    source_dir, output_dir = sys.argv[1], sys.argv[2]
    os.makedirs(output_dir, exist_ok=True)
    count = 0
    for source in sorted(glob.glob(os.path.join(source_dir, '*', 'preview.svg'))):
        name = os.path.basename(os.path.dirname(source))
        slug = re.sub(r'[^a-z0-9]+', '-', name.lower()).strip('-')
        with open(os.path.join(output_dir, slug + '.svg'), 'w', encoding='utf-8', newline='\n') as f:
            f.write(build(source))
        count += 1
    print(count, 'icons written to', output_dir)
