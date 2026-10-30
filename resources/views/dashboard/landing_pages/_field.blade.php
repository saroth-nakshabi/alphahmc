{{-- One content field of the landing page editor. $f: field key, $spec: [label, type, required?], $value. --}}
@php
    $fid = 'lpf-' . \Illuminate\Support\Str::random(8);
    [$label, $type] = $spec;
    $required = !empty($spec[2]);
@endphp
<div class="lpe-field {{ $type === 'textarea' ? 'lpe-wide' : '' }}">
    <label class="form-label small fw-semibold mb-1" for="{{ $fid }}">{{ $label }}@if($required) <span class="text-danger" aria-hidden="true">*</span>@endif</label>
    @if($type === 'textarea')
        <textarea class="form-control form-control-sm" id="{{ $fid }}" data-f="{{ $f }}" rows="2" @if($required) aria-required="true" @endif>{{ $value }}</textarea>
    @elseif($type === 'int')
        <input type="number" min="0" max="60" class="form-control form-control-sm" id="{{ $fid }}" data-f="{{ $f }}" value="{{ $value }}">
    @elseif($type === 'project')
        <select class="form-select form-select-sm" id="{{ $fid }}" data-f="{{ $f }}">
            <option value="0">Choose a project</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" @selected((int) $value === $project->id)>{{ $project->name }} ({{ $project->slug }})</option>
            @endforeach
        </select>
    @elseif($type === 'page')
        <select class="form-select form-select-sm" id="{{ $fid }}" data-f="{{ $f }}">
            <option value="">Choose a page</option>
            @if($value !== '' && !isset($pageTargets[$value]))<option value="{{ $value }}" selected>Unpublished or deleted page: hidden on the site</option>@endif
            @foreach($pageTargets as $key => $t)
                <option value="{{ $key }}" @selected($value === $key)>{{ $t['label'] }}</option>
            @endforeach
        </select>
    @else
        <input type="text" class="form-control form-control-sm" id="{{ $fid }}" data-f="{{ $f }}" value="{{ $value }}" @if($required) aria-required="true" @endif>
    @endif
</div>
