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
                                {{-- <th rowspan="2">Submitted On</th> --}}
                                <th rowspan="2">Applicant Name</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2"></th>
                            </tr>
                            <tr>
                                <th style="max-width:40px;">Inter-Dept</th>
                                <th style="max-width:40px;">Intra-Dept</th>
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
            let columns = [
                "overall_seniority_idx|nonsearchable",
                "dept_seniority_idx|nonsearchable",
                "deceased_ein",
                {
                    data: "deceased_emp_name",
                    render: (data, type, row) => {
                        return `
                            <div>${data}</div>
                            <div class="text-muted" title="Date of Expiry"><span class="fw-bold">Expired On:</span> ${row.deceased_doe}</div>
                        `;
                    },
                },
                //"deceased_doe",
                "deceased_field_dept_desc",
                /* "proforma_submission_date", */
                {
                    data: "applicant_name",
                    render: (data, type, row) => {
                        return `
                            <div>${data}</div>
                            <div class="fw-bold text-muted">Submitted On:</div>
                            <div class="text-muted">${row.proforma_submission_date}</div>
                        `;
                    }
                },
                {
                    data: "proforma_status",
                    render: (data, type, row) => {
                        return `
                            <div>${data}</div>
                            <div class="text-muted">By: ${row.remarks.by}</div>
                            <div class="text-muted small">${row.remarks.date}</div>
                        `;
                    }
                },
                "action|nonorderable|nonsearchable"
            ];
            loadAjaxTable({
                id: "#application-table",
                url: proforma_list_url,
                message: "No Performa Found",
                columns: columns,
                order: [0, 'asc'],
                param: params,
                action: true,
            });
        }

        $(document).on('click', '.statusBtn', function(e) {
            e.preventDefault();
            let application_status = $(this).data('application_status');
            applicationTable(application_status); // Reload with selected status
        });
    </script>
@endpush
