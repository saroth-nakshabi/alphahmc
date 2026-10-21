"""Build the self-hosted Font Awesome 6.5.1 in public/front/assets/vendor/fontawesome-6.5.1.

The fonts are subset to the icons the site uses (views, app code, front JS/CSS and any SQL dumps
passed in, because planner steps store icon classes in the database). Each subset face carries a
unicode-range, and the full font is declared before it for the same family, so an icon outside
the subset still renders: the browser then downloads the full file for it.

Run after adding icons to the site (needs: pip install fonttools brotli):
    cd <folder holding all.min.css and the four .woff2 files from cdnjs font-awesome/6.5.1>
    python <repo>/scripts/fontawesome/build.py <repo> <repo>/public/front/assets/vendor/fontawesome-6.5.1 [dump.sql ...]
"""
import glob
import os
import re
import sys

from fontTools import subset
from fontTools.ttLib import TTFont

BS = chr(92)  # backslash, kept out of string literals on purpose

repo, out, extras = sys.argv[1], sys.argv[2], sys.argv[3:]
css = open('all.min.css', encoding='utf-8').read()

# Glyph rules look like .fa-a:before,.fa-b:before{content:"<backslash>f00d"}
name2cp = {}
glyph_rule = re.compile(r'((?:\.fa-[a-z0-9-]+:before,?)+)\{content:"' + re.escape(BS) + r'([0-9a-f]+)"\}')
for sels, cp in glyph_rule.findall(css):
    for n in re.findall(r'\.fa-([a-z0-9-]+):before', sels):
        name2cp[n] = int(cp, 16)

files = []
for pat in ['resources/**/*.php', 'app/**/*.php', 'routes/*.php', 'config/*.php',
            'public/front*/**/*.js', 'public/front*/**/*.css']:
    files += glob.glob(os.path.join(repo, pat), recursive=True)
files = [f for f in files if '/vendor/' not in f.replace(BS, '/')] + extras

tokens, raw = set(), set()
css_content = re.compile(r'content:\s*["\']' + re.escape(BS) + r'([ef][0-9a-f]{3})["\']', re.I)
for f in files:
    try:
        text = open(f, encoding='utf-8', errors='ignore').read()
    except OSError:
        continue
    tokens |= set(re.findall(r'fa-([a-z0-9-]+)', text))
    if 'Font Awesome' in text or 'FontAwesome' in text:   # pseudo-element icons; skips other icon fonts
        raw |= {int(cp, 16) for cp in css_content.findall(text)}

used = {name2cp[t] for t in tokens if t in name2cp} | raw
print('files', len(files), 'icons', len(used), 'css content codepoints', len(raw))

os.makedirs(os.path.join(out, 'webfonts'), exist_ok=True)
os.makedirs(os.path.join(out, 'css'), exist_ok=True)

ranges = {}
for name in ['fa-solid-900', 'fa-regular-400', 'fa-brands-400']:
    font = TTFont(name + '.woff2')
    keep = sorted(used & set(font.getBestCmap().keys()))
    opts = subset.Options()
    opts.flavor = 'woff2'
    opts.layout_features = ['*']
    opts.notdef_outline = True
    sub = subset.Subsetter(opts)
    sub.populate(unicodes=keep)
    sub.subset(font)
    dst = os.path.join(out, 'webfonts', name + '-subset.woff2')
    font.save(dst)
    ranges[name] = ','.join('u+%x' % c for c in keep)
    print(name, len(keep), 'glyphs', os.path.getsize(dst), 'bytes, full', os.path.getsize(name + '.woff2'))
    with open(name + '.woff2', 'rb') as src, open(os.path.join(out, 'webfonts', name + '.woff2'), 'wb') as cp:
        cp.write(src.read())
with open('fa-v4compatibility.woff2', 'rb') as src, open(os.path.join(out, 'webfonts', 'fa-v4compatibility.woff2'), 'wb') as cp:
    cp.write(src.read())


def faces(family, weight, name, with_style=True):
    style = 'font-style:normal;' if with_style else ''
    head = '@font-face{font-family:"%s";%sfont-weight:%s;font-display:swap;' % (family, style, weight)
    full = head + 'src:url(../webfonts/%s.woff2) format("woff2")}' % name
    part = head + 'src:url(../webfonts/%s-subset.woff2) format("woff2");unicode-range:%s}' % (name, ranges[name])
    return full + part  # the later face wins inside its range; other icons fall back to the full file


new_faces = (faces('Font Awesome 6 Brands', 400, 'fa-brands-400')
             + faces('Font Awesome 6 Free', 400, 'fa-regular-400')
             + faces('Font Awesome 6 Free', 900, 'fa-solid-900')
             + faces('Font Awesome 5 Brands', 400, 'fa-brands-400', False)
             + faces('Font Awesome 5 Free', 900, 'fa-solid-900', False)
             + faces('Font Awesome 5 Free', 400, 'fa-regular-400', False))

blocks = [b for b in re.findall(r'@font-face\{[^}]*\}', css) if '"FontAwesome"' not in b]
first = css.index(blocks[0])
for b in blocks:
    css = css.replace(b, '', 1)
css = css[:first] + new_faces + css[first:]
css = css.replace('font-family:"FontAwesome";font-display:block;', 'font-family:"FontAwesome";font-display:swap;')
css = re.sub(r',url\(\.\./webfonts/[a-z0-9-]+\.ttf\) format\("truetype"\)', '', css)

header = ('/* Font Awesome Free 6.5.1 by @fontawesome (https://fontawesome.com/license/free), self-hosted.\n'
          '   Fonts subset to the icons this site uses; the full files stay as a unicode-range fallback.\n'
          '   Rebuild with scripts/fontawesome/build.py */\n')
if css.startswith('/*'):
    css = css.split('*/', 1)[1].lstrip('\n')
dst = os.path.join(out, 'css', 'fontawesome.min.css')
with open(dst, 'w', encoding='utf-8', newline='\n') as fh:
    fh.write(header + css)
print('css', os.path.getsize(dst), 'bytes')
