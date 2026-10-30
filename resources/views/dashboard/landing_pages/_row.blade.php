{{-- One row of a repeatable list, with move and remove buttons. --}}
<div class="lpe-row">
    <div class="lpe-row-fields">
        @foreach($cols as $f => $spec)
            @include('dashboard.landing_pages._field', ['f' => $f, 'spec' => $spec, 'value' => $row[$f] ?? ''])
        @endforeach
    </div>
    <div class="lpe-row-tools btn-group-vertical btn-group-sm" role="group" aria-label="Row actions">
        <button type="button" class="btn btn-light lpe-up" title="Move up" aria-label="Move row up"><i class="ti ti-arrow-up"></i></button>
        <button type="button" class="btn btn-light lpe-down" title="Move down" aria-label="Move row down"><i class="ti ti-arrow-down"></i></button>
        <button type="button" class="btn btn-light text-danger lpe-remove" title="Remove" aria-label="Remove row"><i class="ti ti-trash"></i></button>
    </div>
</div>
