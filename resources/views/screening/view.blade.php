@extends('layouts.app')

@section('content')


<link href="{{ asset('assets/DataTableCompact/datatablecompact.css') }}" rel="stylesheet">

<div class="container mt-3">


   
    <table class="table table-bordered shadow table-sm display data-table">
        <thead>
            <tr>
                <th class="text-center" scope="col">S.No.</th>
                <th class="text-center" scope="col">Titles</th>               
                <th class="text-center" scope="col">Validity</th>
                <th class="text-center" scope="col">Document</th>
            </tr>
        </thead>
        <tbody class="">
            @foreach($screenings as $row)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>{{ (strlen($row->document_caption)>20)? substr($row->document_caption, 0, 80)."...": $row->document_caption}}
                </td>
               
                <td>
                    {{ date("Y-m-d", strtotime( $row->validity ) )  }}
                </td>
                <td class="text-center" id="view_{{$row->screening_id}}" >
                    <a href="{{ route('screening.getdoc', [ $row->screening_id ]) }}" target="_blank">View</a>
                </td>
               
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- Modal -->

<script type="text/javascript" src="{{ asset('assets/DataTableCompact/datatablecompact.js') }}"></script>

<script nonce="{{ csp_nonce() }}">
var _token = "{{ csrf_token() }}";
</script>



<script type="text/javascript" src="{{ asset('assets/js/process/screening.js') }}"></script>

@endsection