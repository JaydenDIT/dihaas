
    @extends('layouts.app')

    @section('content')
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

    <link href="{{ asset('assets/css/index.css') }}" rel="stylesheet">
    <div class="container">
    <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>{{ __('Vacancy status') }}</h3>
                            </div>
                            <div class="col-6">
                                <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ url('ddo-assist/vacancySearch') }}" enctype="multipart/form-data" class="was-validated">
                                    <div class="row textright">
                                        @csrf

                                        <div class="col-10 marginright_textalign">
                                            <input type="text" class="form-control" placeholder="Search by Designation" name="searchItem" style="margin-right:0px;">
                                        </div>
                                        <div class="col-2 margin_textalign">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Add input field for search query

                     
                         <div class="col-3"> -->

                            <!-- Add input field for search query -->
                            <!-- <input type="text" id="searchInput" class="form-control" placeholder="Search..."> -->
                            <!-- </div> -->

                        </div>
                    </div>

                    <div class="card-body">

                     
                        <hr>
                        <a class="textdecoration" href="{{ route('downloadpdfvacancy') }}" target=”_blank”>

                            <button id="myButton2" class="btn btn-success">Download Pdf</button>
                        </a>

                        @if(empty($vacancyList))
                                
                                <button  type="button" class="btn btn-success"  disabled>Print</button>
                                @else
                                <button id="printButtonCard1" type="button" class="btn btn-success " >Print</button>

                                @endif
                      
                        <br>
                        <br>

                        <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr class="text-center fontsize_backgroundcolor_color">
                                        <th colspan="4"><b>Vacancy As Per CMIS</b></th>
                                        <th colspan="4"><b>Vacancy As Per Department</b></th>
                                    </tr>
                                  
                                    <tr>

                                        <th style="background-color: #f0d597;">Designation</th>
                                        <th style="background-color: #f0d597;">Post Count</th>
                                        <th style="background-color: #f0d597;">Employee Count</th>
                                        <th style="background-color: #f0d597;">Vacancy</th>
                                         <th style="background-color: #04AA6D;">Current Employee Under DIH</th>
                                        <th style="background-color: #04AA6D;">Total Post Vacant</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for DIH</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for Direct Recruitment</th>
                                        <th style="background-color: #04AA6D;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(empty($vacancyList))
                                    <tr>
                                        <td colspan="8" class="textcenter">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach($vacancyData as $data)
                                    <tr>

                                        <td>{{ $data->designation }}</td>
                                        <td>{{ $data->post_count }}</td>
                                        <td>{{ $data->emp_cnt }}</td>
                                        <td>{{ $data->vacancy }}</td>
                                         <td style="background-color: #04AA6D;" id="current_employee_under_dih_{{ $data->id }}">{{ $data->current_employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="total_post_vacant_dept_{{ $data->id }}">{{ $data->total_post_vacant_dept }}</td>
                                        <td style="background-color: #04AA6D;" id="employee_under_dih_{{ $data->id }}">{{ $data->employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="post_under_dih_{{ $data->id }}">{{ $data->post_under_direct }}</td>
                                        <td>
                                            <button class="btn btn-primary w-100 update-button" data-id="{{ $data->id }}">Update</button>
                                         
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>
<!-- ------------------The below table is for Printing Purpose ------------------------------------->
                        <div class="table-responsive displaynone" id="card1-table">

                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="5"><b>Vacancy As Per CMIS</b></th>
                                        <th colspan="3"><b>Vacancy As Per Department</b></th>
                                    </tr>
                                  
                                    <tr>

                                        <th>Designation</th>
                                        <th>Post Count</th>
                                        <th>Employee Count</th>
                                        <th>Vacancy</th>
                                        <th style="background-color: #04AA6D;">Current Employee Under DIH</th>
                                        <th style="background-color: #04AA6D;">Total Post Vacant</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for DIH</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for Direct Recruitment</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(empty($vacancyList))
                                    <tr>
                                        <td colspan="8" class="textcenter">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach($vacancyDataPrint as $data)
                                    <tr>

                                        <td>{{ $data->designation }}</td>
                                        <td>{{ $data->post_count }}</td>
                                        <td>{{ $data->emp_cnt }}</td>
                                        <td>{{ $data->vacancy }}</td>
                                         <td style="background-color: #04AA6D;" id="current_employee_under_dih_{{ $data->id }}">{{ $data->current_employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="total_post_vacant_dept_{{ $data->id }}">{{ $data->total_post_vacant_dept }}</td>
                                        <td style="background-color: #04AA6D;" id="employee_under_dih_{{ $data->id }}">{{ $data->employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="post_under_dih_{{ $data->id }}">{{ $data->post_under_direct }}</td>
                                       
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>


                        <!-- Pagination Links -->
                        @if($vacancyData != null)
                        <div class="row">
                            {!! $vacancyData->links() !!}
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>














    <!-- Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Vacancy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Update Form -->
                    <form id="updateVacancyForm" name="updateVacancyForm" action="{{ route('vacancy.update.ajax') }}" method="POST">
                        <input type="hidden" name="percentage" id="percentageValue" value="{{$percentageModel->vpercentage}}">
                        @csrf
                        {{-- @method('POST') --}}
                        <!-- Add your form fields here -->
                        <input type="hidden" name="id" id="id" value="">
                          <div class="mb-3">
                            <label for="current_employee_under_dih" class="form-label">Previous Employee Under DIH</label>
                            <input type="number" class="form-control" font style="background-color: #f0d597" id="current_employee_under_dih" name="current_employee_under_dih" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="total_post_vacant_dept" class="form-label">Total Post Vacant</label>
                            <input type="number" class="form-control" id="total_post_vacant_dept" name="total_post_vacant_dept"  required>
                        </div>
                        <div class="mb-3">
                            <label for="employee_under_dih" class="form-label">Post Vacant for DIH</label>
                            <input type="number" class="form-control" font style="background-color: #f0d597" id="employee_under_dih" name="employee_under_dih"  readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="post_under_direct" class="form-label">Post Vacant for Direct Recruitment</label>
                            <input type="number" class="form-control" font style="background-color: #f0d597" id="post_under_direct" name="post_under_direct"  readonly required>
                        </div>
                        <!-- Add other form fields -->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="submit_vac_btn"  class="btn btn-primary" data-bs-toggle="modal">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script nonce="{{ csp_nonce() }}">
        //The below code is for print
        // The print function for Card 1
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


</script>

  
    <script nonce="{{ csp_nonce() }}">
        $(document).ready(function() {
            $(document).on('click', '.update-button', function() {
                var row = $(this).closest('tr');
                var id = $(this).data('id');
                // var id = row.find('td:eq(0)').text();
                // Index starts from 0
                // row.find('td:eq(4)'): This finds all <td> elements within the row and selects the one at index 4.
                // row.find('td:eq(5)'): This finds all <td> elements within the row and selects the one at index 5.
                // row.find('td:eq(6)'): This finds all <td> elements within the row and selects the one at index 6.
                var current_employee_under_dih = row.find('td:eq(4)').text();
                var total_post_vacant_dept = row.find('td:eq(5)').text();
                var employee_under_dih = row.find('td:eq(6)').text();
                var post_under_direct = row.find('td:eq(7)').text();

                openDialog(id, current_employee_under_dih, total_post_vacant_dept, employee_under_dih, post_under_direct);
            });

            function openDialog(id, current_employee_under_dih, total_post_vacant_dept, employee_under_dih, post_under_direct) {
                $('#id').val(id);
                $('#current_employee_under_dih').val(current_employee_under_dih);
                $('#total_post_vacant_dept').val(total_post_vacant_dept);
                $('#employee_under_dih').val(employee_under_dih);
                $('#post_under_direct').val(post_under_direct);
                $('#updateModal').modal('show');
            }
            
            document.getElementById("submit_vac_btn").addEventListener("click", function(event){
                event.preventDefault();
                
                var form = $("#updateVacancyForm");               
                
                var id = $('#id').val()

                // Serialize form data for debugging
                var formData = form.serialize();

                //var formData = new FormData(document.forms['updateVacancyForm']);

                //alert(formData);
                //console.log('Form Data:', formData); // Log form data to the console
                let url = form.attr('action');
                console.log("action URL:"+url);
                                
                $.ajax({
                    url: url,
                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        // Add other headers if needed
                    },
                    // processData: false,
                    // contentType: false,
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        alert(response.message);

                        if (response.success) {
                            // Update UI or show success message
                            $('#updateModal').modal('hide');

                            // Get the updated data from the response
                            var updatedData = response.data;
                            $('#current_employee_under_dih_' + id).html($('#current_employee_under_dih').val());
                            $('#total_post_vacant_dept_' + id).html($('#total_post_vacant_dept').val());
                            $('#employee_under_dih_' + id).html($('#employee_under_dih').val());
                            $('#post_under_dih_' + id).html($('#post_under_direct').val());
                        
                            // Perform any additional actions after successful update
                        } else {
                            $('#updateModal').modal('hide');
                        
                        }
                    },

                    error: function(xhr, status, error) {
                        console.log(xhr, status, error);
                        alert(JSON.parse(xhr.responseText).message);
                    }

                }); 
            });
        });
  

        
    
    

    document.querySelector("#total_post_vacant_dept").addEventListener("keyup", (e) => {
        
        let total_vac = parseFloat(e.target.value);
        console.log(total_vac);
        let percentage = document.querySelector("#percentageValue");

        try{
            let dih = Math.round((parseFloat(percentage.value)*total_vac)/100);
            console.log("val: "+dih)
            $("#employee_under_dih").val(dih);
            $("#post_under_direct").val(total_vac -dih);
        }
        catch(error){
            console.error(error);
        }
        
    });
       
    </script>
  

    @endsection
