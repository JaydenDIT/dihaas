@extends('layouts.app')
@section('content')
    <h5>Configure vaccancy percentage</h5>
    <p class="small text-muted">Configure how many of the total vaccant posts will go to the die-in-harness</p>

    <div class="my-2">
        @php
            $dia_percentage = empty($vaccancyPercentage) ? 0 : $vaccancyPercentage->dia_percentage;
            $effective_date = empty($vaccancyPercentage)
                ? ''
                : \Carbon\Carbon::parse($vaccancyPercentage->effective_date)->format('Y-m-d');
        @endphp
        <form action="{{ route('admin.postvaccancies.save-configuration') }}" name="vaccancyConfigurationForm">
            @csrf
            <div class="col-sm-4">
                <label for="dia_percentage" class="mb-2">Percentage for Die-in-Harness:</label>
                <input type="number" name="dia_percentage" id="dia_percentage"
                    value="{{ $vaccancyPercentage->dia_percentage ?? 0 }}" class="form-control">
            </div>
            <div class="col-sm-4 mt-4">
                <label for="effective_date" class="mb-2">With effect from:</label>
                <input type="date" name="effective_date" id="effective_date" value="{{ $effective_date }}"
                    class="form-control">
            </div>
            <div class="mt-4">
                <button type="submit" name="submit_btn" class="btn btn-success btn-sm"><i class="fa fa-save"></i>
                    Update</button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        const vaccancyConfigurationForm = document.forms['vaccancyConfigurationForm'];

        vaccancyConfigurationForm.onsubmit = function(event) {
            event.preventDefault();

            vaccancyConfigurationForm.submit_btn.disabled = true;
            vaccancyConfigurationForm.submit_btn.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating ...`;

            const formData = new FormData(vaccancyConfigurationForm);

            fetch(vaccancyConfigurationForm.action, {
                method: "POST",
                body: formData
            }).then(async resp => {
                const data = await resp.json()
                if (!resp.ok) {
                    const errMsg = data?.message || "An unknown error has occured.";
                    throw new Error(errMsg);
                }
                success_message(data.message, 6000);
                vaccancyConfigurationForm.submit_btn.innerHTML = `Update`;
                vaccancyConfigurationForm.submit_btn.disabled = false;
            }).catch(error => {
                const errorMsg = error?.message || "Unknown error occurred.";
                console.error("POST error:", errorMsg);
                error_message("Failed to save configuration: " + errorMsg, 6000);
                vaccancyConfigurationForm.submit_btn.innerHTML = `Update`;
                vaccancyConfigurationForm.submit_btn.disabled = false;
            });

        };
    </script>
@endpush
