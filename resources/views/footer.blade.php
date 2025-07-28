{{-- <style nonce="{{ csp_nonce() }}">
    .footer {
        background-color: #66ccff;
    
        bottom: 1;
    
        color: black;
    
    
        padding-top: 20px;
    
    
    }
    </style>
    
    
    <div class="row footer ">
    
    
        <div class="d-flex justify-content-center">
            <div class=" mx-3">
                <img src="{{asset('assets/images/nic.jpeg')}}" alt="emblem" height="60" width="60">
            </div>
            <div style="line-height: 1.5;" class="footer_font_size">
                Website Content Managed by {{$getDeptName}}, GoM
                <br>
                {{$getDeveloper}} Manipur
                <br>
                Last Updated: 05 Mar 2024
            </div>
    
    
        </div>
    </div> --}}

    <div class="row footer_background fontcolour">
        <!-- first -->
        <div class="col-sm-1">
    
        </div>
        <div class="col-sm-4 pt-3">
    
            <div class="d-flex justify-content-center align-items-center">
                <div style="padding-right:10px;">
                    <img src="{{ asset('assets/images/nic.jpeg') }}" alt="emblem" height="60" width="60">
                </div>
                <div class="line_height footer_font_size d-flex justify-content-center align-items-center">
                    Website Content Managed by {{$getDeptName}}, GoM
                    <br>
                    {{$getDeveloper}} Manipur
                    <br>
                    @php
                    $latestUserDate = \App\Models\User::latest('created_at')->value('created_at');
                    @endphp
                    @if($latestUserDate)
                    Last Updated: {{ $latestUserDate->format('Y-m-d') }}
                    @else
    
                    Last Updated : {{ date('Y-m-d') }}
                    @endif
    
    
    
    
                </div>
            </div>
        </div>
        <!-- second -->
        <div class="col-sm-1">
    
        </div>
        <div class="col-sm-2">
            <div class="line_height footer_font_size px-3  py-2">
    
    
                <h5>Important Links</h5>
    
                <a href="{{ route('home') }}" class="footer_black_font" > Home</a>
                <br>
    
                <a href="{{ route('home') }}" class="footer_black_font">Website Policies </a>
                <br>
    
                <a href="{{ route('home') }}" class="footer_black_font">Disclaimer  </a>
                <br>
                
                <a href="{{ route('sitemap') }}" class="footer_black_font">Sitemap  </a>
    
    
            </div>
        </div>
    
    
        <!-- second -->
        <div class="col-sm-2">
    
        </div>
        <div class="col-sm-2">
            <div class="line_height footer_font_size px-3  py-2">
                @php
                use App\Models\User;
                $usersCount = User::where('role_id', 77)->count();
    
                $usersCount = isset($usersCount) ? $usersCount : 0;
    
                $formattedCount = str_pad($usersCount, 6, '0', STR_PAD_LEFT);
                @endphp
    
                Total Visitor
                <br>
                <h4>
                    <span style="color:white; background-color:black;">
                        {{ $formattedCount }}
                    </span>
                </h4>
    
    
    
                @php
                $usersCountToday = \App\Models\User::where('role_id', 77)
                ->whereDate('created_at', today())
                ->count();
                @endphp
    
    
                Users today: {{ $usersCountToday }}
    
    
                <br>
                @php
                $usersCountThisMonth = \App\Models\User::where('role_id', 77)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();
                @endphp
    
                This month: {{ $usersCountThisMonth }}
                <br>
                Total users:{{ $usersCount }}
                <br>
    
            </div>
        </div>
    
    
    </div>