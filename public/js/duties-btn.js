let confirm_message = {};

$(document).on("click", ".reject-btn", async function (e) {
    e.preventDefault();
    confirm_message = {
        title: "Reject!",
        text: "Are you sure to Reject?",
        type: "warning",
    };
    $("#confirmation_form").attr("action", rejectUrl);
    $("#confirm_remarks").prop("required", true);
    $("#confirm_title").html("Give reason for rejecting this proforma");
    $("#confirm_proforma_id").val(proformaId);
    $("#confirmationModal").modal("show");
});
$(document).on("click", ".revert-btn", async function (e) {
    e.preventDefault();
    confirm_message = {
        title: "Revert!",
        text: "Are you sure to Revert?",
        type: "warning",
    };
    $("#confirmation_form").attr("action", revertUrl);
    $("#confirm_remarks").prop("required", true);
    $("#confirm_title").html("Give reason for reverting this proforma");
    $("#confirm_proforma_id").val(proformaId);
    $("#confirmationModal").modal("show");
});
$(document).on("click", ".forward-btn", async function (e) {
    e.preventDefault();
    confirm_message = {
        title: "Forward!",
        text: "Are you sure to Forward?",
        type: "info",
    };
    $("#confirmation_form").attr("action", forwardUrl);
    $("#confirm_remarks").prop("required", false);
    $("#confirm_title").html("Write a Remarks(Optional)");
    $("#confirm_proforma_id").val(proformaId);
    $("#confirmationModal").modal("show");
});

$(document).on("submit", "#confirmation_form", async function (e) {
    e.preventDefault();

    try {
        const param = new FormData(this);
        const actionUrl = $(this).attr("action");
        await showConfirmation(confirm_message);
        await ajax_send_multipart({
            url: actionUrl,
            param: param,
        });
        await showConfirmation({
            title: "Success",
            text: "Redirecting to proforma list...",
            type: "success",
            showCancelButton: false,
        });
        window.location.href = confirmRedirectUrl;
    } catch (error) {
        console.error("Error:", error);
    }
});
