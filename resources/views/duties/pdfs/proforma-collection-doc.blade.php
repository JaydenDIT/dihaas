<!DOCTYPE html>
<html>

<head>
    <style>
        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 12px;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        table td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div style="background-color: #363333; color:#FFFFFF; text-align:center; padding:10px 10px;vertical-align:middle;">
        <h2>{{ env('APP_NAME', 'DIHAS') }}</h2>
    </div>

    <div class="text-center">
        <h3>Die-in-Harness Appointment System</h3>
        <h3>Department of Personnel</h3>
        <h3>Government of Manipur</h3>
    </div>

    <div style="margin-top:20px;margin-bottom:20px;">
        <h4><strong>DP's File No.:</strong> <span> ***** </span></h4>
    </div>

    <div>
        DP concurs to the proposal of Department of Personnel for appointment of {{ $countTotal }}
        applicants under die-in-harness scheme
        as they fulfilled all the eligibility criteria for appointment under the
        Scheme.
        <p>Details of the {{ $countTotal }} applicants and their U.O number are shown as below:- </p>

        <table id="proforma-collection-table">
            <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Name of Applicant</th>
                    <th>Department</th>
                    <th>Post Given</th>
                    <th>Group</th>
                    <th>U.O Number</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proformas as $key => $proforma)
                    <tr>
                        <td style="text-align:center;">{{ $key + 1 }}.</td>
                        <td>{{ $proforma->applicant_name }}</td>
                        <td>{{ $proforma->uoGeneration->alloted_field_dept_desc }}</td>
                        <td>{{ $proforma->uoGeneration->alloted_dsg_desc }}</td>
                        <td style="text-align:center;">{{ $proforma->uoGeneration->alloted_group_code }}</td>
                        <td>{{ $proforma->uoGeneration->uo_number }}</td>
                        <td>{{ date('d-m-Y', strtotime($proforma->uoGeneration->generated_on)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:145px; width:100%;">
        <div style="display:inline-block; width:100%; text-align:right;">
            Deputy Secretary (DP)
        </div>
    </div>

</body>

</html>
