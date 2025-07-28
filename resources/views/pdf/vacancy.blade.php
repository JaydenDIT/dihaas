<!DOCTYPE html>
<html lang="en">

<head>
   
    <style nonce="{{ csp_nonce() }}">
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
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
    $getDate = Carbon::now()->format('Y-m-d');

    ?>
    <!-- <a class="navbar-brand" href="{{ url('/') }}">
<img src="https://cmdahaisi.mn.gov.in/images/kanglasha.png" width="60" height="60"></a> -->
    <div class="table-responsive">
        <div style="text-align: center;font-size:30px">{{$getProjectShortForm}}</div>

        <div style="text-align: center;font-size:30px">Department: {{$getDeptName}}</div>

        <div style="text-align: center;font-size:25px">List of Vavancies</div>

        <div style="text-align: right;font-size:15px">Dated: {{ $getDate}}</div>
        <br>

        <table class="table">
            <thead>
            <tr>
                                        <th colspan="4">Vacancy As Per CMIS</th>
                                        <th colspan="3">Vacancy As Per Department</th>
                                    </tr>
                <tr>

                    <th>Designation</th>
                    <th>Sanctioned Post</th>                  
                    <th>Employee Count</th>
                    <th>Vacancy</th>
                    <th>Direct Recruitment</th>
                    <th>Employee Under DIH</th>
                    <th>Post Under DIH</th>
                </tr>
            </thead>
            <tbody>
               
                @if(empty($vacancyList))
                <tr>
                    <td colspan="8">No data found</td>
                </tr>
                @else
                @foreach($vacancyData as $record)
                <tr>

                    <td>{{ $record->designation }}</td>
                    <td>{{ $record->post_count }}</td>
                    <td>{{ $record->emp_cnt }}</td>
                    <td>{{ $record->vacancy }}</td>
                    <td>{{ $record->total_post_vacant_dept }}</td>
                    <td>{{ $record->employee_under_dih }}</td>
                    <td>{{ $record->post_under_direct }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
            
        </table>
</body>

</html>