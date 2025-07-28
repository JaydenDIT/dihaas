<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/welcome.css" rel="stylesheet">

</head>


<body>

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-sm-12">
                <!-- <div class="card"> -->
                <!-- <div class="card-header">
                    <div class="row">
                        <div class="col-2">
                            {{ __('Admin Dashboard') }}
                        </div>
                    </div>
                </div> -->

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <!-- saved erroe message -->
                    @if(session()->has('error_message'))
                    <div class="alert alert-danger">
                        {{ session()->get('error_message') }}
                    </div>
                    @endif

                    <p>

                </div>
            </div>
        </div>



        <!-- first row -->
        <div class="row">

            <!-- R1C1 -->

            <div class="col-sm-3">

                <div class="card h-100">
                    <div class="card-body background1">
                        <div class="row h-50 background2">
                            <div class="col-sm-2 background3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                    class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2z" />
                                </svg>
                            </div>
                            <div class="col-sm-10 background4">
                                <h5><b>Total Applicants</b></h5>
                            </div>
                        </div>
                        <br><br>
                        <h4 class="background5">
                            <span class="blue">{{$totalApplicants}}</span>
                        </h4>
                    </div>
                </div>
            </div>
            <!-- R1C2 -->

            <div class="col-sm-3">
                <div class="card h-100">
                    <div class="card-body background1">
                        <div class="row h-50 background2">
                            <div class="col-sm-2 backdround3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                    class="bi bi-activity" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z" />
                                </svg>
                            </div>
                            <div class="col-sm-10 background4">
                                <h5><b>Under Process</b></h5>
                            </div>
                        </div>
                        <br><br>
                        <h4 class="background5">
                            <span class="blue">{{$underProcess}}</span>
                        </h4>
                    </div>
                </div>

            </div>

            <!-- R1C3 -->



            <div class="col-sm-3">
                <div class="card h-100">
                    <div class="card-body background1">
                        <div class="row h-50 background2">
                            <div class="col-sm-2 background3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                    class="bi bi-check-all" viewBox="0 0 16 16">
                                    <path
                                        d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992a.252.252 0 0 1 .02-.022zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486-.943 1.179z" />
                                </svg>
                            </div>
                            <div class="col-sm-10 background4">
                                <h5><b>Appointments Given</b></h5>
                            </div>
                        </div>
                        <br><br>
                        <h4 class="background5">
                            <span class="blue">{{$ProCompleted}}</span>
                        </h4>
                    </div>
                </div>

            </div>

            <!-- R1C4 -->


            <div class="col-sm-3">
                <div class="card h-100">
                    <div class="card-header background6 black">
                        Notification
                    </div>
                    <div class="card-body">

                        <ul class="background7">
                            @foreach($notifications as $row)
                            <li>
                                <a href="{{route('notification.getdoc',[$row->notification_id])}}" target="_blank">
                                    {{$row->document_caption}}
                                </a>


                            </li>
                            @endforeach
                        </ul>

                    </div>
                </div>

            </div>




        </div><br><br>

        <!--Second row -->



        <div class="row">

            <!-- R2C1 -->

            <div class="col-sm-4">

                <div class="card h-100">
                    <div class="card-body background1">
                        <div class="row h-50 background2">
                            <div class="col-sm-2 background3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                    class="bi bi-people" viewBox="0 0 16 16">
                                    <path
                                        d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                </svg>
                            </div>
                            <div class="col-sm-10 background4">
                                <h5><b>No. of applicants not yet verified</b></h5>
                            </div>
                        </div>
                        <br><br>
                        <h4 class="background5">
                            <span class="blue">{{$notYetVerified}}</span>
                        </h4>

                    </div>
                </div>
            </div>

            <!-- R2C2 -->

            <div class="col-sm-4">
                <div class="card h-100">
                    <div class="card-body background1">
                        <div class="row h-50 background2">
                            <div class="col-sm-2 background3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                    class="bi bi-trash" viewBox="0 0 16 16">
                                    <path
                                        d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                    <!-- <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" /> -->
                                </svg>
                            </div>

                            <div class="col-sm-10 background4">
                                <h5><b>No. of applicants verification completed</b></h5>
                            </div>
                        </div>
                        <br><br>
                        <h4 class="background5">
                            <span class="blue">{{$verificationCompleted}}</span>
                        </h4>

                    </div>
                </div>
            </div>



            <!-- R2C3 -->

            <div class="col-sm-4">

                <div class="card h-100">
                    <div class="card-body background1">
                        <div class="row h-50 background2">
                            <div class="col-sm-2 background3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                    class="bi bi-activity" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z" />
                                </svg>
                            </div>
                            <div class="col-sm-10 background4">
                                <h5><b>No. of applicants whose forms are incomplete</b></h5>
                            </div>
                        </div>
                        <br><br>
                        <h4 class="background5">
                            <span class="blue">{{$ProInCompleted}}</span>
                        </h4>
                    </div>
                </div>

            </div>



        </div><br><br>
        <!--Third row-->

        <div class="row">

            <div class="col-sm-12 mb-5">
                <div class="card text-bg-light mb-3">
                    <div class="row g-0">

                        <div class="col-sm-12">
                            <div class="card-header font">
                                <div class="col-md-2">
                                    <img src="{{asset('assets/images/faq_Section.svg')}}"
                                        class="img-fluid rounded-start" alt="FAQ">
                                </div>

                                <h4>Frequently Asked Questions(FAQ)</h4>
                            </div>
                            <div class="card-body font">
                                <p class=" card-text"> <b> What is Die-in-Harness scheme?</b></p>
                                <p>Die-in-Harness scheme is to grant appointment on compassionate grounds to the next of
                                    kin (a dependent family member) of a Government servant who dies in harness leaving
                                    his family in penury and without any means of livelihood. The Scheme is not
                                    extendable to generations and should not be considered as hereditary right. In other
                                    words, the scheme shall not be admissible/extendable to dependent family member of a
                                    Government servant if the Government Servant was appointed under Die-in -harness
                                    Scheme (DIH). </p>
                                <p><b>To whom the scheme is applicable?</b></p>
                                <p>The Scheme shall be applicable to unemployed dependent family member of the deceased
                                    Government servant in the following order of entitlement:</p>
                                <p>
                                <ul>
                                    <li>legal spouse</li>
                                    <li>elder child (unmarried son/daughter or married son of the deceased Government
                                        Servant if living in the same household) </li>
                                    <li>brother/ sister in case of unmarried Government servant</li>
                                </ul>
                                The details information can be downloaded from the link...</span>
                                <span class="fontstyle"><a href="officememo.pdf" target="_blank">Download
                                        here</a> </span>
                                </p>



                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>




    </div>


    </div>





</body>
@endsection