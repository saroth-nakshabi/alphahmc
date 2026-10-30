{{-- Reviewer fields (App\Support\ReviewerFields). $page: the record or null; $serviceLink: true on blogs. --}}
@php
    $rfId = 'rf' . \Illuminate\Support\Str::random(6);
    $rfReviewers = \App\Models\Reviewer::byKey();
@endphp
<div class="row g-3 mb-3 reviewer-fields">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="{{ $rfId }}Reviewer">Reviewed by</label>
        <select class="form-select" id="{{ $rfId }}Reviewer" name="reviewer" aria-describedby="{{ $rfId }}Help">
            <option value="">No reviewer</option>
            @foreach($rfReviewers as $r)
                <option value="{{ $r->key }}" @selected(optional($page)->reviewer === $r->key)>{{ $r->name }}, {{ $r->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="{{ $rfId }}Date">Review date</label>
        <input type="date" class="form-control" id="{{ $rfId }}Date" name="reviewed_at" max="{{ now()->toDateString() }}"
               value="{{ optional(optional($page)->reviewed_at)->toDateString() }}" aria-describedby="{{ $rfId }}Help">
    </div>
    <div class="col-12">
        <div class="form-text" id="{{ $rfId }}Help">The "Reviewed by" line shows on the page only with both a reviewer and a date. Change the date only after the reviewer has read the page again.</div>
    </div>
    @if(!empty($serviceLink))
        <div class="col-12">
            <label class="form-label fw-semibold" for="{{ $rfId }}Link">Service page this guide links to</label>
            <input type="text" class="form-control" id="{{ $rfId }}Link" name="service_link" maxlength="255"
                   value="{{ optional($page)->service_link }}" placeholder="/services/dha-license-healthcare-professionals-dubai" aria-describedby="{{ $rfId }}LinkHelp">
            <div class="form-text" id="{{ $rfId }}LinkHelp">Shown at the end of the guide, with that page's H1 as the link text.</div>
        </div>
    @endif
</div>
