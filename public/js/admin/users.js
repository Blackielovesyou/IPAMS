function users_table() {
    if ($("#staff-tab").hasClass("active")) {
        type = 1;
    } else if ($("#inspector-tab").hasClass("active")) {
        type = 3;
    } else if ($("#applicant-tab").hasClass("active")) {
        type = 2;
    }
    $.ajax({
        type: "GET",
        url: "/admin/get_users",
        data: { type: type },
        headers: {
            "X-CSRF-TOKEN": csrfToken, // Add CSRF token to the request headers
        },
        success: function (response) {
            $("#users_table").DataTable().destroy();
            var table = $("#users_table").DataTable({
                responsive: true, // Enable responsiveness
                data: response,
                columns: [
                    { data: "id" },
                    { data: "name" },
                    { data: "email" },
                    {
                        data: "status",
                        render: function (data, type, row) {
                            if (data === "Active") {
                                return (
                                    '<span class="badge bg-success">' +
                                    data +
                                    "</span>"
                                );
                            } else {
                                return (
                                    '<span class="badge bg-danger">' +
                                    data +
                                    "</span>"
                                );
                            }
                        },
                    },
                    {
                        data: "id",
                        render: function (data, type, row) {
                            let action = `<button class="btn btn-sm btn-success activate_user" 
                                            data-id="${row.id}"  
                                            data-name="${row.name}"
                                            data-bs-toggle="tooltip" title="Activate">
                                            <i class="bi bi-check"></i>
                                        </button>`;
                            if (row.status == "Active") {
                                action = `<button class="btn btn-sm btn-danger deactivate_user" 
                                            data-id="${row.id}"  
                                            data-name="${row.name}"
                                            data-bs-toggle="tooltip" title="Deactivate">
                                            <i class="bi bi-x"></i>
                                        </button>`;
                            }
                            let module_access = `
                                <button class="btn btn-sm btn-success" 
                                    onClick="open_module_access(${row.id})"
                                    data-bs-toggle="tooltip" title="Module Access" 
                                    >
                                    <i class="bi bi-list-task"></i>
                                </button>

                            `;
                            return `
                                ${module_access}
                                <button class="btn btn-sm btn-primary view_profile" 
                                    data-id="${row.id}"  
                                    data-code="${row.code}"
                                    data-name="${row.name}"
                                    data-email="${row.email}"
                                    data-contact="${row.contact}"
                                    data-user_type="${row.user_type}"
                                    data-bs-toggle="tooltip" title="View Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning resetpassword" 
                                    data-id="${row.id}"  
                                    data-name="${row.name}"
                                    data-bs-toggle="tooltip" title="Reset Password">
                                    <i class="bi bi-key"></i>
                                </button>
                                ${action}
                        `;
                        },
                    },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        visible: false,
                        searchable: false,
                    },
                ],
                drawCallback: function () {
                    // Initialize tooltips after table draw
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    );
                    var tooltipList = tooltipTriggerList.map(function (
                        tooltipTriggerEl
                    ) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                    // Attach click event listener to deactivate buttons
                    $(".deactivate_user").on("click", function () {
                        var userId = $(this).data("id");
                        deactivateUser(userId);
                    });

                    // Attach click event listener to activate buttons
                    $(".activate_user").on("click", function () {
                        var userId = $(this).data("id");
                        activateUser(userId);
                    });
                },
            });
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + status + " - " + error);
            // Additional error handling here
        },
    });
}
function users_verification_table() {
    var type = "visitor";
    $.ajax({
        type: "GET",
        url: "/admin/get_users",
        data: { type: type },
        headers: {
            "X-CSRF-TOKEN": csrfToken, // Add CSRF token to the request headers
        },
        success: function (response) {
            $("#users_verification_table").DataTable().destroy();
            var table = $("#users_verification_table").DataTable({
                responsive: true, // Enable responsiveness
                data: response,
                order: [4, "desc"],
                columns: [
                    { data: "id" },
                    { data: "name" },
                    { data: "email" },
                    {
                        data: "id_file_url",
                        render: function (data, type, row) {
                            if (row.id_file_url != null) {
                                return (
                                    '<a href="/storage/' +
                                    row.id_file_url +
                                    '" target="_blank">View ID</a>'
                                );
                            }
                            return "";
                        },
                    },
                    {
                        data: "userStatus",
                        render: function (data, type, row) {
                            var stat = "";
                            if (row.userStatus == 0) {
                                stat = "Pending";
                            } else if (row.userStatus == 3) {
                                stat = "No Upload";
                            } else if (row.userStatus == 1) {
                                stat = "Approved";
                            } else {
                                stat = "Declined";
                            }
                            return stat;
                        },
                    },
                    {
                        data: "encrypt_id",
                        render: function (data, type, row) {
                            var statDisabled = "disabled";
                            if (row.userStatus == 0) {
                                statDisabled = "";
                            }
                            return `
                                <button class="btn btn-primary btn-sm user_action_status" data-id="${data}" data-action="1" data-bs-toggle="tooltip" title="Approve" ${statDisabled}><i class="bi bi-check"></i></button> 
                                <button class="btn btn-danger btn-sm user_action_status" data-id="${data}" data-action="2" data-bs-toggle="tooltip" title="Decline" ${statDisabled}><i class="bi bi-x"></i></button>
                            `;
                        },
                    },
                ],
                drawCallback: function () {
                    // Initialize tooltips after table draw
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    );
                    var tooltipList = tooltipTriggerList.map(function (
                        tooltipTriggerEl
                    ) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                    // Attach click event listener to activate buttons
                    $(".user_action_status").on("click", function () {
                        var action = $.trim($(this).data("action"));
                        var id = $.trim($(this).data("id"));
                        user_verification(id, action);
                    });
                },
            });
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + status + " - " + error);
            // Additional error handling here
        },
    });
}

$(document).on("click", ".choose-type", function (e) {
    users_table();
});

const user_verification = (id, action) => {
    let modal = $.confirm({
        title: "Confirm!",
        content:
            "Are you sure you want to <b>" +
            (action == 1 ? "approve" : "decline") +
            "</b> this user?",
        type: action == 1 ? "blue" : "red",
        buttons: {
            cancel: function () {
                modal.close();
            },
            confirm: function () {
                $.ajax({
                    type: "POST",
                    url: "/admin/user_verification",
                    data: { id: id, action: action },
                    headers: {
                        "X-CSRF-TOKEN": csrfToken, // Add CSRF token to the request headers
                    },
                    success: function (response) {
                        if (response.success) {
                            // Show success alert and reload the table to reflect changes
                            global_showalert(
                                response.message,
                                "Success",
                                "green",
                                "/admin/users_verification"
                            );
                        } else {
                            console.error(
                                "Failed to update user status: " +
                                    response.message
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        global_showalert(
                            "An error occurred: " + status + " - " + error,
                            "Failed",
                            "red"
                        );
                        // Additional error handling here
                    },
                });
            },
        },
    });
};

const deactivateUser = (userId) => {
    let modal = $.confirm({
        title: "Confirm!",
        content:
            "Are you sure you want to <b>Deactivate</b> this user account?",
        type: "red",
        buttons: {
            cancel: function () {
                modal.close();
            },
            confirm: function () {
                $.ajax({
                    type: "POST",
                    url: "/admin/blocked-account",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken, // Add CSRF token to the request headers
                    },
                    data: { userID: userId },
                    success: function (response) {
                        // Handle successful deactivation, e.g., show a success message, refresh the table
                        global_showalert(response.message, "Success", "green");
                        users_table(); // Refresh the users table
                    },
                    error: function (xhr, status, error) {
                        global_showalert(response.message, "Failed", "red");
                    },
                });
            },
        },
    });
};
const activateUser = (userId) => {
    let modal = $.confirm({
        title: "Confirm!",
        content: "Are you sure you want to <b>Activate</b> this user account?",
        type: "green",
        buttons: {
            cancel: function () {
                modal.close();
            },
            confirm: function () {
                $.ajax({
                    type: "DELETE",
                    url: "/admin/activate_user/" + userId,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken, // Add CSRF token to the request headers
                    },
                    success: function (response) {
                        // Handle successful activation, e.g., show a success message, refresh the table
                        global_showalert(response.message, "Success", "green");
                        users_table(); // Refresh the users table
                    },
                    error: function (xhr, status, error) {
                        global_showalert(error, "Failed", "red");
                        // Additional error handling here
                    },
                });
            },
        },
    });
};
$(document).on("click", ".view_profile", function () {
    let id = $.trim($(this).data("id"));

    if (!$("#userProfileModal").is(":visible")) {
        $("#userProfileModal").modal("show");
    }
    $.ajax({
        type: "GET",
        url: `/admin/user_profile/${id}`,
        success: function (response) {
            $("#userProfileModal .modal-dialog").html(response.html);
        },
        error: function (xhr, status, error) {
            // Handle error here
            console.error(xhr.responseText);
        },
    });
});
$(document).on("click", ".resetpassword", function () {
    let id = $.trim($(this).data("id"));
    let name = $.trim($(this).data("name"));
    $("#reset_user_name").text(name);

    let modal = $.confirm({
        title: "Confirm!",
        content:
            "Are you sure you want to reset the password for <b>" +
            name +
            "</b>?",
        type: "red",
        buttons: {
            cancel: function () {
                modal.close();
            },
            confirm: function () {
                showLoading();
                $.ajax({
                    type: "POST",
                    url: "/admin/reset_password",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        user_id: id,
                    },
                    success: function (response) {
                        $("#resetPasswordModal").modal("hide");
                        hideLoading();
                        global_showalert(
                            "Password was reset successfully. A new password has been sent to the user's email.",
                            "Success",
                            "green"
                        );
                        users_table(); // Refresh the users table
                    },
                    error: function (xhr, status, error) {
                        console.error(
                            "An error occurred: " + status + " - " + error
                        );
                        global_showalert(
                            "Failed to reset password. Please try again.",
                            "Failed",
                            "red"
                        );
                    },
                });
            },
        },
    });
});

const open_module_access = (id) => {
    if (!$("#moduleAccessModal").is(":visible")) {
        $("#moduleAccessModal").modal("show");
    }
    $.ajax({
        type: "GET",
        url: `/admin/module_access/${id}`,
        success: function (response) {
            $("#moduleAccessModal .modal-dialog").html(response);
        },
        error: function (xhr, status, error) {
            // Handle error here
            console.error(xhr.responseText);
        },
    });
};

let moduleAccessFormData = null;

let deleteAccessId = null;
let deleteUserId = null;

// Intercept module access form submit and show password modal
$(document).on("submit", "#module_access_form", function (e) {
    e.preventDefault();
    moduleAccessFormData = new FormData(this);
    $("#moduleAccessModal").modal("hide");
    $("#confirmPasswordModal").modal("show");
});

$(document).on("click", ".delete_access", function () {
    deleteAccessId = $(this).data("access-id");
    deleteUserId = $(this).data("user-id");

    $("#deleteAccessId").val(deleteAccessId);
    $("#deleteUserId").val(deleteUserId);
    $("#moduleAccessModal").modal("hide");
    $("#deleteAccessConfirmModal").modal("show");
});

// Handle confirm password form submit
$(document).on("submit", "#confirm_password_form", function (e) {
    e.preventDefault();

    const password = $("#superAdminPassword").val();
    if (!password) {
        global_showalert(
            "Password is required to proceed.",
            "Warning",
            "orange"
        );
        return;
    }

    moduleAccessFormData.append("superadmin_password", password);

    showLoading();

    $.ajax({
        type: "POST",
        url: "/admin/add_module_access",
        data: moduleAccessFormData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#confirmPasswordModal").modal("hide");
            $("#confirm_password_form")[0].reset();
            hideLoading();
            global_showalert(response.message, "Add Success", "blue");
            open_module_access(response.id);
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
            } else if (response.message) {
                errorMessage = response.message;
            }
            global_showalert(errorMessage, "Alert!", "red");
        },
    });
});

// Submit form with password
$(document).on("submit", "#delete_access_form", function (e) {
    e.preventDefault();

    const password = $("#deleteAccessPassword").val();
    if (!password) {
        global_showalert("Password is required.", "Warning", "orange");
        return;
    }

    showLoading();

    $.ajax({
        type: "DELETE",
        url: `/admin/module_access/${deleteAccessId}/${deleteUserId}`,
        data: { superadmin_password: password },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#deleteAccessConfirmModal").modal("hide");
            $("#delete_access_form")[0].reset();
            hideLoading();
            global_showalert(response.message, "Deleted", "green");
            open_module_access(deleteUserId);
        },
        error: function (xhr) {
            hideLoading();
            let response = JSON.parse(xhr.responseText);
            let message = response.message || "Failed to delete access.";
            global_showalert(message, "Error", "red");
        },
    });
});

$("#confirmResetPassword").on("click", function () {});
let users_management = $("main.users-management");
if (users_management.length) {
    users_table();
    users_verification_table();
}
