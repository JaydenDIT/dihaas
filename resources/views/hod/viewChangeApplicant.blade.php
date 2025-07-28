<!DOCTYPE html>
<html>

<head>

</head>

<body>
    @extends('.layouts.app')
    @section('content')

    <div class="container">
<br>
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="table-responsive">



                        <table class="table">
                            <thead>
                                <!-- <th scope="col">#</th> -->
                                <th scope="col">EIN</th>
                                <th scope="col">Deceased Name</th>
                                <th scope="col">DOE</th>
                                <th>Application No.</td>
                                <th>Submitted Date</td>
                                <th>Applicant Name</td>
                                <th>DOB</td>
                                <th scope="col">Mobile No.</th>

                            </thead>

                            <tbody>
                                @forelse($changeapplicants as $changeapplicant)
                                <tr>
                                    <td>{!! $changeapplicant->ein !!}</td>
                                    <td>{!! $changeapplicant->deceased_emp_name !!}</td>
                                    <td>{!! $changeapplicant->deceased_doe !!}</td>
                                    <td>{!! $changeapplicant->appl_number !!}</td>
                                    <td>{!! $changeapplicant->appl_date !!}</td>
                                    <td>{!! $changeapplicant->applicant_name !!}</td>
                                    <td>{!! $changeapplicant->applicant_dob !!}</td>
                                    <td>{!! $changeapplicant->applicant_mobile !!}</td>

                                    <td>
                                        <a href="{{ route('changeApplicant', ['id' => $changeapplicant->id]) }}" class="btn btn-outline-primary">Change</a>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">No Applicants Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

        </div>

    </div>

    @endsection

</body>

</html>