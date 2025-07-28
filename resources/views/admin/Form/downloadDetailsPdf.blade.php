<!DOCTYPE html>
<html>

<head>
    <style nonce="{{ csp_nonce() }}">

        @page {
                        margin: 100px 25px;
                    }
        
                    header {
                        position: fixed;
                        top: -60px;
                        left: 0px;
                        right: 0px;
                        height: 50px;
                        font-size: 20px !important;
                        background-color: #423737;
                        color: white;
                        text-align: center;
                        line-height: 35px;
                    }
        
                    footer {
                        position: fixed; 
                        bottom: -60px; 
                        left: 0px; 
                        right: 0px;
                        height: 50px; 
                        font-size: 20px !important;
                        background-color: #423737;
                        color: white;
                        text-align: center;
                        line-height: 35px;
                    }
        
     
            </style>
</head>

<body>
    <?php

    use App\Models\DepartmentModel;
    use App\Models\PortalModel;

    $getPortalName = PortalModel::where('id', 1)->first();
    //Portal name short form    
    $getProjectShortForm = $getPortalName->short_form_name;
    //Application long name
    $getSoftwareName = $getPortalName->software_name;
    $getDeptName = $getPortalName->department_name;
    $getGovtName = $getPortalName->govt_name;
    $getDeveloper = $getPortalName->developed_by;
    $getCopyright = $getPortalName->copyright;
    ?>

    <?php

    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;

    $user_id = Auth::user()->id;
    $getUser = User::get()->where('id', $user_id)->first();
    $Department = DepartmentModel::get()->where('dept_id', $getUser->dept_id)->first();
    $getDeptName = $Department->dept_name;
    $getDate = Carbon::now()->format('d-m-Y');

    ?>
    <header>
        {{$getProjectShortForm}}
    </header>
    <footer>
        Copyright © <?php echo date("Y");?> - DP & AR, Government of Manipur
    </footer>
    <div class="table-responsive">
     
        <div style="text-align: center;font-size:25px">Submitted Proforma</div>
        <div style="text-align: center;font-size:22px">Department: {{ $getDeptName}}</div>
        <div style="text-align: right;font-size:15px">Dated: {{ $getDate}}</div>
        <br>


        <table border="1" id="example" class="mdl-data-table" style="width:100%">
            <thead>
                <tr>
                    <th colspan="2">
                        <h3>Deceased Details</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>EIN:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->ein }}</td>
                </tr>
                <tr>
                    <td><b>Employee Name:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->deceased_emp_name }}</td>
                </tr>
                <tr>
                    <td><b>Department Name:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->dept_name }}</td>
                </tr>
                <tr>
                    <td><b>Date of birth:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : date('Y-m-d', strtotime($empDetails->deceased_dob)) }}</td>
                </tr>
                <tr>
                    <td><b>Date of appointment:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : date('Y-m-d', strtotime($empDetails->deceased_doa)) }}</td>
                </tr>
                <tr>
                    <td><b>Post:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->desig_name }}</td>
                </tr>
                <tr>
                    <td><b>Ministry Name:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->ministry }}</td>
                </tr>
                <tr>
                    <td><b>Grade:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->grade_name }}</td>
                </tr>
                <tr>
                    <td><b>Expired on duty:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->expire_on_duty }}</td>
                </tr>
                <tr>
                    <td><b>Cause of death:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->deceased_causeofdeath }}</td>
                </tr>
                <tr>
                    <td><b>Date of expiry:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : date('Y-m-d', strtotime($empDetails->deceased_doe)) }}</td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" id="example" class="mdl-data-table" style="width:100%">
            <thead>
                <tr>
                    <th colspan="2">
                        <h3>Applicant Details</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Name:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->applicant_name }}</td>
                </tr>
                <tr>
                    <td><b>Date of Birth:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : date('Y-m-d', strtotime($empDetails->applicant_dob)) }}</td>
                </tr>
                <tr>
                    <td><b>Mobile Number:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->applicant_mobile }}</td>
                </tr>
                <tr>
                    <td><b>Physically Handicapped:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->physically_handicapped }}</td>
                </tr>
                <tr>
                    <td><b>Caste:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->caste }}</td>
                </tr>
                <tr>
                    <td><b>Date of Application Submitted:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : date('Y-m-d', strtotime($empDetails->appl_date)) }}</td>
                </tr>
                <tr>
                    <td><b>Relationship:</b></td>
                    <td>{{ $Relationship == null ? 'No Record Found' : $Relationship }}</td>
                </tr>
                <tr>
                    <td><b>Qualification:</b></td>
                    <td>{{ $educations == null ? 'No Record Found' : $educations }}</td>
                </tr>
                <tr>
                    <td><b>Email id:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->applicant_email_id }}</td>
                </tr>
                <tr>
                    <td><b>Gender:</b></td>
                    <td>{{ $gender == null ? 'No Record Found' : $gender->sex }}</td>
                </tr>
                <tr>
                    <td><b>Post Preference in Parent Department:</b></td>
                </tr>
                <tr>'
                <td><b>First Preference:</b></td>'
                    <td>{{ $post == null ? 'No Record Found' : $post }}</td>
                </tr>
                <tr>
                    <td><b>Grade:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->applicant_grade }}</td>
                </tr>

                <tr>
                    <td><b>Second Preference:</b></td>
                    <td>{{ $secondpost == null ? 'No Record Found' : $secondpost }}</td>
                </tr>
                <tr>
                    <td><b>Grade:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->second_grade_id }}</td>
                </tr>

                <tr>
                    <td><b>Post Preference in Other Department:</b></td>
                </tr>
                <tr>'
                <td><b>Department Name:</b></td>'
                    <td>{{ $diff_dept == null ? 'No Record Found' : $diff_dept }}</td>
                </tr>
                <tr>'
                <td><b>Post Name:</b></td>'
                    <td>{{ $thirdpost == null ? 'No Record Found' : $thirdpost }}</td>
                </tr>
                <tr>
                    <td><b>Grade:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->third_grade_id }}</td>
                </tr>


            </tbody>
        </table>

        <br>

        <table border="1" id="example" class="mdl-data-table" style="width:100%">
            <thead>
                <tr>
                    <th colspan="2">
                        <h3>Present Address Details</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Locality:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->emp_addr_lcality }}</td>
                </tr>
                <tr>
                    <td><b>Pincode:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->emp_pincode }}</td>
                </tr>
                <tr>
                    <td><b>Sub Division:</b></td>
                    <td>{{ $subDiv == null ? 'No Record Found' : $subDiv->sub_division_name }}</td>
                </tr>
                <tr>
                    <td><b>District:</b></td>
                    <td>{{ $District == null ? 'No Record Found' : $District }}</td>
                </tr>
                <tr>
                    <td><b>State:</b></td>
                    <td>{{ $stateDetails == null ? 'No Record Found' : $stateDetails }}</td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="1" id="example" class="mdl-data-table" style="width:100%">
            <thead>
                <tr>
                    <th colspan="2">
                        <h3>Permanent Address Details</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Locality:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->emp_addr_lcality_ret }}</td>
                </tr>
                <tr>
                    <td><b>Pincode:</b></td>
                    <td>{{ $empDetails == null ? 'No Record Found' : $empDetails->emp_pincode_ret }}</td>
                </tr>
                <tr>
                    <td><b>Sub Division:</b></td>
                    <td>{{ $subDiv1 == null ? 'No Record Found' : $subDiv1->sub_division_name }}</td>
                </tr>
                <tr>
                    <td><b>District:</b></td>
                    <td>{{ $District1 == null ? 'No Record Found' : $District1 }}</td>
                </tr>
                <tr>
                    <td><b>State:</b></td>
                    <td>{{ $stateDetails1 == null ? 'No Record Found' : $stateDetails1 }}</td>
                </tr>
            </tbody>
        </table>

        <table border="1" id="example" class="mdl-data-table" style="width:100%">
            <thead>
                <tr>
                    <th colspan="5">
                        <h3>Family Details</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Sl. No.</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Relation</th>
                </tr>
                @foreach($familyCount as $k=>$familyMember)
                <tr>
                    <td>{{($k+1)}}.</td>
                    <td>{{ $familyMember == null ? 'No Record Found' : $familyMember['name'] }}</td>
                    <td>{{ $familyMember == null ? 'No Record Found' : date('Y-m-d', strtotime($familyMember['dob'])) }}</td>
                    <td>{{ $familyMember == null ? 'No Record Found' : $familyMember['gender'] }}
                    <td>{{ $RelationshipF == null ? 'No Record Found' : $RelationshipF }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table border="1" id="example" class="mdl-data-table" style="width:100%">
            <thead>
                <tr>
                    <th colspan="1">
                        <h3>Uploaded Files Name</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($filesDetails as $key=>$fileCount)
                <tr>
                    <td>{{($key+1)}}. {{$fileCount['doc_name']}}</td>
                   
                </tr>
                @endforeach


            </tbody>
        </table>


</body>

</html>