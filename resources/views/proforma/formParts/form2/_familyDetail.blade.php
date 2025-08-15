<div class="row bg-light border rounded p-3 mt-3">
    <h5 class="py-2 bg-success bg-gradient text-white fw-bold rounded">Family Details</h5>
    <form id="familyDetailForm" name="familyDetailForm">
        @csrf
        {{-- Family Member Form --}}
        <div class="row g-3 align-items-center mb-3">
            <input type="hidden" name="action" value="create" id="family_detail_action" />
            <input type="hidden" name="family_detail_id" id="family_detail_id">
            <div class="col-md-4">
                <label class="form-label fw-bold">Full Name</label>
                <input type="text" name="fullname" id="family_detail_fullname" class="form-control" placeholder="Enter full name">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Relationship</label>
                <select name="relationship_id" id="family_detail_relationship_id" class="form-select" required>
                    <option value="" selected disabled>Choose...</option>
                    @foreach($relationships as $row)
                    <option value="{{$row->relationship_id}}">{{$row->relationship_name}}</option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-4">
                <label class="form-label fw-bold">Date of Birth</label>
                <input type="date" name="dob" id="family_detail_dob" class="form-control">
            </div>


            <div class="col-md-4">
                <label class="form-label fw-bold">Gender</label>
                <div class="d-flex gap-3 mt-1">
                    <div>
                        <input type="radio" name="family_detail_gender" id="family_detail_gender_male" value="male">
                        <label for="family_detail_gender_male">Male</label>
                    </div>
                    <div>
                        <input type="radio" name="family_detail_gender" id="family_detail_gender_female" value="female">
                        <label for="family_detail_gender_female">Female</label>
                    </div>
                    <div>
                        <input type="radio" name="family_detail_gender" id="family_detail_gender_transgender" value="transgender">
                        <label for="family_detail_gender_transgender">Transgender</label>
                    </div>
                </div>
            </div>

            <div class="col-md-2 align-self-end">
                <button class="btn btn-success w-100" id="addFamilyMemberBtn" type="button">Add</button>
            </div>
        </div>

    </form>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="familyDetailsTable">
            <thead class="table-dark">
                <tr>
                    <th>Full Name</th>
                    <th>Relationship</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th style="width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($familyMembers as $member)
                <tr data-id="{{ $member->family_detail_id }}">
                    <td>{{ $member->fullname }}</td>
                    <td>{{ $member->relationship->relationship_name }}</td>
                    <td>{{ $member->gender }}</td>
                    <td>{{ $member->dob }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary editMember" data-row='{{ urlencode(json_encode($member)) }}'>Edit</button>
                        <button class="btn btn-sm btn-danger deleteMember" data-row='{{ urlencode(json_encode($member)) }}'>Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Agreement --}}
    <div class="row mb-4 ps-3 mt-4">
        <div class="form-check ps-4">
            <input class="form-check-input" type="checkbox" name="flexCheckDefault" id="flexCheckDefault" required>
            <label class="form-check-label" for="flexCheckDefault">
                I agree all the above information are correct. I also undertake that the remaining mandatory
                document will be uploaded.
            </label>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="hstack gap-3 my-3 p-3">
        <div class="ms-auto">
            <button class="btn btn-md btn-success" id="saveFamilyDetail" type="button">Save</button>
            @if($current_step > 2)
            <button class="btn btn-md btn-success nextBtn" type="button" data-step="2">Next</button>
            @endif
        </div>
    </div>
</div>