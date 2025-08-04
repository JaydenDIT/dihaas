let conditionIndex = 0;

$(document).ready(function () {
    $("#add-condition").click(function () {
        const $row = createConditionRow(conditionIndex++);
        $("#conditions-container").append($row);
    });

    $("#conditions-container").on("change", ".field-select", function () {
        const field = $(this).val();
        const $row = $(this).closest(".condition-row");
        const $operation = $row.find(".operation-select");

        // Populate operations
        $operation.empty().append('<option value="">Select Operator</option>');

        if (field && criteriaJson[field]) {
            const ops = criteriaJson[field].operations;
            ops.forEach((op) => {
                $operation.append(`<option value="${op}">${op}</option>`);
            });
            $operation.prop("disabled", false);

            // Rebuild value input (dropdown or text)
            updateValueInput($row, field);
        } else {
            $operation.prop("disabled", true);
        }

        updateSimilarProcesses();
    });

    $("#conditions-container").on(
        "change input",
        ".operation-select, .value-input",
        function () {
            updateSimilarProcesses();
        }
    );

    $("#conditions-container").on("click", ".remove-condition", function () {
        $(this).closest(".condition-row").remove();
        updateSimilarProcesses();
    });

    updateSimilarProcesses();
});

$(document).on("submit", "#process-form", async function (e) {
    e.preventDefault();

    const form = this;
    removeError();

    try {
        await validateForm(form); // custom validation

        const data = getFormDataAsJson(form);

        // Ensure process_criteria is parsed as array
        const criteriaRaw = $("#process_criteria").val();
        data.process_criteria = JSON.parse(criteriaRaw || "[]");
        data.action = "create";
        delete data.field;
        delete data.operation;

        // console.log(data);

        await ajax_send_multipart({
            url: saveUrl,
            param: data,
            json: true,
        });

        // Immediately add to existingProcesses using submitted data
        existingProcesses.push({
            process_name: data.process_name,
            process_criteria: JSON.stringify(data.process_criteria),
        });

        // Reset form (optional)
        resetForm(form);

        $("#conditions-container").empty();
        $("#similar-processes-list").empty();
        updateSimilarProcesses();
        success_message("Filters applied successfully");
    } catch (error) {
        console.error(error);
    }
});

function createConditionRow(index) {
    let $row = $(`
        <div class="condition-row row mb-2" data-index="${index}">
            <div class="col-md-4">
                <select class="form-select field-select" name="field" required>
                    <option value="">Select Field</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select operation-select" name="operation" required disabled>
                    <option value="">Select Operator</option>
                </select>
            </div>
            <div class="col-md-4 value-wrapper">
                <input type="text" class="form-control value-input" name="value" placeholder="Value" required>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-danger btn-sm remove-condition">&times;</button>
            </div>
        </div>
    `);

    // Populate fields
    for (const field in criteriaJson) {
        $row.find(".field-select").append(
            `<option value="${field}">${criteriaJson[field].name}</option>`
        );
    }

    return $row;
}

function updateValueInput($row, field) {
    const wrapper = $row.find(".value-wrapper");
    wrapper.empty();

    const criteria = criteriaJson[field];

    if (criteria && criteria.enum) {
        const select = $(
            '<select class="form-select value-input" name="value" required></select>'
        );
        select.append('<option value="">Select Value</option>');
        criteria.enum.forEach((val) => {
            select.append(`<option value="${val}">${val}</option>`);
        });
        wrapper.append(select);
    } else {
        wrapper.append(
            '<input type="text" class="form-control value-input" name="value" placeholder="Value" required>'
        );
    }
}

function updateSimilarProcesses() {
    let criteria = [];

    $("#conditions-container .condition-row").each(function () {
        const field = $(this).find(".field-select").val();
        const operation = $(this).find(".operation-select").val();
        const value = $(this).find(".value-input").val();

        if (field && operation && value) {
            criteria.push({ field, operation, value });
        }
    });

    $("#process_criteria").val(JSON.stringify(criteria));

    // Normalize function
    const normalize = (arr) =>
        [...arr]
            .sort((a, b) => {
                if (a.field !== b.field) return a.field.localeCompare(b.field);
                if (a.operation !== b.operation)
                    return a.operation.localeCompare(b.operation);
                return String(a.value).localeCompare(String(b.value));
            })
            .map((obj) => JSON.stringify(obj));

    let current = normalize(criteria);

    let matched;

    if (criteria.length === 0) {
        matched = existingProcesses;
    } else {
        matched = existingProcesses.filter((p) => {
            try {
                const pc = JSON.parse(p.process_criteria || "[]");
                const existing = normalize(pc);
                return current.every((item) => existing.includes(item));
            } catch {
                return false;
            }
        });
    }

    // Update similar processes list
    $("#similar-processes-list").empty();

    if (matched.length) {
        matched.forEach((p) => {
            let criteriaText = "";
            try {
                const pc = JSON.parse(p.process_criteria || "[]");
                if (pc.length) {
                    criteriaText = '<ul class="mb-0 small">';
                    pc.forEach((c) => {
                        criteriaText += `<li><strong>${c.field}</strong> ${c.operation} <em>${c.value}</em></li>`;
                    });
                    criteriaText += "</ul>";
                } else {
                    criteriaText =
                        '<div class="text-muted small">No criteria</div>';
                }
            } catch {
                criteriaText =
                    '<div class="text-danger small">Invalid criteria</div>';
            }

            $("#similar-processes-list").append(`
        <li class="list-group-item">
            <div><strong>${p.process_name}</strong></div>
            ${criteriaText}
        </li>
    `);
        });
    } else {
        $("#similar-processes-list").append(
            `<li class="list-group-item text-muted">No matches found</li>`
        );
    }

    // Disable/enable submit button
    $("button[type='submit']").prop(
        "disabled",
        matched.length > 0 && criteria.length > 0
    );
}
