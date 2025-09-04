<section class="section proforma-list-section">
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card info-card success-card">
                <div class="card-body">
                    <h5 class="card-title">Proforma List</h5>
                    <table class="table table-stripped table-bordered" id="application-table">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th colspan="2" class="text-center">Seniority</th>
                                <th rowspan="2">EIN</th>
                                <th rowspan="2">Deceased Name</th>
                                <th rowspan="2">Expired On</th>
                                <th rowspan="2">Submitted On</th>
                                <th rowspan="2">Submitted By</th>
                                <th rowspan="2">Department</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2"></th>
                            </tr>
                            <tr>
                                <th>Intra-Dept</th>
                                <th>Inter-Dept</th>
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

        $(document).ready(function() {
            setTimeout(function() {
                applicationTable();
            }, 300);
        });


        function applicationTable(application_status = "{{ $view ?? 'all' }}") {
            $(".statusBtn").removeClass('btn-success').addClass('btn-primary');
            $(this).addClass('btn-success');
            $(".statusBtn[data-application_status='" + application_status + "']").addClass('btn-success');
            let columns = [
                "DT_RowIndex|nonorderable|nonsearchable",
                "overall_seniority_idx|nonorderable|nonsearchable",
                "dept_seniority_idx|nonorderable|nonsearchable",
                "deceased_ein",
                "deceased_emp_name",
                "deceased_doe",
                "proforma_submission_date",
                "applicant_name",
                "deceased_field_dept_desc",
                "proforma_status"
            ];
            loadAjaxTable({
                id: "#application-table",
                url: proforma_list_url,
                message: "No Performa Found",
                columns: columns,
                param: {
                    application_status: application_status
                },
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
