<!DOCTYPE html>
<html>

<head>
    <style>
        .text-center {
            text-align: center;
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
        <h4><strong>DP's File No.:</strong> <span>{{ $proforma->uoFileSubmission->uo_file_submission_id }}</span></h4>
    </div>
    <div>
        DP concurs to the Proposal of {{ $proforma->uoGeneration->alloted_field_dept_desc }} for appointment of
        <strong>{{ $proforma->applicant_name }}</strong> {{ strtolower($proforma->relationship->relationship_name) }} of
        (L) Smt
        <strong>{{ $proforma->deceased_emp_name }}</strong>, ex-{{ $proforma->deceased_emp_desig }} to the post of
        <strong>{{ $proforma->uoGeneration->alloted_dsg_desc }}</strong> in the Department of
        <strong>{{ $proforma->uoGeneration->alloted_field_dept_desc }}</strong>
        as Group <strong>{{ $proforma->uoGeneration->alloted_group_code }}</strong> under the die-in-harness scheme as
        he/she has
        fulfilled all the eligibility criteria for appointment
        under the Scheme. Accordingly, the following U.O. number is allotted:-
    </div>
    <div style="margin-top:25px;">
        U.O. No. <strong>{{ $proforma->uoGeneration->uo_number }}</strong> Dated:
        {{ date('d-m-Y', strtotime($proforma->uoGeneration->generated_on)) }}
    </div>

    <div style="margin-top:145px; width:100%;">

        <div style="display:inline-block; width:100%; text-align:right;">
            Deputy Secretary (DP)
        </div>
    </div>

</body>

</html>
