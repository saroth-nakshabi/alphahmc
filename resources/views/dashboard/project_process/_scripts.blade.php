<script src="{{ asset('public/dashboard/dist/libs/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    function initTinyMCE(selector, extraCfg) {
        extraCfg = extraCfg || {};
        if (typeof tinymce !== 'undefined') {
            tinymce.init(Object.assign({
                selector: selector,
                plugins: 'code searchreplace autolink directionality visualblocks link media table charmap nonbreaking anchor advlist lists wordcount fullscreen',
                toolbar: 'undo redo | blocks | bold italic underline forecolor backcolor | link | alignleft aligncenter alignright | bullist numlist | fullscreen code',
                menubar: true, height: 240, automatic_uploads: true, images_upload_url: '/upload-image',
                branding: false, promotion: false,
            }, extraCfg));
        }
    }

    initTinyMCE('.rich-textarea', { height: 220 });
    initTinyMCE('#process_intro', { height: 180, menubar: false });

    // Select2 for the assignment multi-selects
    $('.select2-assign').select2({ width: '100%', placeholder: function(){ return $(this).data('placeholder'); } });

    // Live accordion title sync
    $(document).on('input', '.process-header-input', function () {
        $(this).closest('.accordion-item').find('.process-item-title').text($(this).val().trim() || 'Process Step');
    });

    let processIdx = {{ count($processItems) }};
    function updateProcessCount() {
        const n = $('#process-accordion .accordion-item').length;
        $('#process-count').text(n + ' ' + (n === 1 ? 'item' : 'items'));
        n === 0 ? $('#process-empty-state').removeClass('d-none') : $('#process-empty-state').addClass('d-none');
    }

    const PROCESS_SERVICES = @json($services->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values());
    function escAttr(str){ return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function processServiceOptions() {
        let html = '<option value="">— No service —</option>';
        PROCESS_SERVICES.forEach(function (s) { html += '<option value="' + s.id + '">' + escAttr(s.name) + '</option>'; });
        return html;
    }

    function buildProcessItem(idx) {
        return '<div class="accordion-item process-section-item mb-2 border rounded" id="process-item-' + idx + '">' +
            '<h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#process-collapse-' + idx + '" aria-expanded="true">' +
            '<span class="badge me-2 text-white" style="background:#0891b2;min-width:26px">#</span>' +
            '<span class="process-item-title">New Process Step</span></button></h2>' +
            '<div id="process-collapse-' + idx + '" class="accordion-collapse collapse show"><div class="accordion-body">' +
            '<div class="row g-3">' +
            '<div class="col-12"><label class="form-label">Process header</label>' +
            '<input type="text" name="process_header[]" class="form-control process-header-input" placeholder="e.g. Initial Assessment" /></div>' +
            '<div class="col-12"><label class="form-label">Process description</label>' +
            '<textarea id="process_desc_' + idx + '" name="process_description[]" rows="4" class="form-control" placeholder="Process step description..."></textarea></div>' +
            '<div class="col-12"><label class="form-label">Related service <span class="text-muted fw-normal">(optional)</span></label>' +
            '<select name="process_service_ids[]" class="form-control">' + processServiceOptions() + '</select></div>' +
            '</div><div class="d-flex justify-content-end mt-3">' +
            '<button type="button" class="btn btn-sm btn-outline-danger remove-process-section"><i class="ti ti-trash me-1"></i> Remove</button>' +
            '</div></div></div></div>';
    }

    $('#addProcessBtn').on('click', function () {
        $('#process-accordion').append(buildProcessItem(processIdx));
        initTinyMCE('#process_desc_' + processIdx, { height: 180 });
        processIdx++;
        updateProcessCount();
    });

    $(document).on('click', '.remove-process-section', function () {
        const $item = $(this).closest('.accordion-item');
        const label = $item.find('.process-item-title').text();
        Swal.fire({ title: 'Remove process step?', html: 'Remove <strong>' + label + '</strong>?', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Yes, remove it', reverseButtons: true })
            .then(function (r) {
                if (r.isConfirmed) {
                    $item.find('textarea[id]').each(function () {
                        const tid = $(this).attr('id');
                        if (tid && typeof tinymce !== 'undefined' && tinymce.get(tid)) { try { tinymce.get(tid).destroy(); } catch(e){} }
                    });
                    $item.slideUp(200, function () { $(this).remove(); updateProcessCount(); });
                }
            });
    });

    // Sync TinyMCE content into the textareas before the form posts.
    $('#pp_form').on('submit', function () {
        if (typeof tinymce !== 'undefined') { try { tinymce.triggerSave(); } catch(e){} }
        return true;
    });

    @if(session('success'))
        Swal.fire({ title: 'Done', text: @json(session('success')), icon: 'success', timer: 2800, timerProgressBar: true });
    @endif
});
</script>
