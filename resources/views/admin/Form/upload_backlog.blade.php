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
        @include('admin.progress.form_progress_backlog')

        <div class="row justify-content-center">

            <!-- @include('admin.form_menu_buttons') -->

            <br>
            <div class="col-md-12 margintop">
                <!-- Files upload for HOD Assist-->
                <div class="card displayshow" id="emolument_section">
                    <div class="card-header">
                  
                        <div class="row">
                        <div class="col-12 textleft">
                         <b>EIN: {{$empDetails->ein }} &nbsp; Deceased Name : {{ $empDetails->deceased_emp_name }} &nbsp; D.O.E: {{date('Y-m-d', strtotime($empDetails->deceased_doe))}} &nbsp;&nbsp;&nbsp; Applicant Name : {{ $empDetails->applicant_name }}</b>    

                            </div>
                        </div>
                    </div>
                
                    <div class="container">
                        <form name="uploadform" action="{{route('upload-backlog-files')}}" method="post" enctype="multipart/form-data">

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
                                 <div id="form_header" class="textalign_fontsize"><b>Backlog Upload Documents</b>
                            </div>

                            <br>
                            @foreach($reqDocs as $docs)
                            <input type="hidden" name="row[{{$loop->index}}][doc_id]" value="{{$docs['doc_id']}}">
                                <input type="hidden" name="row[{{$loop->index}}][doc_name]" value="{{$docs['doc_name']}}">
                                <input type="hidden" name="row[{{$loop->index}}][is_mandatory]" value="{{$docs['is_mandatory']}}">
                                <div class="table-responsive">
                                <div class="row p-2">
                                    <div class="col-5">
                                        <div class="">
                                            <label for=""><b>{{$docs['doc_name']}}</b></label>
                                        </div> 
                                    </div>
                                    <div class="col-3">
                                        <input class="form-control" type="file" id="{{$docs['doc_id']}}" name="row[{{$loop->index}}][file]"  accept="application/pdf,image/x-png,image/gif,image/jpeg">
                                    </div>
                                    <div class="col-4">
                                        @if(isset($fileArray[$docs['doc_id']]))
                                        <button type="button" class="btn btn-danger remove-button"
                                            data_details_id="<?= $docs['doc_id'] ?>">Remove</button>
                                        <a href="viewFile/{{$docs['doc_id']}}" target="_blank"
                                            title="opens in new tab">{{$fileArray[$docs['doc_id']]}}</a>
                                        @else
                                        <div>
                                            <button type="button"
                                                class="btn btn-primary upload-button">Upload</button>
                                            No file uploaded
                                        </div>
                                        @endif
                                    </div>
                                  
                                 </div>                          
                                </div>
                                 @endforeach

                            <br>
                          

                            <br>
                            <br>
                            <div class="position-relative">
                                <div class="label position-absolute top-50 start-50 translate-middle">
                               
                                <!-- enter-family-details-backlog -->
                                <a href="{{ route('view-family-details-dihas') }}"
                                class="btn btn-success btn-sm">Back</a>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-success" id="submitButton">Save
                                As Draft</button>
                                    &nbsp;&nbsp;&nbsp;<a href="{{ route('other_form_details_submit_backlog') }}" id="close" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                      Next
                                      </a> 
                                  

                             
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
    
                    {{-- <script nonce="{{ csp_nonce() }}">
                        onSubmitHandler = () =>{
                          
                          document.uploadform.action = "upload-applicant-files-draftbacklog";
                          document.uploadform.submit();
                      }

                      onUpload = () =>{
                         document.uploadform.action = "upload-backlog-files";
                         document.uploadform.submit();
                      }


                      onDelete = (id) =>{
                          document.uploadform.deleteId.value = id;
                         document.uploadform.action = "upload-applicant-files-fdeletebacklog";
                         document.uploadform.submit();
                       
                  }
                  </script> --}}
                  <script type="text/javascript" src="{{ asset('assets/js/auth/upload.js') }}"></script>
                  @endsection
</body>