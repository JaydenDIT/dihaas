@extends('layouts.app')

@section('content')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
<style nonce="{{ csp_nonce() }}">
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {background-color: coral;}
</style>

<?php $selected = session()->get('deptId') ?>

<div class="container">
<br>
    <div class="row justify-content-center">
        <div class="col-md-6 mb-4">
            <form id="card1-form" method="POST" action="{{ route('vacancy_list_dpview') }}">
                @csrf
                <div class="form-group row">
                    <label for="dept_id" class="col-md-4 col-form-label text-md-end"><b>{{ __('Department') }}</b></label>
                    <div class="col-md-6">
                        <select class="form-select" aria-label="Default select example" id="dept_id" name="dept_id">
                            <option value="" selected>All Department</option>
                            @foreach($deptListArray as $option)
                            <option value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}" required
                                {{($selected == $option['dept_id'])?'selected':''}}> {{$option['dept_name']}}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" id="submit-card1" class="btn btn-success">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <?php

        $getYear = date('Y');

        ?>
<div class="col-md-5">
                                <div class="d-flex justify-content-end">
                                    <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST"
                                        action="{{ url('ddo-assist/vacancy_list_dpviewSearch') }}"
                                        enctype="multipart/form-data" class="was-validated">
                                        <div class="row" style="text-align: right;">
                                            @csrf

                                            <div class="col-10" style="margin-right: -17px; text-align:right;">
                                                <input type="text" class="form-control" placeholder="Search by Designation"
                                                    name="searchItem" style="margin-right:0px;">
                                            </div>
                                            <div class="col-2"
                                                style="margin-right:-15px;margin-left:0px; text-align:left;">
                                                <button class="btn btn-outline-secondary" type="submit"
                                                    id="button-addon2">Search</button>
                                            </div>
                                        </div>
                                    </form>


                                  
                                </div>
                            </div>

        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-title p-2">
                        <div class="row">
                            <div class="col-md-6">
                                <b style="color:blue"><u>CMIS Vacancy as on 31st March {{$getYear}}</u></b>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    <button id="printButtonCard1" type="button" class="btn btn-success" >Print</button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div id="card1-content">

                        <!-- @isset($vacancies) -->
                        <!-- Display the vacancy list for Card 1 here -->

                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead class="thead-dark">
                                <tr class="text-center" style="font-size:18px;background-color:rgb(184, 184, 184); color:dodgerblue; ">
                                        <th colspan="5"><b>Vacancy As Per CMIS</b></th>
                                        <th colspan="4"><b>Vacancy As Per Department</b></th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f0d597;">Department Name</th>
                                        <th style="background-color: #f0d597;">Post Name</th>
                                        <th style="background-color: #f0d597;">Sanctioned Post</th>
                                        <th style="background-color: #f0d597;">Employee count</th>
                                        <th style="background-color: #f0d597;">Post Vacancy</th>
                                        <th style="background-color: #04AA6D;">Total Post Vacant</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for DIH</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for Direct Recruitment</th>
                                        {{-- <th style="background-color: #04AA6D;">Direct Recruitment</th>
                                        <th style="background-color: #04AA6D;">Employee Under DIH</th>
                                        <th style="background-color: #04AA6D;">Post Under DIH </th> --}}

                                    </tr>
                                </thead>
                                <tbody>
                                    @if(empty($vacancyList1))
                                    <tr>
                                        <td colspan="5" style="text-align: center;">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach($vacancyArray1 as $vacancy)
                                    <tr>
                                        <td>{{ $vacancy['department'] }}</td>
                                        <td>{{ $vacancy['designation'] }}</td>
                                        <td>{{ $vacancy['post_count'] }}</td>
                                        <td>{{ $vacancy['emp_cnt'] }}</td>
                                        <td>{{ ($vacancy['vacancy']) }}</td>

                                        <td style="background-color: #04AA6D;">{{ ($vacancy['total_post_vacant_dept']) }}</td>
                                        <td style="background-color: #04AA6D;">{{ ($vacancy['employee_under_dih']) }}</td>
                                        <td style="background-color: #04AA6D;">{{ ($vacancy['post_under_direct']) }}</td>

                                          {{-- <td style="background-color: #04AA6D;">{{ ($vacancy['total_post_vacant_dept']) }}</td>
                                        <td style="background-color: #04AA6D;">{{ ($vacancy['employee_under_dih']) }}</td>
                                        <td style="background-color: #04AA6D;">{{ ($vacancy['post_under_direct']) }}</td> --}}

                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- //THE BELOW CODE IS USE FOR PRINTING PURPOSE -->
                        <div class="table-responsive" id="card1-table" style="display:none;">
                            <table>
                                <thead>
                                    <tr>
                                        <th colspan="5">Vacancy As Per CMIS</th>
                                        <th colspan="3">Vacancy As Per Department</th>
                                    </tr>
                                    <tr>
                                        <!-- <th>Id</th> -->
                                        <th>Department Name</th>
                                        <th>Post Name</th>
                                        <th>Sanctioned Post</th>
                                        <th>Employee Count</th>
                                        <th>Vacancy</th>
                                        <th style="background-color: #04AA6D;">Total Post Vacant</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for DIH</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for Direct Recruitment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vacancyArray1print as $vacancy)
                                    <tr>
                                        <!-- <td>{{ $vacancy['id'] }}</td> -->
                                        <td>{{ $vacancy['department'] }}</td>
                                        <td>{{ $vacancy['designation'] }}</td>
                                        <td>{{ $vacancy['post_count'] }}</td>
                                        <td>{{ $vacancy['emp_cnt'] }}</td>
                                        <td>{{ $vacancy['vacancy'] }}</td>
                                       
                                        <td style="background-color: #04AA6D;">{{ ($vacancy['total_post_vacant_dept']) }}</td>
                                        <td style="background-color: #04AA6D;">{{ ($vacancy['employee_under_dih']) }}</td>
                                        <td style="background-color: #04AA6D;">{{ ($vacancy['post_under_direct']) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        @if($vacancyArray1 != null)
                        <div class="row">
                            {!! $vacancyArray1->links() !!}
                        </div>
                        @endif
                        <!-- @endisset -->
                    </div>
                </div>
            </div>




            
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>





<script nonce="{{ csp_nonce() }}">
    //The below code is for print
    // The print function for Card 1
    document.addEventListener("DOMContentLoaded", function() {
    var printButton = document.getElementById("printButtonCard1");
    
    printButton.addEventListener("click", function() {
        printListCard1();
    });
});
    //The below code is for print
    // The print function for Card 1
    
    function printListCard1() {
        var cardContent = document.getElementById('card1-table').innerHTML;
        var borderedContent = '<div class="center-table" >' + cardContent + '</div>' +
            '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}' +
            '.center-table {display: flex; justify-content: center; align-items: center;}</style>';
        printContent(borderedContent);
    }
    
    function printListCard1() {
        var cardContent = document.getElementById('card1-table').innerHTML;
        var borderedContent = '<div class="center-table" >' + cardContent + '</div>' +
            '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}' +
            '.center-table {display: flex; justify-content: center; align-items: center;}</style>';
        printContent(borderedContent);
    }

//not used
    // The print function for Card 2
    function printListCard2() {
        var cardContent = document.getElementById('card2-table').innerHTML;
        var borderedContent = '<div class="center-table" >' + cardContent + '</div>' +
            '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}' +
            '.center-table {display: flex; justify-content: center; align-items: center;}</style>';
        printContent(borderedContent);
    }

    // Shared function to print content
    function printContent(content) {
        var printWindow = window.open('', '_blank');


        <?php

        use App\Models\DepartmentModel;
        use App\Models\PortalModel;
        use App\Models\User;
        use Carbon\Carbon;
        use Illuminate\Support\Facades\Auth;

        $getPortalName = PortalModel::where('id', 1)->first();
        //Portal name short form    
        $getProjectShortForm = $getPortalName->short_form_name;
        //Application long name
        $getSoftwareName = $getPortalName->software_name;
        $getDeptName = $getPortalName->department_name;
        $getGovtName = $getPortalName->govt_name;
        $getDeveloper = $getPortalName->developed_by;
        $getCopyright = $getPortalName->copyright;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $Department = DepartmentModel::get()->where('dept_id', $getUser->dept_id)->first();
        $getDeptName = $Department->dept_name;
        $getDate = Carbon::now()->format('Y-m-d');

        ?>

        printWindow.document.write("<html><head><title>{{$getSoftwareName}}</title></head><body>");
        printWindow.document.write("<div style='display: flex; align-items: center;'>");
        printWindow.document.write(
            "<img src='https://cmdahaisi.mn.gov.in/images/kanglasha.png' alt='Image' width='80' height='100'>");
        printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
        // printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
        printWindow.document.write("<p>{{$getProjectShortForm}}</p>");
        printWindow.document.write("<p>Vacancy List</p>");
        printWindow.document.write("</div></div>");
        printWindow.document.write("<p><br></br></p>");
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }


    //The below code is for Submit
    $(document).ready(function() {
        /*$('#submit-card1').on('click', function() {
            var formData = $('#card1-form').serialize();
            var formData1 = $('#card1-form').serialize();

            $.ajax({
                url: "{{ route('submitCard1Form') }}",
                type: 'POST',
                data: formData, formData1,
                success: function(response) {
                    var vacancyListHtml = $(response).find('#card1-content .table-responsive')
                        .html();
                    $('#card1-content .table-responsive').html(vacancyListHtml);

                    var vacancyListHtml1 = $(response).find('#card2-content .table-responsive')
                        .html();
                    $('#card2-content .table-responsive').html(vacancyListHtml1);

                }
            });
        });*/
    });
</script>

<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>
@endsection