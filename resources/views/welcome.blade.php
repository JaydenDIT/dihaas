@extends('layouts.app')
@push('css')
    <link href="{{ asset('assets/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
@endpush
@section('content')
    {{-- Google Font 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> --}}

    {{-- AOS Animation CSS
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    --}}

    <div class="container py-5">
        <div class="top-container ps">
            <div class="row g-4">
                {{-- About Section --}}
                <div class="col-lg-8" data-aos="fade-right">
                    {{-- Header --}}
                    <div class="mb-5 text-center" data-aos="fade-down">
                        <div>
                            <img src="{{ asset('assets/images/kanglasha.png') }}" alt="emblem" height="80"
                                class="my-1">
                            <h3>
                                <a class="navbar-nav txtnone my-2" href="{{ route('home') }}">
                                    <span class="my-2 dihas_style">Die-in-Harness Appoinment System
                                    </span>
                                </a>
                            </h3>
                            <h4 class="section-heading">(DIHAS)</h3>
                        </div>
                        <p class="lead-text">
                            Empowering <i>Families</i>, Continuing <i>Legacies</i>.
                        </p>
                    </div>
                    <p class="lead-text text-muted">
                        The DIHAS scheme provides compassionate appointments to dependents of deceased government servants
                        under Die-in-Harness Scheme, Government of Manipur.
                    </p>
                    <div class="gradient-section shadow-sm mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">About DIHAS</h5>
                            <a class="fw-bold text-primary" data-bs-toggle="collapse" href="#aboutMore" role="button">
                                <i>Know more</i>
                            </a>
                        </div>
                        <div class="collapse" id="aboutMore">
                            <p>
                                This web portal is designed for submission of application form by the public for
                                getting appointment under Die-in-Harness Scheme. Before submitting, one should read
                                carefully the required documents/certificates. Citizens can also submit through the
                                deceased parent department, which may upload and enter details on behalf of the applicant.
                            </p>
                            <h6 class="mt-4">Preference to be given</h6>
                            <ul>
                                <li>Government servants who died while on official duty or in insurgency-related violence
                                    ("Preference-I").</li>
                                <li>Government servants who died off-duty due to accidents/natural causes, excluding suicide
                                    or
                                    excessive drinking ("Preference-II").</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Objective & Benefits --}}
                    <div class="row g-4">
                        <div class="col-md-6" data-aos="fade-up">
                            <div class="card card-hover shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-warning text-dark d-flex justify-content-center align-items-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-lightbulb"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Objective</h5>
                                    </div>
                                    <p class="lead-text">
                                        To grant appointment on compassionate grounds...
                                        <a class="fw-bold" data-bs-toggle="collapse" href="#objectiveMore">Read more</a>
                                    </p>
                                    <div class="collapse" id="objectiveMore">
                                        <p class="lead-text">
                                            ...to the next of kin of a Government servant who dies in harness, leaving
                                            the family in penury. The scheme is not hereditary.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="card card-hover shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-info text-white d-flex justify-content-center align-items-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-smile"></i>
                                        </div>
                                        <h5 class="ms-3 mb-0">Benefits</h5>
                                    </div>
                                    <p class="lead-text">
                                        Applicable to unemployed dependents...
                                        <a class="fw-bold" data-bs-toggle="collapse" href="#benefitsMore">Read more</a>
                                    </p>
                                    <div class="collapse" id="benefitsMore">
                                        <ul>
                                            <li>Legal spouse</li>
                                            <li>Elder child living in same household</li>
                                            <li>Brother/sister in case of unmarried Government servant</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Login Section --}}
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="card card-hover shadow-sm border-0 mt-4">
                        <div class="card-body">
                            <h5 class="text-center text-primary mb-4">Login</h5>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    @if (Route::has('password.request'))
                                        <a class="small text-decoration-underline" href="{{ route('password.request') }}">
                                            {{ __('Forgot password?') }}
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Log in') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            {{-- How to Apply --}}
            <div class="text-center my-5" data-aos="zoom-in">
                <h4 class="section-heading"><i>How to Apply</i></h4>
                <p class="lead-text">Follow these 4 steps to apply for DIHAS.</p>
            </div>
            <div class="row text-center g-4">
                @foreach ([['1', 'Fill Out General Details', 'Applicant details, address, post applied.'], ['2', 'Details of Family', 'Info about family members excluding applicant.'], ['3', 'Upload Documents', 'Required documents as per the scheme.'], ['4', 'Submit Application', 'After filling and uploading all details.']] as $step)
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="circle mx-auto mb-3">{{ $step[0] }}</div>
                        <h6 class="fw-bold text-primary">{{ $step[1] }}</h6>
                        <p class="small text-muted">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Partner Logos --}}
            <div class="d-flex justify-content-center flex-wrap gap-4 py-5 border-top mt-5" data-aos="fade-up">
                @foreach ([['manipur.jpeg', 'https://manipur.gov.in/'], ['treasury.jpeg', 'https://treasurymanipur.nic.in/'], ['cmis.jpeg', 'https://cmis.man.nic.in/'], ['india.jpeg', 'https://www.india.gov.in/'], ['egras.jpeg', 'https://egrasmanipur.nic.in/']] as $logo)
                    <a href="{{ $logo[1] }}" target="_blank">
                        <img src="{{ asset('assets/images/' . $logo[0]) }}" class="partner-logo">
                    </a>
                @endforeach
            </div>

        </div>
    @endsection
    @push('js')
        {{-- AOS Animation JS 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script> --}}
        <script src="{{ asset('assets/aos/aos.js') }}"></script>
        <script>
            AOS.init({
                duration: 800,
                once: true
            });
        </script>
    @endpush
