<?php
session_start();
require_once "db.php"; // DB connection

// No caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Auth check
if (!isset($_SESSION['id'], $_SESSION['role'])) {
    echo "<script>
        localStorage.setItem('lastPage', window.location.href);
        window.history.back();
    </script>";
    exit;
}

// Role check – only superadmin allowed
if ($_SESSION['role'] !== 'superadmin') {
    echo "<script>
        localStorage.setItem('lastPage', window.location.href);
        window.history.back();
    </script>";
    exit;
}

$userRole = $_SESSION['role'];

// App name
$appName = "MyApp";
$sqlApp = "SELECT appname FROM app_info ORDER BY id ASC LIMIT 1";
if ($resultApp = $conn->query($sqlApp)) {
    if ($resultApp->num_rows > 0) {
        $rowApp = $resultApp->fetch_assoc();
        $appName = htmlspecialchars($rowApp['appname']);
    }
    $resultApp->free();
}

// ✅ Fetch permit applications directly (no need to join users table)
$applications = [];
$sqlApps = "SELECT id, application_number, permit_type, full_name, email, status, created_at
            FROM permit_applications
            ORDER BY created_at DESC";
$resultApps = $conn->query($sqlApps);
if ($resultApps) {
    while ($row = $resultApps->fetch_assoc()) {
        $applications[] = $row;
    }
    $resultApps->free();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBO SuperAdmin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/superadmin.css">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <!-- ✅ Single Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top shadow-sm">
        <div class="container-fluid d-flex align-items-center">
            <!-- Burger Menu -->
            <button class="btn btn-outline-secondary me-2" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-none d-sm-flex flex-column dashboard-title" style="min-width: 0;">
                <span style="font-size: clamp(0.9rem, 2vw, 1.25rem);" class="fw-bold mb-0">
                    <?php echo htmlspecialchars($appName); ?>
                </span>
            </div>

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
            <a class="nav-link mb-3" onclick="showSection('#permitApplicationsSection')">
                <i class="bi bi-clipboard-check-fill me-2"></i> Permit Applications
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

            <!-- Permit Applications Section (hidden by default) -->
            <div id="permitApplicationsSection" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <h3 class="fw-bold text-dark d-flex align-items-center mb-2" style="font-size: 1.4rem;">
                        <i class="bi bi-clipboard-check-fill text-primary me-2"></i>
                        Permit Applications
                    </h3>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="applicationsTable" class="table table-hover align-middle text-center mb-0">
                                <thead class="table-primary text-dark">
                                    <tr>
                                        <th>Application No.</th>
                                        <th>Applicant</th>
                                        <th>Permit Type</th>
                                        <th>Status</th>
                                        <th>Submitted On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($applications)): ?>
                                        <?php foreach ($applications as $app): ?>
                                            <tr id="appRow-<?= $app['id'] ?>">
                                                <td>
                                                    <?php
                                                    $year = date("Y", strtotime($app['created_at']));
                                                    $prefix = match ($app['permit_type']) {
                                                        'building' => 'BP',
                                                        'electrical' => 'EP',
                                                        'plumbing' => 'PP',
                                                        'occupancy' => 'OP',
                                                        default => ''
                                                    };
                                                    echo htmlspecialchars($prefix . '-' . $year . '-' . $app['application_number']);
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($app['full_name']) ?></td>
                                                <td><?= ucfirst(htmlspecialchars($app['permit_type'])) ?></td>
                                                <td>
                                                    <span
                                                        class="badge rounded-pill px-3 py-2 
                                            bg-<?php echo $app['status'] === 'approved' ? 'success' : ($app['status'] === 'rejected' ? 'danger' : 'warning'); ?>"
                                                        id="status-<?= $app['id'] ?>">
                                                        <?= ucfirst(htmlspecialchars($app['status'])) ?>
                                                    </span>
                                                </td>
                                                <td><?= date("M d, Y", strtotime($app['created_at'])) ?></td>
                                                <td class="text-center" id="actions-<?= $app['id'] ?>">
                                                    <?php if (!in_array($app['status'], ['approved', 'rejected'])): ?>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary view-btn"
                                                                data-id="<?= $app['id'] ?>" title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </button>

                                                            <button class="btn btn-sm btn-outline-success approve-btn"
                                                                data-id="<?= $app['id'] ?>" title="Approve" data-bs-toggle="modal"
                                                                data-bs-target="#approveModal">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger reject-btn"
                                                                data-id="<?= $app['id'] ?>" title="Reject" data-bs-toggle="modal"
                                                                data-bs-target="#rejectModal">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                No permit applications found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <!-- User Management Section -->
            <div id="userManagementSection">
                <h3 class="fw-bold text-dark d-flex align-items-center" style="font-size: 1.25rem;">
                    <i class="bi bi-people me-2"></i> User Management
                </h3>

                <!-- Tabs + Add Button -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
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
                    <button class="btn btn-success mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#addUserModal"
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
                                                <button class='btn btn-sm btn-primary edit-btn' title='Edit'>
                                                    <i class='bi bi-pencil'></i>
                                                </button>
                                                <button class='btn btn-sm btn-danger delete-btn' title='Delete'>
                                                    <i class='bi bi-trash'></i>
                                                </button>
                                                <button class='btn btn-sm btn-warning reset-btn' title='Reset Password' 
                                                        data-user-name='{$row['fullname']}' data-user-email='{$row['email']}' data-user-id='{$row['id']}'>
                                                    <i class='bi bi-key'></i>
                                                </button>
                                                <!-- ✅ Access Button (Admin Only) -->
                                                <button class='btn btn-sm btn-info access-btn' title='Manage Access' 
                                                        data-user-id='{$row['id']}' data-user-name='{$row['fullname']}'>
                                                    <i class='bi bi-shield-lock'></i>
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
                                                <button class='btn btn-sm btn-primary edit-btn' title='Edit'><i class='bi bi-pencil'></i></button>
                                                <button class='btn btn-sm btn-danger delete-btn' title='Delete'><i class='bi bi-trash'></i></button>
                                                <button class='btn btn-sm btn-warning reset-btn' title='Reset Password' data-user-name='{$row['fullname']}' data-user-email='{$row['email']}'  data-user-id='{$row['id']}'><i class='bi bi-key'></i></button>
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


            <!-- Edit User Modal -->
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm">
                                <input type="hidden" id="editUserId">
                                <div class="mb-3">
                                    <label for="editFullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="editFullName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editEmail" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong id="deleteUserName"></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
                                    <option value="admin">admin</option>
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

            <!-- Access Modal -->
            <div class="modal fade" id="accessModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="accessForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Manage Access for <span id="accessUserName"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="user_id" id="accessUserId">

                                <!-- Checkboxes for modules -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="permitAccess" name="modules[]"
                                        value="permit_applications">
                                    <label class="form-check-label" for="permitAccess">Permit Applications</label>
                                </div>
                                <!-- you can add more modules here later -->
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Confirmation Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to reject this application?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmRejectBtn">Reject</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approve Confirmation Modal -->
            <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="approveModalLabel">Confirm Approval</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to approve this application?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" id="confirmApproveBtn">Approve</button>
                        </div>
                    </div>
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
                                    placeholder="Enter system name" value="<?php echo htmlspecialchars($appName); ?>"
                                    required>
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

    </main>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="../javascript/logout.js"></script>
    <script src="../javascript/superadmin.js"></script>

    <!-- SWEETALERT PASSWORD STATUS (PHP-generated) -->
    <?php if (isset($_SESSION['pass_status'])):
        $status = $_SESSION['pass_status']['status'];
        $message = $_SESSION['pass_status']['message'];
        unset($_SESSION['pass_status']);
        ?>
        <script>
            Swal.fire({
                icon: '<?php echo $status; ?>',
                title: '<?php echo $status === "success" ? "Success!" : "Error!"; ?>',
                text: '<?php echo $message; ?>',
                confirmButtonColor: '#0d6efd'
            });
        </script>
    <?php endif; ?>

</body>

</html>