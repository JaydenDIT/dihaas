@extends('layouts.app')

@push('css')
<link href="{{ asset('assets/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    <h4>Process and duty mapping:</h4>
    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <label for="process_id"><strong>Select Process:</strong></label>
            <select id="process_id" class="form-control">
                <option value="">-- Select Process --</option>
                @foreach($processes as $process)
                <option value="{{ $process->process_id }}">{{ $process->process_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="saveMapping" class="btn btn-primary">SAVE</button>
        </div>
    </div>

    <div class="row">
        <!-- Excluded Duties -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>DUTIES NOT INCLUDED IN THE SELECTED PROCESS</strong></div>
                <div class="card-body" id="excluded-duties" style="min-height: 400px;">
                    <!-- Excluded duties loaded here via JS -->
                </div>
            </div>
        </div>

        <!-- Included Duties -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>DUTIES INCLUDED IN THE SELECTED PROCESS</strong></div>
                <div class="card-body" id="included-duties" style="min-height: 400px;">
                    <!-- Included duties loaded here via JS -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{asset('assets/jquery-ui/jquery-ui.js')}}"></script>
<script>
    let selectedProcessId = null;

    // Handle process selection change
    $(document).on('change', '#process_id', function() {
        selectedProcessId = $(this).val();
        if (!selectedProcessId) return;
        fetchDuties(selectedProcessId);
    });

    // Handle Save button click
    $(document).on('click', '#saveMapping', async function() {
        if (!selectedProcessId) return alert('Please select a process first.');

        const included = [];
        $('#included-duties .duty-box').each(function(index) {
            const $box = $(this);
            included.push({
                tasks_id: $box.data('tasks_id'),
                sequence: index + 1,
                allow_drop: $box.find('.chk-drop').prop('checked'),
                allow_reject: $box.find('.chk-reject').prop('checked'),
                allow_esign: $box.find('.chk-esign').prop('checked'),
            });
        });

        try {
            const res = await ajax_send_multipart({
                url: "{{ route('admin.processtaskmapping.save') }}",
                method: 'POST',
                param: {
                    pid: selectedProcessId,
                    included
                },
                json: true
            });
            success_message(res.message || 'Mapping saved successfully.');
        } catch (err) {
            error_message(err?.responseJSON?.message || 'Failed to save mapping.');
        }
    });

    // Fetch duties via AJAX
    async function fetchDuties(pid) {
        $('#included-duties').html('Loading...');
        $('#excluded-duties').html('Loading...');

        try {
            const res = await ajax_send_multipart({
                url: "{{ route('admin.processtaskmapping.data', ['id' => '___ID___']) }}".replace('___ID___', pid),
                method: 'POST',
                json: true
            });
            renderExcluded(res.excluded);
            renderIncluded(res.included);
        } catch (err) {
            error_message(err);
        }
    }

    // Render excluded duties
    function renderExcluded(duties) {
        let html = '';
        duties.forEach((duty, index) => {
            html += `
            <div class="duty-box card mb-2 p-2"
                 data-tasks_id="${duty.tasks_id}"
                 data-tasks_name="${duty.tasks_name}"
                 data-tasks_description="${duty.tasks_description}"
                 data-tasks_duty="${duty.tasks_duty}"
                 style="cursor: pointer;">
                <small>${index + 1}. ${duty.tasks_name} (Duty Id = ${duty.tasks_id})</small>
                <em>${duty.tasks_description}</em>
            </div>
        `;
        });
        $('#excluded-duties').html(html);
        makeDraggable();
    }


    // Render included duties with checkboxes
    function renderIncluded(duties) {
        let html = '';
        duties.forEach((duty, index) => {
            html += `
                <div class="duty-box card mb-2 p-2" data-tasks_id="${duty.tasks_id}">
                    <small>${index + 1}. ${duty.tasks_name} (Duty Id = ${duty.tasks_id})</small>
                    <em>${duty.tasks_description}</em>
                    <div class="form-check mt-1">
                        <input type="checkbox" class="form-check-input" checked disabled>
                        <label class="form-check-label">${duty.tasks_duty}</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input chk-drop" ${duty.allow_drop ? 'checked' : ''}>
                        <label class="form-check-label">Allow Return To Forwarder.</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input chk-reject" ${duty.allow_reject ? 'checked' : ''}>
                        <label class="form-check-label">Allow Reject Application.</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input chk-esign" ${duty.allow_esign ? 'checked' : ''}>
                        <label class="form-check-label">Need Esign (It must be after certified copy is prepared).</label>
                    </div>
                </div>
            `;
        });
        $('#included-duties').html(html);
        makeDraggable();
    }

    // Enable jQuery UI sortable for drag-and-drop
    function makeDraggable() {
        $("#included-duties, #excluded-duties").sortable({
            connectWith: ".card-body",
            items: ".duty-box",
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,

            receive: function(event, ui) {
                const $item = $(ui.item);
                const parentId = $(this).attr('id');

                if (parentId === 'included-duties') {
                    // Add checkboxes when moved to included
                    const tasks_id = $item.data('tasks_id');
                    const tasks_name = $item.data('tasks_name');
                    const tasks_description = $item.data('tasks_description');
                    const tasks_duty = $item.data('tasks_duty');

                    $item.html(`
                        <small>${tasks_name} (Duty Id = ${tasks_id})</small>
                        <em>${tasks_description}</em>
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input" checked disabled>
                            <label class="form-check-label">${tasks_duty}</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input chk-drop">
                            <label class="form-check-label">Allow Return To Forwarder.</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input chk-reject">
                            <label class="form-check-label">Allow Reject Application.</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input chk-esign">
                            <label class="form-check-label">Need Esign (It must be after certified copy is prepared).</label>
                        </div>
                    `);

                } else if (parentId === 'excluded-duties') {
                    // Remove checkboxes when moved to excluded
                    const tasks_id = $item.data('tasks_id');
                    const name = $item.find('strong, small').first().text().split('(')[0].trim();
                    const description = $item.find('em').first().text();

                    $item.html(`
                    <strong>${name} (Duty Id = ${tasks_id})</strong><br>
                    <em>${description}</em>
                `);
                }
            }
        }).disableSelection();
    }
</script>
@endpush