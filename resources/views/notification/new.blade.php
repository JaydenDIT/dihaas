@extends('layouts.app')

@section('content')


<link href="{{ asset('assets/DataTableCompact/datatablecompact.css') }}" rel="stylesheet">

<div class="container mt-3">


    <button class="btn btn-primary mb-2" onClick="addNew();"> Add New </button>


    <table class="table table-bordered shadow table-sm display data-table">
        <thead>
            <tr>
                <th class="text-center" scope="col">S.No.</th>
                <th class="text-center" scope="col">Titles</th>
                <th class="text-center" scope="col">Document</th>
                <th class="text-center" scope="col">Validity</th>
                <th class="text-center" scope="col">Action</th>
            </tr>
        </thead>
        <tbody class="">
            @foreach($notifications as $row)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>{{ (strlen($row->document_caption)>20)? substr($row->document_caption, 0, 20)."...": $row->document_caption}}
                </td>
                <td class="text-center" id="view_{{$row->notification_id}}" >
                    <a href="{{ route('notification.getdoc', [ $row->notification_id ]) }}" target="_blank">View</a>
                </td>
                <td>
                    {{ date("Y-m-d", strtotime( $row->validity ) )  }}
                </td>
                <td>
                   <button type="button" class="btn btn-sm btn-primary" onClick="editData({{json_encode($row)}});" > Edit </button> | 
                   <a href="{{route('notification.delete',[$row->notification_id])}}" >
                        <button type="button" class="btn btn-sm btn-danger" onClick="$('#loading-div').show();" > Remove </button> 
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Notification/Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="notification-form" id="notificationform"  method="post" enctype='multipart/form-data'
                    action="{{ route('notification.save') }}">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-sm-3 text-end fw-bold">Caption: </div>
                        <div class="col-sm-8 ps-4">
                            <input type="text" class="form-control" name="document_caption"  value="" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-3 text-end fw-bold">Document: </div>
                        <div class="col-sm-8 ps-4">
                            <input type="file" class="form-control" name="document" accept="application/pdf" value=""  required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-3 text-end fw-bold">Valid Upto: </div>
                        <div class="col-sm-8 ps-4">
                            <input type="date" class="form-control" name="validity" value="" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" >Submit</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="notification-editform"  method="post" enctype='multipart/form-data'
                    action="{{ route('notification.save') }}">
                    @csrf
                    <input type="hidden" class="form-control" name="notification_id" id="edit_notification_id"  value="" required>
                    <div class="row mb-4">
                        <div class="col-sm-3 text-end fw-bold">Caption: </div>
                        <div class="col-sm-8 ps-4">
                            <input type="text" class="form-control" name="document_caption" id="edit_document_caption"  value="" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-3 text-end fw-bold">Document: <span id="view_span"></span></div>
                        <div class="col-sm-8 ps-4">
                            <input type="file" class="form-control" name="document" accept="application/pdf" value="">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-3 text-end fw-bold">Valid Upto: </div>
                        <div class="col-sm-8 ps-4">
                            <input type="date" class="form-control" name="validity" id="edit_validity" value="" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" >Submit</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>






<script type="text/javascript" src="{{ asset('assets/DataTableCompact/datatablecompact.js') }}"></script>

<script>
var _token = "{{ csrf_token() }}";
</script>



<script type="text/javascript" src="{{ asset('assets/js/process/notification.js') }}"></script>

@endsection