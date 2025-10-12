function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");
    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    } else {
        password.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
}

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
            $("#registerform")[0].reset();
            global_showalert(response.message, "Congrats", "blue", "/login");
            // Redirect to login page after displaying the message
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

$(document).on("submit", "#loginform", function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    // Show loading indicator
    showLoading();
    $.ajax({
        type: "POST",
        url: "/loginsubmit",
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
            $("#loginform")[0].reset();
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

let welcomeBlade = $("main.welcome");
if (welcomeBlade.length) {
    $("#forgotPasswordForm").submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        showLoading();

        $.ajax({
            url: "/password-reset-request",
            method: "POST",
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            success: function (response) {
                hideLoading();
                global_showalert(response.message, "Success", "green");
                $("#forgotPasswordModal").modal("hide");
                $("#forgotPasswordForm")[0].reset();
            },
            error: function (xhr) {
                hideLoading();
                let response = JSON.parse(xhr.responseText);
                let errorMessage = "An error occurred";

                if (response.errors && response.errors.forgot_email) {
                    errorMessage = response.errors.forgot_email[0]; // Just show the first error for email
                } else if (response.message) {
                    errorMessage = response.message;
                }

                global_showalert(errorMessage, "Alert!", "red");
            },
        });
    });

    $("a[data-bs-target='#forgotPasswordModal']").click(function () {
        let emailValue = $("input[name='email']").val();
        $("#forgot_email").val(emailValue);
    });
}
let resetPass = $("main.resetPass");
if (resetPass.length) {
    $("#passwordResetSubmitForm").submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        showLoading();

        $.ajax({
            url: "/password-reset-submit",
            method: "POST",
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            success: function (response) {
                hideLoading();
                global_showalert(
                    response.message,
                    "Success",
                    "green",
                    response.redirect
                );
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
}

let captchaCode = "";

// Function to generate captcha
function generateCaptcha() {
    let chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789";
    captchaCode = "";
    for (let i = 0; i < 5; i++) {
        captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    $("#captchaText").text(captchaCode);
}

