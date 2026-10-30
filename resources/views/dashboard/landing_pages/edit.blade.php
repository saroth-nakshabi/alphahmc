@extends('dashboard/layout')

@php
    use App\Models\LandingPage;
    use App\Support\LandingPageSchema;

    $isNew = !$page->exists;
    $canEdit = auth()->user()->can($isNew ? 'create landing pages' : 'edit landing pages');
    $relatedIds = array_map('intval', $page->related_ids ?? []);
    // Facility links: chosen pages first, in their order, then the rest.
    $relatedList = $others->sortBy(fn ($o) => ($i = array_search($o->id, $relatedIds, true)) === false ? 1000 + $o->id : $i)->values();
@endphp

@section('custom_css')
<style>
    .lpe-fields { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px 16px; }
    .lpe-field.lpe-wide { grid-column: 1 / -1; }
    .lpe-row { display: flex; gap: 10px; align-items: flex-start; padding: 10px; border: 1px solid #e5eaef; border-radius: 8px; background: #fafbfc; margin-bottom: 8px; }
    .lpe-row-fields { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px 12px; min-width: 0; }
    .lpe-row-fields .lpe-wide { grid-column: 1 / -1; }
    .lpe-sec.is-off > .card-body { opacity: .55; }
    .lpe-sec .card-header { display: flex; align-items: center; gap: 10px; background: #fff; }
    .lpe-sec .card-header h6 { margin: 0; flex: 1; }
    .lpe-sticky { position: sticky; top: 80px; }
    .lpe-related li { display: flex; align-items: center; gap: 8px; padding: 6px 0; border-bottom: 1px solid #f0f2f5; }
    .lpe-related li label { flex: 1; margin: 0; }
    .lpe-hero-preview { width: 100%; aspect-ratio: 21 / 8; object-fit: cover; border-radius: 8px; background: #eef2f5; }
    .lpe-count { font-variant-numeric: tabular-nums; }
    .lpe-savebar { position: sticky; top: 64px; z-index: 5; background: #fff; }
</style>
@endsection

@section('content')
<div class="card bg-light-info shadow-none position-relative overflow-hidden mb-3">
    <div class="card-body px-4 py-3">
        <h4 class="fw-semibold mb-1"><i class="ti ti-file-text me-2"></i>{{ $isNew ? 'New landing page' : $page->name }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a class="text-muted" href="{{ route('admin.landing-pages.index') }}">Landing pages</a></li>
                <li class="breadcrumb-item active">{{ $isNew ? 'New' : 'Edit' }}</li>
            </ol>
        </nav>
    </div>
</div>

@foreach(['success' => 'success', 'error' => 'danger'] as $key => $class)
    @if(session($key))
        <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">
            {{ session($key) }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach

<form id="lpeForm" novalidate enctype="multipart/form-data"
      data-action="{{ $isNew ? route('admin.landing-pages.store') : route('admin.landing-pages.update', $page->id) }}">

    <div class="card shadow-sm border-0 mb-3 lpe-savebar">
        <div class="card-body py-2 d-flex flex-wrap gap-2 align-items-center">
            <span class="small text-muted me-auto" id="lpeState">
                @if($isNew) Not saved yet. @else Last saved {{ $page->updated_at->format('j M Y, H:i') }}. @endif
            </span>
            @unless($isNew)
                <a class="btn btn-sm btn-outline-secondary" href="{{ $page->url() }}" target="_blank" rel="noopener" id="lpeView">
                    <i class="ti ti-external-link me-1"></i>{{ $page->isPublished() ? 'View live page' : 'Preview draft' }}
                </a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.landing-pages.history', $page->id) }}"><i class="ti ti-history me-1"></i>History</a>
            @endunless
            @if($canEdit)
                <input type="text" class="form-control form-control-sm" style="max-width:260px" name="note" placeholder="What changed (optional, for the history)" aria-label="What changed">
                <button type="submit" class="btn btn-sm btn-info" id="lpeSave"><i class="ti ti-device-floppy me-1"></i><span>Save</span></button>
            @endif
        </div>
    </div>

    <div class="alert alert-danger d-none" id="lpeErrors" role="alert" tabindex="-1"></div>
    <div class="alert alert-warning d-none" id="lpeWarnings" role="status"></div>

    <div class="row">
        {{-- Page settings --}}
        <div class="col-xl-4 mb-3">
            <div class="lpe-sticky">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Page</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" for="lpeName">Name in the admin <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="form-control form-control-sm" id="lpeName" name="name" value="{{ $page->name }}" required aria-required="true" maxlength="255">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" for="lpeSlug">URL <span class="text-danger" aria-hidden="true">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">/facility-setup/</span>
                                <input class="form-control" id="lpeSlug" name="slug" value="{{ $page->slug }}" required aria-required="true" maxlength="120" data-touched="{{ $isNew ? '0' : '1' }}" aria-describedby="lpeSlugHelp">
                            </div>
                            <div class="form-text" id="lpeSlugHelp">Ads point at this URL. Change it on a live page only together with the ads.</div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold mb-1" for="lpeStatus">Status</label>
                                <select class="form-select form-select-sm" id="lpeStatus" name="status">
                                    @foreach(LandingPage::STATUSES as $value => $label)<option value="{{ $value }}" @selected($page->status === $value)>{{ $label }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold mb-1" for="lpeEmirate">Emirate</label>
                                <select class="form-select form-select-sm" id="lpeEmirate" name="emirate">
                                    @foreach(LandingPageSchema::EMIRATES as $e)<option @selected($page->emirate === $e)>{{ $e }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" for="lpeType">Facility type</label>
                            <input class="form-control form-control-sm" id="lpeType" name="facility_type" value="{{ $page->facility_type }}" list="lpeTypes" maxlength="100" aria-describedby="lpeTypeHelp">
                            <datalist id="lpeTypes">@foreach(LandingPageSchema::FACILITY_TYPES as $t)<option value="{{ $t }}">@endforeach</datalist>
                            <div class="form-text" id="lpeTypeHelp">Label of this page's facility link, and the form's preset. Leave empty on emirate pages.</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-semibold mb-1" for="lpeLead">Conversions</label>
                            <select class="form-select form-select-sm" id="lpeLead" name="lead_type" aria-describedby="lpeLeadHelp">
                                @foreach(LandingPage::LEAD_TYPES as $value => $label)<option value="{{ $value }}" @selected($page->lead_type === $value)>{{ $label }}</option>@endforeach
                            </select>
                            <div class="form-text" id="lpeLeadHelp">Which Google Ads conversions this page reports. Ask the Ads team before changing it.</div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Search</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" for="lpeSeoTitle">SEO title <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="form-control form-control-sm" id="lpeSeoTitle" name="seo_title" value="{{ $page->seo_title }}" required aria-required="true" maxlength="255" data-count="60" aria-describedby="lpeSeoTitleCount">
                            <div class="form-text lpe-count" id="lpeSeoTitleCount"></div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" for="lpeMeta">Meta description <span class="text-danger" aria-hidden="true">*</span></label>
                            <textarea class="form-control form-control-sm" id="lpeMeta" name="meta_description" rows="3" required aria-required="true" maxlength="500" data-count="160" aria-describedby="lpeMetaCount">{{ $page->meta_description }}</textarea>
                            <div class="form-text lpe-count" id="lpeMetaCount"></div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" for="lpeCanonical">Canonical URL</label>
                            <input class="form-control form-control-sm" id="lpeCanonical" name="canonical" value="{{ $page->canonical }}" maxlength="255" placeholder="Empty: this page's own URL">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="lpeIndex" name="indexable" value="1" @checked($page->indexable)>
                            <label class="form-check-label small" for="lpeIndex">Show in search results and the sitemap</label>
                        </div>
                        <div class="form-text">Off by default: ads landing pages stay out of search so they do not compete with the service pages. Turn it on only after the SEO team agrees.</div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Hero image</h6>
                        <img class="lpe-hero-preview mb-2" id="lpeHeroPreview" alt=""
                             src="{{ $page->hero_image ? asset('public/' . $page->hero_image) : '' }}" @unless($page->hero_image) hidden @endunless>
                        <label class="form-label small fw-semibold mb-1" for="lpeHero">{{ $page->hero_image ? 'Replace the image' : 'Upload an image' }}@unless($page->hero_image) <span class="text-danger" aria-hidden="true">*</span>@endunless</label>
                        <input type="file" class="form-control form-control-sm" id="lpeHero" name="hero_image" accept="image/jpeg,image/png" aria-describedby="lpeHeroHelp">
                        <div class="form-text" id="lpeHeroHelp">JPG or PNG, at least 1200 by 600 pixels, up to 6 MB. The site serves it resized and as WebP.</div>
                        <label class="form-label small fw-semibold mb-1 mt-2" for="lpeHeroAlt">Alt text <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="form-control form-control-sm" id="lpeHeroAlt" name="hero_image_alt" value="{{ $page->hero_image_alt }}" required aria-required="true" maxlength="255">
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1">Floor plans: the same floor, planned twice</h6>
                        <p class="small text-muted mb-2">The unplanned and the planned layout of this facility type, shown in the comparison slider. Use two images of the same size and framing. Until both are uploaded, the page shows the built-in drawings.</p>
                        @foreach(['before' => 'Unplanned layout', 'after' => 'Planned layout'] as $side => $sideLabel)
                            @php $planImg = $page->{"plan_{$side}_image"}; @endphp
                            <img class="lpe-hero-preview mb-2" id="lpePlan{{ ucfirst($side) }}Preview" alt=""
                                 src="{{ $planImg ? asset('public/' . $planImg) : '' }}" @unless($planImg) hidden @endunless>
                            <label class="form-label small fw-semibold mb-1" for="lpePlan{{ ucfirst($side) }}">{{ $sideLabel }}: {{ $planImg ? 'replace the image' : 'upload an image' }}</label>
                            <input type="file" class="form-control form-control-sm" id="lpePlan{{ ucfirst($side) }}" name="plan_{{ $side }}_image" accept="image/jpeg,image/png" aria-describedby="lpePlanHelp">
                            <label class="form-label small fw-semibold mb-1 mt-2" for="lpePlan{{ ucfirst($side) }}Alt">{{ $sideLabel }}: alt text <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="form-control form-control-sm mb-3" id="lpePlan{{ ucfirst($side) }}Alt" name="plan_{{ $side }}_alt" value="{{ $page->{"plan_{$side}_alt"} }}" maxlength="255">
                        @endforeach
                        <div class="form-text" id="lpePlanHelp">JPG or PNG, at least 800 pixels wide, up to 6 MB. Alt text is required once an image is uploaded.</div>
                        @if($page->plan_before_image || $page->plan_after_image)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="lpePlanRemove" name="plan_images_remove" value="1">
                                <label class="form-check-label small" for="lpePlanRemove">Remove both floor plans and show the built-in drawings</label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1">Facility links in the hero</h6>
                        <p class="small text-muted mb-2">Tick the pages to link, this page included, and set their order. Drafts stay hidden until published. With none ticked, the hero shows the facilities list instead.</p>
                        <ul class="list-unstyled mb-0 lpe-related" id="lpeRelated">
                            @foreach($relatedList as $other)
                                <li>
                                    <input class="form-check-input mt-0" type="checkbox" name="related_ids[]" value="{{ $other->id }}" id="lpeRel{{ $other->id }}" @checked(in_array($other->id, $relatedIds, true))>
                                    <label class="small" for="lpeRel{{ $other->id }}">{{ $other->name }} @if($other->status !== 'published')<span class="badge bg-light-secondary text-muted">Draft</span>@endif @if($other->id === $page->id)<span class="badge bg-light-info text-info">This page</span>@endif</label>
                                    <button type="button" class="btn btn-sm btn-light lpe-rel-up" aria-label="Move {{ $other->name }} up"><i class="ti ti-arrow-up"></i></button>
                                    <button type="button" class="btn btn-sm btn-light lpe-rel-down" aria-label="Move {{ $other->name }} down"><i class="ti ti-arrow-down"></i></button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 mb-3 lpe-part" data-part="page">
                <div class="card-header bg-white"><h6 class="fw-semibold mb-0">{{ LandingPageSchema::PAGE['label'] }}</h6></div>
                <div class="card-body">@include('dashboard.landing_pages._part', ['def' => LandingPageSchema::PAGE, 'data' => $content['page']])</div>
            </div>
            <div class="card shadow-sm border-0 mb-3 lpe-part" data-part="hero">
                <div class="card-header bg-white"><h6 class="fw-semibold mb-0">{{ LandingPageSchema::HERO['label'] }}</h6></div>
                <div class="card-body">@include('dashboard.landing_pages._part', ['def' => LandingPageSchema::HERO, 'data' => $content['hero']])</div>
            </div>

            <p class="small text-muted mb-2">Sections appear on the page in this order. Switch a section off to hide it; its copy is kept.</p>
            <div id="lpeSections">
                @foreach($content['sections'] as $section)
                    @php $def = LandingPageSchema::SECTIONS[$section['key']]; $sid = 'lpeSec' . $loop->index; @endphp
                    <div class="card shadow-sm border-0 mb-3 lpe-sec {{ $section['enabled'] ? '' : 'is-off' }}" data-key="{{ $section['key'] }}">
                        <div class="card-header">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input lpe-enabled" type="checkbox" role="switch" id="{{ $sid }}On" @checked($section['enabled'])>
                                <label class="visually-hidden" for="{{ $sid }}On">Show {{ $def['label'] }}</label>
                            </div>
                            <h6 class="fw-semibold">{{ $def['label'] }}</h6>
                            <button type="button" class="btn btn-sm btn-light lpe-sec-up" aria-label="Move {{ $def['label'] }} up"><i class="ti ti-arrow-up"></i></button>
                            <button type="button" class="btn btn-sm btn-light lpe-sec-down" aria-label="Move {{ $def['label'] }} down"><i class="ti ti-arrow-down"></i></button>
                            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#{{ $sid }}Body" aria-expanded="false" aria-controls="{{ $sid }}Body" aria-label="Show or hide the fields of {{ $def['label'] }}"><i class="ti ti-chevron-down"></i></button>
                        </div>
                        <div class="collapse" id="{{ $sid }}Body">
                            <div class="card-body">@include('dashboard.landing_pages._part', ['def' => $def, 'data' => $section['data']])</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</form>
@endsection

@section('custom_js')
<script>
(function () {
    'use strict';
    var form = document.getElementById('lpeForm');
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var dirty = false;

    function closest(el, sel) { return el.closest(sel); }
    function move(item, dir) {
        if (dir < 0 && item.previousElementSibling) item.parentNode.insertBefore(item, item.previousElementSibling);
        if (dir > 0 && item.nextElementSibling) item.parentNode.insertBefore(item.nextElementSibling, item);
        dirty = true;
    }

    // ── Lists, sections and facility links ──
    form.addEventListener('click', function (e) {
        var b = e.target.closest('button');
        if (!b) return;
        if (b.classList.contains('lpe-add')) {
            var list = closest(b, '.lpe-list');
            var row = list.querySelector('template').content.firstElementChild.cloneNode(true);
            // Fresh ids so each label points at its own input.
            row.querySelectorAll('[id]').forEach(function (el) {
                var id = 'lpf-' + Math.random().toString(36).slice(2, 10);
                var label = row.querySelector('label[for="' + el.id + '"]');
                el.id = id; if (label) label.htmlFor = id;
            });
            list.querySelector('.lpe-rows').appendChild(row);
            var first = row.querySelector('input, textarea, select'); if (first) first.focus();
            dirty = true;
        } else if (b.classList.contains('lpe-remove')) {
            closest(b, '.lpe-row').remove(); dirty = true;
        } else if (b.classList.contains('lpe-up') || b.classList.contains('lpe-down')) {
            move(closest(b, '.lpe-row'), b.classList.contains('lpe-up') ? -1 : 1);
        } else if (b.classList.contains('lpe-sec-up') || b.classList.contains('lpe-sec-down')) {
            move(closest(b, '.lpe-sec'), b.classList.contains('lpe-sec-up') ? -1 : 1);
            b.focus();
        } else if (b.classList.contains('lpe-rel-up') || b.classList.contains('lpe-rel-down')) {
            move(closest(b, 'li'), b.classList.contains('lpe-rel-up') ? -1 : 1);
            b.focus();
        }
    });
    form.addEventListener('change', function (e) {
        dirty = true;
        if (e.target.classList.contains('lpe-enabled')) closest(e.target, '.lpe-sec').classList.toggle('is-off', !e.target.checked);
    });
    form.addEventListener('input', function () { dirty = true; });
    window.addEventListener('beforeunload', function (e) { if (dirty) { e.preventDefault(); e.returnValue = ''; } });

    // ── URL from the name on a new page, until the URL is edited by hand ──
    var slug = document.getElementById('lpeSlug');
    slug.addEventListener('input', function () { slug.dataset.touched = '1'; });
    document.getElementById('lpeName').addEventListener('input', function (e) {
        if (slug.dataset.touched === '1') return;
        slug.value = e.target.value.toLowerCase().normalize('NFKD').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 120);
    });

    // ── Character counts for the SEO fields ──
    form.querySelectorAll('[data-count]').forEach(function (el) {
        var out = document.getElementById(el.getAttribute('aria-describedby'));
        var update = function () {
            var n = el.value.length, max = +el.dataset.count;
            out.textContent = n + ' characters' + (n > max ? ', over the ' + max + ' that search results usually show' : '');
            out.classList.toggle('text-warning', n > max);
        };
        el.addEventListener('input', update); update();
    });

    // ── Hero image: check type and size before upload, and show it ──
    var hero = document.getElementById('lpeHero'), preview = document.getElementById('lpeHeroPreview');
    hero.addEventListener('change', function () {
        var f = hero.files[0];
        clearError(hero);
        if (!f) return;
        if (!/^image\/(jpeg|png)$/.test(f.type)) { showError(hero, 'Upload the hero image as JPG or PNG.'); hero.value = ''; return; }
        if (f.size > 6 * 1024 * 1024) { showError(hero, 'The hero image must be 6 MB or smaller.'); hero.value = ''; return; }
        preview.src = URL.createObjectURL(f); preview.hidden = false;
    });

    // ── Content JSON from the editor, in DOM order; data-path matches the server's error keys ──
    function readFields(scope, prefix) {
        var out = {};
        scope.querySelectorAll(':scope > .lpe-fields [data-f]').forEach(function (el) {
            el.dataset.path = prefix + '.' + el.dataset.f;
            out[el.dataset.f] = el.value;
        });
        scope.querySelectorAll(':scope > .lpe-list').forEach(function (list) {
            var name = list.dataset.list;
            out[name] = Array.prototype.map.call(list.querySelectorAll(':scope > .lpe-rows > .lpe-row'), function (row, j) {
                var r = {};
                row.querySelectorAll('[data-f]').forEach(function (el) {
                    el.dataset.path = prefix + '.' + name + '.' + j + '.' + el.dataset.f;
                    r[el.dataset.f] = el.value;
                });
                return r;
            });
        });
        return out;
    }
    function content() {
        var parts = {};
        form.querySelectorAll('.lpe-part').forEach(function (card) {
            parts[card.dataset.part] = readFields(card.querySelector('.card-body'), card.dataset.part);
        });
        parts.sections = Array.prototype.map.call(form.querySelectorAll('#lpeSections > .lpe-sec'), function (sec, i) {
            return { key: sec.dataset.key, enabled: sec.querySelector('.lpe-enabled').checked,
                     data: readFields(sec.querySelector('.card-body'), 'sections.' + i) };
        });
        return JSON.stringify(parts);
    }

    // ── Errors next to their fields ──
    function showError(el, msg) {
        el.classList.add('is-invalid');
        el.setAttribute('aria-invalid', 'true');
        var fb = document.createElement('div');
        fb.className = 'invalid-feedback d-block lpe-fb';
        fb.id = (el.id || 'lpe') + '-err';
        fb.textContent = msg;
        el.setAttribute('aria-describedby', ((el.getAttribute('aria-describedby') || '') + ' ' + fb.id).trim());
        (el.closest('.input-group') || el).insertAdjacentElement('afterend', fb);
        var collapse = el.closest('.collapse');
        if (collapse && !collapse.classList.contains('show')) bootstrap.Collapse.getOrCreateInstance(collapse, { toggle: false }).show();
    }
    function clearError(el) {
        el.classList.remove('is-invalid'); el.removeAttribute('aria-invalid');
        var fb = document.getElementById((el.id || 'lpe') + '-err');
        if (fb) { el.setAttribute('aria-describedby', (el.getAttribute('aria-describedby') || '').replace(fb.id, '').trim()); fb.remove(); }
    }
    function clearAll() {
        form.querySelectorAll('.is-invalid').forEach(clearError);
        ['lpeErrors', 'lpeWarnings'].forEach(function (id) { var b = document.getElementById(id); b.classList.add('d-none'); b.innerHTML = ''; });
    }
    function listBox(id, intro, items) {
        var box = document.getElementById(id);
        box.innerHTML = '';
        var p = document.createElement('p'); p.className = 'mb-1 fw-semibold'; p.textContent = intro; box.appendChild(p);
        var ul = document.createElement('ul'); ul.className = 'mb-0 small';
        items.forEach(function (t) { var li = document.createElement('li'); li.textContent = t; ul.appendChild(li); });
        box.appendChild(ul);
        box.classList.remove('d-none');
        return box;
    }

    // ── Save (AJAX) ──
    var saveBtn = document.getElementById('lpeSave');
    // Turning search indexing on needs a confirmation (SEO agent and Saroth, 30 Oct 2026).
    var indexSwitch = document.getElementById('lpeIndex');
    var indexedBefore = indexSwitch ? indexSwitch.checked : false;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!saveBtn) return;
        if (indexSwitch && indexSwitch.checked && !indexedBefore
            && !window.confirm('This page shares most of its text with the other landing pages. Index it only after the SEO agent approves its own content.\n\nSave with search indexing on?')) {
            return;
        }
        clearAll();
        var fd = new FormData(form);
        fd.append('content', content());
        saveBtn.disabled = true;
        saveBtn.querySelector('span').textContent = 'Saving';

        fetch(form.dataset.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd,
        }).then(function (r) {
            return r.json().catch(function () { return { success: false, message: 'The server sent an unexpected answer (' + r.status + ').' }; });
        }).then(function (d) {
            saveBtn.disabled = false;
            saveBtn.querySelector('span').textContent = 'Save';
            if (d.success) {
                dirty = false;
                if (d.warnings && d.warnings.length) listBox('lpeWarnings', 'Saved, but check the copy rules: no em or en dashes, and no "compliant" or "compliance".', d.warnings);
                if (d.redirect) { window.location = d.redirect; return; }
                document.getElementById('lpeState').textContent = d.message;
                if (d.hero_url) { preview.src = d.hero_url; preview.hidden = false; hero.value = ''; }
                if (indexSwitch) indexedBefore = indexSwitch.checked;
                ['before', 'after'].forEach(function (side) {
                    var key = side.charAt(0).toUpperCase() + side.slice(1);
                    var img = document.getElementById('lpePlan' + key + 'Preview'), input = document.getElementById('lpePlan' + key);
                    var url = d.plan_urls ? d.plan_urls[side] : null;
                    if (img) { img.src = url || ''; img.hidden = !url; }
                    if (input) input.value = '';
                });
                if (typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: d.message });
                return;
            }
            var messages = [];
            Object.keys(d.errors || {}).forEach(function (key) {
                var msg = d.errors[key][0];
                messages.push(msg);
                var el = key.indexOf('content.') === 0
                    ? form.querySelector('[data-path="' + key.slice(8) + '"]')
                    : form.querySelector('[name="' + key.replace(/\.\d+$/, '[]') + '"]') || form.querySelector('[name="' + key + '"]');
                if (el) showError(el, msg);
            });
            listBox('lpeErrors', d.message || 'The page was not saved.', messages).focus();
        }).catch(function () {
            saveBtn.disabled = false;
            saveBtn.querySelector('span').textContent = 'Save';
            listBox('lpeErrors', 'The page was not saved.', ['Check your connection and save again.']).focus();
        });
    });
})();
</script>
@endsection
