function generateCaptcha() {
    let chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789";
    captchaCode = "";
    for (let i = 0; i < 5; i++) {
        captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    $("#captchaText").text(captchaCode);
}
$("#refreshCaptcha").on("click", function () {
    generateCaptcha();
});

$("#counselorLoginModal").on("shown.bs.modal", function () {
    generateCaptcha();
});

$(document).on("submit", "#registerform", function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    // Show loading indicator
    showLoading();
    $.ajax({
        type: "POST",
        url: "/registersubmit",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideLoading();
            global_showalert(response.message, "Success", "green");
            $("#registerform")[0].reset();
        },
        error: function (xhr) {
            hideLoading();
            let response = JSON.parse(xhr.responseText);
            let errorMessage = "An error occurred";
            if (response.errors) {
                errorMessage = "";
                for (let errorKey in response.errors) {
                    errorMessage += response.errors[errorKey][0] + "\n";
                }
            }
            global_showalert(errorMessage, "Alert!", "red");
        },
    });
});

$("#adminLoginForm").on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
        showLoading();
        $.ajax({
            url: "/loginsubmit",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                hideLoading();
                global_showalert(
                    response.message,
                    "Login Success",
                    "blue",
                    response.redirect
                );
            },
            error: function (xhr) {
                hideLoading();
                if (xhr.status === 409) {
                    global_showalert(
                        xhr.responseJSON.message,
                        "Warning!",
                        "orange"
                    );
                } else {
                    let response = JSON.parse(xhr.responseText);
                    let errorMessage = "An error occurred";
                    if (response.errors) {
                        errorMessage = "";
                        for (let errorKey in response.errors) {
                            errorMessage += response.errors[errorKey][0] + "\n";
                        }
                    }
                    global_showalert(errorMessage, "Alert!", "red");
                }
            },
        });
});
