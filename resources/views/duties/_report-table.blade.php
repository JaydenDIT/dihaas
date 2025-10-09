<table class="report-table table" id="application-table">
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th colspan="2" class="text-center">Seniority</th>
            <th colspan="3" class="text-center">Deceased Employee Details</th>
            <th rowspan="2" class="text-center">Applicant Details</th>
            <th rowspan="2">Status</th>
            <th rowspan="2">Remarks</th>
            <th rowspan="2"></th>
        </tr>
        <tr>
            <th title="Seniority index across the departments.">Inter-<br />Dept.</th>
            <th title="Seniority index within the department.">Intra-<br />Dept.</th>
            <th title="Employee Identification Number of the Manipur Govt.'s employee">EIN</th>
            <th title="Name of the deceased employee">Name</th>
            {{-- <th title="Date of expiry of the deceased employee">DOE</th> --}}
            <th title="Department">Dept.</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
@push('js')
    <script>
        //js function to prepare columns for the data table

        /**
         * @argument allow_multi_select means user will be allowed to select one or more rows
         * Columns we have  [
                "DT_RowIndex|nonorderable|nonsearchable",
                "overall_seniority_idx|nonorderable|nonsearchable",
                "dept_seniority_idx|nonorderable|nonsearchable",
                "deceased_ein",
                "deceased_emp_name",
                "deceased_doe",
                "deceased_field_dept_desc",
                "applicant_name",
                "applicant_dob",
                "proforma_submission_date",
                "proforma_status",
                "remarks"
            ]; 
         */
        function getProformaColumnsForDataTable(params) {
            let columns = [{
                    data: 'DT_RowIndex',
                    render: (data, type, row) => {
                        if (params.allow_multi_select) {
                            return `<input type="checkbox" 
                            data-overall_seniority_idx="${row.overall_seniority_idx}" class="select-single form-check-input proforma-checkbox" name="selected_proforma[]" 
                            value="${row.proforma_id}" disabled/>`;
                        }
                        return row.DT_RowIndex;
                    },
                    orderable: false,
                    searchable: false
                },
                "overall_seniority_idx|nonorderable|nonsearchable",
                "dept_seniority_idx|nonorderable|nonsearchable",
                "deceased_ein",
                {
                    data: "deceased_emp_name",
                    render: (data, type, row) => {
                        return `<div>${data}</div>
                        <div class="text-muted" title="Date of Expiry"><span class="fw-bold">DOE:</span> ${row.deceased_doe}</div>
                        `;
                    },
                    orderable: false
                },
                // "deceased_doe|nonorderable",
                "deceased_field_dept_desc|nonorderable",
                //"applicant_name|nonorderable",
                {
                    data: "applicant_name",
                    render: (data, type, row) => {
                        return `
                            <div>${data}</div>
                            <div class="text-email">${row.applicant_email}</div>
                            <div class="text-muted" title="Date of Birth"><span class="fw-bold">DOB:</span> ${row.applicant_dob}</div>
                        `;
                    },
                    orderable: false,
                },
                {
                    data: "remarks",
                    render: (data, type, row) => {
                        if (data == null) {
                            return 'N/A';
                        }
                        return `
                        <div class="mb-1"><span class="status status-${row.proforma_status}">${row.proforma_status}</span></div>
                        <div>By: ${data.by}</div>
                        <div class="text-muted">${data.date}</div>
                        `;
                    },
                    orderable: false
                },
                {
                    data: "remarks",
                    render: (data, type, row) => {
                        if (data == null) {
                            return 'N/A';
                        }
                        return `<div>${data.remark}</div>`;
                    },
                    orderable: false
                },
                {
                    data: "action",
                    searchable: false,
                    orderable: false,
                    print: false,
                }
            ];
            return columns;
        }
    </script>
@endpush
