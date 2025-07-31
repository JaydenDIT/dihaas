<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')


<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ __('Application Status') }}</h3>
                        </div>
                        <div class="col-6">
                          
                        </div>
                    </div>
                </div>

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

                    <!-- {{ __('You are logged in!') }} -->
                <div class="row">
                        <div class="col-6">
                   <h4 class="background5">
                    <span class="blue">{{$status}}</span>
                </h4>
                          
  
                     </div>
                 </div>
                    
                     </div>
                 </div>
             </div>
         </div>
     </div>
  




@endsection

<script type="text/javascript">
    function setRevertData(temp_array) {
        // console.log(temp_array['ein']);
        if (!confirm('Are You Sure that the Applicant File is NOT OK?')) {
            return;
        }
        $("#remarkModal").modal('show');
        let form = document.forms['revertForm'];
        // form.appl_number.value = temp_array['appl_number'];
        form.ein.value = temp_array['ein'];
        console.log(temp_array['ein'])

    }
</script>