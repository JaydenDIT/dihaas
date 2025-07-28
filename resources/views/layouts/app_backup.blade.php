<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>


    <!-- esign mendatory script -->
    <script src="{{asset('esign/dsc-signer.js')}}" type="text/javascript"></script>

    <script src="{{asset('esign/dscapi-conf.js')}}" type="text/javascript"></script>

    <script src="{{asset('esign/jquery.blockUI.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <!-- end esign -->


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- dynamic form -->
</head>

<body>
    <div id="app" style="background-color: whitesmoke;">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- {{ config('app.name', 'Laravel') }} -->
                    <img src="https://yt3.ggpht.com/ytc/AKedOLSbEDsbXQBg4hJIvr1qH3EqOlG0bkKw2hPkxKHQ=s900-c-k-c0x00ffffff-no-rj" width="60" height="60">
                </a>
                @if (Route::has('login') && Auth::user() != null)
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span style="font-family: Elephant;font-size: 45px;font-weight:bold;line-height:66px">&nbsp;DIHAS</span>
                </a>
                @else
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span style="font-family: Elephant;font-size: 45px;font-weight:bold;line-height:66px">&nbsp;Die-in-Harness Appointment Approval System</span>
                </a>
                @endif

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav">
                        <!-- check is there any login user and if user is exist -->
                        @if (Route::has('login') && Auth::user() != null)

                        <!-- home -->
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ url('/home') }}">
                                <span class="sr-only" style="font-weight: bold;">Home</span>
                            </a>
                        </li>


                        <!-- DDO Assist. -->
                        @if(Auth::user()->role_id == 1 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ddo-assist/expireEmployees') }}">
                                <span class="sr-only" style="font-weight: bold;">Retiring-Employees</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ddo-assist/viewStartEmp') }}">
                                <span class="sr-only" style="font-weight: bold;">Started Employees</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ddo-assist/empListToUpload') }}">
                                <span class="sr-only" style="font-weight: bold;">Upload Files</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ddo-assist/getForm') }}">
                                <span class="sr-only" style="font-weight: bold;">Verify & Forward</span>
                            </a>
                        </li>
                        @endif

                        <!-- DDO -->
                        @if(Auth::user()->role_id == 2 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ddo/ddo') }}">
                                <span class="sr-only" style="font-weight: bold;">DDO</span>
                            </a>
                        </li>
                        <!-- E-Sign Setting -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>E-Sign</b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">

                                <!-- e-sign list -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/view-e-sign-list') }}">
                                        <span class="sr-only" style="font-weight: bold;">E-Sign List</span>
                                    </a>
                                </li>
                                <!-- e-sign registration  -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-registration') }}">
                                        <span class="sr-only" style="font-weight: bold;">Registration</span>
                                    </a>
                                </li>

                                <!-- e-sign file -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-file') }}">
                                        <span class="sr-only" style="font-weight: bold;">Sign Files</span>
                                    </a>
                                </li> -->

                            </ul>
                        </li>

                        @endif

                        <!-- HOD -->
                        @if(Auth::user()->role_id == 3 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('hod/hod') }}">
                                <span class="sr-only" style="font-weight: bold;">HOD</span>
                            </a>
                        </li>
                        <!-- E-Sign Setting -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>E-Sign</b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">

                                <!-- e-sign list -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/view-e-sign-list') }}">
                                        <span class="sr-only" style="font-weight: bold;">E-Sign List</span>
                                    </a>
                                </li>
                                <!-- e-sign registration  -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-registration') }}">
                                        <span class="sr-only" style="font-weight: bold;">Registration</span>
                                    </a>
                                </li>

                                <!-- e-sign file -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-file') }}">
                                        <span class="sr-only" style="font-weight: bold;">Sign Files</span>
                                    </a>
                                </li> -->

                            </ul>
                        </li>
                        @endif

                        <!-- HOD Assist -->
                        @if(Auth::user()->role_id == 4 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('hod-assist/hod-assist') }}">
                                <span class="sr-only" style="font-weight: bold;">HOD-Assist</span>
                            </a>
                        </li>
                        @endif

                        <!-- Admin -->
                        @if(Auth::user()->role_id == 5 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/admin') }}">
                                <span class="sr-only" style="font-weight: bold;">Admin</span>
                            </a>
                        </li>
                        <!-- E-Sign Setting -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>E-Sign</b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">

                                <!-- e-sign list -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/view-e-sign-list') }}">
                                        <span class="sr-only" style="font-weight: bold;">E-Sign List</span>
                                    </a>
                                </li>
                                <!-- e-sign registration  -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-registration') }}">
                                        <span class="sr-only" style="font-weight: bold;">Registration</span>
                                    </a>
                                </li>

                                <!-- e-sign file -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-file') }}">
                                        <span class="sr-only" style="font-weight: bold;">Sign Files</span>
                                    </a>
                                </li> -->

                            </ul>
                        </li>
                        @endif

                        <!-- ADMIN ASSIST  -->
                        @if(Auth::user()->role_id == 6 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin-assist/admin-assist') }}">
                                <span class="sr-only" style="font-weight: bold;">Admin-Assist</span>
                            </a>
                        </li>
                        @endif

                        <!-- pensionCell -->
                        @if(Auth::user()->role_id == 7 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('pension-cell/pensionCell') }}">
                                <span class="sr-only" style="font-weight: bold;">Pension-Cell</span>
                            </a>
                        </li>
                        <!-- E-Sign Setting -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>E-Sign</b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">

                                <!-- e-sign list -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/view-e-sign-list') }}">
                                        <span class="sr-only" style="font-weight: bold;">E-Sign List</span>
                                    </a>
                                </li>
                                <!-- e-sign registration  -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-registration') }}">
                                        <span class="sr-only" style="font-weight: bold;">Registration</span>
                                    </a>
                                </li>

                                <!-- e-sign file -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-file') }}">
                                        <span class="sr-only" style="font-weight: bold;">Sign Files</span>
                                    </a>
                                </li> -->

                            </ul>
                        </li>
                        @endif

                        <!-- AG -->
                        @if(Auth::user()->role_id == 8 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ag/ag') }}">
                                <span class="sr-only" style="font-weight: bold;">AG</span>
                            </a>
                        </li>
                        <!-- E-Sign Setting -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>E-Sign</b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">

                                <!-- e-sign list -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/view-e-sign-list') }}">
                                        <span class="sr-only" style="font-weight: bold;">E-Sign List</span>
                                    </a>
                                </li>
                                <!-- e-sign registration  -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-registration') }}">
                                        <span class="sr-only" style="font-weight: bold;">Registration</span>
                                    </a>
                                </li>

                                <!-- e-sign file -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-file') }}">
                                        <span class="sr-only" style="font-weight: bold;">Sign Files</span>
                                    </a>
                                </li> -->

                            </ul>
                        </li>
                        @endif

                        <!-- NDC -->
                        @if(Auth::user()->role_id == 9 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ndc/ndcAuthority') }}">
                                <span class="sr-only" style="font-weight: bold;">NDC Authority.</span>
                            </a>
                        </li>
                        <!-- E-Sign Setting -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>E-Sign</b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">

                                <!-- e-sign list -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/view-e-sign-list') }}">
                                        <span class="sr-only" style="font-weight: bold;">E-Sign List</span>
                                    </a>
                                </li>
                                <!-- e-sign registration  -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-registration') }}">
                                        <span class="sr-only" style="font-weight: bold;">Registration</span>
                                    </a>
                                </li>

                                <!-- e-sign file -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/e-sign-file') }}">
                                        <span class="sr-only" style="font-weight: bold;">Sign Files</span>
                                    </a>
                                </li> -->

                            </ul>
                        </li>
                        @endif

                        <!-- NDC Assist -->
                        @if(Auth::user()->role_id == 10 )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('ndc-assist/ndcAssist') }}">
                                <span class="sr-only" style="font-weight: bold;">NDC Assist.</span>
                            </a>
                        </li>
                        @endif


                        
                        @endif

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('changePasswordGet') }}">Change Password </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session()->has('submit_error'))
            <div class="container">
                <div class="alert alert-danger">
                    <p>Please check and save Forms :</p>
                    <ul>
                        @foreach(session()->get('submit_error') as $error_msg)
                        <li>{{ $error_msg['form_name']}} ( Form-{{$error_msg['id']}})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>

</html>
<!-- style for form block (id=dividerDiv) -->
<style>
    .pagination {
        float: right;
        margin-top: 10px;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: #cdc59e42;
        color: dark;
    }

    /* .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: var(--bs-table-striped-bg);
        color: #21252970;
    } */

    .btn-success {
        color: black;
        background-color: #d1cfb9;
        border-color: #2d5436;
    }

    .btn-sm,
    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.7875rem;
        border-radius: 0.2rem;
    }

    .btn-danger {
        color: black;
        background-color: #fdfdfd;
        border-color: #9f0a18;
    }

    #dividerDiv {
        padding-left: 20px;
        color: white;
        background-color: gray;
        height: 30px;
        line-height: 30px;
        border-radius: 5px;
    }
</style>
<script>
    // district onchange action
    $(document).ready(function() {
        // cmis emp load button action
        $('#load_all_emp_list_btn').on('click', function() {
            // $('#load_all_emp_list_btn').attr("disabled", true);
            $('#load_all_emp_list_btn').addClass('disabled');
            // $('#loader').show();
        });
        // $('#emp_addr_district_model_field').change(e);
        // present address
        $('#emp_addr_district_model_field').on('change', function() {
            var districtId = this.value;
            // ajax call for sub division
            $.ajax({
                type: 'GET',
                // url: '/get-emp-by-ein/' + x,
                url: '/pps/public/ddo-assist/get-sub-division/' + districtId,
                success: function(data) {
                    // sub division select option
                    console.log(data);
                    jQuery('select[name="emp_addr_subdiv"]').empty();
                    jQuery.each(data.suvDiv, function(key, value) {
                        $('select[name="emp_addr_subdiv"]').append('<option value="' + value.sub_division_name + '">' + value.sub_division_name + '</option>');
                    });

                    // set assembly const select option data
                    jQuery('select[name="emp_addr_assem_cons"]').empty();
                    jQuery.each(data.assemConst, function(key, value) {
                        $('select[name="emp_addr_assem_cons"]').append('<option value="' + value.assembly_constcy_desc + '">' + value.assembly_constcy_desc + '</option>');
                    });
                }
            });
        });
        // address after retirement
        $('#emp_addr_district_retr').on('change', function() {
            var districtId = this.value;
            // ajax call for sub division
            $.ajax({
                type: 'GET',
                // url: '/get-emp-by-ein/' + x,
                url: '/pps/public/ddo-assist/get-sub-division/' + districtId,
                success: function(data) {
                    // sub division select option
                    jQuery('select[name="emp_addr_subdiv_ret"]').empty();
                    jQuery.each(data.suvDiv, function(key, value) {
                        $('select[name="emp_addr_subdiv_ret"]').append('<option value="' + value.sub_division_name + '">' + value.sub_division_name + '</option>');
                    });

                    // set assembly const select option data
                    jQuery('select[name="emp_addr_assem_cons_ret"]').empty();
                    jQuery.each(data.assemConst, function(key, value) {
                        $('select[name="emp_addr_assem_cons_ret"]').append('<option value="' + value.assembly_constcy_desc + '">' + value.assembly_constcy_desc + '</option>');
                    });
                }
            });
        });

        // pay scale details 
        $('#pay_comm_cd_field_model').on('change', function() {
            var comsCode = this.value;
            // ajax call for sub division
            $.ajax({
                type: 'GET',
                // url: '/get-emp-by-ein/' + x,
                url: '/pps/public/ddo-assist/get-pay-coms-pay-scale/' + comsCode,
                success: function(data) {
                    // console.log(data);
                    // sub division select option
                    jQuery('select[name="psc_dscr"]').empty();
                    jQuery.each(data, function(key, value) {
                        $('select[name="psc_dscr"]').append('<option value="' + value.id + '">' + value.psc_dscr + '</option>');
                    });
                }
            });
        });

        // get bank branch dependent to bank name 
        $('#bnk_name_field_model').on('change', function() {
            var id = this.value;
            $('#bank_id').val(id);
            // ajax call for sub division
            $.ajax({
                type: 'GET',
                // url: '/get-emp-by-ein/' + x,
                url: '/pps/public/ddo-assist/get-bank-name-branch/' + id,
                success: function(data) {
                    // set bank branch 
                    console.log(data);
                    jQuery('select[name="brnm_model"]').empty();
                    jQuery.each(data, function(key, value) {
                        $('select[name="brnm_model"]').append('<option value="' + value.brnm + '">' + value.brnm + '</option>');
                    });
                }
            });
        });

    });
    // ..........................................
    $("select[name='brnm_model']").on('change', function() {
        // var project_id = $(this).value;
        var project_id = $("select[name='brnm']").val();
        // console.log(project_id);
    })
    // Varify & forward table
    // on load if rate is full or reduce
    $(document).ready(function() {
        $(function() {
            if ($("#both_forms_files_radio3").is(":checked")) {
                $('#forms_section1').show();
                $('#files_section1').show();
            }
        })
        $('#formsRadio1').click(function() {
            $('.forms_section1').show();
            $('.files_section1').hide();
        });
        // ......
        $('#filesRadio2').click(function() {
            $('.forms_section1').hide();
            $('.files_section1').show();
        });
        $('#both_forms_files_radio3').click(function() {
            $('.forms_section1').show();
            $('.files_section1').show();
        });
    });


    // e-sign a file
    function myFunction() {
        var x = document.getElementById("tsaurls").value;
        if (x != 0) {
            document.getElementById("tsaURL").value = x;
        } else {
            document.getElementById("tsaURL").value = "";
        }
    }
    $(document).ready(function() {
        $('#w1').hide();

        $('#btnDecryptVerify').hide();
        $('#btnDecryptVerifyWithCrt').hide();

        var initConfig = {
            "preSignCallback": function() {
                // do something 
                // based on the return sign will be invoked

                $('#ndcAthorityIssueModal').modal('hide');
                return true;
            },
            "postSignCallback": function(alias, sign, key) {
                $('#signedPdfData').val(sign);
                $('#lblEncryptedKey').val(key);

                //$('#btnDecryptVerify').show();
                $('#btnDecryptVerifyWithCrt').show();

            },
            signType: 'pdf',
            mode: 'batch',
            certificateData: $('#cert').val()

        };
        dscSigner.configure(initConfig);

        $('#cert').bind('input propertychange', function() {
            var initConfig = {
                "preSignCallback": function() {
                    // do something before signing
                    alert("Pre-sign event fired");
                    return true;
                },
                "postSignCallback": function(alias, sign, key) {
                    //do something after signing

                    $('#signedPdfData').val(sign);
                    $('#lblEncryptedKey').val(key);
                    //$('#btnDecryptVerify').show();
                    $('#btnDecryptVerifyWithCrt').show();

                },
                signType: 'pdf',
                mode: 'batch',
                certificateData: $('#cert').val()
            };
            dscSigner.configure(initConfig);
        });

        $('#signPdf').click(function() {
            batchInitAuto();
            var data = $("#pdfData").val();
            if (data != null || data != '') {
                token = $("#txtBatchToken").val()
                dscSigner.signpdfbatch(data, token);
                $("#txtBatchSize").val(dscSigner.batchsize());

                if (dscSigner.batchsize() == 0) {
                    $("#txtBatchSize").removeAttr("disabled");
                    $("#btnInitBatch").removeAttr("disabled");
                }
            }
            $('#signPdf').hide();
        });

        function batchInitAuto() {
            var batch_size = $("#txtBatchSize").val();
            $("#txtBatchToken").val(dscSigner.initbatch(batch_size));
            $("#txtBatchSize").attr("disabled", "disabled");
            $("#btnInitBatch").attr("disabled", "disabled");
            var c = $("#txtBatchToken").val();
        }

        $('#btnInitBatch').click(function() {
            var batch_size = $("#txtBatchSize").val();
            $("#txtBatchToken").val(dscSigner.initbatch(batch_size));
            $("#txtBatchSize").attr("disabled", "disabled");
            $("#btnInitBatch").attr("disabled", "disabled");
        });

        $('#btnDecryptVerify').click(function() {

            var sign = $('#signedPdfData').val();
            var key = $('#lblEncryptedKey').val();

            // Implement Decrypt Verify here
            var requestData = {
                action: "DECRYPT_VERIFY",
                en_sig: sign,
                ek: key
            };

            $.ajax({
                url: dscapibaseurl + "/pdfsignature",
                type: "post",
                dataType: "json",
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                async: false
            }).done(function(data) {
                if (data.status_cd == 1) {
                    var jsonData = JSON.parse(atob(data.data));
                    $('#decryptedSignature').val(jsonData.sig);
                    $('#decodedSignedXML').val(atob(jsonData.sig));
                    $('#verifiedSignature').val(atob(data.data));
                    $('#verificationResponse').val(atob(data.data));

                    //Set Class to download link
                    $('#downloadDiv').addClass('btn btn-info');
                    //get pdf data
                    var pdfData = jsonData.sig;
                    var dlnk = document.getElementById('downloadDiv');
                    dlnk.href = 'data:application/pdf;base64,' + pdfData;
                    $("#downloadDiv").text("Download Signed PDF File");

                    $('#btnDecryptVerify').hide();
                    $('#btnDecryptVerifyWithCrt').hide();
                } else {
                    alert("Verification Failed");
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            });
        });
        var getIssuesPdfBase64 = "";

        $('#btnDecryptVerifyWithCrt').click(function() {

            $('#verificationResponse').val('');

            var sign = $('#signedPdfData').val();
            var key = $('#lblEncryptedKey').val();

            // Implement Verify here
            var requestData = {
                action: "DECRYPT_VERIFY_WITH_CERT",
                en_sig: sign,
                ek: key,
                certificate: $('#cert').val()
            };
            $.ajax({
                url: dscapibaseurl + "/pdfsignature",
                type: "post",
                dataType: "json",
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                async: false
            }).done(function(data) {
                if (data.status_cd == 1) {
                    var jsonData = JSON.parse(atob(data.data));
                    $('#decryptedSignature').val(jsonData.sig);
                    $('#decodedSignedXML').val(atob(jsonData.sig));
                    $('#verifiedSignature').val(atob(data.data));
                    $('#verificationResponse').val(atob(data.data));

                    //Set Class to download link
                    $('#downloadDiv').addClass('btn btn-info');
                    //get pdf data
                    var pdfData = jsonData.sig;
                    getIssuesPdfBase64 = pdfData;
                    $('#signedPdfData').val(getIssuesPdfBase64);
                    $('#saveBtnBase64issuedpdf').show();
                    $('#btnDecryptVerifyWithCrt').hide();

                    // var dlnk = document.getElementById('downloadDiv');
                    // dlnk.href = 'data:application/pdf;base64,' + pdfData;
                    // $("#downloadDiv").text("Download Signed PDF File");

                    // $('#btnDecryptVerify').hide();
                    //$('#btnDecryptVerifyWithCrt').hide();
                } else {
                    $('#verificationResponse').val(JSON.stringify(data));
                    alert("Verification Failed");
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var data = e.target.result;
                    var base64 = data.replace(/^[^,]*,/, '');
                    $("#pdfData").val(base64);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#pdfFile").change(function() {
            readURL(this);
        });

    });
    // end e-sign a file
    // esign................................
    $(document).ready(function() {

        $('#loadCert').click(function() {
            var serialNo = $('#serialNum').val();
            var cert = $('#cert').val();
            if (serialNo == "" && cert == "") {
                $.blockUI({
                    message: '<h5><img src="resources/images/please-wait-fb.gif" /> Initializing NICDSign.Please Wait...</h5>'
                });
            }
            setTimeout(
                function() {
                    if (serialNo == "" && cert == "") {
                        $(document).ajaxStop($.unblockUI);
                        getDSCDetails();
                    }
                }, 3000);
        });

        function getDSCDetails() {

            dscSigner.certificate(function(res) {
                $('#cname').val(res.certificates[0].subject);
                $('#pan').val(res.certificates[0].pan);
                $('#serialNum').val(res.certificates[0].serialNumber);
                $('#validFrom').val(res.certificates[0].notBefore);
                $('#validTo').val(res.certificates[0].notAfter);
                $('#cert').val(res.certificates[0].certificate);
                $('#sts').val("ACTIVE");
                $('#panel').hide();
            });
        }

        var serialNo = $('#serialNum').val();
        var cert = $('#cert').val();
        if (serialNo == "" && cert == "") {
            $.blockUI({
                message: '<h5><img src="resources/images/please-wait-fb.gif" /> Initializing NICDSign.Please Wait...</h5>'
            });
        }
        setTimeout(
            function() {
                if (serialNo == "" && cert == "") {
                    $(document).ajaxStop($.unblockUI);
                    getDSCDetails();
                }
            }, 3000);
    });
    // end .............................................................

    // service details NQS Calculation
    // not count
    function calculateNQS() {
        var boy_service_days = parseInt($('#boy_service_D').val());
        var boy_service_months = parseInt($('#boy_service_M').val());
        var boy_service_years = parseInt($('#boy_service_Y').val());

        var EoL_not_counting_days = parseInt($('#EoL_not_counting_D').val());
        var EoL_not_counting_months = parseInt($('#EoL_not_counting_M').val());
        var EoL_not_counting_years = parseInt($('#EoL_not_counting_Y').val());


        var Sus_notCount_days = parseInt($('#Sus_notCount_D').val());
        var Sus_notCount_months = parseInt($('#Sus_notCount_M').val());
        var Sus_notCount_years = parseInt($('#Sus_notCount_Y').val());


        var Interruption_serv_days = parseInt($('#Interruption_serv_D').val());
        var Interruption_serv_months = parseInt($('#Interruption_serv_M').val());
        var Interruption_serv_years = parseInt($('#Interruption_serv_Y').val());

        var Period_awaited_days = parseInt($('#Period_awaited_D').val());
        var Period_awaited_months = parseInt($('#Period_awaited_M').val());
        var Period_awaited_years = parseInt($('#Period_awaited_Y').val());

        var Period_not_treat_days = parseInt($('#Period_not_treat_D').val());
        var Period_not_treat_months = parseInt($('#Period_not_treat_M').val());
        var Period_not_treat_years = parseInt($('#Period_not_treat_Y').val());

        // count 
        var milt_serv_days = parseInt($('#milt_serv_D').val());
        var milt_serv_months = parseInt($('#milt_serv_M').val());
        var milt_serv_years = parseInt($('#milt_serv_Y').val());

        var Civil_service_days = parseInt($('#Civil_service_D').val());
        var Civil_service_months = parseInt($('#Civil_service_M').val());
        var Civil_service_years = parseInt($('#Civil_service_Y').val());

        var Benifits_serv_days = parseInt($('#Benifits_serv_D').val());
        var Benifits_serv_months = parseInt($('#Benifits_serv_M').val());
        var Benifits_serv_years = parseInt($('#Benifits_serv_Y').val());

        // gross
        var Gross_days = parseInt($('#grossDays').val());
        var Gross_months = parseInt($('#grossMonths').val());
        var Gross_years = parseInt($('#grossYears').val());




        var CountDays = milt_serv_days + Civil_service_days + Benifits_serv_days;
        var CountMonths = milt_serv_months + Civil_service_months + Benifits_serv_months;
        var CountYears = milt_serv_years + Civil_service_years + Benifits_serv_years;
        var TotalCountDays = parseInt(CountYears * 12) + (CountMonths * 30) + CountDays;

        var NotCountDays = (boy_service_days + EoL_not_counting_days + Sus_notCount_days + Interruption_serv_days + Period_awaited_days + Period_not_treat_days);
        var NotCountMonths = boy_service_months + EoL_not_counting_months + Sus_notCount_months + Interruption_serv_months + Period_awaited_months + Period_not_treat_months;
        var NotCountYears = boy_service_years + EoL_not_counting_years + Sus_notCount_years + Interruption_serv_years + Period_awaited_years + Period_not_treat_years;

        var TotalNotCountDays = parseInt(NotCountYears * 30) + (NotCountMonths * 30) + NotCountDays;
        var TotalGrossDays = parseInt(Gross_years * 30) + (Gross_months * 30) + Gross_days;
        var TotalRemainDays = (TotalGrossDays + TotalCountDays) - TotalNotCountDays;

        // added days months years to gross days months years
        var daysWithCountDays = parseInt(Gross_days + CountDays);
        var monthsWithCountMonth = parseInt(Gross_months + CountMonths);
        var yearsWithCountYear = parseInt(Gross_years + CountYears);

        // subtract not counted days months and years from above value
        var subNotCountDays = parseInt(daysWithCountDays - NotCountDays);
        var subNotCountMonth = parseInt(monthsWithCountMonth - NotCountMonths);
        var subNotCountYears = parseInt(yearsWithCountYear - NotCountYears);

        // days calculation
        if (subNotCountDays >= 365) {
            var year = Math.trunc(subNotCountDays / 365);
            var days = subNotCountDays % 365;
            if (days > 30) {
                var month = Math.trunc(days / 30);
                var daysRemain = Math.trunc(days % 30);
            } else {
                var month = 0;
                var daysRemain = days;
            }
        } else if (subNotCountDays >= 30) {
            var month = Math.trunc(subNotCountDays / 30);
            var daysRemain = Math.trunc(subNotCountDays % 30);
            var year = 0;
        } else {
            var daysRemain = Math.trunc(subNotCountDays);
            var month = 0;
            var year = 0;

        }

        // months calculation
        if (subNotCountMonth > 12) {
            var monthCalyear = Math.trunc(subNotCountMonth / 12);
            var monthCalmonthRemain = Math.trunc(subNotCountMonth % 12);
        } else {
            var monthCalmonthRemain = Math.trunc(subNotCountMonth);
            var monthCalyear = 0;

        }

        // finding NQS
        var NqsDays = daysRemain;
        var NqsMonths = month + monthCalmonthRemain;
        var NqsYear = year + monthCalyear + subNotCountYears;


        // if days months and years goes -ve value then assign 0 value
        if (NqsDays < 0) {
            NqsDays = 0;
        }
        if (NqsMonths < 0) {
            NqsMonths = 0;
        }
        if (NqsYear < 0) {
            NqsYear = 0;
        }
        var currentMonths = NqsMonths;



        // NQS DayS Months Years Calculation
        // var NQSYears = parseInt(TotalRemainDays / 365);
        // var NQSMonths = parseInt((TotalRemainDays - (NQSYears * 365)) / 30); //floor(($daysss - ($years * 365))/30);
        // var NQSDays = parseInt(TotalRemainDays - ((NQSYears * 365) + (NQSMonths * 30))); //floor(($daysss - ($years * 365))/30);


        if (NqsYear >= 33) {
            var NQS_smp_years = 66;
        } else {
            var NQS_smp_years = 2 * parseInt(NqsYear);
            console.log(currentMonths);
            if (currentMonths >= 3 && currentMonths < 9) {
                var NQS_smp_years = NQS_smp_years + 1;
            } else if (currentMonths >= 9) {
                var NQS_smp_years = NQS_smp_years + 2;
            }
        }


        // set Net Qualifying Service days months and years valuemm
        $('#nqs_days').val(NqsDays);
        $('#nqs_months').val(NqsMonths);
        $('#nqs_years').val(NqsYear);
        $('#nqsYears').html(NqsYear);
        if (NqsYear > 33) {
            $('#nqsYearsPermissible').html("(33 years permissible)");
        }

        $('#nqsDays').html(NqsDays);
        $('#nqsMonths').html(NqsMonths);

        $('#NqsSmp').html(NQS_smp_years);
        $('#NqsSmp_input').val(NQS_smp_years);

    }
    calculateNQS();
    $("#boy_service_D").keyup(function() {
        calculateNQS();
    });
    $("#boy_service_M").keyup(function() {
        calculateNQS();
    });
    $("#boy_service_Y").keyup(function() {
        calculateNQS();
    });

    $("#EoL_not_counting_D").keyup(function() {
        calculateNQS();
    });
    $("#EoL_not_counting_M").keyup(function() {
        calculateNQS();
    });
    $("#EoL_not_counting_Y").keyup(function() {
        calculateNQS();
    });

    $("#Sus_notCount_D").keyup(function() {
        calculateNQS();
    });
    $("#Sus_notCount_M").keyup(function() {
        calculateNQS();
    });
    $("#Sus_notCount_Y").keyup(function() {
        calculateNQS();
    });

    $("#Interruption_serv_D").keyup(function() {
        calculateNQS();
    });
    $("#Interruption_serv_M").keyup(function() {
        calculateNQS();
    });
    $("#Interruption_serv_Y").keyup(function() {
        calculateNQS();
    });

    $("#Period_awaited_D").keyup(function() {
        calculateNQS();
    });
    $("#Period_awaited_M").keyup(function() {
        calculateNQS();
    });
    $("#Period_awaited_Y").keyup(function() {
        calculateNQS();
    });

    $("#Period_not_treat_D").keyup(function() {
        calculateNQS();
    });
    $("#Period_not_treat_M").keyup(function() {
        calculateNQS();
    });
    $("#Period_not_treat_Y").keyup(function() {
        calculateNQS();
    });

    $("#milt_serv_D").keyup(function() {
        calculateNQS();
    });
    $("#milt_serv_M").keyup(function() {
        calculateNQS();
    });
    $("#milt_serv_Y").keyup(function() {
        calculateNQS();
    });

    $("#Civil_service_D").keyup(function() {
        calculateNQS();
    });
    $("#Civil_service_M").keyup(function() {
        calculateNQS();
    });
    $("#Civil_service_Y").keyup(function() {
        calculateNQS();
    });

    $("#Benifits_serv_D").keyup(function() {
        calculateNQS();
    });
    $("#Benifits_serv_M").keyup(function() {
        calculateNQS();
    });
    $("#Benifits_serv_Y").keyup(function() {
        calculateNQS();
    });


    // end..............................

    // emolument Average emolument calculation
    $(document).ready(function() {
        // emol input row 1
        var row0Basic = $('#emolBasicPay0').val();
        var row0Npa = $('#emolNpa0').val();
        var row0Amount = parseInt(row0Basic) + parseInt(row0Npa);
        $('#emolAmount0').val(row0Amount);
        $('#emolBasicPay0').keyup(function() {
            row0Basic = $('#emolBasicPay0').val();
            row0Npa = $('#emolNpa0').val();
            row0Amount = parseInt(row0Basic) + parseInt(row0Npa);
            $('#emolAmount0').val(row0Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa0').keyup(function() {
            var row0Basic = $('#emolBasicPay0').val();
            var row0Npa = $('#emolNpa0').val();
            var row0Amount = parseInt(row0Basic) + parseInt(row0Npa);
            $('#emolAmount0').val(row0Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });

        // emol input row 2
        var row1Basic = $('#emolBasicPay1').val();
        var row1Npa = $('#emolNpa1').val();
        var row1Amount = parseInt(row1Basic) + parseInt(row1Npa);
        $('#emolAmount1').val(row1Amount);
        $('#emolBasicPay1').keyup(function() {
            row1Basic = $('#emolBasicPay1').val();
            row1Npa = $('#emolNpa1').val();
            row1Amount = parseInt(row1Basic) + parseInt(row1Npa);
            $('#emolAmount1').val(row1Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa1').keyup(function() {
            row1Basic = $('#emolBasicPay1').val();
            row1Npa = $('#emolNpa1').val();
            row1Amount = parseInt(row1Basic) + parseInt(row1Npa);
            $('#emolAmount1').val(row1Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 3
        var row2Basic = $('#emolBasicPay2').val();
        var row2Npa = $('#emolNpa2').val();
        var row2Amount = parseInt(row2Basic) + parseInt(row2Npa);
        $('#emolAmount2').val(row2Amount);
        $('#emolBasicPay2').keyup(function() {
            row2Basic = $('#emolBasicPay2').val();
            row2Npa = $('#emolNpa2').val();
            row2Amount = parseInt(row2Basic) + parseInt(row2Npa);
            $('#emolAmount2').val(row2Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa2').keyup(function() {
            row2Basic = $('#emolBasicPay2').val();
            row2Npa = $('#emolNpa2').val();
            row2Amount = parseInt(row2Basic) + parseInt(row2Npa);
            $('#emolAmount2').val(row2Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 4
        var row3Basic = $('#emolBasicPay3').val();
        var row3Npa = $('#emolNpa3').val();
        var row3Amount = parseInt(row3Basic) + parseInt(row3Npa);
        $('#emolAmount3').val(row3Amount);
        $('#emolBasicPay3').keyup(function() {
            row3Basic = $('#emolBasicPay3').val();
            row3Npa = $('#emolNpa3').val();
            row3Amount = parseInt(row3Basic) + parseInt(row3Npa);
            $('#emolAmount3').val(row3Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa3').keyup(function() {
            row3Basic = $('#emolBasicPay3').val();
            row3Npa = $('#emolNpa3').val();
            row3Amount = parseInt(row3Basic) + parseInt(row3Npa);
            $('#emolAmount3').val(row3Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 5
        var row4Basic = $('#emolBasicPay4').val();
        var row4Npa = $('#emolNpa4').val();
        var row4Amount = parseInt(row4Basic) + parseInt(row4Npa);
        $('#emolAmount4').val(row4Amount);
        $('#emolBasicPay4').keyup(function() {
            row4Basic = $('#emolBasicPay4').val();
            row4Npa = $('#emolNpa4').val();
            row4Amount = parseInt(row4Basic) + parseInt(row4Npa);
            $('#emolAmount4').val(row4Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa4').keyup(function() {
            row4Basic = $('#emolBasicPay4').val();
            row4Npa = $('#emolNpa4').val();
            row4Amount = parseInt(row4Basic) + parseInt(row4Npa);
            $('#emolAmount4').val(row4Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 6
        var row5Basic = $('#emolBasicPay5').val();
        var row5Npa = $('#emolNpa5').val();
        var row5Amount = parseInt(row5Basic) + parseInt(row5Npa);
        $('#emolAmount5').val(row5Amount);
        $('#emolBasicPay5').keyup(function() {
            row5Basic = $('#emolBasicPay5').val();
            row5Npa = $('#emolNpa5').val();
            row5Amount = parseInt(row5Basic) + parseInt(row5Npa);
            $('#emolAmount5').val(row5Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa5').keyup(function() {
            row5Basic = $('#emolBasicPay5').val();
            row5Npa = $('#emolNpa5').val();
            row5Amount = parseInt(row5Basic) + parseInt(row5Npa);
            $('#emolAmount5').val(row5Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 7
        var row6Basic = $('#emolBasicPay6').val();
        var row6Npa = $('#emolNpa6').val();
        var row6Amount = parseInt(row6Basic) + parseInt(row6Npa);
        $('#emolAmount6').val(row6Amount);
        $('#emolBasicPay6').keyup(function() {
            row6Basic = $('#emolBasicPay6').val();
            row6Npa = $('#emolNpa6').val();
            row6Amount = parseInt(row6Basic) + parseInt(row6Npa);
            $('#emolAmount6').val(row6Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa6').keyup(function() {
            row6Basic = $('#emolBasicPay6').val();
            row6Npa = $('#emolNpa6').val();
            row6Amount = parseInt(row6Basic) + parseInt(row6Npa);
            $('#emolAmount6').val(row6Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 8
        var row7Basic = $('#emolBasicPay7').val();
        var row7Npa = $('#emolNpa7').val();
        var row7Amount = parseInt(row7Basic) + parseInt(row7Npa);
        $('#emolAmount7').val(row7Amount);
        $('#emolBasicPay7').keyup(function() {
            row7Basic = $('#emolBasicPay7').val();
            row7Npa = $('#emolNpa7').val();
            row7Amount = parseInt(row7Basic) + parseInt(row7Npa);
            $('#emolAmount7').val(row7Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa7').keyup(function() {
            row7Basic = $('#emolBasicPay7').val();
            row7Npa = $('#emolNpa7').val();
            row7Amount = parseInt(row7Basic) + parseInt(row7Npa);
            $('#emolAmount7').val(row7Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 9
        var row8Basic = $('#emolBasicPay8').val();
        var row8Npa = $('#emolNpa8').val();
        var row8Amount = parseInt(row8Basic) + parseInt(row8Npa);
        $('#emolAmount8').val(row8Amount);
        $('#emolBasicPay8').keyup(function() {
            row8Basic = $('#emolBasicPay8').val();
            row8Npa = $('#emolNpa8').val();
            row8Amount = parseInt(row8Basic) + parseInt(row8Npa);
            $('#emolAmount8').val(row8Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa8').keyup(function() {
            row8Basic = $('#emolBasicPay8').val();
            row8Npa = $('#emolNpa8').val();
            row8Amount = parseInt(row8Basic) + parseInt(row8Npa);
            $('#emolAmount8').val(row8Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });

        // emol input row 10
        var row9Basic = $('#emolBasicPay9').val();
        var row9Npa = $('#emolNpa9').val();
        var row9Amount = parseInt(row9Basic) + parseInt(row9Npa);
        $('#emolAmount9').val(row9Amount);
        $('#emolBasicPay9').keyup(function() {
            row9Basic = $('#emolBasicPay9').val();
            row9Npa = $('#emolNpa9').val();
            row9Amount = parseInt(row9Basic) + parseInt(row9Npa);
            $('#emolAmount9').val(row9Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);

        });
        $('#emolNpa9').keyup(function() {
            row9Basic = $('#emolBasicPay9').val();
            row9Npa = $('#emolNpa9').val();
            row9Amount = parseInt(row9Basic) + parseInt(row9Npa);
            $('#emolAmount9').val(row9Amount);
            // Average
            var row0Amount = parseInt($('#emolAmount0').val());
            var row1Amount = parseInt($('#emolAmount1').val());
            var row2Amount = parseInt($('#emolAmount2').val());
            var row3Amount = parseInt($('#emolAmount3').val());
            var row4Amount = parseInt($('#emolAmount4').val());
            var row5Amount = parseInt($('#emolAmount5').val());
            var row6Amount = parseInt($('#emolAmount6').val());
            var row7Amount = parseInt($('#emolAmount7').val());
            var row8Amount = parseInt($('#emolAmount8').val());
            var row9Amount = parseInt($('#emolAmount9').val());
            var averageEmol = row0Amount + row1Amount + row2Amount +
                row3Amount + row4Amount +
                row5Amount + row6Amount + row7Amount +
                row8Amount + row9Amount;
            // console.log(row0Amount);

            $('#averageEmol').val(averageEmol / 10);
        });
        // 
        // Average
        var row0Amount = parseInt($('#emolAmount0').val());
        var row1Amount = parseInt($('#emolAmount1').val());
        var row2Amount = parseInt($('#emolAmount2').val());
        var row3Amount = parseInt($('#emolAmount3').val());
        var row4Amount = parseInt($('#emolAmount4').val());
        var row5Amount = parseInt($('#emolAmount5').val());
        var row6Amount = parseInt($('#emolAmount6').val());
        var row7Amount = parseInt($('#emolAmount7').val());
        var row8Amount = parseInt($('#emolAmount8').val());
        var row9Amount = parseInt($('#emolAmount9').val());
        var averageEmol = row0Amount + row1Amount + row2Amount +
            row3Amount + row4Amount +
            row5Amount + row6Amount + row7Amount +
            row8Amount + row9Amount;
        // console.log(row0Amount);

        $('#averageEmol').val(averageEmol / 10);
        // ............................

    });
    // end...................................


    // modal to Issue NDC by NDC Authority.
    $(document).ready(function() {
        // $("#test").click(function() {
        $(document).on('click', '#issueNdcBtn', function() {
            var ein = $(this).val();
            $('#ndcAthorityIssueModal').modal('show');
            $.ajax({
                type: 'GET',
                // url: '/get-emp-by-ein/' + x,
                url: '/pps/public/get-esigned-requirement/' + ein,
                success: function(response) {
                    $('#ein_field').val(response.ein);
                    $('#pdfData').val(response.base64_file);
                    $('#cert').val(response.cert);
                }
            })
        });

    });



    // modal to generate NDC by NDC Assist.
    $(document).ready(function() {
        // $("#test").click(function() {
        $(document).on('click', '#genSingleNdcBtn', function() {
            var ein = $(this).val();
            $('#genSingleNdcModal').modal('show');
            $.ajax({
                type: 'GET',
                url: '/pps/public/get-emp-by-ein/' + ein,
                success: function(response) {
                    $('#einField').val(response.ein);
                }
            })
        });

    });



    // preview signature
    $(document).ready(function(e) {
        $('#signature').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-signature').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
    // preview passport photo
    $(document).ready(function(e) {
        $('#photo').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-photo').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
    // preview join-family
    $(document).ready(function(e) {
        $('#join_family').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-join_family').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
    // preview join-family
    $(document).ready(function(e) {
        $('#finger_print').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-finger_print').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
    // .........................................

    // tooltips 
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    // .............
    var i = 0;
    $("#addRemoveIp").click(function() {
        ++i;
        $("#multiForm").append(
            '<tr>' +
            '<td>' +
            '<input type="text" name="new_data[' + i + '][name]" class="form-control" value="" required/>' +
            '</td>' +
            '<td>' +
            '<input type="date" name="new_data[' + i + '][dob]" class="form-control" value="" required/>' +
            '</td>' +
            '<td>' +
            '<select class="form-select" aria-label="Default select example" name="new_data[' + i + '][gender]" required>' +
            '<option value=""selected>Select </option>' +
            '<option value="M">Male</option>' +
            '<option value="F">Female</option>' +
            '<option value="T">Transgender</option>' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="new_data[' + i + '][relation]" class="form-control" value="" required/>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="new_data[' + i + '][remarks]" class="form-control" value="" required/>' +
            '</td>' +
            '<td>' +
            '<button type="button" class="remove-item btn btn-danger btn-sm">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">' +
            '<path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />' +
            '</svg></button>' +
            '</td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.remove-item', function() {
        $(this).parents('tr').remove();
    });

    $('#applicable').click(function() {
        $('#compulsory_section').show();
        $('#removal_section').hide();
    });
    // service end reason > if compulsory or removal
    $('#servie_end_reason').change(function() {
        if (parseInt($(this).val()) == 8) {
            $('#removal_section').hide();
            $('#compulsory_section').show();
        } else if (parseInt($(this).val()) == 9) {
            $('#removal_section').show();
            $('#compulsory_section').hide();
        } else {
            $('#removal_section').hide();
            $('#compulsory_section').hide();
        }

    });
    // rate change 
    // service end reason > if compulsory or removal
    $('#select_rate_allowed').change(function() {
        if ($(this).val() == "R") {
            $('#full_rate_col').hide();
            $('#reduce_rate_col').show();
        } else {
            $('#reduce_rate_col').hide();
            $('#full_rate_col').show();
        }

    });
    // on load if rate is full or reduce
    $(function() {
        if ($("#is_reduce_selected").is(":selected")) {
            $('#full_rate_col').hide();
            $('#reduce_rate_col').show();
        }
    })

    $('#rem_radio_btn').click(function() {
        $('#removal_section').hide();
        $('#compulsory_section').hide();
    });

    // on change service
    $('#emp_service_type').change(function() {
        var serviceId = $(this).val();
        if (serviceId == 2) {
            $("#foreign_serv_details").show();
        }
        if (serviceId == 1) {
            $("#foreign_serv_details").hide();
        }
    });

    // if foreign service is selected
    $(function() {
        if ($("#service_2").is(":selected")) {
            $("#foreign_serv_details").show();
        }
    })
    // if Government service is selected
    // $(function() {
    //     if ($("#service_1").is(":selected")) {
    //     }
    // })

    // if compulsory on load
    $(function() {
        if ($("#service_reason_option8").is(":selected")) {
            $('#removal_section').hide();
            $('#compulsory_section').show();
        }
    })
    $(function() {
        if ($("#service_reason_option9").is(":selected")) {
            $('#removal_section').show();
            $('#compulsory_section').hide();
        }
    })
    //     // Millitary Service Section
    //     // if applicable
    $('#applicable').click(function() {
        $('#applicable_section').show();
        $('#applicable_section1').show();
        $('#applicable_section2').show();
        $('#applicable_section3').show();
    });
    // if not applicable 
    $('#non_applicable').click(function() {
        $('#applicable_section').hide();
        $('#applicable_section1').hide();
        $('#applicable_section2').hide();
        $('#applicable_section3').hide();
    });

    // load auto
    $(function() {
        if ($("#applicable").is(":checked")) {
            $('#applicable_section').show();
            $('#applicable_section1').show();
            $('#applicable_section2').show();
            $('#applicable_section3').show();
        }
    })

    //     // Service in Autonomous Body
    $('#autono_applicable').click(function() {
        $('#autono_applicable_section').show();
        $('#autono_applicable_section1').show();
        $('#autono_applicable_section2').show();
    });
    $('#non_autono_applicable').click(function() {
        $('#autono_applicable_section').hide();
        $('#autono_applicable_section1').hide();
        $('#autono_applicable_section2').hide();
    });

    // if applicable is selected
    $(function() {
        if ($("#autono_applicable").is(":checked")) {
            $('#autono_applicable_section').show();
            $('#autono_applicable_section1').show();
            $('#autono_applicable_section2').show();
        }
    })
    // if Non applicable is selected
    $(function() {
        if ($("#non_autono_applicable").is(":checked")) {
            $('#autono_applicable_section').hide();
            $('#autono_applicable_section1').hide();
            $('#autono_applicable_section2').hide();
        }
    })



    //autonomous grid table dynamic form
    var j = 0;
    $("#addAutonoFormRowBtn").click(function() {
        ++i;
        $("#autonomous_table_multiForm").append(
            '<tr>' +
            '<td>' +
            '<input type="text" name="new_data[' + i + '][organisation_name]" class="form-control" value=""/>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="new_data[' + i + '][post_held]" class="form-control" value=""/>' +
            '</td>' +
            '<td>' +
            '<input type="date" name="new_data[' + i + '][from_dt]" class="form-control" value=""/>' +
            '</td>' +
            '<td>' +
            '<input type="date" name="new_data[' + i + '][to_dt]" class="form-control" value=""/>' +
            '</td>' +
            '<td>' +
            '<input type="date" name="new_data[' + i + '][entrance_dt]" class="form-control" value=""/>' +
            '</td>' +
            '<td>' +
            '<input class="form-check-input" type="radio" name="new_data[' + i + '][period_count]" id="pension_counted" value="Y">' +
            '<label class="form-check-label" for="new_data[' + i + '][period_count]">Yes</label><br>' +
            '<input class="form-check-input" type="radio" name="new_data[' + i + '][period_count]" id="pension_not_counted" value="N" checked>' +
            '<label class="form-check-label" for="new_data[' + i + '][period_count]">No</label>' +
            '</td>' +
            '<td>' +
            '<input class="form-check-input" type="radio" name="new_data[' + i + '][discharge_pension_liability]" id="liabilities_dicharge" value="Y">' +
            '<label class="form-check-label" for="new_data[' + i + '][discharge_pension_liability]">Yes</label><br>' +
            '<input class="form-check-input" type="radio" name="new_data[' + i + '][discharge_pension_liability]" id="liabilities_not_dicharge" value="N" checked>' +
            '<label class="form-check-label" for="new_data[' + i + '][discharge_pension_liability]" >No</label>' +
            '</td>' +
            '<td>' +
            '<button type="button" class="remove_automousformrow btn btn-danger btn-sm">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">' +
            '<path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />' +
            '</svg></button>' +
            '</td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.remove_automousformrow', function() {
        $(this).parents('tr').remove();
    });

    // Gvt Due detais
    var x = 1;
    var y = 0;

    var getSnCount = $('#govDueOthersSnCount').val();
    if (getSnCount != null) {
        y = getSnCount;
    }
    $("#addDueDeailsBtn").click(function() {

        ++x;
        ++y;

        $("#otherDueFields").append(
            '<tr>' +
            // '<td>' +
            // '<b>'+ y +'</b>'+
            '<input type="hidden" name="other_due_new[' + y + '][sn]" class="form-control" value=""/>' +
            // '</td>' +
            '<td>' +
            '<input type="text" name="other_due_new[' + y + '][due_name]" class="form-control" value=""/>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="other_due_new[' + y + '][due_nature]" class="form-control" />' +
            '</td>' +
            '<td>' +
            '<input type="number" name="other_due_new[' + y + '][due_amt]" class="form-control" value="" />' +
            '</td>' +
            '<td>' +
            '<button type="button" class="remove-item btn btn-danger btn-sm">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">' +
            '<path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />' +
            '</svg></button>' +
            '</td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.remove-item', function() {

        $(this).parents('tr').remove();
        y = y - 1;
    });


    // NDC Assist Modal view for generate and forward NDC
    // var myModal = new bootstrap.Modal(document.getElementById('myModal'), options)
    // var exampleModal = document.getElementById('exampleModal')
    // exampleModal.addEventListener('show.bs.modal', function(event) {
    //     // Button that triggered the modal
    //     var button = event.relatedTarget
    //     // Extract info from data-bs-* attributes
    //     var recipient = button.getAttribute('data-bs-whatever')
    //     // If necessary, you could initiate an AJAX request here
    //     // and then do the updating in a callback.
    //     //
    //     // Update the modal's content.
    //     var modalTitle = exampleModal.querySelector('.modal-title')
    //     var modalBodyInput = exampleModal.querySelector('.modal-body input')

    //     modalTitle.textContent = 'New message to ' + recipient
    //     modalBodyInput.value = recipient
    // })
</script>