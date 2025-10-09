<!DOCTYPE html>
<html>

<head>
    <style>
        .text-center {
            text-align: center;
        }

        .lead-text {
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .dihas-header {
            line-height: 1.0;
        }

        .dihas_style {
            color: rgb(115, 19, 206);
            text-decoration: underline;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="text-center">
        <div class="dihas-header">
            <img src="{{ asset('assets/images/kanglasha.png') }}" alt="emblem" height="80">
            <h2>
                <span class="dihas_style">Die-in-Harness Appoinment System
                </span>
            </h2>
            <h4 class="section-heading">(DIHAS)</h3>
        </div>
        <div class="lead-text">
            Empowering <i><strong>Families</strong></i>, Continuing <i><strong>Legacies</strong></i>.
            <h4>Department of Personnel, Government of Manipur</h4>
        </div>
        <hr>
    </div>
    {{-- End Header --}}

    <div style="margin-top:20px;margin-bottom:20px;">
        <h4><strong>DP's File No.:</strong> <span>{{ $proforma->uoFileSubmission->uo_file_submission_id }}</span></h4>
    </div>
    <div>
        DP hereby appoints
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
