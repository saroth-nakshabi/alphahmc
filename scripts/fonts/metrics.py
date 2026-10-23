# Metric-matched fallback faces for resources/views/front/partials/fonts.blade.php: size-adjust and
# ascent/descent/line-gap overrides so a local font takes the same space as the web font (no CLS on swap).
# Run from the repo root: py -m pip install fonttools brotli; py scripts/fonts/metrics.py [page url]
# Needs Arial/Times New Roman (C:/Windows/Fonts) and Roboto/Noto Serif variable TTFs from github.com/google/fonts
# next to this script (Roboto-VF.ttf, NotoSerif-VF.ttf; not committed). Paste the output into the partial.
# fonts, from the real page text. Prints @font-face blocks.
import io, re, html
from fontTools.ttLib import TTFont
from fontTools.varLib import instancer

W = r'C:/Windows/Fonts/'
HERE = __import__('os').path.dirname(__import__('os').path.abspath(__file__)) + '/'
FONTS = 'public/front/assets/fonts/'
TARGETS = {'Outfit': FONTS + 'outfit-latin.woff2', 'Libre Baskerville': FONTS + 'libre-baskerville-latin.woff2', 'Inter': FONTS + 'inter-latin.woff2'}
SERIF = {'Libre Baskerville'}
# fallback sets: (label suffix, regular file/instance, bold file/instance, local() names regular, local() names bold)
SANS = [('', (W + 'arial.ttf', None), (W + 'arialbd.ttf', None),
         ["Arial", "ArialMT", "Liberation Sans", "LiberationSans"], ["Arial Bold", "Arial-BoldMT", "Liberation Sans Bold", "LiberationSans-Bold"]),
        (' Android', (HERE + 'Roboto-VF.ttf', 400), (HERE + 'Roboto-VF.ttf', 700), ["Roboto", "Roboto-Regular"], ["Roboto Bold", "Roboto-Bold"])]
SERIFS = [('', (W + 'times.ttf', None), (W + 'timesbd.ttf', None),
           ["Times New Roman", "TimesNewRomanPSMT", "Liberation Serif", "LiberationSerif"], ["Times New Roman Bold", "TimesNewRomanPS-BoldMT", "Liberation Serif Bold", "LiberationSerif-Bold"]),
          (' Android', (HERE + 'NotoSerif-VF.ttf', 400), (HERE + 'NotoSerif-VF.ttf', 700), ["Noto Serif", "NotoSerif-Regular"], ["Noto Serif Bold", "NotoSerif-Bold"])]

import sys, urllib.request
page = urllib.request.urlopen(sys.argv[1] if len(sys.argv) > 1 else 'https://alphahmc.com/services/dha-license-healthcare-professionals-dubai').read().decode('utf-8')
text = html.unescape(re.sub(r'<[^>]+>', ' ', re.sub(r'<(script|style)\b.*?</\1>', ' ', page, flags=re.S)))
text = re.sub(r'\s+', ' ', text)[:20000]

def load(path, wght):
    f = TTFont(path)
    if 'fvar' in f and wght is not None:
        axes = {a.axisTag: a for a in f['fvar'].axes}
        loc = {'wght': wght}
        if 'wdth' in axes: loc['wdth'] = 100
        if 'opsz' in axes: loc['opsz'] = axes['opsz'].defaultValue
        f = instancer.instantiateVariableFont(f, loc)
    return f

def avg_width(f):
    cmap, hmtx, upm = f.getBestCmap(), f['hmtx'], f['head'].unitsPerEm
    tot = n = 0
    for ch in text:
        g = cmap.get(ord(ch))
        if g: tot += hmtx[g][0]; n += 1
    return tot / n / upm

def vmetrics(f):
    h, upm = f['hhea'], f['head'].unitsPerEm
    return h.ascent / upm, abs(h.descent) / upm, h.lineGap / upm

out = []
for fam, file in TARGETS.items():
    for wght, label in ((400, 'regular'), (700, 'bold')):
        t = load(file, wght)
        tw = avg_width(t); asc, desc, gap = vmetrics(t)
        for suffix, reg, bold, names_r, names_b in (SERIFS if fam in SERIF else SANS):
            path, inst = reg if wght == 400 else bold
            fb = load(path, inst)
            sa = tw / avg_width(fb)
            names = names_r if wght == 400 else names_b
            src = ', '.join(f'local("{n}")' for n in names)
            out.append(f'@font-face {{ font-family: "{fam} Fallback{suffix}"; src: {src}; font-weight: {wght}; '
                       f'size-adjust: {sa*100:.2f}%; ascent-override: {asc/sa*100:.2f}%; descent-override: {desc/sa*100:.2f}%; line-gap-override: {gap/sa*100:.2f}%; }}')
print('\n'.join(out))
