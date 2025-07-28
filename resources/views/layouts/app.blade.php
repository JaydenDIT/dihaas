<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">

    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->
    <!--<script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>-->

    <link href="{{ asset('assets/css/googlefont/font.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/bootstrap-5.2.3/css/bootstrap.min.css') }}" rel="stylesheet" media='all'>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">-->
    <!--Sweet alert -->
    <link href="{{ asset('assets/sweetalert2/sweetalert3.css') }}" rel="stylesheet" media='all'>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/kanglasha.png') }}" />

    <script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-5.2.3/js/bootstrap.min.js')  }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('assets/DataTableCompact/datatablecompact.js') }}"></script> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"> -->


    <!-- end esign -->


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <!--<link rel="dns-prefetch" href="//fonts.gstatic.com">-->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

    <!-- Styles -->
    <link href="{{ asset('css/authentication.css')  }}" rel="stylesheet" media='all'>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/layoutapp.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/pdf.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/upload.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/form_progress.css') }}" rel="stylesheet">


    <!-- 
    
   
    
   
   
   
    
    -->



    <!-- dynamic form -->
    <style nonce="{{ csp_nonce() }}">
    body {
        width: 100vw;
        overflow-x: hidden;
    }


    .dropdown:hover>.dropdown-menu {
        display: block;

    }

    .dropdown>.dropdown-toggle:active {

        pointer-events: none;
    }

    /* body {
            font-size: 0.9rem !important;
        } */

    #loading-div {
        position: fixed;
        display: grid;
        place-items: center;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0.7;
        background-color: #fff;
        z-index: 9999;
    }

    #loading-image {
        top: 100px;
        left: 240px;
        z-index: 10000;
    }

    .top-container_first {
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000; 
            transition: top 3s; /* Add smooth transition */
        }

        .top-container_hidden {
            top: -150px; /* Hide the top container by moving it above the viewport */
        }
    </style>
</head>

<body>
    <!-- <div class="row" style="height:50px; background-color:lightgrey;">
        <div class="col-sm-12 d-flex justify-content-center" style="display:flex; margin-top: 10px; ">
           
            <button class="bg-dark text-white" style=" margin-right: 20px; height:30px;" id="skipButton">Skip to main content</button>
            
            <p style=" margin-right: 20px;"> <button type="button" class="bg-dark text-white" id="decreaseFontSizeBtn">-A</button></p>
            <p style=" margin-right: 20px;"> <button type="button" class="bg-dark text-white" id="resetFontSizeBtn">A</button></p>
            <p style=" margin-right: 20px;"> <button type="button" class="bg-dark text-white" id="increaseFontSizeBtn">+A</button></p>
        </div>

    </div> -->


    <div id="loading-div">
        <img id="loading-image" src="{{ asset('assets/img/loader.gif')}}" alt="Loading...">
    </div>
    <!-- below php is for making dynamic header and footer text display -->
    <?php

    use App\Models\PortalModel;

    $getPortalName = PortalModel::where('id', 1)->first();
    //Portal name short form    
    $getProjectShortForm = $getPortalName->short_form_name;
    //Application long name
    $getSoftwareName = $getPortalName->software_name;
    $getDeptName = $getPortalName->department_name;
    $getGovtName = $getPortalName->govt_name;
    $getDeveloper = $getPortalName->developed_by;
    // $getCopyright = $getPortalName->copyright;
    ?>


    <!-- <div id="app" class="bg">  -->
    <!-- <div class="ps"> -->
    <!-- above code make header sticky -->
    <!-- <nav class="navbar navbar-expand-md navbar-light shadow-sm bg_cl"> -->
    @if (Route::has('login') && Auth::user() != null)
    <div class="top-container ps">
        <div class="container">
        <div class="row">
            <div class="col-12 dihas_flex">
                <img src="{{asset('assets/images/kanglasha.png')}}" alt="emblem" height="80" class="my-1">
                <a class="navbar-nav txtnone my-2" href="{{ route('home') }}">
                    <span class="my-2 dihas_style">Die-in-Harness
                        <br>
                        Appoinment System
                    </span>
                </a>

            </div>
        </div>
    </div>
    
    <div class="row">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="myHeader2">
        <!-- <div class="container"> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
            aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse padding_navbar" id="navbarTogglerDemo01">
            @if (Route::has('login') && Auth::user() != null)
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0 ">
                <li class="nav-item active ">
                    <a class="nav-link verdana " href="{{ route('home') }}">Home<span
                            class="sr-only">(current)</span></a>
                </li>

                @if(Auth::user()->role_id == 77 )

                <li class="nav-item">
                    <a class="nav-link verdana" href="{{ route('enterProformaDetails') }}">
                        Submit Application
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link verdana" href="{{ route('viewStatusApplicant') }}">
                        View status
                    </a>
                </li>
               
                @endif
                <!-- For HOD Assistant -->
                @if(Auth::user()->role_id == 1 )

              

                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Proforma Data Entry
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a onclick="resetSessionEIN(event)" class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ url('ddo-assist/enterProformaDetails') }}">New Applicant</a></li>
                        <li><a onclick="resetSessionEINBL(event)" class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('enterBacklogDetails') }}">Backlog Entry</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewchangeApplicant') }}">Change Applicant</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" aria-current="page"
                        href="{{ url('ddo-assist/viewStartEmp') }}">Submitted
                        Application</a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link"
                        href="{{ url('ddo-assist/viewRejectedListHODAssist') }}">Reverted List</a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">File Status</a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link"
                        href="{{ url('ddo-assist/selectDeptByDPApproveDept') }}">Approved List</a>
                </li>

              
                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Vacancy
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ url('ddo-assist/vacancy') }}">Add Vacancy</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ url('ddo-assist/vacancystatus') }}">Status</a></li>
                    </ul>
                </li>
               
             
                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class=" verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Seniority List</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" aria-current="page"
                        href="{{ url('ddo-assist/viewTransferListByHodAssistant') }}">Transfer Applicants</a>
                </li>



                @endif

                <!-- For HOD -->
                @if(Auth::user()->role_id == 2 )
              
                <li class="nav-item ">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewForwardEmp') }}">
                        Forwarded Applicants
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewRejectedListHOD') }}">
                        Reverted List
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">
                        Files Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/selectDeptByDPApproveDept') }}">
                        Approved List
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/vacancystatus') }}">
                        Vacancy Status
                    </a>
                </li>


                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Seniority List</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" aria-current="page"
                        href="{{ url('ddo-assist/viewTransferListByHod') }}">Transfer</a>
                </li>

                @endif

                @if(Auth::user()->role_id == 3 )
               

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewForwardToADAssist') }}">
                        Forwarded Applicants
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewRevertedListADAssist') }}">
                        Reverted List
                    </a>
                </li>
                <li class="nav-item">
                    <a class=" verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">
                        Files Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link verdana_txtnone " href="{{ url('ddo-assist/selectDeptByDPApproveDept') }}">
                        Approved List
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/vacancystatus') }}">
                        Vacancy Status
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class=" verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Seniority List</a></li>
                    </ul>
                </li>

                @endif

                @if(Auth::user()->role_id == 4 )
              
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewForwardToADNodal') }}">
                        Forwarded Applicants
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewRevertedListADNodal') }}">
                        Reverted List
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">
                        Files Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/selectDeptByDPApproveDept') }}">
                        Approved List
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/vacancystatus') }}">
                        Vacancy Status
                    </a>
                </li>


                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Seniority List</a></li>
                    </ul>
                </li>

                @endif

                @if(Auth::user()->role_id == 5 )
               <li class="nav-item">
                    <a class="verdana_txtnone nav-link" aria-current="page"
                        href="{{ url('ddo-assist/viewStartEmp') }}">Submitted
                        Application</a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/selectDeptByDPAssist') }}">
                        Forwarded Applicants
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">
                        Files Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewRevertedListDP') }}">
                        Reverted List
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Upload
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Notification</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.new') }}">Screening Committee Report</a></li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/selectDeptByDPApprove') }}">
                        Approved List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/vacancy_list_dpview') }}">
                        Vacancy Status
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Inter Seniority List</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/transfer_applicant_By_DPAssist') }}">
                        Transfer Applicant
                    </a>
                </li>
              
                @endif

                @if(Auth::user()->role_id == 6 )
               
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/selectDeptByDPNodal') }}">
                        Forwarded Applicants
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">
                        Files Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/selectDeptByDPApprove') }}">
                        Approved List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/vacancy_list_dpview') }}">
                        Vacancy Status
                    </a>
                </li>


                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Seniority List</a></li>
                    </ul>
                </li>

                @endif

                <!-- Signing Authority 1 This role for signing authority from DP-->
                @if(Auth::user()->role_id == 8 )

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('/viewEsignByDeputySecretary') }}">
                        Pending for eSign
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">
                        Files Status
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/vacancy_list_dpview') }}">
                        Vacancy Status
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Inter-Seniority List</a></li>
                    </ul>
                </li>

                @endif

                @if(Auth::user()->role_id == 9 )

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('/viewEsignByDepartmentSigningAuthority') }}">
                        Pending for eSign
                    </a>
                </li>
                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/viewFileStatus') }}">
                        Files Status
                    </a>
                </li>

                <li class="nav-item">
                    <a class="verdana_txtnone nav-link" href="{{ url('ddo-assist/vacancystatus') }}">
                        Vacancy Status
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <!-- Add dropdown class to this list item -->
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Add dropdown menu items here -->
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('screening.view') }}">Screening Committee Report</a></li>
                        <li><a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('viewSeniorityStatus') }}">Inter-Seniority List</a></li>
                    </ul>
                </li>

                @endif

                <!-- end -->
                <!-- Admin -->
                @if(Auth::user()->role_id == 999 )

                 <li class="nav-item dropdown">
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        User Management
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li class="nav-item">
                            <a class=" verdana_txtnone dropdown-item yellow-bg" href="/register_new">
                                Register User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ route('register_user_edit') }}">
                                Edit User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ route('official.officialViewUser') }}">
                                View Users
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        System Configuration
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/applicationnumbers') }}">
                                Application Number Format
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/uonomenclatures') }}">
                                UO Number Format
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/files_name') }}">
                                Files to Upload
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item dropdown">
                    <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Master Data
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                         <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/vpercent') }}">
                                Vacancy Percentage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/eligibilities') }}">
                                Eligibility Age
                            </a>
                        </li>
                        <!-- e-sign list -->
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/departments') }}">
                                Department
                            </a>
                        </li>
                        <!-- e-sign registration  -->



                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/educations') }}">
                                Education
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/states') }}">
                                State
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/districts') }}">
                                District
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/subdivisions') }}">
                                Sub-Division
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/roles') }}">
                                Roles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/remarksapproves') }}">
                                Remarks Approve
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/remarksrejects') }}">
                                Remarks Reject
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/dpauthorities') }}">
                                DP Signing Authority
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/deptauthorities') }}">
                                Department Signing Authority
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/successstories') }}">
                                Upload Success Story
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="verdana_txtnone dropdown-item yellow-bg" href="{{ url('/uploadimages') }}">
                                Upload Photo
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- </div> -->
                @endif
            </ul>
            @endif
        </div>
      
        <!-- Login -->
        <div class="d-flex">
            <ul class="navbar-nav d-flex me-auto mb-2 mb-lg-0">
                @guest
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                     
                    </a>                 
                    @if (Route::has('login'))

                    @endif

                    @else

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fontncolor fa-solid fa-user "></i><b class="white">{{ Auth::user()->name }}</b>
                    </a>
                    <ul class="dropdown-menu   dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('setting.profile') }}">
                                <i class="font20 fa-solid fa-lock"></i><span class="color"> Change Password</span>
                            </a>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="font20 fa-solid fa-user"></i><span class="color">{{ __('Logout') }}</span>
                            </a>
                            @csrf
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                </li>
                @endguest
            </ul>
        </div>
    </nav>
      </div>
    </div>
    @endif
    @if (request()->is('sitemap', 'dihas_overview', 'contact_us'))


    <div style="background-color:#0f523e;">
        <div class="container d-flex justify-content-end py-2">
            <a href="/" style="text-decoration:none; font-size:13px;" class="text-white">Back to home</a>
        </div>
    </div>

    <!-- Second row -->
    <div class="sitemap_background">
        <div class="container">
            <div class="row">
                <div class="col-12 dihas_flex">
                    <img src="{{ asset('assets/images/kanglasha.png') }}" alt="emblem" class="my-1 kanglasha_logo">
                    <a class="navbar-nav txtnone" href="{{ url('/') }}">
                        <span class="my-2 dihas_style">Die-in-Harness
                            <br> Appoinment System
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif


@if (request()->url() == url('citizen/register') && Auth::guest())
    <div class="top-container_first ps">    

<div style="background-color:#0f523e;">
        <div class="container d-flex justify-content-end  py-2">

            <a href="/" style="text-decoration:none;  font-size:13px;" class="text-white">Back to home</a>
        </div>
    </div>

    <div class="row" style="height: 50px; background-color: #383838;">
        <div class="col-sm-12 d-flex justify-content-center" style="display: flex; margin-top: 10px;">

            <p class=" text-white"
                style="margin-right: 20px;  background-color: #24715c; height: 30px; background-color: #383838;"
                id="skipButton">Skip to main content</p>
            <p
                style=" margin-top: 4px; margin-right: 10px;border-left: 2px solid grey; height: 15px; background-color: #383838;">
            </p>

            <p style="margin-right: 20px;  background-color: #383838;" class=" text-white "
                id="decreaseFontSizeBtn">-A</p>
            <p style="margin-right: 20px;  background-color: #383838;" class=" text-white" id="resetFontSizeBtn">A
            </p>
            <p style="margin-right: 20px;  background-color: #383838;" class=" text-white" id="increaseFontSizeBtn">
                +A</p>
            <p
                style=" margin-top: 4px; margin-right: 10px;border-left: 2px solid grey; height: 15px; background-color: #383838;">
            </p>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 dihas_flex">
                <img src="{{asset('assets/images/kanglasha.png')}}" alt="emblem" class="my-1 kanglasha_logo">
                <a class="navbar-nav txtnone" href="{{ url('/') }}">
                    <span class="my-2 dihas_style">Die-in-Harness
                        <br>
                        Appoinment System
                    </span>
                </a>

                <a href="/dihas_overview" class="dihas_overview_padding text_dihas_overview">
                    <span>Overview</span>
                </a>

                <a href="/sitemap" class="dihas_contact_us text_contact_us">
                    <span>Sitemap</span>
                </a>
                <a href="/sitemap" class="dihas_contact_us text_contact_us">
                    <span>Manual</span>
                </a>
                <a href="/contact_us" class="dihas_contact_us text_contact_us">
                    <span>Contact Us </span>
                </a>

            </div>
        </div>
    </div>
</div>
    @endif
  
    @if (Auth::user() == null && request()->url() != url('citizen/register') && !request()->is('sitemap') && !request()->is('dihas_overview') && !request()->is('contact_us'))
    <div class="top-container_first ps">
        <div class="row" style="height: 50px; background-color: #383838;">
            <div class="col-sm-12 d-flex justify-content-center" style="display: flex; margin-top: 10px;">

            <p  class=" text-white" style="margin-right: 20px;  background-color: #24715c; height: 30px; background-color: #383838;" id="skipButton">Skip to main content</p>
          
            <p style=" margin-top: 4px; margin-right: 10px;border-left: 2px solid grey; height: 15px; background-color: #383838;"></p>

           <p style="margin-right: 20px;  background-color: #383838;"class=" text-white " id="decreaseFontSizeBtn">-A</p>
            <p style="margin-right: 20px;  background-color: #383838;"  class=" text-white" id="resetFontSizeBtn">A</p>
            <p  style="margin-right: 20px;  background-color: #383838;"  class=" text-white" id="increaseFontSizeBtn">+A</p>
            <p style=" margin-top: 4px; margin-right: 10px;border-left: 2px solid grey; height: 15px; background-color: #383838;"></p>

            </div>
        </div>
       
        <div class="container">
            <div class="row">
                <div class="col-12 dihas_flex">
                    <img src="{{asset('assets/images/kanglasha.png')}}" alt="emblem" class="my-1 kanglasha_logo">
                    <a class="navbar-nav txtnone" href="{{ url('/') }}">
                        <span class="my-2 dihas_style">Die-in-Harness
                            <br>
                            Appoinment System
                        </span>
                    </a>
                    <a href="/dihas_overview" class="dihas_overview_padding text_dihas_overview">
                        <span>Overview</span>
                    </a>

                    <a href="/sitemap" class="dihas_contact_us text_contact_us">
                        <span>Sitemap</span>
                    </a>
                    <div class="nav-item dropdown manual_design">
                        <!-- Add dropdown class to this list item -->
                        <a class="verdana_txtnone nav-link dropdown" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="color:black;">
                         Manual
                        </a>

                        <!-- Pdfs are stored inside dihaas\public\assets\files-->
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <!-- Add dropdown menu items here -->
                            <li><a  class="verdana_txtnone dropdown-item yellow-bg"
                                    href="{{asset('assets/files/citizen.pdf')}}" target="blank">Citizen User</a></li>
                            <li><a  class="verdana_txtnone dropdown-item yellow-bg"
                                    href="{{asset('assets/files/department.pdf')}}" target="blank">Department User</a></li>
                            <li><a class="verdana_txtnone dropdown-item yellow-bg"
                            href="{{asset('assets/files/superadmin.pdf')}}" target="blank">Superadmin User</a></li>
                        </ul>
                    </div>
                    <a href="/contact_us" class="dihas_contact_us text_contact_us">
                        <span>Contact Us </span>
                    </a>
                </div>            
                  </div> 
            </div>
        </div>    
    @endif

    <div id="content">
        <div class="container">
            @if(session()->has('submit_error'))

            <div class="alert alert-danger">
                <p>Please check and save Forms :</p>
                <ul>
                    @foreach(session()->get('submit_error') as $error_msg)
                    <li>{{ $error_msg['form_name']}} ( Form-{{$error_msg['id']}})</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <br>
        <br>
        @yield('content')
    </div>

     @include('footer')
    <script>
    $(document).ready(function() {
        $("#loading-div").hide();
        $('.navbar-toggler').on('click', function() {
            let el = $('#navbarTogglerDemo01');
            if (el.hasClass('show'))
                el.removeClass('show');
        });
    });

    window.onscroll = function() {
        myHeaderFunction();
    };

    var header = document.getElementById("myHeader");
    var sticky;
    if(header){
        sticky = header.offsetTop;
    }

   
    function myHeaderFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }


    function resetSessionEIN(event) {
        //alert(9)
        event.preventDefault();
        $.ajax({
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/ddo-assist/removeSessionEIN", // Update the route to match your Laravel route
            success: function(response) {
                window.location.href = '/ddo-assist/enterProformaDetails';
                // You can handle the response here if needed
            }
        });
    }

    function resetSessionEINBL(event) {
        //alert(9)
        event.preventDefault();
        $.ajax({
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/ddo-assist/removeSessionEIN", // Update the route to match your Laravel route
            success: function(response) {
                window.location.href = '/ddo-assist/enterBacklogDetails';
                // You can handle the response here if needed
            }
        });
    }


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
            '<select class="form-select" aria-label="Default select example" name="new_data[' + i +
            '][gender]" required>' +
            '<option value=""selected>Select </option>' +
            '<option value="M">Male</option>' +
            '<option value="F">Female</option>' +
            // '<option value="T">Transgender</option>' +
            '</select>' +
            '</td>' +
            '<td>' +
            // '<input type="text" name="new_data[' + i + '][relation]" class="form-control" value="" required/>' +
            '<select class="form-select" aria-label="Default select example" name="new_data[' + i +
            '][relation]" required>' +
            '<option value=""selected>Select </option>' +
            '<option value="1">Wife</option>' +
            '<option value="2">Husband</option>' +
            '<option value="3">Son</option>' +
            '<option value="4">Daughter</option>' +

            '</select>' +
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

   
    function myHeaderFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }


    function ajax_send_multipart(url, param, my_function, json = false) {
        var contentType = false;
        if (json) {
            var dataType = 'json';
            contentType = "application/json; charset=UTF-8";
        }

        $.ajax({
            async: true,
            url: url,
            method: "POST",
            data: param,
            processData: false,
            contentType: contentType,
            enctype: 'multipart/form-data',
            beforeSend: function() {
                $("#loading-div").show();
            },
            success: function(datalist) {
                $("#loading-div").hide();
                if (typeof my_function == 'function') {
                    my_function(datalist);
                }
            },
            error: function(jqXHR, exception, errorThrown) {
                result = {
                    status: -1,
                    msg: JSON.parse(jqXHR.responseText)
                };
                //console.log(JSON.parse(jqXHR.responseText));
                $("#loading-div").hide();
                if (typeof my_function == 'function') {
                    my_function({
                        status: -1,
                        msg: JSON.parse(jqXHR.responseText)
                    });
                }
            }
        });

    }
    </script>
    <script src="{{ asset('assets/js/input-validation.js') }}"></script>

    <script>
        // Set original font sizes
        let originalH2FontSize = 30;
        let originalH6FontSize = 15;
        let originalEmpoweringFontSize = 30;
        let originalAboutDihasFontSize = 20;
        let original4CardsHeadingFontSize = 20;
    
    
    
    
        // Set current font sizes
        let currentH2FontSize = originalH2FontSize;
        let currentH6FontSize = originalH6FontSize;
        let currentEmpoweringFontSize = originalEmpoweringFontSize;
        let currentAboutDihasFontSize = originalAboutDihasFontSize;
        let current4CardsHeadingFontSize = original4CardsHeadingFontSize;
    
    
    
    
        // Function to change font size
        function changeFontSize(change) {
            if (change === 0) {
                // Reset font size to original sizes
                currentH2FontSize = originalH2FontSize;
                currentH6FontSize = originalH6FontSize;
                currentEmpoweringFontSize = originalEmpoweringFontSize;
                currentAboutDihasFontSize = originalAboutDihasFontSize;
                current4CardsHeadingFontSize = original4CardsHeadingFontSize;
    
    
            } else {
                // Adjust the font sizes by the change value
                currentH2FontSize += change;
                currentH6FontSize += change;
                currentEmpoweringFontSize += change;
                currentAboutDihasFontSize += change;
                current4CardsHeadingFontSize += change;
            }
    
            var elements = document.getElementsByClassName('dihasTitle');
            for (var i = 0; i < elements.length; i++) {
                // Change font size to the new size
                elements[i].style.fontSize = currentH2FontSize + 'px';
            }
    
            var elements = document.getElementsByClassName('change_font');
            for (var i = 0; i < elements.length; i++) {
                // Change font size to the new size
                elements[i].style.fontSize = currentH6FontSize + 'px';
            }
    
    
            var elements = document.getElementsByClassName('empowering_font');
            for (var i = 0; i < elements.length; i++) {
                // Change font size to the new size
                elements[i].style.fontSize = currentEmpoweringFontSize + 'px';
            }
    
            var elements = document.getElementsByClassName('about_dihas_font');
            for (var i = 0; i < elements.length; i++) {
                // Change font size to the new size
                elements[i].style.fontSize = currentAboutDihasFontSize + 'px';
            }
    
            var elements = document.getElementsByClassName('4_cards_heading');
            for (var i = 0; i < elements.length; i++) {
                // Change font size to the new size
                elements[i].style.fontSize = current4CardsHeadingFontSize + 'px';
            }
    
        }
    
        const decreaseFontSizeBtn = document.getElementById('decreaseFontSizeBtn');
        // Event listeners for buttons
        if(decreaseFontSizeBtn)
        {
                decreaseFontSizeBtn.addEventListener('click', function() {
                changeFontSize(-1);
            });
        }

        const resetFontSizeBtn = document.getElementById('resetFontSizeBtn');
        if(resetFontSizeBtn)
        {
            resetFontSizeBtn.addEventListener('click', function() {
                changeFontSize(0);
            });
        }
    
        const increaseFontSizeBtn = document.getElementById('increaseFontSizeBtn');
        if(increaseFontSizeBtn)
        {
            increaseFontSizeBtn.addEventListener('click', function() {
                changeFontSize(1);
            });
        }
        </script>

        <!-- JavaScript to toggle visibility of the skip section -->
 <!-- JavaScript to toggle visibility of the skip section -->
 <!-- <script>
        document.getElementById('skipButton').addEventListener('click', function() {
            var skipSection = document.querySelector('.skip');
            if (skipSection.style.display === 'none') {
                skipSection.style.display = 'block';
            } else {
                skipSection.style.display = 'none';
            }
        });
    </script> -->

  <!-- JavaScript to scroll to top when skip button is clicked -->
  <script>
    // Function to scroll to top when skip button is clicked
    const skipButton = document.getElementById('skipButton');
    if(skipButton)
        {skipButton.addEventListener('click', function() {
            var targetSection = document.querySelector('.content-section');
            
            // Scroll to the top offset of the target section
            window.scrollTo({
                top: targetSection.offsetTop,
                behavior: 'smooth'
            });
        });
    }

    // Function to toggle the visibility of the top container on scroll
    var lastScrollTop = 0;
    window.addEventListener("scroll", function() {
        var currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        if (currentScroll > lastScrollTop) {
            // Scrolling down, hide the top container
            if(document.querySelector('.top-container_first')) 
            document.querySelector('.top-container_first').classList.add('top-container_hidden');
            var contentSection = document.querySelector('.content-section');
         contentSection.style.marginTop = '200px'; 
        } 
        else{
            if(document.querySelector('.top-container_first')) 
            document.querySelector('.top-container_first').classList.remove('top-container_hidden'); 
            var contentSection = document.querySelector('.content-section');
         contentSection.style.marginTop = '130px'; // Adjust margin as needed
        }
        lastScrollTop = currentScroll;
    });

    // Add margin to the content-section on page load
    window.onload = function() {
        var contentSection = document.querySelector('.content-section');
        //if(contentSection){
            contentSection.style.marginTop = '130px'; // Adjust margin as needed
        //}
    };
</script>

</body>

</html>