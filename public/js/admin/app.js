function addExpertise() {
    const template = document.querySelector(
        "#expertise-template .input-group"
    );
    if (!template) {
        alert("Expertise template not found!");
        return;
    }

    const clone = template.cloneNode(true);
    clone.classList.remove("d-none");

    // Reset all form values
    clone.querySelectorAll("input, select").forEach((el) => {
        el.value = "";
    });

    // Reset the select explicitly to placeholder (if needed)
    const select = clone.querySelector("select");
    if (select) select.selectedIndex = 0;

    // Hide the "Other" input field if exists
    const otherInput = clone.querySelector(".expertise-other-input");
    if (otherInput) otherInput.classList.add("d-none");

    // Append to container
    document.getElementById("expertise-container").appendChild(clone);
}

function removeExpertise(btn) {
    btn.parentElement.remove();
}

function toggleExpertiseOther(selectElement) {
    const input = selectElement.nextElementSibling;
    if (selectElement.value === "Other") {
        input.classList.remove("d-none");
    } else {
        input.classList.add("d-none");
        input.value = ""; // clear input if switching back
    }
}



$("#specializationSelectWrapper").hide(); // initially hide it

$("#year_level").on("change", function () {
    var yearLevel = $(this).val();

    if (yearLevel === "3rd year" || yearLevel === "4th year") {
        $("#specializationSelectWrapper").show();
    } else {
        $("#specializationSelectWrapper").hide();
        $("#specializationSelect")
            .empty()
            .append("<option selected disabled>Select specialization</option>");
    }
});

$('input[name="instructor_for"]').on("change", function () {
    if ($(this).val() === "minor") {
        $("#department-container").addClass("d-none");
        $("#subject-category-container").removeClass("d-none");
        $('[name="department_id"]').prop("disabled", true);
        $('[name="subject_category_id"]').prop("disabled", false);
    } else {
        $("#department-container").removeClass("d-none");
        $("#subject-category-container").addClass("d-none");
        $('[name="department_id"]').prop("disabled", false);
        $('[name="subject_category_id"]').prop("disabled", true);
    }
});
