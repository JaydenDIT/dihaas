<!DOCTYPE html>
<html>

<head>
    <!-- Add Bootstrap 5 CSS link here (if not already included) -->
   
</head>

<body>

    @extends('.layouts.app')

    @section('content')

    <div class="container">


        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @endif


        @if ($ein && $applicantName)
        <div class="card">
            <div class="card-header d-flex justify-content-between">

                <div class="d-flex flex-row">
                    <div class="card-text"><strong>EIN:</strong> {{ $ein }}</div>
                    &nbsp; &nbsp; &nbsp;
                    <div class="card-text"><strong>Applicant Name:</strong> {{ $applicantName }}</div>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="p-2">Second Applicant</div>
                    <div class="p-2">
                        <div style="width:500px;">
                            @if($second_applicant == "")
                            <select id="applicantSelect" class="form-select" onchange="updateHiddenInput()">
                            @else
                            <select id="applicantSelect" class="form-select" onchange="updateHiddenInput()" disabled>
                            @endif
                            <option value="0" selected>Select </option>
                            @foreach ($familyMembers as $familyMember)
                                    @if($second_applicant == $familyMember->name)
                                    <option value="{{ $familyMember->name }}" selected >{{ $familyMember->name }}</option>
                                    @else
                                       <!-- age is an attribute -->
                                        @php
                                            $dob = \Carbon\Carbon::parse($familyMember->dob);
                                            $age = $dob->age;
                                         
                                        @endphp

                                        @if ($age >= 15)
                                            <option value="{{ $familyMember->name }}">{{ $familyMember->name }}</option>
                                        @endif
                                   @endif
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="p-2">

                        <form id="saveApplicantForm" action="{{ route('save_applicant') }}" method="post">
                            @csrf
                            <input type="hidden" name="applicant_name" id="applicant_name" value="">
                            @if($change_status == 0)
                                <button id="saveApplicantBtn" type="button" class="btn btn-outline-primary">Save Applicant Name</button>
                            @endif
                       </form>

                        <!-- <a class="btn btn-primary" href="#" role="button">Update Second Applicant Data</a> -->
                    </div>
                    <div class="p-2">
                        <form action="{{ route('update_second_applicant_data') }}" method="post">
                            @csrf
                            <input type="hidden" name="applicant_name" id="applicant_name" value="">
                            @if($change_status == 1)
                            <button id="updateApplicantBtn" type="submit" class="btn btn-primary" >Update Second Applicant Data</button>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-danger">
            Applicant not found.
        </div>
        @endif

    </div>

    @endsection

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <!-- Add Bootstrap 5 JS and Popper.js links here (if not already included) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script nonce="{{ csp_nonce() }}">
        function updateHiddenInput() {
            // Get the selected value from the dropdown
            var selectedApplicant = document.getElementById("applicantSelect").value;

            // Update the hidden input field value with the selected applicant name
            document.getElementById("applicant_name").value = selectedApplicant;
        }
        $('document').ready(function(){
            
            $('#saveApplicantBtn').on('click',function(){
                if($('#applicantSelect').val() == "0"){
                    alert("Select applicant name");
                    $('#applicantSelect').focus();
                    return false;
                }
                $('#saveApplicantForm').submit();
            });
       })
    </script>
</body>

</html>