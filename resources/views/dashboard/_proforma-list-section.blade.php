<section class="section proforma-list-section">
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card info-card success-card">
                <div class="card-body">
                    <h5 class="card-title">Proforma List</h5>
                    <table class="table table-stripped table-bordered" id="application-table">
                        <thead>
                            <tr>
                                {{-- <th rowspan="2">#</th> --}}
                                <th colspan="2" class="text-center">Seniority</th>
                                <th colspan="3" class="text-center">Deceased Employee Detail</th>
                                <th rowspan="2">Submitted On</th>
                                <th rowspan="2">Applicant Name</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2"></th>
                            </tr>
                            <tr>
                                <th style="max-width:40px;" title="Seniority across the departments">Inter-Dept</th>
                                <th style="max-width:40px;" title="Seniority within the department">Intra-Dept</th>
                                <th title="Employee Identification Number">EIN</th>
                                <th>Name</th>
                                {{-- <th title="Date of expiry of the employee">DOE</th> --}}
                                <th title="Department">Dept.</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@push('js')
    <script>
        //route: duties.proforma.ajaxlist
        const proforma_list_url = "{{ route('duties.proforma.ajaxlist') }}";
        var params = {};
        @if (Auth::user()->role->role_group == 'citizen')
            params['created_by'] = "{{ auth()->id() }}";
        @endif

        $(document).ready(function() {
            setTimeout(function() {
                applicationTable();
            }, 300);
        });


        function applicationTable(application_status = "{{ $view ?? 'all' }}") {
            $(".statusBtn").removeClass('btn-success').addClass('btn-primary');
            $(this).addClass('btn-success');
            $(".statusBtn[data-application_status='" + application_status + "']").addClass('btn-success');

            params['application_status'] = application_status;
            let columns = [{
                    data: "overall_seniority_idx",
                    searchable: false
                },
                {
                    data: "dept_seniority_idx",
                    searchable: false
                },
                {
                    data: "deceased_ein"
                },
                {
                    data: "deceased_emp_name",
                    render: (data, type, row) => {
                        return `
                            <div>${data}</div>
                            <div class="text-muted" title="Date of Expiry">
                                <span class="fw-bold">Expired On:</span><br/> 
                                ${row.deceased_doe}
                            </div>
                        `;
                    },
                },
                {
                    data: "deceased_field_dept_desc"
                },
                {
                    data: "proforma_submission_date"
                },
                {
                    data: "applicant_name",
                    render: (data, type, row) => {
                        return `
                            <div>${data}</div>
                            <div class="text-email">${row.applicant_email}</div>
                            <div class="text-muted" title="Date of Birth">
                                <span class="fw-bold">DOB:</span> ${row.applicant_dob}
                            </div>
                        `;
                    }
                },
                {
                    data: "proforma_status",
                    render: (data, type, row) => {
                        return `
                            <div><span class="status status-${data}">${data}</span></div>
                            <div class="text-muted">By: ${row.remarks.by}</div>
                            <div class="text-muted small">${row.remarks.date}</div>
                        `;
                    }
                },
                {
                    data: "action",
                    orderable: false,
                    searchable: false,
                    print: false,
                },
                /* 
                {
                    data: "applicant_email",
                    name: "applicant_email",
                    visible: false
                },
                {
                    data: "applicant_dob",
                    name: "applicant_dob",
                    visible: false
                }, */
            ];

            loadAjaxTable({
                id: "#application-table",
                url: proforma_list_url,
                message: "No Performa Found",
                columns: columns,
                order: [0, 'asc'],
                param: params,
                "lengthMenu": [
                    [10, 15, 25, 50, -1],
                    [10, 15, 25, 50, "All"]
                ],
                //action: true,
            });

            /*
            $('#application-table').DataTable({
                responsive: true,
                "destroy": true,
                "stateSave": false,
                "columnDefs": [{
                    "searchable": false,
                    "targets": [0, 1]
                }, ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: proforma_list_url,
                    type: "POST",
                    data: {
                        _token: document.querySelector("meta[name='csrf-token']").getAttribute("content"),
                        application_status: application_status
                    }
                },
                columns: columns,
                "lengthMenu": [
                    [10, 15, 25, 50, -1],
                    [10, 15, 25, 50, "All"]
                ],
                order: [
                    [0, 'asc']
                ]

            });
            */
        }

        $(document).on('click', '.statusBtn', function(e) {
            e.preventDefault();
            let application_status = $(this).data('application_status');
            applicationTable(application_status); // Reload with selected status
        });
    </script>
@endpush
