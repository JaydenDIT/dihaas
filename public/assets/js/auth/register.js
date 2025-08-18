$(document).ready(function () {
    $("#new_user_form").trigger("reset");
    $("#otp-form").trigger("reset");
    $("#loginli").addClass("active");
});

async function resendOTP(input) {
    try {
        let res;
        if (input == "mobile") {
            res = await ajax_send_multipart({
                url: resendSMSOTP,
            });
        } else {
            res = await ajax_send_multipart({
                url: resendEmailOTP,
            });
        }
        success_message("OTP resend successfully");
    } catch (error) {
        console.error(error);
    }
}

$(".state_options").on("change", async function () {
    let stateId = $(this).val();
    let districtSelect = $("#" + $(this).data("change-id"));
    districtSelect.html(
        '<option value="" disabled selected>Choose...</option>'
    );
    if (!stateId) {
        return;
    }
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
        console.error(error);
    }
});

//for register
$("#same_as_current").click(function () {
    $("#permanent_address1").val("");
    $("#permanent_address2").val("");
    $("#permanent_address3").val("");
    $("#permanent_state_id").val("");
    $("#permanent_pin").val("");
    $("#permanent_state_id").val("");
    $("#permanent_district_id").html(
        "<option value='' diabled selected>" +
            "Choose District..." +
            "</option>"
    );
    $(".permanent_input").prop("disabled", false);
    $(".permanent_required").prop("required", true);
    if ($(this)[0].checked == 1) {
        $(".permanent_required").prop("required", false);
        $(".permanent_input").prop("disabled", true);
    }
});

// this is for citizen
$(document).on("click", "#registerSave", async function (e) {
    try {
        e.preventDefault();
        const form = document.forms["new_user_form"];
        await validateForm(form);
        if ($("#password").val() != $("#password_confirmation").val()) {
            error_message("Password does not match");
            return;
        }
        // const pwd = $("#password").val();
        // const hashedPwd = forge_sha256(pwd);
        // $("#password").val(hashedPwd);
        // $("#password_confirmation").val(hashedPwd);
        const formData = new FormData(form);
        const res = await ajax_send_multipart({
            url: register_url,
            param: formData,
        });
        $("#mobileOtpLabel").html(res.mobileotpmsg);
        $("#otpModal").modal("show");
    } catch (error) {
        console.error(error);
    }
});

$("#otp_submit").click(async function () {
    try {
        $("#otp_error_msg").html("");
        $("#otp_error_msg").hide();
        var form = document.forms["otp-form"];
        await validateForm(form);

        const formData = new FormData(form);
        await ajax_send_multipart({
            url: otpsubmit_url,
            param: formData,
        });
        await showConfirmation({
            title: "Successfully Save",
            text: "Redirecting to login page...",
            type: "success",
            showCancelButton: false,
        });
        $("#new_user_form").trigger("reset");
        $("#otp-form").trigger("reset");
        window.location.href = login_url;
    } catch (error) {
        console.error(error);
    }
});
