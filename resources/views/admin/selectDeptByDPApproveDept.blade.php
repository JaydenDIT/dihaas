<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')
@section('content')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

<link href="{{ asset('assets/DataTableCompact/datatablecompact.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/select.css') }}" rel="stylesheet" >

<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <!-- saved succes message -->
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <!-- saved erroe message -->
                    @if(session()->has('error_message'))
                    <div class="alert alert-danger">
                        {{ session()->get('error_message') }}
                    </div>
                    @endif

                  

                   <div class="row">
                        <div class="col-7">
                            <b class="color">List of Approved Applicants to Generate UO</b>
                        </div>
                         <div class="col-5"> 
                        <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ url('ddo-assist/selectDeptByDPApproveDeptSearch') }}" enctype="multipart/form-data" class="was-validated">
                        <div class="row textright" >
                                    @csrf

                                    <div class="col-10 marginright_textalign" >
                                        <input type="text" class="form-control marginright" placeholder="Search by EIN NO." name="searchItem" >
                                    </div>
                                    <div class="col-2 margin_text" >
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div> 
                    </div>
                    <hr>
                    <p>
                    <form name="frmGenerateUO" method="post" action="" novalidate target="_blank">
                  
                        <div class="textright">

                         @if($empList->isEmpty())
                                
                                <button type="button" class="btn btn-success"  disabled>Print</button>
                                @else
                                <button id="printButtonCard1" type="button" class="btn btn-success" >Print</button>
                                @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed table-striped" id="table">

                                <thead class="thead-dark">
                                    <tr>
                                      
                                        <th scope="col">Sl.No.</th>
                                        <th scope="col">EIN</th>
                                        <th scope="col">Deceased Name</th>                                       
                                        <th scope="col">DOE</th>
                                        <th>Application No.</td>
                                        <th>Applicant Name</td>
                                        <th scope="col">Mobile No.</th>
                                        
                                        <th scope="col" style="color:red;">status</th>
                                        <th scope="col" colspan="4" colspan="4"  class="textcenter">Action</th>
                                    </tr>
                                </thead>
                                <tbody>


                                @if($empList->isEmpty())
                                    <tr>
                                        <td colspan="9"  class="textcenter">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else



                                    @foreach($empList as $key => $data)




                                    <tr>
                                       
                                    <td>{{ $empList->firstItem() + $key }}</td>
                                        <td>{{$data->ein}}</td>
                                        <td>{{$data->deceased_emp_name}}</td>
                                        
                                        <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}</td>
                                        <td>{{$data->appl_number}}</td>

                                        <td>{{$data->applicant_name}}</td>

                                        <td>{{$data->applicant_mobile}}</td>
                                        
                                        <td style="color:red;">{{$data->status}}</td>


                                        @if($data->formSubStat == "submitted")
                                        <td class="textright">
                                        
                                          

                                        </td>
                                        @endif

                                        @if($data->formSubStat == "started")
                                   
                                        @endif
                                        @if($data->formSubStat == "approved")
                                      

                                      
                                      
                                        @endif
                                        @if($data->formSubStat == "appointed")
                                      

                                        @endif

                                        @if($data->formSubStat == "order")
                                <td class="textright">
                                    <a href="{{ route('viewOrder', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm width_height4" role="button" aria-disabled="true">View Order</a>
                                  
                                </td>

                                @endif


                                    </tr>
                                



                                 
                                    @endforeach


                                    @endif

                                </tbody>
                            </table>
                        </div>

              
                        <div class="table-responsive display" id="card1-table">
                            <table class="table table-bordered table-condensed table-striped">

                                <thead class="thead-dark">
                                    <tr>

                                        <th scope="col">Sl.No.</th>
                                        <th scope="col">EIN</th>
                                        <th scope="col">Deceased Name</th>
                                        <th scope="col">Department Name</th>
                                        <th scope="col">DOE</th>
                                        <th>Application No.</td>
                                        <th>Applicant Name</td>
                                        <th scope="col">Mobile No.</th>

                                        <th scope="col" style="color:red;">status</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>



                                



                                    @foreach($empListprint as $data)




                                    <tr>
                                        @if($data->status == 'Appointed')
                                        <td>
                                            <input type="checkbox" name="selectedGrades[]" id="{{$data->dept_id.'|'.$data->ein}}" value="{{ $data->ein }}" onchange="onChangeCheckBox(this);onChangeCheckBox1(this)">
                                        </td>
                                        @endif

                                        @if($data->status == 'Approved')
                                        <td>

                                        </td>
                                        @endif
                                        <th scope="row">{{$loop->index + 1}}</th>
                                        <td>{{$data->ein}}</td>
                                        <td>{{$data->deceased_emp_name}}</td>
                                        <td>{{$data->dept_name}}</td>
                                        <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}</td>
                                        <td>{{$data->appl_number}}</td>

                                        <td>{{$data->applicant_name}}</td>

                                        <td>{{$data->applicant_mobile}}</td>

                                        @if($data->status == '5')
                                        <td style="color:red;">
                                         Appointed  
                                        </td>
                                        @endif

                                        @if($data->status == '4')
                                        <td style="color:red;">
                                        Approved
                                        </td>
                                        @endif

                                        @if($data->formSubStat == "submitted")
                                        <td class="textright">
                                           

                                          

                                        </td>
                                        @endif

                                        @if($data->formSubStat == "started")
                                       
                                        @endif
                                        @if($data->formSubStat == "approved")
                                        <td class="textright">
                                            <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm" role="button" aria-disabled="true">View</a>
                                          
                                        </td>

                                        <td>
                                            <a href="{{ route('fill_uo', Crypt::encryptString($data->ein)) }}" id="fill_uo" class="btn btn-primary width_height2" >Fill UO</a>

                                        </td>
                                       
                                      
                                        @endif
                                        @if($data->formSubStat == "appointed")
                                        <td>
                                            <a href="{{ route('generate-pdf', Crypt::encryptString($data->ein))}}" id="generate_uo" class="badge btn btn-success text-wrap width_height" role="button" aria-disabled="true" target="_blank">Generate Single UO</a>
                                        </td>

                                        @endif

                                        {{-- @if($data->formSubStat == "order")
                                        <td class="textright">
                                            <a href="{{ route('viewOrder', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm width_height4" role="button" aria-disabled="true">View Order</a>
                                         
                                        </td>

                                        @endif --}}


                                    </tr>
                                




                                  
                                    @endforeach


                                

                                </tbody>
                            </table>
                        </div>
                    </form>
                    {{-- @if($empList != null)
                    <div class="row">
                        {!! $empList->links() !!}
                    </div>
                    @endif --}}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script nonce="{{ csp_nonce() }}">
    
    document.addEventListener("DOMContentLoaded", function() {
    var printButton = document.getElementById("printButtonCard1");

    printButton.addEventListener("click", function() {
        printListCard1();
    });
});

    function printListCard1() {
        var cardContent = document.getElementById('card1-table').innerHTML;
        var borderedContent = '<div class="center-table" >' + cardContent + '</div>' +
            '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding:2px;}}' +
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
            "<img src='{{asset('assets/images/kanglasha.png')}}' alt='Image ' width='80' height='100'>");
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


</script>


<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>