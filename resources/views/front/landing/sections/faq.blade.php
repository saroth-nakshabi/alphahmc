{{-- Questions; the same items feed the FAQPage schema in facility.blade.php. --}}
<section class="lp-section" id="faq">
    <div class="lp-wrap lp-faq-grid">
        <h2>{{ $d['heading'] }}</h2>
        <div>
            @foreach($d['items'] as $q)
            <details @if($loop->first) open @endif><summary>{{ $q['question'] }}</summary><p>{{ $q['answer'] }}</p></details>
            @endforeach
        </div>
    </div>
</section>
