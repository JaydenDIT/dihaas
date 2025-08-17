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

<div class="error-container"></div>

@php
$flash = [
'error' => session('error'),
'success' => session('success'),
'errors' => $errors->any() ? $errors->all() : [],
];
@endphp

@push('js')
<script>
    const flashMessages = @json($flash);

    if (flashMessages.error) {
        error_message(flashMessages.error);
    }

    if (flashMessages.success) {
        success_message(flashMessages.success);
    }

    if (flashMessages.errors.length) {
        flashMessages.errors.forEach(msg => error_message(msg));
    }
</script>
@endpush