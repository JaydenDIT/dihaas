<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')


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

    @if($getUser1->role_id == 77)
    <div class="row">

        <!-- R1C1 -->

        <div class="col-sm-7">

            <!-- <div class="card h-100"> -->
            <div class="card-body background1 round h-100">
                <div class="row h-40 background2 ">
                    <div class="col-sm-2 background3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                            class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16">
                            <path
                                d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2z" />
                        </svg>
                    </div>
                    <div class="col-sm-10 background4">
                        <h5><b>status of Application</b></h5>
                    </div>
                </div>
                <br><br>
                <h4 class="background5">
                    <span class="blue">{{$status}}</span>
                </h4>
            </div>
            <!-- </div> -->
        </div>
            

       
        <div class="col-sm-4">
            <div class="card h-100">
                <div class="card-header background6 black">
                    Notification
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @if(empty($notificationsArray))
                                <tr>
                                    <td colspan="1">
                                        <b>No Data Found!</b>
                                    </td>
                                </tr>

                                @else
                                @foreach($notifications as $row)
                                <tr>
                                    <td><a href="{{route('notification.getdoc',[$row->notification_id])}}"
                                            target="_blank">
                                            {{$row->document_caption}}
                                        </a></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($notifications != null)
                    <div class="row">
                        {!! $notifications->links() !!}
                    </div>
                    @endif

                </div>
            </div>

        </div>




    </div><br><br>

    
    @else

    <!-- first row -->
    <div class="row">

        <!-- R1C1 -->

        <div class="col-sm-3">

            <!-- <div class="card h-100"> -->
            <div class="card-body background1 round h-100">
                <div class="row h-50 background2 ">
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
            <!-- </div> -->
        </div>
        <!-- R1C2 -->

        <div class="col-sm-3">
            <!-- <div class="card h-100"> -->
            <div class="card-body background1 round h-100">
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
            <!-- </div> -->

        </div>

        <!-- R1C3 -->



        <div class="col-sm-3">
            <!-- <div class="card h-100"> -->
            <div class="card-body background1 round h-100">
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
            <!-- </div> -->

        </div>

        <!-- R1C4 -->


        <div class="col-sm-3">
            <div class="card h-100">
                <div class="card-header background6 black">
                    Notification
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @if(empty($notificationsArray))
                                <tr>
                                    <td colspan="1">
                                        <b>No Data Found!</b>
                                    </td>
                                </tr>

                                @else
                                @foreach($notifications as $row)
                                <tr>
                                    <td><a href="{{route('notification.getdoc',[$row->notification_id])}}"
                                            target="_blank">
                                            {{$row->document_caption}}
                                        </a></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if($notifications != null)
                    <div class="row">
                        {!! $notifications->links() !!}
                    </div>
                    @endif

                </div>
            </div>

        </div>




    </div><br><br>

    <!--Second row -->



    <div class="row">

        <!-- R2C1 -->

        <div class="col-sm-4">

            <!-- <div class="card h-100"> -->
            <div class="card-body background1 round">
                <div class="row h-50 background2">
                    <div class="col-sm-2 background3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-people"
                            viewBox="0 0 16 16">
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
            <!-- </div> -->
        </div>

        <!-- R2C2 -->

        <div class="col-sm-4">
            <!-- <div class="card h-100"> -->
            <div class="card-body background1 round">
                <div class="row h-50 background2">
                    <div class="col-sm-2 background3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-trash"
                            viewBox="0 0 16 16">
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
            <!-- </div> -->
        </div>



        <!-- R2C3 -->

        <div class="col-sm-4">

            <!-- <div class="card h-100"> -->
            <div class="card-body background1 round">
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
            <!-- </div> -->

        </div>



    </div>

    @endif

</div>
@endsection