@extends('layouts.app')
@section('content')
<!-- Page Content-->

<link href="{{ asset('assets/css/toggle.css') }}" rel="stylesheet">
<!-- <link href="{{ asset('assets/css/welcome.css') }}" rel="stylesheet"> -->

<div class=" container content-section ">

    <div class="row  row_width">

        <div class="col-sm-8">

            <h6 class="newfontcolor verdana change_font"> DIHAS</h6>

            {{-- TODAY --}}


            <div class="newfontcolor fontsize  new_line_height verdana empowering_font"> Empowering <i
                    class="font_family_georgia">Families,</i><br>
                Continuing <i class="font_family_georgia">Legacies.</i>

            </div>
            <br>

            <div class="newfontcolor new_line_height_2 verdana change_font">
                The DIHAS scheme provides compassionate appointments to dependents of deceased government servants under
                Die-in-Harness Scheme, Government of Manipur.
            </div>
            <br>

            <div class="new_background_color">
                <div class="row row_width mb-3">

                    <div class="col">
                        <p class="verdana about_dihas_font about_dihas_font">About DIHAS</p>
                    </div>
                    <div class="col">
                        <p id="myBtn_details" class="details d-flex justify-content-end know_more_font_size change_font"
                            style="color:white; margin-right:40px;   font-family: Arial, Helvetica, sans-serif;"><i>Know
                                more </i></p>

                    </div>

                    <span id="more_details">

                        <p class="white_font_color mx-5 verdana">
                            This web portal is design and develop for submission of application form by the
                            public for getting appointment under Die-in-Harness Scheme, under Government of Manipur.
                            Before
                            submitting
                            application one's should read carefully the required documents/ certificates while
                            submitting the
                            application.
                            Citizen can also submit with an application alongwith all the relevant
                            documents/certificates through
                            the deceased parent Department, where department can upload and enter the details on behalf
                            of the
                            applicant.
                        </p>
                        <h3 class="verdana white_font_color  mx-5">Preference to be given</h3>
                        <li id="change_font1" class="verdana white_font_color  mx-5">Benefit under the Scheme will
                            be
                            available to dependents of
                            those Government
                            servants
                            who died while performing official duties viz. election /census /survey /research / official
                            tour/
                            field
                            inspection etc. or who died in insurgency related violence while performing official duties.
                            These
                            cases
                            shall be categorised as "Preference-I"</li>
                        <li class="verdana white_font_color  mx-5">The next preference will be given to dependents
                            of
                            those Government servants
                            who
                            died
                            not on duty but due to accidents (viz., motor vehicle/ drowning/ fire/ natural calamities
                            etc.) or
                            unnatural death (excluding excessive drinking and suicide cases) leaving his/her family in
                            the state
                            of
                            financial destitution, penury, and without any means of livelihood, subject to availability
                            of
                            vacancy
                            reserved for this scheme. This case shall be categorised as "Preference-II' </li>

                    </span>

                </div>

                <div class="row justify-content-center ">

                    <div class="col-md-5 ">
                        <div class="card two_card mb-4 position-relative  ">
                            <div class="card-body">
                                <h5 class="card-title text-muted  verdana change_font">Objective</h5>

                                <p class="card-text">


                                    <span class="verdana line_height change_font two_card_font">The objective of the
                                        scheme is to
                                        grant appointment on compassionate

                                        <span id="dots">......</span>
                                    </span>

                                    <span id="more" class="verdana two_card_font">
                                        grounds to the next of kin
                                        (a dependent family member)
                                        of a Government servant who
                                        dies in harness leaving his family in penury and without
                                        any means of livelihood. The Scheme is not extendable to
                                        generations and should not be considered as hereditary right. In other words,
                                        thescheme shall not be admissible/extendable to dependent family member of a
                                        Government servant if the Government Servant was appointed under Die-in -harness
                                        Scheme (DIH).
                                    </span>


                                    <!-- Seema -->
                                <p id="myBtn" class="two_card_font change_font">Know more </p>

                                </p>

                            </div>
                            <div class="position-absolute top-0 end-0 m-2 d-flex justify-content-center align-items-center"
                                style="width: 30px; height: 30px; border: 2px solid #000; border-radius: 50%;">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-5">
                        <div class="card two_card mb-3 position-relative ">
                            <div class="card-body">
                                <h5 class="card-title text-muted  mb-2 verdana change_font">Benefits</h5>

                                <p class="card-text">

                                    <span class="verdana line_height change_font two_card_font">
                                        (a) The Scheme shall be applicable to unemployed dependent


                                        <span id="dotsbenefits">......</span>
                                    </span>

                                    <span class="verdana two_card_font" id="morebenefits">
                                        deceased Government
                                        servant in the following order of entitlement:
                                        <br>

                                        (i) legal spouse.
                                        <br>
                                        (ii) elder child (unmarried son/daughter or married son of the deceased
                                        Government Servant if living in the same household).
                                        <br>
                                        (iii) brother/ sister in case of unmaarried Government servant.

                                    </span>


                                    <!-- Seema -->
                                <p id="myBtnbenefits" class="two_card_font change_font" style="color:blue;">
                                    Know more
                                </p>

                                </p>

                            </div>
                            <div class="position-absolute top-0 end-0 m-2 d-flex justify-content-center align-items-center"
                                style="width: 30px; height: 30px; border: 2px solid #000; border-radius: 50%;">
                                <i class="fas fa-smile"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>

        <div class="col-sm-4 ">

            <!-- sidei hougadaba  -->
            <div class="form-container">
                <div class="container">

                    <div class="row background">

                        <div class="col">
                            <h6><b>Login as</b></h6>
                        </div>
                        <div class="col displayflex">
                            <div>
                                <input type="radio" name="login" id="login-btn" checked>
                            </div>
                            <div>
                                <label for="citizen">Citizen</label>
                            </div>

                        </div>

                        <div class="col displayflex">
                            <div>
                                <input type="radio" name="login" id="register-btn">
                            </div>
                            <div>
                                <label for="department">Department</label>
                            </div>

                        </div>

                    </div>
                </div>

                <form id="login-form" class="new_background_color1">
                    <div class="card-body">

                        <div style="text-align:center;font-weight:bold;">
                            <span>Citizen Login Form</span>
                        </div>
                        <br>
                        <form method="POST" action="#" name="login-form" id="login-form">
                            @csrf

                            <input type="hidden" id="login_otp" name="login_otp">

                            <div class="mb-3 row">
                                <!-- naobi -->
                                <div class="col-md-12">
                                    <label for="email" class=" col-form-label text-start">
                                        <i class="fa-solid fa-envelope"></i>
                                        Registered email-id</label>
                                    <input placeholder="example@example.com" type="email"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email') }}" required>
                                    <div class="invalid-feedback" role="alert">
                                    </div>
                                </div>
                            </div>

                            <!-- Department -->
                            <!-- Department -->


                            <!-- naobi -->
                            <div class="mb-3 row">


                                <div class="col-md-12">
                                    <label for="password" class=" col-form-label  text-start">
                                        <i class="fa-solid fa-lock"></i>
                                        {{ __('Password') }}
                                    </label>
                                    <input placeholder="Enter your password" id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    <div class="invalid-feedback" role="alert">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row mb-2">
                                    <div class="col-md-5 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div> -->
                        </form>


                        <div class="row mb-1 mt-3">
                            <!-- naobi -->

                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary w-100 new_background_color"
                                    id="getOTPBtn">Get OTP</button>
                            </div>

                        </div>

                        <div class="row mb-1 login-div2 mt-3" style="display: none;">
                            <!-- naobi (remove offset and replace by col-md-12_ -->
                            <div class="col-md-12" style="display: flex;">
                                <p>Didn't receive an OTP?</p>
                                <p class="link ms-2" type="button" id="resendBtn"
                                    style="border-radius: 50px; color:blue; " disabled>
                                    Resend OTP
                                </p>
                            </div>


                            <div class="row mb-1 text-center">

                                <div id="countdownMessage" style="color: blue;"></div>
                                <div id="otpsendMessage" style="color: blue;"></div>

                            </div>



                            <div class="row-mb-1 text-center">
                                <div class="text-center" id="otp-container" style="display:none">
                                    <input class="input-box" id="Input1" type="text" maxlength="1">
                                    <input class="input-box" id="Input2" type="text" maxlength="1">
                                    <input class="input-box" id="Input3" type="text" maxlength="1">
                                    <input class="input-box" id="Input4" type="text" maxlength="1">
                                    <input class="input-box" id="Input5" type="text" maxlength="1">
                                    <input class="input-box" id="Input6" type="text" maxlength="1">
                                    <input type="hidden" id="combined-input" name="otp">
                                </div>
                            </div>

                            <p>


                            <div class="row mb-3 justify-content-center marinleft">
                                <button type="button" class="btn btn-primary w-100 new_background_color" disabled
                                    id="loginBtn">
                                    Login
                                </button>


                            </div>
                        </div>
                        <br>
                        <div style="text-align:center">
                            <p>
                            <div class="col-sm-12">

                                <a href="{{route('citizen.register')}}">Don't have an account?Sign Up</a>
                                <br>
                                <br>

                                <a href="{{route('password.forgot')}}">Forgot Password?</a>

                            </div>
                        </div>


                    </div>
            </div>
            </form>

            <div id="register-form" class="form-container_department  displaynone background">

                <div class="card-body">
                    <div style="text-align:center;font-weight:bold;">
                        <span>Department Login Form</span>
                    </div>
                    <br>
                    <form method="POST" action="#" name="login-form-dept" id="login-form-dept">
                        @csrf
                        <input type="hidden" id="login_otp_Dept" name="login_otp_Dept">

                        <div class="mb-3 row">

                            <div class="col-md-12">
                                <label for="emailDept" class=" col-form-label  text-start">
                                    <i class="fa-solid fa-envelope"></i>
                                    Registered email-id</label>
                                <input placeholder="example@example.com" type="email"
                                    class="form-control @error('emailDept') is-invalid @enderror" id="emailDept"
                                    name="emailDept" value="{{ old('email') }}" required>
                                <div class="invalid-feedback" role="alert">
                                </div>
                            </div>
                        </div>
                        <p>
                            <!-- Department -->
                            <!-- Department -->

                        <div>
                            <div class="col-md-12">
                                <label for="passwordDept" class="col-md-4 col-form-label  text-start">
                                    <i class="fa-solid fa-lock"></i>
                                    {{ __('Password') }}
                                </label>

                                <input placeholder="Enter your password" id="passwordDept" type="password"
                                    class="form-control @error('passwordDept') is-invalid @enderror" name="passwordDept"
                                    required autocomplete="current-password">

                                <div class="invalid-feedback" role="alert">
                                </div>
                            </div>
                        </div>






                    </form>


                    <div class="row mb-1 mt-3">


                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary w-100 new_background_color"
                                id="getOTPBtnDept">Get OTP</button>
                        </div>

                    </div>

                    <div class="row mb-1 login-div3 mt-3" style="display: none;">
                        <!-- naobi (remove offset and replace by col-md-12_ -->
                        <div class="col-md-12" style="display: flex;">
                            <p>Didn't receive an OTP?</p>
                            <p class="link ms-2" type="button" id="resendBtnDept"
                                style="border-radius: 50px; color:blue; " disabled>
                                Resend OTP
                            </p>
                        </div>


                        <div class="row mb-1 text-center">

                            <div id="countdownMessageDept" style="color: blue;"></div>
                            <div id="otpsendMessageDept" style="color: blue;"></div>

                        </div>



                        <div class="row-mb-2 text-center">
                            <div class="text-center" id="otp-containerDept" style="display:none">
                                <input class="input-box" id="InputDept1" type="text" maxlength="1">
                                <input class="input-box" id="InputDept2" type="text" maxlength="1">
                                <input class="input-box" id="InputDept3" type="text" maxlength="1">
                                <input class="input-box" id="InputDept4" type="text" maxlength="1">
                                <input class="input-box" id="InputDept5" type="text" maxlength="1">
                                <input class="input-box" id="InputDept6" type="text" maxlength="1">
                                <input type="hidden" id="combined-input-Dept" name="otp">
                            </div>
                        </div>

                        <p>


                        <div class="row mb-3 justify-content-center marinleft">

                            <button type="button" class="btn btn-primary w-100 new_background_color" id="loginBtnDept"
                                disabled>
                                Login
                            </button>


                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="row mt-3" style="text-align:center;">
                        <div class="col-sm-12 ">
                            @if (Route::has('password.forgot'))
                            <a href="{{ route('password.forgot') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                            @endif
                        </div>
                    </div>


                    <!-- <div>Registration closes in <span id="time">05:00</span> minutes!</div> -->
                </div>




            </div>



            <!-- sida loire -->








            <!-- sida loire -->

            <!-- second card -->









        </div>



    </div>


    <br>
    <div class=" row row_width">

        <hr>
        <div class="newfontcolor fontsize text-center empowering_font">
            <i class="font_family_georgia">How to Apply</i><br>
        </div>

        <div class="text-center newfontcolor verdana change_font">
            Want to Apply for DIHAS? Follow these 4 simple steps and embark
            <br>
            on your professional journey
        </div>



    </div>
    <br>



    <br>


    <div class="row mb-3 justify-content-center row_width" style="gap:7%">

        <div class="card card_width">
            <div class="card-body">
                <div class="row justify-content-center ">
                    <div class=" circle ">
                        1
                    </div>

                </div>
                <h5><i class="fa-solid fa-bars icon_color1"></i></h5>
                <h6 class="card-subtitle mb-2  card1_color verdana 4_cards_heading">Fill Out General Details</h6>
                <h6 class="card-text verdana change_font">Applicant details information, address, post apply will be entered....
                </h6>

            </div>
        </div>
        <div class="card card_width">
            <div class="card-body">
                <div class="row justify-content-center ">
                    <div class=" circle">
                        2
                    </div>

                </div>
                <h5 class="card-title"> <i class="fas fa-users icon_color2"></i></h5>
                <h6 class="card-subtitle mb-2 card2_color verdana 4_cards_heading"> Details of the family</h6>
                <h6 class="card-text verdana change_font">All family members details excluding applicant will be entered....</h6>

            </div>
        </div>
        <div class="card card_width">
            <div class="card-body">
                <div class="row justify-content-center ">
                    <div class=" circle">
                        3
                    </div>

                </div>
                <h5 class="card-title"> <i class="fas fa-file icon_color3"></i></h5>
                <h6 class="card-subtitle mb-2 card3_color verdana 4_cards_heading">Uploading of Documents</h6>
                <h6 class="card-text verdana change_font">Required documents as per the scheme will be uploaded here....</h6>

            </div>
        </div>
        <div class="card card_width">
            <div class="card-body">
                <div class="row justify-content-center ">
                    <div class=" circle">
                        4
                    </div>

                </div>
                <h5 class="card-title"><i class="fas fa-paper-plane icon_color4"></i></h5>
                <h6 class="card-subtitle mb-2 card4_color verdana 4_cards_heading">Submission of the Application</h6>
                <h6 class="card-text verdana change_font">After filling and uploading all details application can be
                    submitted....</h6>

            </div>
        </div>

    </div>


    <br>


    <!-- Seema -->

    <div class="row row_width">


        <div class=" new_background_color mb-3" style="border-radius:20px;">
            <div class="row g-0">
                <div class="col-md-8">
                    <p class="success_story fontsize empowering_font">Real People <span class="stories">Real Stories</span></p>


                    <p>
                        <?php


                        use App\Models\SuccessStoriesModel;


                        $storiesArray = SuccessStoriesModel::orderBy('status', 'asc')->get();
                        $stories = SuccessStoriesModel::orderBy('status', 'asc')->first();
                        $image = SuccessStoriesModel::orderBy('status', 'asc')->first();

                        // if ($image) {
                        //     // If an image with status 1 is found
                        //     $imagePath = asset('storage/' . str_replace('\\', '/', $image->image)); // Constructing the image path
                        // }

                        ?>
                        @if(count($storiesArray)>0)
                    <p class="bold textcolor apply_font_size empowering_font">Meet <i>{{$stories->name}}</i> </p>



                    <p class="textcolor change_font">
                        {{ (strlen($stories->description)>500)? substr($stories->description, 0, 500): $stories->description}}


                        <span id="dotssuccessStory"></span><span id="moresuccessStory">
                            {{ (strlen($stories->description)>500)? substr($stories->description, 499, 2500): $stories->description}}
                        </span>
                    </p>
                    <p id="myBtnsuccessStory" class="know_more_color change_font">Read more</p>
                    @else
                    <span class="bold textcolor apply_font_size empowering_font">Coming Soon.......</span>
                    @endif



                    </p>
                </div>

                <div class="col-sm-4 text-center">

                    @if($image)
                    <img src="{{ route('image.show', ['filename' => $image->image]) }}" alt="Your Image"
                        class="photo" height="300px" width="200px;">


                    @else
                    <span class="bold textcolor apply_font_size empowering_font">Coming Soon.......</span>
                    @endif

                </div>
            </div>
        </div>


    </div>

    <!-- Seema -->



    <br>




    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <?php

                use App\Models\UploadImageModel;

                $images = UploadImageModel::orderBy('status', 'asc')->take(6)->get();
                ?>
                <h4>&nbsp; &nbsp; &nbsp; <i class="fas fa-images"></i> Photo Gallery</h4>
                @if($images->count() > 0)
                <div class="flex">
                    @foreach($images->take(3) as $image)
                    <a href="{{ route('image.show', ['filename' => $image->image]) }}"> <img class="d-block p-2" src="{{ route('image.show', ['filename' => $image->image]) }}"
                            alt="Your Image" height="100px" width="200px;" data-bs-toggle="modal"
                            data-bs-target="#carouselModal"></a>
                    @endforeach
                </div>
                <div class="flex">
                    @foreach($images->slice(3) as $image)
                    <a href="{{ route('image.show', ['filename' => $image->image]) }}"> <img class="d-block p-2" src="{{ route('image.show', ['filename' => $image->image]) }}"
                            alt="Your Image" height="100px" width="200px;" data-bs-toggle="modal"
                            data-bs-target="#carouselModal"></a>
                    @endforeach
                </div>
                @else
                <span class="bold textcolor apply_font_size empowering_font">Coming Soon.......</span>
                @endif





                <!-- Modal -->
                <div class="modal fade" id="carouselModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-md" style="max-height: 80vh;">
                        <!-- Adjust the max-height value as needed for your desired height -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <!-- <h5 class="modal-title" id="exampleModalLabel">Carousel</h5> -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php

                                        $images = UploadImageModel::orderBy('id', 'desc')->get();
                                        ?>
                                        @foreach($images as $index => $image)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <!-- <img src="{{ asset('storage/' . $image->image) }}" alt="Image {{ $index + 1 }}" class="d-block"
                            height="300px" width="200px;"> -->

                                            <img src="{{ route('image.show', ['filename' => $image->image]) }}"
                                                alt="Your Image" height="300px" width="100%;">

                                        </div>
                                        @endforeach
                                        @if($images->isEmpty())
                                        <div class="carousel-item active">
                                            <span class="bold textcolor apply_font_size empowering_font">No images
                                                available</span>
                                        </div>
                                        @endif
                                    </div>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <!-- <span class="visually-hidden">Previous</span> -->
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <!-- <span class="visually-hidden">Next</span> -->
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
            </div>
            <div class="col-sm-6">
                <div class="text-center">
                    <h4>
                        Notification
                    </h4>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @if(empty($notificationsArray))
                            <tr>
                                <td colspan="1">
                                    <b class="verdana">No Data Found!</b>
                                </td>
                            </tr>

                            @else
                            @foreach($notifications as $row)
                            <tr>
                                <td><a class="change_font"
                                        href="{{route('notification.getdoc',[$row->notification_id])}}" target="_blank">
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


    <br>
    {{-- <div class="row row_width">

        <div class="card text-bg-light mb-3">
            <div class="row g-0">
                <div class="col-md-2">
                    <img src="{{asset('assets/images/faq_Section.svg')}}" class="img-fluid rounded-start imagesizeFAQ"
    alt="FAQ">
</div>
<div class="col-md-10">
    <div class="card-header verdana  new_background_color">
        <h4 class="verdana dihasTitle white_font_color apply_font_size_faq ">Any Question?</h4>
    </div>
    <div class="card-body verdana ">




        <p>


        <p class="verdana change_font">
            <b>
                What is Die-in-Harness scheme?
            </b>
        </p>
        <br>
        <p>
        <h6 class="verdana line_height change_font">Die-in-Harness scheme is to grant
            appointment on
            compassionate
            grounds to the next of kin
            (a dependent family member) of a Government servant who dies in harness leaving his
            family in penury and without any means of livelihood. The Scheme is not extendable to
            generations and should not be considered as hereditary right. In other words, the scheme
            shall not be admissible/extendable to dependent family member of a Government servant if
            the Government Servant was appointed under Die-in -harness Scheme (DIH).
            <span id="dotsfaq">......</span>
        </h6>
        </p>
        <span id="morefaq">
            <p>
                <b class="verdana">To whom the scheme is applicable?</b>
            </p>
            <p>
            <h6 class="verdana line_height">(a) The Scheme shall be applicable to unemployed
                dependent
                family
                member of the
                deceased Government servant in the following order of entitlement:</h6>

            <ul class="verdana line_height">
                <li>legal spouse</li>
                <li>elder child (unmarried son/daughter or married son of the deceased Government
                    Servant if living in the
                    same household) </li>
                <li>brother/ sister in case of unmarried Government servant</li>
            </ul>
            <h6 class="verdana line_height">(b) No Objection Certificate i.e., NOC
                (in the form of Affidavit) from other eligible dependents to be submitted if the
                claimant is other than legal spouse.</h6>
            <h6 class="verdana line_height">(c) Undertaking (in the form of Affidavit) to be
                submitted to
                look after the other dependent family members if appointed
                and liable to be terminated from the post failing to comply the Scheme.</h6>
            <h6 class="verdana line_height">
                (d) The scheme shall be applicable only to the dependent of such deceased Government
                servant who was appointed on regular basis/post and not under DIH Scheme.
            </h6>
            <h6 class="verdana line_height">
                (e) The scheme shall not cover those who work on daily wages, casual, apprentice,
                adhoc, contract, work-charged, muster roll, extension, re-employed basis and
                employees who retire on medical ground.
            </h6>
            <h6 class="verdana line_height">
                (f) The Scheme shall not cover missing/absconding Government servants and death
                caused by suicide or by excessive drinking or on account of natural death.
            </h6>
            <h6 class="verdana line_height">
                (g) The Scheme shall not be applicable to adopted son/ adopted daughter.
            </h6>
            <h6 class="verdana line_height">
                (h) The Government Servant should have put in at least 5 years regular service in
                the Government.
            </h6>

            </p>

        </span>
        </p>

        <!-- Seema -->
        <p id="myBtnfaq" style="color:blue" class="change_font">Know more </p>
        <br><br>






    </div>
</div>
</div>
</div>


</div> --}}

<div class="row mb-3 justify-content-center " style="gap:0.1%">

    <div class="icon_width">
        <a href="https://manipur.gov.in/" target="blank">
            <img src="{{asset('assets/images/manipur.jpeg')}}" alt="emblem" height="90" width="200"
                style="padding-top:10px;">
        </a>
    </div>
    <div class="icon_width">
        <a href="https://treasurymanipur.nic.in/" target="blank">
            <img src="{{asset('assets/images/treasury.jpeg')}}" alt="emblem" height="90" width="200"
                style="padding-top:10px;">
        </a>
    </div>
    <div class=" icon_width">
        <a href="https://cmis.man.nic.in/" target="blank">
            <img src="{{asset('assets/images/cmis.jpeg')}}" alt="emblem" height="100" width="200">
        </a>
    </div>
    <div class=" icon_width">
        <a href="https://www.india.gov.in/" target="blank">
            <img src="{{asset('assets/images/india.jpeg')}}" alt="emblem" height="100" width="200">
        </a>
    </div>
    <div class=" icon_width">
        <a href="https://egrasmanipur.nic.in/" target="blank">
            <img src="{{asset('assets/images/egras.jpeg')}}" alt="emblem" height="100" width="200">
        </a>

    </div>










</div>



<script>
    $(function() {
        $('#homeli').addClass('active');
    });
</script>

<script>
    document.getElementById('login-btn').addEventListener('click', function() {
        document.getElementById('login-form').style.display = 'block';
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('login-btn').classList.add('active');
        document.getElementById('register-btn').classList.remove('active');
    });

    document.getElementById('register-btn').addEventListener('click', function() {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'block';
        document.getElementById('login-btn').classList.remove('active');
        document.getElementById('register-btn').classList.add('active');
    });
</script>




<script nonce="{{ csp_nonce() }}">
    var _token = "{{ csrf_token() }}";
    //citizen
    var login_otp = "{{ route('smsLoginCitizenOTP') }}";
    var login_otp_resend = "{{ route('smsLoginCitizenOTPResend') }}";
    var authenticate_url = "{{ route('authenticate') }}";
    var login = "{{ route('authenticate.citizen') }}";
    // Dept
    var login_otp_Dept = "{{ route('smsLoginOTP') }}";
    var login_otp_resend_Dept = "{{ route('smsLoginOTPResend') }}";
    var login_Dept = "{{ route('authenticate') }}";
    //common
    // var captchaUrl = "{{ route('reloadCaptcha') }}";
    var welcome = "{{route('welcome')}}";
</script>

<script type="text/javascript" src="{{ asset('assets/js/auth/forge-sha256.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/auth/login.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/auth/loginDept.js') }}"></script>
<!-- Seema -->
<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        var btn = document.getElementById("myBtn");
        var dots = document.getElementById("dots");
        var moreText = document.getElementById("more");

        if (btn) {
            btn.addEventListener("click", function() {
                if (dots.style.display === "none") {
                    dots.style.display = "inline";
                    btn.innerHTML = "Know more";
                    moreText.style.display = "none";
                } else {
                    dots.style.display = "none";
                    btn.innerHTML = "Know less";
                    moreText.style.display = "inline";
                }
            });
        }
    });
</script>
<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        var btn = document.getElementById("myBtn_details");

        var moreText = document.getElementById("more_details");

        if (btn) {
            btn.addEventListener("click", function() {
                if (dots.style.display === "none") {
                    dots.style.display = "inline";
                    btn.innerHTML = "Know more";
                    moreText.style.display = "none";
                } else {
                    dots.style.display = "none";
                    btn.innerHTML = "Know less";
                    moreText.style.display = "inline";
                }
            });
        }
    });
</script>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        var btn = document.getElementById("myBtnfaq");
        var dots = document.getElementById("dotsfaq");
        var moreText = document.getElementById("morefaq");

        if (btn) {
            btn.addEventListener("click", function() {
                if (dots.style.display === "none") {
                    dots.style.display = "inline";
                    btn.innerHTML = "Know more";
                    moreText.style.display = "none";
                } else {
                    dots.style.display = "none";
                    btn.innerHTML = "Know less";
                    moreText.style.display = "inline";
                }
            });
        }
    });
</script>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        var btn = document.getElementById("myBtnbenefits");
        var dots = document.getElementById("dotsbenefits");
        var moreText = document.getElementById("morebenefits");

        if (btn) {
            btn.addEventListener("click", function() {
                if (dots.style.display === "none") {
                    dots.style.display = "inline";
                    btn.innerHTML = "Know more";
                    moreText.style.display = "none";
                } else {
                    dots.style.display = "none";
                    btn.innerHTML = "Know less";
                    moreText.style.display = "inline";
                }
            });
        }
    });
</script>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        var btn = document.getElementById("myBtnsuccessStory");
        var dots = document.getElementById("dotssuccessStory");
        var moreText = document.getElementById("moresuccessStory");
        if (btn) {
            btn.addEventListener("click", function() {
                if (dots.style.display === "none") {
                    dots.style.display = "inline";
                    btn.innerHTML = "Know more";
                    moreText.style.display = "none";
                } else {
                    dots.style.display = "none";
                    btn.innerHTML = "Know less";
                    moreText.style.display = "inline";
                }
            });
        }
    });
</script>
<!-- Seema -->
@endsection