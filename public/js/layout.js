$(function () {
    $(".hidden").hide();
    $("#loading-div").hide();
});

(function () {
    "use strict";
    // Runs after the page has fully loaded
    window.addEventListener("load", () => {
        // Select all forms with the 'needs-validation' class
        const forms = document.querySelectorAll(".needs-validation");

        // Attach the validation logic to each form
        forms.forEach((form) => {
            form.addEventListener("submit", (event) => {
                // Prevent submission if form is invalid
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                // Add the 'was-validated' class for styling feedback
                form.classList.add("was-validated");
            });
        });
    });
})();

function validateForm(form) {
    return new Promise(function (resolve, reject) {
        var checkForm = form.checkValidity();
        form.classList.add("was-validated");
        if (!checkForm) {
            error_message("Fill up all the mandatory fields.");
            form.querySelector(":invalid").focus(); // Focus on the first invalid field
            // console.log(form.querySelector(":invalid"));
            reject("Form validation failed");
        } else {
            resolve(); // Call resolve when the form is valid
        }
    });
}

function localDataTable(table, callbackfn = "") {
    colourTable(table);
    if ($.fn.DataTable.isDataTable(table)) {
        $(table).DataTable().destroy();
    }

    var dTable;
    // DataTable
    dTable = $(table).DataTable({
        dom:
            "<'row'<'col-sm-12 col-md-6 mb-1'B><'col-sm-2 col-md-2'l><'col-sm-4 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 mt-1'p>>",
        //dom: '<"container-fluid"<"row"<"col"B><"col"l><"col"f>>>rtip',
        buttons: [
            {
                extend: "excelHtml5",
                className: "btn btn-sm",
                text: "Export to Excel",
                exportOptions: {
                    columns: function (idx, data, node) {
                        return $.trim($(node).text()) !== ""; // Exclude columns with empty header
                    },
                },
            },
            {
                extend: "pdfHtml5",
                text: "Export to PDF",
                className: "btn btn-sm",
                exportOptions: {
                    columns: function (idx, data, node) {
                        return $.trim($(node).text()) !== ""; // Exclude columns with empty header
                    },
                },
            },
        ],
        drawCallback: function () {
            if (typeof callbackfn == "function") {
                callbackfn();
            }
        },
    });

    $(".dataTables_wrapper .form-select").select2("destroy");

    return dTable;
}

function decodeURI(data) {
    return JSON.parse(decodeURIComponent(data.replace(/\+/g, " ")));
}

function loadAjaxTable(data, callbackfn = "") {
    var table = data["id"];
    colourTable(table);

    // Define columns and handle custom attributes
    var columns = data["columns"].map((ele) => {
        let col = { data: ele };
        if (ele.includes("|")) {
            let parts = ele.split("|");
            col = { data: parts[0] };
            parts.slice(1).forEach((part) => {
                if (part === "nonorderable") {
                    col.orderable = false;
                }
                if (part === "nonsearchable") {
                    col.searchable = false;
                }
                if (part === "no-print") {
                    col.print = false;
                }
                if (part === "datetime") {
                    col.render = function (data) {
                        if (data) {
                            const event = new Date(data);
                            const options = {
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                            };
                            const date = event.toLocaleDateString(
                                "en-IN",
                                options
                            );
                            const time = event.toLocaleTimeString("en-IN");
                            return `${date} ${time}`;
                        }
                        return "";
                    };
                }
            });
        }
        return col;
    });

    // Add action column if required
    if (data["action"] === true) {
        columns.push({
            data: "action",
            orderable: false,
            searchable: false,
            print: false, // Exclude from print/export
        });
    }

    // Parameter function for server-side data
    let param = function (d) {
        d._token = _token;
    };
    if (typeof data["param"] === "function") {
        param = data["param"];
    }

    // Default DOM structure and length menu
    let dom =
        data["dom"] ||
        "<'row'<'col-sm-12 col-md-6 mb-1'B><'col-sm-2 col-md-2 mb-1'l><'col-sm-4 col-md-4  mb-1'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 mt-1'p>>";

    let lengthMenu = data["lengthMenu"] || [
        [10, 25, 50],
        [10, 25, 50],
    ];

    // Destroy any existing DataTable instance on the table
    if ($.fn.DataTable.isDataTable(table)) {
        $(table).DataTable().destroy();
    }
    $(table + " body").empty();

    // Define export button options
    let exportButtonOptions = [];
    if (data["export"] != false) {
        exportButtonOptions = [
            {
                extend: "excelHtml5",
                text: "Export to Excel",
                className: "btn btn-sm",
                exportOptions: {
                    columns: function (idx, data, node) {
                        // Use the col.print property to exclude columns
                        const columnConfig = columns[idx];
                        return columnConfig && columnConfig.print !== false;
                    },
                    modifier: {
                        search: "none",
                        order: "current",
                        page: "all",
                    },
                    format: {
                        body: function (data, row, column, node) {
                            // Handle ordered and unordered lists
                            if ($(node).find("li").length > 0) {
                                const isOrdered = $(node).find("ol").length > 0;
                                return $(node)
                                    .find("li")
                                    .map(function (index) {
                                        const prefix = isOrdered
                                            ? index + 1 + ". "
                                            : "- ";
                                        return prefix + $(this).text().trim();
                                    })
                                    .get()
                                    .join("\n"); // Join items with newlines
                            }
                            return $(node).text().trim(); // Return plain text for non-list content
                        },
                    },
                },
            },
            {
                extend: "pdfHtml5",
                text: "Export to PDF",
                className: "btn btn-sm",
                exportOptions: {
                    columns: function (idx, data, node) {
                        // Use the col.print property to exclude columns
                        const columnConfig = columns[idx];
                        return columnConfig && columnConfig.print !== false;
                    },
                    modifier: {
                        search: "none",
                        order: "current",
                        page: "all",
                    },
                    format: {
                        body: function (data, row, column, node) {
                            // Handle ordered and unordered lists
                            if ($(node).find("li").length > 0) {
                                const isOrdered = $(node).find("ol").length > 0;
                                return $(node)
                                    .find("li")
                                    .map(function (index) {
                                        const prefix = isOrdered
                                            ? index + 1 + ". "
                                            : "- ";
                                        return prefix + $(this).text().trim();
                                    })
                                    .get()
                                    .join("\n"); // Join items with newlines
                            }
                            return $(node).text().trim(); // Return plain text for non-list content
                        },
                    },
                },
            },
        ];
    }
    // Initialize DataTable
    var dTable = $(table).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: "POST",
            url: data["url"],
            data: param,
        },
        language: { emptyTable: data["message"] },
        columns: columns,
        order: data["order"] || [], // Set initial order
        drawCallback: function () {
            if (typeof callbackfn === "function") {
                callbackfn();
            }
        },
        dom: dom,
        buttons: exportButtonOptions,
        select: true,
        stateSave: false,
        lengthMenu: lengthMenu,
    });

    return dTable;
}

function colourTable(id) {
    $(id).addClass("table");
    $(id).addClass("table-striped");
    $(id).addClass("table-bordered");
    $(id).addClass("border-primary");
}

function error_message(message) {
    var html = `<div class="alert alert-danger alert-dismissible fade show " role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
    $(".error-container").append(html);
}
function success_message(message) {
    var html = `<div class="alert alert-success alert-dismissible fade show" role="alert" >
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
    $(".error-container").append(html);
}

function removeError() {
    $(".error-container").html("");
    $(".is-invalid").removeClass("is-invalid");
    $(".was-validated").removeClass("was-validated");
}

function reinitSelect2(id = "", parent_id = "") {
    id = id || ".form-select";
    // Iterate over each matching element
    $(id).each(function () {
        // Check if the element has been initialized with Select2
        if ($(this).data("select2")) {
            $(this).select2("destroy"); // Destroy only if it's a Select2 instance
        }
        // Reinitialize Select2
        if (parent_id != "") {
            $(this).select2({
                theme: "bootstrap-5",
                dropdownParent: $(parent_id),
                width: "100%",
            });
        } else {
            $(this).select2({
                theme: "bootstrap-5",
            });
        }
    });
}

async function ajax_send_multipart(arg) {
    const {
        url = "",
        method = "POST",
        param = "",
        my_function = null,
        json = false,
        find = "",
        formError = true, //error on form submission
        sideError = false, //error on the side of the page
        mainError = true, //main error messages
    } = arg;

    const contentType = json ? "application/json; charset=UTF-8" : false;
    const data = json ? JSON.stringify(param) : param;

    return new Promise((resolve, reject) => {
        $.ajax({
            url,
            method,
            data,
            processData: false,
            contentType,
            enctype: "multipart/form-data",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                $("#loading-div").show();
            },
            success: function (response) {
                $("#loading-div").hide();
                if (typeof my_function === "function") {
                    my_function(response);
                }
                resolve(response);
            },
            error: function (jqXHR) {
                $("#loading-div").hide();
                let result = {
                    status: -1,
                    message: "An unexpected error occurred",
                };

                try {
                    const data = JSON.parse(jqXHR.responseText);
                    result.message = data.message || result.message;
                    result.errors = data.errors || null;
                } catch (e) {
                    // Use default error structure
                }

                if (result.errors) {
                    if (
                        typeof result.errors === "object" &&
                        !Array.isArray(result.errors)
                    ) {
                        // It's an object
                        Object.keys(result.errors).forEach((key) => {
                            const errorMessage = result.errors?.[key] || "";
                            // Error in form
                            if (formError) {
                                const elementId = $(`#${find}${key}`);
                                const elementClass = $(`.${find}${key}`);

                                if (elementId.length) {
                                    elementId.addClass("is-invalid");
                                    elementId
                                        .siblings(".invalid-feedback")
                                        .html(errorMessage);
                                } else if (elementClass.length) {
                                    elementClass.addClass("is-invalid");
                                    elementClass
                                        .siblings(".invalid-feedback")
                                        .html(errorMessage);
                                }
                            }
                            // Side error
                            if (sideError && errorMessage) {
                                error_message(errorMessage);
                            }
                        });
                    } else if (typeof result.errors === "string") {
                        // It's a string
                        if (sideError) {
                            error_message(result.errors);
                        }
                    }
                }
                //main error
                if (result.message && mainError) {
                    error_message(result.message);
                }

                reject(result);
            },
            complete: function () {
                $("#loading-div").hide(); // Ensure loader is hidden
            },
        });
    });
}

function getFormDataAsJson(form) {
    var obj = {};
    var curr_element;
    var curr_key = "";
    var curr_val = "";
    var array_val = {};
    var radio_val = {};

    for (var i = 0; i < form.elements.length; i++) {
        curr_element = form.elements[i];
        if (curr_element.name.trim() == "") {
            continue;
        }
        if (
            curr_element.type.toLowerCase() !== "submit" &&
            curr_element.type.toLowerCase() !== "button" &&
            curr_element.type.toLowerCase() !== "reset"
        ) {
            curr_key = curr_element.name.trim();

            curr_key = curr_key.replace("[]", "");
            curr_val = curr_element.value.trim();

            if (array_val[curr_key] === undefined) {
                array_val[curr_key] = [];
            }
            if (radio_val[curr_key] === undefined) {
                radio_val[curr_key] = "";
            }
            var key_name = curr_element.name.trim();
            if (
                key_name.substring(key_name.length - 2, key_name.length) == "[]"
            ) {
                if (curr_element.type.toLowerCase() === "checkbox") {
                    if (curr_element.checked === true) {
                        array_val[curr_key].push(curr_val);
                    }
                    obj[curr_key] = array_val[curr_key];
                } else {
                    if (curr_val !== "") {
                        array_val[curr_key].push(curr_val);
                    }
                    obj[curr_key] = array_val[curr_key];
                }
            } else {
                if (curr_element.type.toLowerCase() === "checkbox") {
                    obj[curr_key] =
                        curr_element.checked === true ? curr_val : "";
                } else if (curr_element.type.toLowerCase() === "radio") {
                    if (curr_element.checked === true) {
                        radio_val[curr_key] = curr_val;
                    }
                    obj[curr_key] = radio_val[curr_key];
                } else {
                    obj[curr_key] = curr_val;
                }
            }
        }
    }
    return obj;
}

$(document.body).on("keydown", ".is_number", function (event) {
    var key = window.event ? event.keyCode : event.which;
    var allowKey = [8, 9, 46];
    if (allowKey.includes(key)) {
        return true;
    } else if (/^[0-9]$/i.test(event.key)) {
        return true;
    } else {
        return false;
    }
});

$(document.body).on("keydown", ".is_decimal", function (event) {
    var key = window.event ? event.keyCode : event.which;
    // console.log(key);
    var allowKey = [8, 9, 46, 110, 190];
    if (allowKey.includes(key)) {
        return true;
    } else if (/^[0-9]$/i.test(event.key)) {
        return true;
    } else {
        return false;
    }
});

$(document.body).on("keydown", ".is_char", function (event) {
    var key = window.event ? event.keyCode : event.which;
    var allowKey = [8, 9, 46];
    if (allowKey.includes(key)) {
        return true;
    } else if ((key >= 65 && key <= 91) || (key >= 97 && key <= 122)) {
        return true;
    } else {
        return false;
    }
});

$(document.body).on("keydown", ".is_name", function (event) {
    /* console.log( event.keyCode ); */
    var key = window.event ? event.keyCode : event.which;
    var allowKey = [8, 9, 46, 32];
    if (allowKey.includes(key)) {
        return true;
    } else if ((key >= 65 && key <= 91) || (key >= 97 && key <= 122)) {
        return true;
    } else {
        return false;
    }
});

$(document.body).on("keydown", ".is_address", function (event) {
    var key = window.event ? event.keyCode : event.which;
    var allowKey = [8, 9, 46, 32, 173, 191, 222, 188, 190, 16, 48, 57];

    // console.log(key);
    if (allowKey.includes(key)) {
        return true;
    } else if (/^[0-9]$/i.test(event.key)) {
        return true;
    } else if ((key >= 65 && key <= 91) || (key >= 97 && key <= 122)) {
        return true;
    } else {
        return false;
    }
});

function resetFormById(formId) {
    const formElement = document.getElementById(formId);
    formElement.classList.remove("was-validated");
    formElement.reset();
}
function resetForm(form) {
    if (!form || !(form instanceof HTMLFormElement)) return;

    form.classList.remove("was-validated");
    form.reset();
}

//object and prefix of the id
async function setMatchValue(obj = {}, prefix = "") {
    Object.keys(obj).forEach(function (key) {
        const field = $(`#${prefix}${key}`);
        if (field.length) {
            field.val(obj[key]);
        }
    });
}

$(".close-modal").on("click", function () {
    const modalId = $(this).data("closeid");
    closeModal(modalId);
});

function closeModal(modalId) {
    document.activeElement.blur();
    $("#" + modalId).modal("hide");
    // reinitSelect2(".form-select");
}

// Function to show confirmation dialog with default values
function showConfirmation({
    title = "Confirm Action",
    text = "Are you sure?",
    type = "warning",
    showCancelButton = true,
    confirmButtonText = "Yes, proceed",
    cancelButtonText = "Cancel",
} = {}) {
    return new Promise((resolve, reject) => {
        swal.fire({
            title: title,
            text: text,
            icon: type,
            showCancelButton: showCancelButton,
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
        }).then((result) => {
            if (result.isConfirmed) {
                resolve("Confirmed");
            } else {
                reject("Action Cancelled");
            }
        });
    });
}
