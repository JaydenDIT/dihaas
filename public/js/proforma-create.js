$(document).ready(function () {
    fieldHide([
        "under_expire_on_duty_flag",
        "under_applicant_qualification_id",
    ]);
    if (action == "edit") {
        $("#menu-draft").addClass("active");
        setEditData(application_data);
    } else {
        $("#menu-submit").addClass("active");
    }
});

function fieldHide(arr) {
    arr.forEach(function (value) {
        hideElement("." + value);
    });
}

function hideElement(ele) {
    $(ele).hide();
    $(ele).prop("disabled", true);
}

function showElement(ele) {
    $(ele).show();
    $(ele).prop("disabled", false);
}

function changeFormField(classname, that, flag = "1") {
    classname = "." + classname;
    hideElement(classname);

    if ($(that).val() == flag) {
        showElement(classname);
    }
}

$(document).on("click", ".ein-search-btn", async function (e) {
    e.preventDefault();

    try {
        const res = await ajax_send_multipart({
            url: empDetailUrl.replace("__ID__", $("#deceased_ein").val()),
            method: "GET",
        });

        await loadPost(res[0]["dept_cd"]);
        $("#deceased_emp_name").val(res[0]["emp_lname"]);
        $("#deceased_desig_cd").val(res[0]["desig_cd"]);
        $("#deceased_emp_desig").val(res[0]["emp_desig"]);
        $("#deceased_adm_dept_cd").val(res[0]["adm_dept_cd"]);
        $("#deceased_adm_dept_desc").val(res[0]["adm_dept_desc"]);
        $("#deceased_field_dept_cd").val(res[0]["field_dept_cd"]);
        $("#deceased_field_dept_desc").val(res[0]["field_dept_desc"]);
        $("#deceased_doa").val(res[0]["emp_entry_dt"]);
        $("#deceased_dob").val(res[0]["emp_birth_dt"]);
        $("#deceased_emp_group").val(res[0]["emp_group"]);
    } catch (error) {
        console.error(error);
    }
});

async function loadPost(dept_code) {
    let targetSelect = $(".request_post");
    targetSelect.html('<option value="" disabled selected>Loading...</option>');
    try {
        const res = await ajax_send_multipart({
            url: loadpost.replace("__ID__", dept_code), // <-- route with .replace
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
    districtSelect.html(
        '<option value="" disabled selected>Loading...</option>'
    );
    try {
        const res = await ajax_send_multipart({
            url: loadDistrict.replace("__ID__", stateId),
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

    targetSelect.html('<option value="" disabled selected>Loading...</option>');

    try {
        const res = await ajax_send_multipart({
            url: loadSubDivision.replace("__ID__", districtId), // <-- route with .replace
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

$("#same_as_current").on("click", function () {
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
