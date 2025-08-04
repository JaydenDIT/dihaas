$(document).ready(function () {
    const fieldSelect = $("#field-select");
    const operationSelect = $("#operation-select");
    const valueWrapper = $("#value-wrapper");
    const valueInput = $("#value-input");
    const conditionsContainer = $("#conditions-container");
    const hiddenCriteriaInput = $("#process_criteria");
    const addConditionBtn = $("#add-condition");
    const similarList = $("#similar-processes-list");

    let criteria = [];
    try {
        const parsed = JSON.parse(hiddenCriteriaInput.val());
        if (Array.isArray(parsed)) {
            criteria = parsed;
            renderConditions();
        }
    } catch (e) {}

    function updateProcessCriteriaInput() {
        hiddenCriteriaInput.val(JSON.stringify(criteria));
    }

    function escapeHtml(text) {
        return $("<div>").text(text).html();
    }

    function renderConditions() {
        conditionsContainer.empty();
        criteria.forEach((c, index) => {
            const row = `
                <tr data-index="${index}">
                    <td>${criteriaJson[c.field]?.name || c.field}</td>
                    <td>${c.operation}</td>
                    <td>${escapeHtml(c.value)}</td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-condition">×</button></td>
                </tr>`;
            conditionsContainer.append(row);
        });
        updateProcessCriteriaInput();
        filterSimilarProcesses(criteria);
    }

    function loadOperators(fieldKey) {
        operationSelect.empty().prop("disabled", true);
        const field = criteriaJson[fieldKey];
        if (!field) return;

        const operations = field.operations || [];
        operationSelect.append(`<option value="">Select Operator</option>`);
        operations.forEach((op) => {
            operationSelect.append(`<option value="${op}">${op}</option>`);
        });
        operationSelect.prop("disabled", false);

        // Load appropriate input
        if (field.enum) {
            let selectHtml = `<select class="form-select" id="value-input">`;
            selectHtml += `<option value="">Select</option>`;
            field.enum.forEach((val) => {
                selectHtml += `<option value="${val}">${val}</option>`;
            });
            selectHtml += `</select>`;
            valueWrapper.html(selectHtml);
        } else if (field.type === "date") {
            valueWrapper.html(
                `<input type="date" class="form-control" id="value-input" />`
            );
        } else {
            valueWrapper.html(
                `<input type="text" class="form-control" id="value-input" placeholder="Value" />`
            );
        }
    }

    fieldSelect.on("change", function () {
        const selectedField = $(this).val();
        if (selectedField) {
            loadOperators(selectedField);
        } else {
            operationSelect.empty().prop("disabled", true);
            valueWrapper.html(
                `<input type="text" class="form-control" id="value-input" placeholder="Value" />`
            );
        }
    });

    addConditionBtn.on("click", function () {
        const field = fieldSelect.val();
        const operation = operationSelect.val();
        const value = $("#value-input").val();

        if (!field || !operation || value === "") {
            alert("Please fill all condition fields.");
            return;
        }

        criteria.push({ field, operation, value });
        renderConditions();

        // Reset inputs
        fieldSelect.val("");
        operationSelect.empty().prop("disabled", true);
        valueWrapper.html(
            `<input type="text" class="form-control" id="value-input" placeholder="Value" />`
        );
    });

    conditionsContainer.on("click", ".remove-condition", function () {
        const rowIndex = $(this).closest("tr").data("index");
        criteria.splice(rowIndex, 1);
        renderConditions();
    });

    // Filter and render matching similar processes
    function filterSimilarProcesses(currentCriteria) {
        similarList.empty();
        if (!Array.isArray(existingProcesses)) return;

        const matches = existingProcesses.filter((proc) => {
            let existing;
            try {
                existing = JSON.parse(proc.process_criteria || "[]");
            } catch {
                return false;
            }

            // Every selected condition must be matched in existing
            return currentCriteria.every((cond) =>
                existing.some(
                    (e) =>
                        e.field === cond.field &&
                        e.operation === cond.operation && // handle typo if exists
                        e.value == cond.value
                )
            );
        });

        if (matches.length === 0) {
            similarList.append(
                `<li class="list-group-item text-muted">No matching processes found.</li>`
            );
            return;
        }

        matches.forEach((proc) => {
            let criteriaHtml = "";
            try {
                const critArr = JSON.parse(proc.process_criteria || "[]");
                if (Array.isArray(critArr) && critArr.length > 0) {
                    criteriaHtml = `<ul class="mt-1 mb-0 ps-3 small text-muted">`;
                    critArr.forEach((c) => {
                        const fieldLabel =
                            criteriaJson[c.field]?.name || c.field;
                        const op = c.operation; // normalize
                        criteriaHtml += `<li><strong>${fieldLabel}</strong> ${op} ${escapeHtml(
                            c.value
                        )}</li>`;
                    });
                    criteriaHtml += `</ul>`;
                }
            } catch {}

            const html = `<li class="list-group-item">
                <strong>${proc.process_name}</strong><br/>
                <small>${proc.process_description || ""}</small>
                ${criteriaHtml}
            </li>`;
            similarList.append(html);
        });
    }

    $("#process-form").on("submit", async function (e) {
        e.preventDefault();
        removeError();
        updateProcessCriteriaInput();
        const form = this;

        try {
            await validateForm(form);

            const data = getFormDataAsJson(form);
            data.process_criteria = criteria;
            delete data.field;
            delete data.operation;

            await ajax_send_multipart({
                url: saveUrl,
                param: data,
                json: true,
                method: action == "create" ? "POST" : "PUT",
            });

            if (action === "create") {
                existingProcesses.push({
                    process_name: data.process_name,
                    process_description: data.process_description,
                    process_criteria: JSON.stringify(data.process_criteria),
                });
                resetForm(form);
                criteria = [];
                renderConditions();
            }

            success_message("Saved successfully");
        } catch (err) {
            console.error(err);
        }
    });

    // Initial load (in case criteria are prefilled)
    filterSimilarProcesses(criteria);
});
