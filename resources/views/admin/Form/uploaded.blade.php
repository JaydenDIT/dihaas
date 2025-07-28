<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="{{ asset('assets/css/upload.css') }}" rel="stylesheet">
    <title>DIHAS</title>
   

</head>

<body>

    @extends('layouts.app')

    @section('content')
    @php $form_no = 3; @endphp

    <div class="container">
    <br>
        @include('admin.progress.form_progress')

        <div class="row justify-content-center">

            <!-- @include('admin.form_menu_buttons') -->

            <br>
            <div class="col-md-12 margintop">
                <!-- Files upload for HOD Assist-->
                <div class="card displayshow" id="emolument_section">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-12 center">
                        <?php
                            // Convert the date string to a DateTime object
                            $deceasedDate = new DateTime($empDetails['deceased_doe']);

                            // Add 6 months to the date
                            $sixMonthsLater = $deceasedDate->modify('+6 months');

                            // Display the result in red
                            echo "<p class='font_color'>Last date of submission: " . $sixMonthsLater->format('Y-m-d') . "</p>";
                        ?>
                        </div>
                        
                        <div class="col-12 textleft">
                                <b>EIN: {{$empDetails->ein }} &nbsp; Deceased Name : {{ $empDetails->deceased_emp_name }} &nbsp; D.O.E: {{date('Y-m-d', strtotime($empDetails->deceased_doe))}} &nbsp;&nbsp;&nbsp; Applicant Name : {{ $empDetails->applicant_name }}</b>

                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <form name="uploadform" action="" method="post" enctype="multipart/form-data">

                        <div class="card-body">
                                @csrf

                                @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                                @endif
                                @if(session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                                @endif
                                @if(session()->has('duplicate'))
                            <div class="alert alert-danger">
                                {{ session('duplicate') }}
                            </div>
                            @endif
                            @if(session()->has('eligible'))
                            <div class="alert alert-danger">
                                {{ session('eligible') }}
                            </div>
                            @endif
                                @if(session()->has('errormessage'))
                                <div class="alert alert-danger">
                                    {{ session('errormessage') }}
                                </div>
                                 @endif 
                                 <div id="form_header" class="textalign_fontsize">
                                    <b>Uploaded Documents</b>
                                </div>
                                <h6 class="color_fontweight"> NOTE : Documents marked with asterisk (*) are mandatory</h6>
                            <br>
                            @foreach($reqDocs as $docs)
                            <input readonly type="hidden" name="row[{{$loop->index}}][doc_id]" value="{{$docs['doc_id']}}">
                                <input type="hidden" name="row[{{$loop->index}}][doc_name]" value="{{$docs['doc_name']}}">
                                <input type="hidden" name="row[{{$loop->index}}][is_mandatory]" value="{{$docs['is_mandatory']}}">
                                
                                    <div class="row p-2">
                                        <div class="col-5">
                                            <div class="">
                                                <label for="">
                                                    <b>{{$docs['doc_name']}}
                                                    @if($docs['is_mandatory']=='Y')
                                                    <span class="color">*</span>
                                                    @endif
                                                    </b>
                                                </label>
                                            </div> 
                                        </div>
                                     
                                        <div class="col-6">
                                            @if(isset($fileArray[$docs['doc_id']]))
                                                <a href="viewFile/{{$docs['doc_id']}}" target="_blank" title="opens in new tab">{{$fileArray[$docs['doc_id']]}}</a>
                                            @else
                                                <div>
                                                No file uploaded
                                                </div>
                                            @endif
                                        </div>
                                  
                                 </div>                          
                               
                                 @endforeach

                            <br>
                          

                            <br>
                            <br>
                                <div class="position-relative">
                                    <div class="label position-absolute top-50 start-50 translate-middle">
                                        <a href="{{ route('other_form_details') }}" class="btn btn-success btn-sm" tabindex="-1" role="button" aria-disabled="true">Next</a>




                                    </div>
                                </div>
                                <input type="hidden" name="deleteId">
                        </form>
                        <br>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
                    <script nonce="{{ csp_nonce() }}">
                        onSubmitHandler = () => {

                            document.uploadform.action = "upload-applicant-files-draft2ndAppl";
                            document.uploadform.submit();
                        }
                        onUpload = () => {
                            document.uploadform.action = "upload-applicant-files2ndAppl";
                            document.uploadform.submit();
                        }

                        onDelete = (id) => {
                            document.uploadform.deleteId.value = id;
                            document.uploadform.action = "upload-applicant-files-fdelete2ndAppl";
                            document.uploadform.submit();

                        }
                    </script>
                    @endsection
</body>