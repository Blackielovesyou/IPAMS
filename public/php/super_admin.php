<?php
session_start();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// If not logged in → block
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    echo "<script>
        localStorage.setItem('lastPage', window.location.href);
        window.history.back();
    </script>";
    exit;
}

// Only superadmin can access
if ($_SESSION['role'] !== 'superadmin') {
    echo "<script>
        localStorage.setItem('lastPage', window.location.href);
        window.history.back();
    </script>";
    exit;
}

// ✅ Allowed
$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBO SuperAdmin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
/* ✅ Hide page until fully styled */
body {
    min-height: 100vh;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease-in;
}
body.loaded {
    visibility: visible;
    opacity: 1;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 56px; /* height of navbar */
    left: -280px; /* hidden by default */
    width: 280px;
    height: 100%;
    background: #fff;
    border-right: 1px solid #ddd;
    transition: left 0.3s ease;
    z-index: 2000; /* ✅ above main content */
    padding: 1rem;
}
body.sidebar-visible .sidebar {
    left: 0;
}

/* Main content */
.main-content {
    margin-left: 0;
    padding-top: 80px; /* space for fixed navbar */
    transition: margin-left 0.3s ease;
}

/* Push content on desktop */
@media (min-width: 992px) {
    body.sidebar-visible .main-content {
        margin-left: 280px;
    }
}

/* Sidebar links */
.sidebar .nav-link {
    font-weight: 600;
    color: #000 !important;
    transition: background-color 0.2s, color 0.2s;
    cursor: pointer;
    border-radius: 8px;
}
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background-color: #0d6efd;
    color: #fff !important;
}


/* Hide sections by default */
#systemSettingsSection,
#systemLogsSection {
    display: none;
}

/* Remove DataTables sorting arrows */
table.dataTable thead th::after {
    content: "" !important;
    display: none !important;
}

/* Dashboard Cards: stack nicely on mobile */
@media (max-width: 575.98px) {
    .row .col-6.col-lg-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

/* Sidebar overlay on mobile */
@media (max-width: 991.98px) {
    .sidebar {
        left: -250px;
        width: 250px;
        top: 56px;
        height: calc(100% - 56px);
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    body.sidebar-visible .sidebar {
        left: 0;
    }
    .main-content {
        margin-left: 0 !important;
        padding: 70px 15px 20px 15px;
    }
}

/* Tables: wrap long buttons */
.table td .btn {
    white-space: nowrap;
    margin-bottom: 5px;
}

/* Modals and forms on mobile */
@media (max-width: 576px) {
    .modal-body {
        padding: 1rem;
    }
    .modal-footer {
        flex-direction: column;
    }
    .modal-footer .btn {
        width: 100%;
        margin-bottom: 5px;
    }
    .d-grid button {
        width: 100%;
    }
}
</style>



</head>

<body class="bg-light">

    <!-- ✅ Single Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top shadow-sm">
        <div class="container-fluid d-flex align-items-center">
            <!-- Burger Menu -->
            <button class="btn btn-outline-secondary me-2" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                IPAMS
            </a>

            <!-- Spacer -->
            <div class="flex-grow-1"></div>

            <!-- User Dropdown -->
            <div class="dropdown">
                <a class="fw-semibold text-decoration-none text-dark dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo htmlspecialchars($userRole); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#" id="logoutBtn">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="nav flex-column">
            <a class="nav-link mb-3" onclick="showSection('#dashboardSection')">
                <i class="bi bi-file-earmark-text-fill me-2"></i> Application Management
            </a>
            <a class="nav-link mb-3" onclick="showSection('#userManagementSection')">
                <i class="bi bi-person-fill me-2"></i> User Management
            </a>
            <a class="nav-link mb-3" onclick="showSection('#systemSettingsSection')">
                <i class="bi bi-gear-fill me-2"></i> System Settings
            </a>
            <a class="nav-link mb-3"><i class="bi bi-shield-lock-fill me-2"></i> Security Access</a>
            <a class="nav-link mb-3" onclick="showSection('#systemLogsSection')">
                <i class="bi bi-journal-text me-2"></i> System Logs
            </a>
            <a class="nav-link mb-3"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">

            <!-- Dashboard Section -->
            <div id="dashboardSection">
                <div class="mb-4 text-center">
                    <h2 class="fw-bold">System Overview</h2>
                    <p class="text-muted">Complete system statistics and management controls</p>
                </div>

                <!-- Dashboard Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="card text-center p-3 shadow-sm">
                            <h6>Total Applications</h6>
                            <h4>1,247</h4>
                            <small class="text-success">+12% this month</small>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card text-center p-3 shadow-sm">
                            <h6>Active Users</h6>
                            <h4>89</h4>
                            <small>15 online now</small>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card text-center p-3 shadow-sm">
                            <h6>Admin Staff</h6>
                            <h4>12</h4>
                            <small>8 active today</small>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card text-center p-3 shadow-sm">
                            <h6>System Health</h6>
                            <h4 class="text-success">98%</h4>
                            <small class="text-success">All systems operational</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card p-3 shadow-sm">
                    <h5 class="mb-3">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success"><i class="bi bi-person-plus me-2"></i>Create New Admin</button>
                        <button class="btn btn-success"><i class="bi bi-phone me-2"></i>System Backup</button>
                        <button class="btn btn-success" onclick="showSystemLogs()"><i
                                class="bi bi-journal-text me-2"></i>View System Logs</button>
                    </div>
                </div>
            </div>

            <!-- User Management Section -->
            <div id="userManagementSection">
                <h3 class="fw-bold text-dark d-flex align-items-center" style="font-size: 1.25rem;">
                    <i class="bi bi-people me-2"></i> User Management
                </h3>

                <!-- Tabs + Add Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="admins-tab" data-bs-toggle="tab"
                                data-bs-target="#admins" type="button" role="tab">Admin</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="applicants-tab" data-bs-toggle="tab"
                                data-bs-target="#applicants" type="button" role="tab">Applicant</button>
                        </li>
                    </ul>

                    <!-- Add User Button -->
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal"
                        title="Add Account">
                        <i class="bi bi-person-plus me-2"></i> Add Account
                    </button>
                </div>

                <!-- Single Card containing both tables -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Admin Table -->
                            <div class="tab-pane fade show active" id="admins" role="tabpanel">
                                <div class="table-responsive">
                                    <table id="adminsTable" class="table table-striped table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include("db.php");
                                            $sql = "SELECT id, CONCAT(first_name,' ',last_name) AS fullname, email, role 
                                        FROM users 
                                        WHERE role = 'admin' AND role != 'superadmin'
                                        ORDER BY id DESC";
                                            $result = $conn->query($sql);
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr data-role='admin'>
                                            <td>{$row['id']}</td>
                                            <td>{$row['fullname']}</td>
                                            <td>{$row['email']}</td>
                                            <td>
                                                <button class='btn btn-sm btn-primary' title='Edit'><i class='bi bi-pencil'></i></button>
                                                <button class='btn btn-sm btn-danger' title='Delete'><i class='bi bi-trash'></i></button>
                                                <button class='btn btn-sm btn-warning reset-btn' 
                                                    title='Reset Password' 
                                                    data-user-name='{$row['fullname']}'>
                                                    <i class='bi bi-key'></i>
                                                </button>
                                            </td>
                                        </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center text-muted'>No admin users found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Applicant Table -->
                            <div class="tab-pane fade" id="applicants" role="tabpanel">
                                <div class="table-responsive">
                                    <table id="applicantsTable" class="table table-striped table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT id, CONCAT(first_name,' ',last_name) AS fullname, email, role 
                                        FROM users 
                                        WHERE role = 'Applicant' 
                                        ORDER BY id DESC";
                                            $result = $conn->query($sql);
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr data-role='applicant'>
                                            <td>{$row['id']}</td>
                                            <td>{$row['fullname']}</td>
                                            <td>{$row['email']}</td>
                                            <td>
                                                <button class='btn btn-sm btn-primary' title='Edit'><i class='bi bi-pencil'></i></button>
                                                <button class='btn btn-sm btn-danger' title='Delete'><i class='bi bi-trash'></i></button>
                                                <button class='btn btn-sm btn-warning reset-btn' 
                                                    title='Reset Password' 
                                                    data-user-name='{$row['fullname']}'>
                                                    <i class='bi bi-key'></i>
                                                </button>
                                            </td>
                                        </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center text-muted'>No applicants found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Reset Password Confirmation Modal -->
            <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Reset Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Are you sure you want to reset the password for <b id="resetUserName"></b>?</p>
                            <!-- Spinner hidden by default -->
                            <div id="resetLoading" class="d-none">
                                <div class="spinner-border text-warning" role="status"></div>
                                <span>Processing...</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-warning" id="confirmResetBtn">Yes, Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1">
                <div class="modal-dialog">
                    <form action="add_user.php" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <option value="applicant">Applicant</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Add User</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- System Settings Section -->
            <div id="systemSettingsSection" class="mt-0">
                <!-- Page Title -->
                <h3 class="fw-bold text-dark d-flex align-items-center" style="font-size: 1.25rem;">
                    <i class="bi bi-gear me-2"></i> System Settings
                </h3>

                <!-- Change System Name & Logo -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <form action="update_settings.php" method="POST" enctype="multipart/form-data">
                            <!-- Change System Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">System Name</label>
                                <input type="text" class="form-control" name="system_name"
                                    placeholder="Enter system name" required>
                            </div>

                            <!-- Change Logo -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Upload Logo</label>
                                <input type="file" class="form-control" name="logo">
                            </div>

                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>

                <!-- Change Super Admin Password -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <form action="superadmin_change_password.php" method="POST">
                            <!-- Current Password -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Password</label>
                                <input type="password" class="form-control" name="current_password"
                                    placeholder="Enter current password" required>
                            </div>

                            <!-- New Password -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">New Password</label>
                                <input type="password" class="form-control" name="new_password"
                                    placeholder="Enter new password" required>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Confirm New Password</label>
                                <input type="password" class="form-control" name="confirm_password"
                                    placeholder="Confirm new password" required>
                            </div>

                            <button type="submit" class="btn btn-warning">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- System Logs Section -->
            <div id="systemLogsSection" class="mt-0">
                <!-- Page Title -->
                <h3 class="fw-bold text-dark d-flex align-items-center" style="font-size: 1.20rem;">
                    <i class="bi bi-clock-history me-2"></i> System Logs
                </h3>

                <!-- Logs Card -->
                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- ✅ Date Filters inside card body -->
                        <form method="get" class="row g-3 align-items-end mb-4">
                            <div class="col-md-3">
                                <label for="dateFrom" class="form-label fw-semibold">Date From</label>
                                <input type="date" class="form-control" id="dateFrom" name="dateFrom">
                            </div>
                            <div class="col-md-3">
                                <label for="dateTo" class="form-label fw-semibold">Date To</label>
                                <input type="date" class="form-control" id="dateTo" name="dateTo">
                            </div>
                        </form>

                        <!-- Logs Table -->
                        <div class="table-responsive">
                            <table id="logsTable" class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>IP</th>
                                        <th class="px-4 text-nowrap">User ID</th>
                                        <th>Type</th>
                                        <th>Content</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include("db.php");

                                    // ✅ Filter query if date range is set
                                    $where = "";
                                    if (!empty($_GET['dateFrom']) && !empty($_GET['dateTo'])) {
                                        $dateFrom = $_GET['dateFrom'];
                                        $dateTo = $_GET['dateTo'];
                                        $where = "WHERE DATE(created_at) BETWEEN '$dateFrom' AND '$dateTo'";
                                    }

                                    $sql = "SELECT * FROM system_logs $where ORDER BY created_at DESC";
                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $ip = ($row['ip_address'] === '::1') ? '127.0.0.1' : $row['ip_address'];

                                            echo "<tr>
                                    <td>{$ip}</td>
                                    <td class='px-4'>{$row['user_id']}</td>
                                    <td>{$row['action_type']}</td>
                                    <td>{$row['content']}</td>
                                    <td style='white-space: nowrap;'>" . date("F j, Y", strtotime($row['created_at'])) . "</td> <!-- ✅ September 15, 2025 (one line) -->
                                    <td style='white-space: nowrap;'>" . date("g:i:s A", strtotime($row['created_at'])) . "</td> <!-- ✅ 1:25:55 PM (one line) -->
                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center text-muted'>No logs found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Logs Section -->
            <div id="systemLogsSection" class="mt-4">
                <!-- Page Title -->
                <div class="mb-3">
                    <h3 class="fw-bold text-dark">
                        <i class="bi bi-journal-text me-2"></i> System Logs
                    </h3>
                </div>

                <!-- Date Filters -->
                <form method="get" class="row g-3 align-items-end mb-3">
                    <div class="col-auto">
                        <label for="dateFrom" class="form-label fw-semibold">Date From</label>
                        <input type="date" class="form-control" id="dateFrom" name="dateFrom">
                    </div>
                    <div class="col-auto">
                        <label for="dateTo" class="form-label fw-semibold">Date To</label>
                        <input type="date" class="form-control" id="dateTo" name="dateTo">
                    </div>

                </form>

                <!-- Logs Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="logsTable" class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>IP</th>
                                        <th>User ID</th>
                                        <th>Type</th>
                                        <th>Content</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                        </div>
    </main>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="../javascript/logout.js"></script>
    <script src="../javascript/superadmin.js"></script>

    <script>
    $(document).ready(function () {
        // ✅ Show page only after everything is ready
        $("body").addClass("loaded");

        $('#usersTable, #adminsTable, #applicantsTable, #logsTable').DataTable({
            paging: true,
            searching: true,
            lengthChange: true,
            pageLength: 10,
            ordering: false
        });

        <?php if (isset($_SESSION['pass_status'])):
            $status = $_SESSION['pass_status']['status'];
            $message = $_SESSION['pass_status']['message'];
            unset($_SESSION['pass_status']);
            ?>
            Swal.fire({
                icon: '<?php echo $status; ?>',
                title: '<?php echo $status === "success" ? "Success!" : "Error!"; ?>',
                text: '<?php echo $message; ?>',
                confirmButtonColor: '#0d6efd'
            });
        <?php endif; ?>

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el);
        });

        let selectedUserName = '';
        let selectedUserId = '';

        $('#resetPasswordModal').on('show.bs.modal', function () {
            $('#resetLoading').hide();
            $('#confirmResetBtn').prop('disabled', false);
            $('#resetPasswordModal .btn-secondary').prop('disabled', false);
        });

        $('.reset-btn').on('click', function () {
            selectedUserName = $(this).data('user-name');
            selectedUserId = $(this).closest('tr').find('td:first').text();
            $('#resetUserName').text(selectedUserName);
            $('#resetPasswordModal').modal('show');
        });

        $('#confirmResetBtn').on('click', function () {
            if (!selectedUserId) return;
            $('#resetLoading').removeClass('d-none');
            $('#confirmResetBtn').prop('disabled', true);
            $('#resetPasswordModal .btn-secondary').prop('disabled', true);
            $.ajax({
                url: 'reset_password.php',
                method: 'POST',
                data: { user_id: selectedUserId },
                success: function () {
                    $('#resetLoading').addClass('d-none');
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Reset!',
                        text: `Password for ${selectedUserName} has been reset to default (password123).`,
                        confirmButtonColor: '#0d6efd'
                    });
                    $('#resetPasswordModal').modal('hide');
                },
                error: function () {
                    $('#resetLoading').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to reset password.',
                        confirmButtonColor: '#d33'
                    });
                },
                complete: function () {
                    $('#confirmResetBtn').prop('disabled', false);
                    $('#resetPasswordModal .btn-secondary').prop('disabled', false);
                }
            });
        });

        $('#sidebarToggle').on('click', function () {
            $('body').toggleClass('sidebar-visible');
            localStorage.setItem('sidebarState', $('body').hasClass('sidebar-visible') ? 'open' : 'closed');
        });

        if (localStorage.getItem('sidebarState') === 'open') {
            $('body').addClass('sidebar-visible');
        }

        $(document).on("click", function (e) {
            if ($(window).width() < 992) {
                if (!$(".sidebar, #sidebarToggle").is(e.target) &&
                    $(".sidebar, #sidebarToggle").has(e.target).length === 0) {
                    $("body").removeClass("sidebar-visible");
                    localStorage.setItem('sidebarState', 'closed');
                }
            }
        });

        let lastSection = localStorage.getItem("lastSection") || "#dashboardSection";
        showSection(lastSection);
    });

    function filterUserTable(role) {
        $("#usersTable tbody tr").each(function () {
            var userRole = $(this).data("role");
            $(this).toggle(role === "All" || userRole === role);
        });
    }

    function showSection(sectionId) {
        $('#dashboardSection, #userManagementSection, #systemSettingsSection, #systemLogsSection').hide();
        $(sectionId).show();
        $('.sidebar .nav-link, .offcanvas-body .nav-link').removeClass('active');
        $(`.sidebar .nav-link[onclick="showSection('${sectionId}')"],
        .offcanvas-body .nav-link[onclick="showSection('${sectionId}')"]`).addClass('active');
        localStorage.setItem("lastSection", sectionId);
    }
</script>

</body>

</html>