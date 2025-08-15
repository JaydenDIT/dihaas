// Click to navigate to step
$(document).on("click", ".btn-step", function () {
    let step = $(this).data("step");
    // Allow clicking only if step is unlocked
    if (!$(this).hasClass("disabled")) {
        showStep(step);
    }
});

$(document).on("click", ".nextBtn", function () {
    let step = $(this).data("step");
    showStep(step + 1);
});

$(document).on("click", ".prevBtn", function () {
    let step = $(this).data("step");
    showStep(step - 1);
});

function showStep(step) {
    step = step;
    $(".setup-content").hide();
    $("#step-" + step).show();

    $(".btn-circle").removeClass("active");
    $(".btn-circle")
        .eq(step - 1)
        .addClass("active");
}
