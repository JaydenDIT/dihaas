$(document).ready(function () {
    fieldHide(hiddenClass);
    let step = Number(current_step);
    step = step < 1 ? 1 : step > 3 ? 3 : step;
    showStep(step);
});

$(document).on("click", ".ein-search-btn", async function (e) {
    e.preventDefault();

    try {
        const res = await ajax_send_multipart({
            url: searchEmpByEIN.replace("__ID__", $("#deceased_ein").val()),
            method: "GET",
        });
        await loadPost(res[0]["dept_cd"]);
        $("#deceased_emp_name").val(res[0]["emp_lname"]);
        $("#deceased_desig_cd").val(res[0]["desig_cd"]);
        $("#deceased_emp_desig").val(res[0]["emp_desig"]);
        $("#deceased_adm_dept_cd").val(res[0]["adm_dept_cd"]);
        $("#deceased_adm_dept_desc").val(res[0]["adm_dept_desc"]);
        $("#deceased_field_dept_cd").val(res[0]["dept_cd"]);
        $("#deceased_field_dept_desc").val(res[0]["field_dept_desc"]);
        $("#deceased_doa").val(res[0]["emp_entry_dt"]);
        $("#deceased_dob").val(res[0]["emp_birth_dt"]);
        $("#deceased_emp_group").val(res[0]["emp_group"]);
    } catch (error) {
        console.error(error);
    }
});

$(document.body).on("change", ".request_post", function () {
    if ($(this).val() == "") {
        return;
    }
    let grade_id = $("#" + $(this).data("change-id"));
    // Get the selected option and its data attribute
    let groupCode = $(this).find(":selected").data("group");
    $(grade_id).val(groupCode);
});

$(document.body).on("click", ".expire_on_duty_flag", function () {
    changeFormField("under_expire_on_duty_flag", this);
});

$(document.body).on("change", "#applicant_qualification_id", function () {
    hideElement(".under_applicant_qualification_id");
    $("#applicant_qualification_name").val($(this).find(":selected").text());
    if ($(this).find(":selected").text() == "Others") {
        // $("#relationship_name").val(
        //     action == "edit" ? application_data.relationship_name : ""
        // );
        showElement(".under_applicant_qualification_id");
    }
});

$(document.body).on("keyup", "#applicant_qualification_other", function () {
    $("#applicant_qualification_name").val($(this).val());
});

$(document.body).on("change", ".state_id_flag", async function (e) {
    let stateId = $(this).val();
    let districtSelect = $("#" + $(this).data("change-id"));
    //clear both district and subdivision
    districtSelect.html(
        '<option value="" disabled selected>Choose...</option>'
    );
    $("#" + districtSelect.data("change-id")).html(
        '<option value="" disabled selected>Choose...</option>'
    );
    if (!stateId) {
        return;
    }
    districtSelect.html(
        '<option value="" disabled selected>Loading...</option>'
    );
    try {
        const res = await ajax_send_multipart({
            url: getDistrictByStateId.replace("__ID__", stateId),
            method: "GET",
        });

        districtSelect
            .empty()
            .append('<option value="" disabled selected>Choose...</option>');
        res.forEach(function (district) {
            districtSelect.append(
                `<option value="${district.district_id}">${district.district_name}</option>`
            );
        });
    } catch (error) {
        districtSelect.html(
            '<option value="" disabled selected>Error loading</option>'
        );
    }
});

// On change of district
$(document.body).on("change", ".district_id_flag", async function () {
    let districtId = $(this).val();
    let targetSelect = $("#" + $(this).data("change-id"));
    if (!districtId) {
        targetSelect.html(
            '<option value="" disabled selected>Choose...</option>'
        );
        return;
    }

    targetSelect.html('<option value="" disabled selected>Loading...</option>');

    try {
        const res = await ajax_send_multipart({
            url: getSubDivisionByDistrictId.replace("__ID__", districtId), // <-- route with .replace
            method: "GET",
        });
        targetSelect
            .empty()
            .append('<option value="" disabled selected>Choose...</option>');
        res.forEach(function (item) {
            targetSelect.append(
                `<option value="${item.subdivision_id}">${item.subdivision_name}</option>`
            );
        });
    } catch (error) {
        console.error("Error loading sub-entities:", error);
        targetSelect.html(
            '<option value="" disabled selected>Error loading</option>'
        );
    }
});

$(document.body).on("change", ".address", async function (e) {
    syncAddress();
});

$(document.body).on("click", "#same_as_current", function (e) {
    if ($(this).is(":checked")) {
        syncAddress();
        setPermanentReadonly(true);
    } else {
        setPermanentReadonly(false);
        $("#applicant_permanent_locality").val("");
        $("#applicant_permanent_state_id").html(
            '<option value="" disabled selected>Choose...</option>'
        );
        $("#applicant_permanent_district_id").html(
            '<option value="" disabled selected>Choose...</option>'
        );
        $("#applicant_permanent_subdivision_id").html(
            '<option value="" disabled selected>Choose...</option>'
        );
        $("#applicant_permanent_pincode").val("");
    }
});

function setPermanentReadonly(isReadonly) {
    $(
        "#applicant_permanent_locality,#applicant_permanent_state_id,#applicant_permanent_district_id,#applicant_permanent_subdivision_id,#applicant_permanent_pincode"
    ).prop("readonly", isReadonly);
    $(
        "#applicant_permanent_locality,#applicant_permanent_state_id,#applicant_permanent_district_id,#applicant_permanent_subdivision_id,#applicant_permanent_pincode"
    ).prop("disabled", isReadonly);
}

function syncAddress() {
    if ($("#same_as_current").is(":checked")) {
        // Address
        $("#applicant_permanent_locality").val(
            $("#applicant_current_locality").val()
        );

        // Copy & set State options + value
        $("#applicant_permanent_state_id").html(
            $("#applicant_current_state_id").html()
        );
        $("#applicant_permanent_state_id").val(
            $("#applicant_current_state_id").val()
        );

        // Copy & set District options + value
        $("#applicant_permanent_district_id").html(
            $("#applicant_current_district_id").html()
        );
        $("#applicant_permanent_district_id").val(
            $("#applicant_current_district_id").val()
        );

        // Copy & set Subdivision options + value
        $("#applicant_permanent_subdivision_id").html(
            $("#applicant_current_subdivision_id").html()
        );
        $("#applicant_permanent_subdivision_id").val(
            $("#applicant_current_subdivision_id").val()
        );

        // Pin Code
        $("#applicant_permanent_pincode").val(
            $("#applicant_current_pincode").val()
        );
    }
}

$(document.body).on("change", "#request_adm_dept_cd_3", async function (e) {
    let targetSelect = $("#request_field_dept_cd_3");
    targetSelect.html('<option value="" disabled selected>Loading...</option>');
    try {
        const res = await ajax_send_multipart({
            url: getDepartmentByAdmCd.replace("__ID__", $(this).val()), // <-- route with .replace
            method: "GET",
        });
        targetSelect
            .empty()
            .append('<option value="" disabled selected>Choose...</option>');
        res.field_dept.forEach(function (item) {
            targetSelect.append(
                `<option value="${item.field_dept_cd}" >${item.field_dept_desc}</option>`
            );
        });
    } catch (error) {
        console.error("Error loading sub-entities:", error);
        targetSelect.html(
            '<option value="" disabled selected>Error loading</option>'
        );
    }
});

async function loadPost(dept_code, targetSelect = $(".request_post")) {
    targetSelect.html('<option value="" disabled selected>Loading...</option>');
    try {
        const res = await ajax_send_multipart({
            url: getPostByDeptCode.replace("__ID__", dept_code), // <-- route with .replace
            method: "GET",
        });
        targetSelect
            .empty()
            .append('<option value="" disabled selected>Choose...</option>');
        res.forEach(function (item) {
            targetSelect.append(
                `<option value="${item.dsg_serial_no}" data-group="${item.group_code}" >${item.dsg_desc}</option>`
            );
        });
    } catch (error) {
        console.error("Error loading sub-entities:", error);
        targetSelect.html(
            '<option value="" disabled selected>Error loading</option>'
        );
    }
}

$(document.body).on("change", "#request_field_dept_cd_3", async function (e) {
    loadPost($(this).val(), $("#request_dsg_srno_3"));
});

$(document.body).on("change", "#request_dsg_srno_3", function () {
    if ($(this).val() == "") {
        return;
    }
    // Get the selected option and its data attribute
    let groupCode = $(this).find(":selected").data("group");
    $("#request_group_code_3").val(groupCode);
});

// Step 1 submit
$(document).on("click", "#saveProforma", async function (e) {
    e.preventDefault();
    const form = document.getElementById("proforma-step1");
    const param = new FormData(form);
    param.append(
        "request_dsg_desc_1",
        $("#request_dsg_srno_1").find(":selected").text()
    );
    param.append(
        "request_dsg_desc_2",
        $("#request_dsg_srno_2").find(":selected").text()
    );
    param.append(
        "request_adm_dept_desc_3",
        $("#request_adm_dept_cd_3").find(":selected").text()
    );
    param.append(
        "request_field_dept_desc_3",
        $("#request_field_dept_cd_3").find(":selected").text()
    );
    param.append(
        "request_dsg_desc_3",
        $("#request_dsg_srno_3").find(":selected").text()
    );

    try {
        await validateForm(form);
        let res;
        if (action == "create") {
            res = await ajax_send_multipart({
                url: saveProforma,
                param: param,
            });
        } else {
            res = await ajax_send_multipart({
                url: updateProforma.replace("__ID__", proforma_id),
                param: param,
            });
        }
        await showConfirmation({
            title: "Successfully Save",
            text: "Redirecting to Next Step...",
            type: "success",
            showCancelButton: false,
        });
        window.location.href = editProforma.replace("__ID__", res.proforma_id);
    } catch (err) {
        console.error(err);
    }
});

//family details
// Add or update member
$(document).on("click", "#addFamilyMemberBtn", async function () {
    try {
        const form = document.getElementById("familyDetailForm");
        const param = new FormData(form);
        let res;

        if ($("#family_detail_action").val() == "edit") {
            res = await ajax_send_multipart({
                url: familyDetailUpdate.replace(
                    "__ID__",
                    $("#family_detail_id").val()
                ),
                param: param,
            });

            renderFamilyTableRow(res.data, false); // update existing
            success_message("Family Member Updated");
        } else {
            res = await ajax_send_multipart({
                url: familyDetailAdd.replace("__ID__", proforma_id),
                param: param,
            });
            renderFamilyTableRow(res.data, true); // add new
            success_message("Family Member Added");
        }

        resetFamilyForm();
    } catch (err) {
        console.error(err);
    }
});

// Render single row
function renderFamilyTableRow(encodeData, isNew = true) {
    member = decodeURI(encodeData);
    const rowHtml = `
        <tr data-id="${member.family_detail_id}">
            <td>${member.fullname}</td>
            <td>${member.relationshipText}</td>
            <td>${member.gender}</td>
            <td>${member.dob}</td>
            <td>
                <button class="btn btn-sm btn-primary editMember" data-row='${encodeData}'>Edit</button>
                <button class="btn btn-sm btn-danger deleteMember" data-row='${encodeData}'>Delete</button>
            </td>
        </tr>
    `;

    if (isNew) {
        $("#familyDetailsTable tbody").append(rowHtml);
    } else {
        $(
            `#familyDetailsTable tbody tr[data-id="${member.family_detail_id}"]`
        ).replaceWith(rowHtml);
    }
}

// Reset form
function resetFamilyForm() {
    $("#family_detail_fullname").val("");
    $("#family_detail_relationship_id").val("");
    $("input[name='family_detail_gender']").prop("checked", false);
    $("#family_detail_dob").val("");
    $("#family_detail_action").val("create");
    $("#family_detail_id").val("");
    $("#addFamilyMemberBtn").text("Add");
}

// Edit member
$(document).on("click", ".editMember", function () {
    const member = decodeURI($(this).data("row"));
    $("#family_detail_action").val("edit");
    $("#family_detail_id").val(member.family_detail_id);
    $("#family_detail_fullname").val(member.fullname);
    $("#family_detail_relationship_id").val(member.relationship_id);
    $(`input[name='family_detail_gender'][value='${member.gender}']`).prop(
        "checked",
        true
    );
    $("#family_detail_dob").val(member.dob);
    $("#addFamilyMemberBtn").text("Update");
});

// Delete member
$(document).on("click", ".deleteMember", async function () {
    const member = decodeURI($(this).data("row"));
    try {
        await ajax_send_multipart({
            url: familyDetailDelete.replace("__ID__", member.family_detail_id),
            method: "DELETE",
        });
        $(
            `#familyDetailsTable tbody tr[data-id="${member.family_detail_id}"]`
        ).remove();
        success_message("Family Member Deleted");
    } catch (err) {
        console.error(err);
    }
});
// Step 2 submit
$(document).on("click", "#saveFamilyDetail", async function (e) {
    e.preventDefault();
    try {
        if ($("#familyDetailsTable tbody tr").length === 0) {
            error_message(`Add at least one family member`);
            return;
        }
        await ajax_send_multipart({
            url: completeFamilyDetail.replace("__ID__", proforma_id),
        });
        await showConfirmation({
            title: "Successfully Save",
            text: "Redirecting to Next Step...",
            type: "success",
            showCancelButton: false,
        });
        window.location.href = editProforma.replace("__ID__", proforma_id);
    } catch (err) {
        console.error(err);
    }
});

// Upload of the documents

// Open modal when upload button clicked
$(document).on("click", ".upload-doc-btn", function () {
    const docData = decodeURI($(this).data("upload"));
    $("#upload_document_name").val(docData.document_name);
    $("#upload_document_list_id").val(docData.document_list_id);
    $("#upload_document_file").attr(
        "accept",
        docData.document_type === "pdf" ? "application/pdf" : "*/*"
    );
    $("#sizeHelp").text(`Max file size: ${docData.max_size_kb} kB`);
    $("#docUploadModal").modal("show");
});

// Handle form submission properly
$(document).on("submit", "#docUploadForm", async function (e) {
    e.preventDefault();
    const form = this;

    try {
        await validateForm(form);
        const formData = new FormData(form);
        const res = await ajax_send_multipart({
            url: uploadDocumentSave,
            param: formData,
        });

        requiredDocumentsLeft = res.data.requiredDocumentsLeft;
        const fileUrl = res.data.url;
        const preview = document.getElementById(
            `document-preview-${$("#upload_document_list_id").val()}`
        );
        preview.innerHTML = `<a href="${fileUrl}" target="_blank">View</a>`;
        success_message("Uploaded successfully");
        resetForm(form);
        $("#docUploadModal").modal("hide");
    } catch (err) {
        console.error(err);
    }
});
// Step 3 submit
$(document).on("click", "#saveDocumentBtn", async function (e) {
    e.preventDefault();
    try {
        if (requiredDocumentsLeft > 0) {
            error_message(
                `Please upload all required documents. Left ${requiredDocumentsLeft}`
            );
            return;
        }
        await ajax_send_multipart({
            url: completeUploadDocument.replace("__ID__", proforma_id),
        });
        await showConfirmation({
            title: "Successfully Save",
            text: "You can now Submit the Form",
            type: "success",
            showCancelButton: false,
        });
        window.location.href = editProforma.replace("__ID__", proforma_id);
    } catch (err) {
        console.error(err);
    }
});
// Final Submit
$(document).on("click", "#finalFormSubmitBtn", async function (e) {
    e.preventDefault();
    try {
        await showConfirmation({
            title: "Confirm Submission",
            text: "Are you sure you want to submit the form? Any further changes will not be allowed.",
        });
        await ajax_send_multipart({
            url: formSubmitUrl.replace("__ID__", proforma_id),
        });
        await showConfirmation({
            title: "Successfully Submitted",
            text: "Redirecting to list....",
            type: "success",
            showCancelButton: false,
        });
        window.location.href = `${redirectUrl}?view=forwarded`;
    } catch (err) {
        console.error(err);
    }
});
