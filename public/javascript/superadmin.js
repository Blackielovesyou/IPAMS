$(document).ready(function () {
    $("body").addClass("loaded");

    // ----- DATATABLES -----
    $("#usersTable, #adminsTable, #applicantsTable, #logsTable").DataTable({
        paging: true,
        searching: true,
        lengthChange: true,
        pageLength: 10,
        ordering: false,
    });

    $("#applicationsTable").DataTable({
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        language: {
            search: "Search Applications:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ applications",
            paginate: { previous: "Prev", next: "Next" }
        }
    });

    // ----- BOOTSTRAP TOOLTIPS -----
    $("[title]").each(function () {
        new bootstrap.Tooltip(this);
    });

    let selectedUserId = "", selectedUserName = "", selectedUserEmail = "";
    let selectedAppId = null; // For reject modal

    // ----- RESET PASSWORD -----
    $("#resetPasswordModal").on("show.bs.modal", function () {
        $("#resetLoading").hide();
        $("#confirmResetBtn, #resetPasswordModal .btn-secondary").prop("disabled", false);
    });

    $(".reset-btn").click(function () {
        selectedUserName = $(this).data("user-name");
        selectedUserId = $(this).data("user-id");
        selectedUserEmail = $(this).data("user-email");
        $("#resetUserName").text(selectedUserName);
        $("#resetPasswordModal").modal("show");
    });

    $("#confirmResetBtn").click(function () {
        if (!selectedUserId) return;
        $("#resetLoading").removeClass("d-none");
        $("#confirmResetBtn, #resetPasswordModal .btn-secondary").prop("disabled", true);

        $.ajax({
            url: "reset_password.php",
            method: "POST",
            data: { user_id: selectedUserId, user_email: selectedUserEmail },
            success: function () {
                Swal.fire({
                    icon: "success",
                    title: "Password Reset!",
                    text: `Password for ${selectedUserName} has been reset.`,
                    confirmButtonColor: "#0d6efd",
                });
                $("#resetPasswordModal").modal("hide");
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: xhr.responseText,
                    confirmButtonColor: "#d33",
                });
            },
            complete: function () {
                $("#resetLoading").addClass("d-none");
                $("#confirmResetBtn, #resetPasswordModal .btn-secondary").prop("disabled", false);
            },
        });
    });

    // ----- SIDEBAR TOGGLE -----
    $("#sidebarToggle").click(function () {
        $("body").toggleClass("sidebar-visible");
        localStorage.setItem(
            "sidebarState",
            $("body").hasClass("sidebar-visible") ? "open" : "closed"
        );
    });
    if (localStorage.getItem("sidebarState") === "open")
        $("body").addClass("sidebar-visible");
    $(document).click(function (e) {
        if ($(window).width() < 992 && !$(e.target).closest(".sidebar, #sidebarToggle").length) {
            $("body").removeClass("sidebar-visible");
            localStorage.setItem("sidebarState", "closed");
        }
    });

    // ----- SHOW LAST SECTION -----
    showSection(localStorage.getItem("lastSection") || "#dashboardSection");

    // ----- EDIT USER -----
    $(document).on("click", ".edit-btn", function () {
        let $row = $(this).closest("tr");
        selectedUserId = $row.find("td:first").text();
        selectedUserName = $row.find("td:eq(1)").text();
        let email = $row.find("td:eq(2)").text();

        $("#editUserId").val(selectedUserId);
        $("#editFullName").val(selectedUserName);
        $("#editEmail").val(email);
        $("#editUserModal").modal("show");
    });

    $("#saveEditBtn").click(function () {
        $.post(
            "update_user.php",
            {
                id: $("#editUserId").val(),
                fullname: $("#editFullName").val(),
                email: $("#editEmail").val(),
            },
            function (res) {
                if (res.success) {
                    let $row = $(`#adminsTable tr td:first:contains(${res.id}), #applicantsTable tr td:first:contains(${res.id})`).closest("tr");
                    $row.find("td:eq(1)").text(res.fullname);
                    $row.find("td:eq(2)").text(res.email);
                    Swal.fire("Updated!", "User info updated successfully.", "success");
                    $("#editUserModal").modal("hide");
                } else {
                    Swal.fire("Error!", res.message || "Update failed.", "error");
                }
            },
            "json"
        );
    });

    // ----- ACCESS MANAGEMENT -----
    $(document).on("click", ".access-btn", function() {
        let userId = $(this).data("user-id");
        let userName = $(this).data("user-name");

        $("#accessUserId").val(userId);
        $("#accessUserName").text(userName);

        $.post("get_access.php", { user_id: userId }, function(data) {
            $("#accessForm input[type=checkbox]").prop("checked", false);
            if (data.success) {
                data.modules.forEach(function(m) {
                    $(`#accessForm input[value='${m}']`).prop("checked", true);
                });
            }
        }, "json");

        $("#accessModal").modal("show");
    });

    $("#accessForm").submit(function(e) {
        e.preventDefault();
        $.post("save_access.php", $(this).serialize(), function(data) {
            if (data.success) {
                alert("Access updated!");
                $("#accessModal").modal("hide");
            } else {
                alert("Failed to update.");
            }
        }, "json");
    });

    // ----- DELETE USER -----
    $(document).on("click", ".delete-btn", function () {
        let $row = $(this).closest("tr");
        selectedUserId = $row.find("td:first").text();
        selectedUserName = $row.find("td:eq(1)").text();
        $("#deleteUserName").text(selectedUserName);
        $("#deleteUserModal").modal("show");
    });

    $("#confirmDeleteBtn").click(function () {
        $.post(
            "delete_user.php",
            { id: selectedUserId },
            function (res) {
                if (res.success) {
                    $(`#adminsTable tr td:first:contains(${selectedUserId}), #applicantsTable tr td:first:contains(${selectedUserId})`).closest("tr").remove();
                    Swal.fire("Deleted!", "User has been deleted.", "success");
                    $("#deleteUserModal").modal("hide");
                } else {
                    Swal.fire("Error!", res.message || "Deletion failed.", "error");
                }
            },
            "json"
        );
    });

    // ----- APPLICATIONS ACTION BUTTONS -----
    $(document).on("click", ".view-btn", function () {
        let id = $(this).data("id");
        window.location.href = "review.php?id=" + id;
    });

    // Approve button
    $(document).on("click", ".approve-btn", function () {
        let id = $(this).data("id");
        $.post('update_permit_status.php', { id: id, status: 'approved' }, function (res) {
            if (res === 'success') {
                $('#status-' + id)
                    .removeClass('bg-warning bg-danger')
                    .addClass('bg-success')
                    .text('Approved');
                $('#actions-' + id).html(''); // remove buttons
                Swal.fire("Approved!", "Application " + id + " approved.", "success");
            } else {
                Swal.fire("Error!", "Failed to approve the application.", "error");
            }
        });
    });

    // Reject button: modal triggers are handled in HTML, JS below
    $(document).on('click', '.reject-btn', function () {
        selectedAppId = $(this).data('id');
    });

    $('#confirmRejectBtn').on('click', function () {
        if (!selectedAppId) return;
        $.post('update_permit_status.php', { id: selectedAppId, status: 'rejected' }, function (response) {
            if (response === 'success') {
                $('#status-' + selectedAppId)
                    .removeClass('bg-warning bg-success')
                    .addClass('bg-danger')
                    .text('Rejected');
                $('#actions-' + selectedAppId).html('');
                $('#rejectModal').modal('hide');
            } else {
                alert('Failed to reject the application.');
            }
        });
    });

});

// ----- FILTER USER TABLE -----
function filterUserTable(role) {
    $("#usersTable tbody tr").each(function () {
        $(this).toggle(role === "All" || $(this).data("role") === role);
    });
}

// ----- SHOW SECTION -----
function showSection(sectionId) {
    const sections = [
        "#dashboardSection",
        "#userManagementSection",
        "#permitApplicationsSection",
        "#systemSettingsSection",
        "#systemLogsSection"
    ];

    sections.forEach(id => $(id).hide());
    $(sectionId).show();

    $(".sidebar .nav-link").removeClass("active");
    $(`.sidebar .nav-link[onclick="showSection('${sectionId}')"]`).addClass("active");

    localStorage.setItem("lastSection", sectionId);
}

// Restore last section on page load
$(document).ready(function() {
    const lastSection = localStorage.getItem("lastSection") || "#dashboardSection";
    showSection(lastSection);
});
