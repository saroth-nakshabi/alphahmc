{{-- Floor comparison (drawings from the prototype, kept in code) and the key facts for this facility. --}}
<section class="lp-section" id="planning">
    <div class="lp-wrap">
        <div class="lp-split-head">
            <h2>{{ $d['heading'] }}</h2>
            @if($d['text'] !== '')<p class="lp-lede">{{ $d['text'] }}</p>@endif
        </div>
        <div class="lp-compare" id="lp-compare" style="--pos:50%">
            <svg viewBox="0 0 1200 560" role="img" aria-label="Illustration of an unplanned layout: two treatment rooms, a long corridor and an oversized waiting area">
        <rect x="0" y="0" width="1200" height="560" fill="#ffffff"/>
        <defs><pattern id="lp-hatch" width="12" height="12" patternUnits="userSpaceOnUse" patternTransform="rotate(45)"><rect width="12" height="12" fill="#fbecea"/><line x1="0" y1="0" x2="0" y2="12" stroke="#e8b4ad" stroke-width="3"/></pattern></defs>
        <rect x="60" y="60" width="1080" height="440" fill="#ffffff" stroke="#0e2427" stroke-width="4"/>
        <rect x="60" y="60" width="430" height="250" fill="#eef3f3" stroke="#0e2427" stroke-width="2"/>
        <text x="275" y="190" text-anchor="middle" font-family="Outfit, sans-serif" font-size="22" fill="#3c5457">Waiting</text>
        <rect x="60" y="310" width="430" height="190" fill="#eef3f3" stroke="#0e2427" stroke-width="2"/>
        <text x="275" y="410" text-anchor="middle" font-family="Outfit, sans-serif" font-size="22" fill="#3c5457">Reception</text>
        <rect x="490" y="60" width="140" height="440" fill="url(#lp-hatch)" stroke="#0e2427" stroke-width="2"/>
        <text x="560" y="285" text-anchor="middle" font-family="Outfit, sans-serif" font-size="19" fill="#b42318" transform="rotate(-90 560 285)">Corridor with no clinical use</text>
        <rect x="630" y="60" width="255" height="220" fill="#e3f1f2" stroke="#0e2427" stroke-width="2"/>
        <text x="757" y="175" text-anchor="middle" font-family="Outfit, sans-serif" font-size="22" fill="#0e2427">Treatment</text>
        <rect x="885" y="60" width="255" height="220" fill="#e3f1f2" stroke="#0e2427" stroke-width="2"/>
        <text x="1012" y="175" text-anchor="middle" font-family="Outfit, sans-serif" font-size="22" fill="#0e2427">Treatment</text>
        <rect x="630" y="280" width="510" height="120" fill="url(#lp-hatch)" stroke="#0e2427" stroke-width="2"/>
        <text x="885" y="347" text-anchor="middle" font-family="Outfit, sans-serif" font-size="19" fill="#b42318">Store placed in clinical space</text>
        <rect x="630" y="400" width="255" height="100" fill="#f4f7f7" stroke="#0e2427" stroke-width="2"/>
        <text x="757" y="457" text-anchor="middle" font-family="Outfit, sans-serif" font-size="20" fill="#3c5457">Staff</text>
        <rect x="885" y="400" width="255" height="100" fill="#f4f7f7" stroke="#0e2427" stroke-width="2"/>
        <text x="1012" y="457" text-anchor="middle" font-family="Outfit, sans-serif" font-size="20" fill="#3c5457">Sterilisation, far from treatment</text>
      </svg>
            <div class="lp-after" aria-hidden="true">
                <svg viewBox="0 0 1200 560">
          <rect x="0" y="0" width="1200" height="560" fill="#ffffff"/>
          <rect x="60" y="60" width="1080" height="440" fill="#ffffff" stroke="#0e2427" stroke-width="4"/>
          <rect x="60" y="60" width="260" height="250" fill="#eef3f3" stroke="#0e2427" stroke-width="2"/>
          <text x="190" y="190" text-anchor="middle" font-family="Outfit, sans-serif" font-size="22" fill="#3c5457">Waiting</text>
          <rect x="60" y="310" width="260" height="190" fill="#eef3f3" stroke="#0e2427" stroke-width="2"/>
          <text x="190" y="410" text-anchor="middle" font-family="Outfit, sans-serif" font-size="22" fill="#3c5457">Reception</text>
          <rect x="320" y="245" width="820" height="70" fill="#f8fbfb" stroke="#0e2427" stroke-width="2"/>
          <text x="730" y="287" text-anchor="middle" font-family="Outfit, sans-serif" font-size="18" fill="#3c5457">Short patient corridor</text>
          <g fill="#005e67" stroke="#0e2427" stroke-width="2">
            <rect x="320" y="60" width="205" height="185"/><rect x="525" y="60" width="205" height="185"/><rect x="730" y="60" width="205" height="185"/><rect x="935" y="60" width="205" height="185"/>
          </g>
          <g font-family="Outfit, sans-serif" font-size="22" fill="#ffffff" text-anchor="middle">
            <text x="422" y="160">Treatment</text><text x="627" y="160">Treatment</text><text x="832" y="160">Treatment</text><text x="1037" y="160">Treatment</text>
          </g>
          <rect x="320" y="315" width="300" height="185" fill="#1e9ba7" stroke="#0e2427" stroke-width="2"/>
          <text x="470" y="413" text-anchor="middle" font-family="Outfit, sans-serif" font-size="21" fill="#ffffff">Sterilisation, central</text>
          <rect x="620" y="315" width="260" height="185" fill="#d7eef0" stroke="#0e2427" stroke-width="2"/>
          <text x="750" y="413" text-anchor="middle" font-family="Outfit, sans-serif" font-size="21" fill="#0e2427">X-ray, shielded</text>
          <rect x="880" y="315" width="260" height="185" fill="#f4f7f7" stroke="#0e2427" stroke-width="2"/>
          <text x="1010" y="413" text-anchor="middle" font-family="Outfit, sans-serif" font-size="21" fill="#3c5457">Staff and store</text>
        </svg>
            </div>
            <div class="lp-handle" aria-hidden="true"></div>
            <div class="lp-compare-tags" aria-hidden="true"><span>{{ $d['tag_before'] }}</span><span>{{ $d['tag_after'] }}</span></div>
            <input type="range" min="0" max="100" value="50" id="lp-compare-range" aria-label="Compare the unplanned and the planned layout">
        </div>
        <div class="lp-compare-note">
            <span>{{ $d['note'] }}</span>
            <span class="lp-legend"><span><i style="background:#005e67"></i>{{ $d['legend_rooms'] }}</span><span><i style="background:#fbecea;border:1px solid #e8b4ad"></i>{{ $d['legend_lost'] }}</span></span>
        </div>
        @if(count($d['facts']))
        <div class="lp-facts">
            <h3>{{ $d['facts_heading'] }}</h3>
            <ul>@foreach($d['facts'] as $fact)<li>{{ $fact['text'] }}</li>@endforeach</ul>
        </div>
        @endif
    </div>
</section>
