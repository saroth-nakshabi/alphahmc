{{-- Fields and repeatable lists of one editor part (page, hero or a section).
     $def: schema entry (fields, lists), $data: its current values. --}}
<div class="lpe-fields">
    @foreach($def['fields'] as $f => $spec)
        @include('dashboard.landing_pages._field', ['f' => $f, 'spec' => $spec, 'value' => $data[$f] ?? ''])
    @endforeach
</div>

@foreach($def['lists'] ?? [] as $list => [$listLabel, $cols])
    <div class="lpe-list mt-3" data-list="{{ $list }}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small fw-semibold">{{ $listLabel }}</span>
            <button type="button" class="btn btn-sm btn-outline-info lpe-add"><i class="ti ti-plus me-1"></i>Add row</button>
        </div>
        <div class="lpe-rows">
            @foreach($data[$list] ?? [] as $row)
                @include('dashboard.landing_pages._row', ['cols' => $cols, 'row' => $row])
            @endforeach
        </div>
        <template>@include('dashboard.landing_pages._row', ['cols' => $cols, 'row' => []])</template>
    </div>
@endforeach
