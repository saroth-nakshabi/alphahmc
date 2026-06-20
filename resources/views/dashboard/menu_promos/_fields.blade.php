{{-- Shared promo form fields. Expects $promo (model or null). --}}
<div class="mb-3">
    <label class="form-label small fw-semibold">Eyebrow <span class="text-muted">(small label above title)</span></label>
    <input type="text" name="eyebrow" class="form-control form-control-sm"
           value="{{ old('eyebrow', $promo->eyebrow ?? '') }}" placeholder="e.g. Not sure where to start?">
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control form-control-sm" required
           value="{{ old('title', $promo->title ?? '') }}" placeholder="e.g. Plan your healthcare project with Alpha">
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Text</label>
    <textarea name="text" rows="2" class="form-control form-control-sm"
              placeholder="One or two short sentences.">{{ old('text', $promo->text ?? '') }}</textarea>
</div>
<div class="row">
    <div class="col-6 mb-3">
        <label class="form-label small fw-semibold">Button Label</label>
        <input type="text" name="cta_label" class="form-control form-control-sm"
               value="{{ old('cta_label', $promo->cta_label ?? '') }}" placeholder="e.g. Plan Your Project">
    </div>
    <div class="col-6 mb-3">
        <label class="form-label small fw-semibold">Button Link</label>
        <input type="text" name="cta_url" class="form-control form-control-sm"
               value="{{ old('cta_url', $promo->cta_url ?? '') }}" placeholder="https://… or /path">
    </div>
</div>
<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1"
           id="active_{{ $promo->id ?? 'new' }}" {{ old('is_active', $promo->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label small" for="active_{{ $promo->id ?? 'new' }}">Active (show in menu)</label>
</div>
