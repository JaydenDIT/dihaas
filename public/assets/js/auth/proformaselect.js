$(document).ready(function () {
    // $('#otpModal').modal('show');
    $("#new_user_form").trigger("reset");
    $("#otp-form").trigger("reset");
    // show_loader();
});


$(".funSelectChange").on("change", function () {
    var param = new FormData();
    param.append("_token", _token);
    param.append("id", $(this).find(":selected").val());

    let html = "";
    let error_msg = "";
    let url = "#";
    let select_id = $(this).attr("data-change");
    switch (select_id) {
       
        case "emp_addr_district":
        case "emp_addr_district_ret":
            html =
                "<option value='' disabled selected>Choose District....</option>";
            error_msg = "No District under the selected State.";
            url = formdistrictoption1_url;
            break;
        case "emp_addr_subdiv":
        case "emp_addr_subdiv_ret":
            html =
                "<option value='' disabled selected>Choose Sub-Division....</option>";
            error_msg = "No Sub Division under the selected District.";
            url = formsubdivisionoption1_url;
            break;
          
    }

    // function funSelectChange(select_id, obj) {
    //     var param = new FormData();
    //     param.append("_token", _token);
    //     param.append("id", obj.value);
    //     var html = "";
    //     var error_msg = "";
    //     var url = "#";
    //     switch (select_id) {
    //         case "current_state_id":
    //         case "permanent_state_id":
    //             html =
    //                 "<option value='' disabled selected>Choose State....</option>";
    //             error_msg = "No State under the selected Country.";
    //             url = stateoption_url;
    //             break;
    //         case "current_district_id":
    //         case "permanent_district_id":
    //             html =
    //                 "<option value='' disabled selected>Choose District....</option>";
    //             error_msg = "No District under the selected State.";
    //             url = districtoption_url;
    //             break;

    //         case "emp_addr_district":
    //         case "emp_addr_district_ret":
    //             html =
    //                 "<option value='' disabled selected>Choose District....</option>";
    //             error_msg = "No District under the selected State.";
    //             url = formdistrictoption1_url;
    //             break;
    //         case "emp_addr_subdiv":
    //         case "emp_addr_subdiv_ret":
    //             html =
    //                 "<option value='' disabled selected>Choose Sub-Division....</option>";
    //             error_msg = "No Sub Division under the selected District.";
    //             url = formsubdivisionoption1_url;
    //             break;
    //     }
    function funSelectChangeAjaxCall(resp){
        //console.log(resp);
        if(resp.status == 0){
            alert(resp.msg);
        }
        
        Object.keys(resp.data).forEach(function(key){
            var row = resp.data[key];
            html += "<option value='" + row.id + "' >" + row.name + "</option>";
        });
        if(resp.data.length == 0){
            alert(error_msg);
        }       
        $("#"+select_id).html(html);
    } 
    ajax_send_multipart(url, param, funSelectChangeAjaxCall);
    
});
    

//for register
// $("#checkPermanent").click(function () {
//     $("#permanent_address1").val("");
//     $("#permanent_address2").val("");
//     $("#permanent_address3").val("");
//     $("#permanent_country_id").val("");
//     $("#permanent_pin").val("");
//     $("#permanent_state_id").html(
//         "<option value='' diabled selected>" + "Choose State..." + "</option>"
//     );
//     $("#permanent_district_id").html(
//         "<option value='' diabled selected>" +
//             "Choose District..." +
//             "</option>"
//     );
//     if ($(this)[0].checked == 1) {
//         $("#permanent_address1").val($("#current_address1").val());
//         $("#permanent_address2").val($("#current_address2").val());
//         $("#permanent_address3").val($("#current_address3").val());
//         $("#permanent_country_id").val($("#current_country_id").val());
//         $("#permanent_pin").val($("#current_pin").val());

//         $("#permanent_state_id").html($("#current_state_id").html());
//         $("#permanent_state_id").val($("#current_state_id").val());

//         $("#permanent_district_id").html($("#current_district_id").html());
//         $("#permanent_district_id").val($("#current_district_id").val());
//     }
// });

//for proforma
$("#presentAddressSamecheckbox").click(function () {

    $("#emp_addr_lcality_ret").val("");
    $("#emp_pincode_ret").val("");
    $("#emp_state_ret").html(
        "<option value='' diabled selected>" + "Choose State..." + "</option>"
    );
    $("#emp_addr_district_ret").html(
        "<option value='' diabled selected>" +
            "Choose District..." +
            "</option>"
    );
    $("#emp_addr_subdiv_ret").html(
        "<option value='' diabled selected>" +
            "Choose Sub Division..." +
            "</option>"
    );
    if ($(this)[0].checked == 1) {
        $("#emp_addr_lcality_ret").val($("#emp_addr_lcality").val());
        $("#emp_pincode_ret").val($("#emp_pincode").val());

        $("#emp_state_ret").html($("#emp_state").html());
        $("#emp_state_ret").val($("#emp_state").val());

        $("#emp_addr_district_ret").html($("#emp_addr_district").html());
        $("#emp_addr_district_ret").val($("#emp_addr_district").val());
        $("#emp_addr_subdiv_ret").html($("#emp_addr_subdiv").html());
        $("#emp_addr_subdiv_ret").val($("#emp_addr_subdiv").val());
    }
});





// $("#mobile").keydown(function (event) {
//     k = event.which;
//     if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8 || k == 9) {
//         if ($(this).val().length == 10) {
//             if (k == 8) {
//                 return true;
//             } else {
//                 event.preventDefault();
//                 return false;
//             }
//         }
//     } else {
//         event.preventDefault();
//         return false;
//     }
// });
