<div class="row  border bg-pink   rounded mt-3">
    <h5 class="bg-success bg-gradient text-white fw-bold  p-2 ps-3">Family Details</h5>

    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-light" id="familyDetailsTable">
            <thead class="table-dark">
                <tr>
                    <th>Full Name</th>
                    <th>Relationship</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proforma->familyDetails as $member)
                <tr data-id="{{ $member->family_detail_id }}">
                    <td>{{ $member->fullname }}</td>
                    <td>{{ $member->relationship->relationship_name }}</td>
                    <td>{{ $member->gender }}</td>
                    <td>{{ $member->dob }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="hstack gap-3 my-3 p-3">
        <div class="ms-auto">
            <button class="btn btn-md btn-success prevBtn" type="button" data-step="2">Prev</button>
            <button class="btn btn-md btn-success nextBtn" type="button" data-step="2">Next</button>
        </div>
    </div>
</div>