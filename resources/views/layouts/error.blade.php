<style>
    /*  Error container */
    .error-container {
        position: fixed;
        bottom: 0px;
        right: 20px;
        min-width: 25%;
        z-index: 9999;
    }
</style>

<div class="error-container">
    @if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show " role="alert">
        {{Session::get('error')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible fade show " role="alert">
        {{ $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endforeach
    @endif

    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show " role="alert">
        {{Session::get('success')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>