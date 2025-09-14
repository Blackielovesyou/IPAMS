<?php
session_start();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect if not logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    header("Location: loginform.php");
    exit;
}

// Restrict access to superadmin role only
if ($_SESSION['role'] !== 'superadmin') {
    header("Location: super_admin.php"); // redirect non-superadmin users
    exit;
}

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
        body {
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
        }

        .offcanvas-start {
            max-width: 300px;
        }

        .sidebar .nav-link,
        .offcanvas-body .nav-link {
            font-weight: 600;
            color: #000 !important;
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
        }

        .sidebar .nav-link:hover,
        .offcanvas-body .nav-link:hover {
            background-color: #f0f0f0;
            color: #000 !important;
        }

        .sidebar .nav-link.active,
        .offcanvas-body .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
        }

        .main-content {
            margin-left: 0;
            padding-top: 80px;
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: 280px;
            }
        }

        #systemLogsSection {
            display: none;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand fw-bold d-none d-lg-flex align-items-center" href="#">
                <i class="bi bi-building me-2"></i> SuperAdmin Dashboard
            </a>
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

    <!-- Sidebar (desktop) -->
    <nav class="d-none d-lg-block bg-white sidebar border-end position-fixed h-100 pt-3">
        <div class="nav flex-column px-3">
            <a class="nav-link mb-3" id="dashboardLink" onclick="showDashboard()"><i
                    class="bi bi-grid-fill me-2"></i>Dashboard
                Overview</a>
            <a class="nav-link mb-3" id="applicationLink" onclick="showDashboard()"><i
                    class="bi bi-file-earmark-text-fill me-2"></i>Application Management</a>
            <a class="nav-link mb-3"><i class="bi bi-person-fill me-2"></i>User Management</a>
            <a class="nav-link mb-3"><i class="bi bi-gear-fill me-2"></i>System Settings</a>
            <a class="nav-link mb-3"><i class="bi bi-shield-lock-fill me-2"></i>Security Access</a>
            <a class="nav-link mb-3" id="systemLogsLink" onclick="showSystemLogs()"><i
                    class="bi bi-journal-text me-2"></i>System Logs</a>
            <a class="nav-link mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Reports & Analytics</a>
        </div>
    </nav>

    <!-- Offcanvas Sidebar (mobile) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="nav flex-column">
                <a class="nav-link mb-3" id="dashboardLinkMobile" onclick="showDashboard()"
                    data-bs-dismiss="offcanvas"><i class="bi bi-grid-fill me-2"></i>Dashboard Overview</a>
                <a class="nav-link mb-3" id="applicationLinkMobile" onclick="showDashboard()"
                    data-bs-dismiss="offcanvas"><i class="bi bi-file-earmark-text-fill me-2"></i>Application
                    Management</a>
                <a class="nav-link mb-3"><i class="bi bi-person-fill me-2"></i>User Management</a>
                <a class="nav-link mb-3"><i class="bi bi-gear-fill me-2"></i>System Settings</a>
                <a class="nav-link mb-3"><i class="bi bi-shield-lock-fill me-2"></i>Security Access</a>
                <a class="nav-link mb-3" id="systemLogsLinkMobile" onclick="showSystemLogs()"
                    data-bs-dismiss="offcanvas"><i class="bi bi-journal-text me-2"></i>System Logs</a>
                <a class="nav-link mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Reports & Analytics</a>
            </div>
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

            

            <!-- System Logs Section -->
            <div id="systemLogsSection" class="mt-4">
                <!-- Page Title -->
                <div class="mb-3">
                    <h3 class="fw-bold text-dark">
                        <i class="bi bi-journal-text me-2"></i> Users Logging / Audit Trails
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
                    <div class="col-auto">
                        <button type="button" id="filterBtn" class="btn btn-primary">Filter</button>
                        <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
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
                                    <?php
                                    include("db.php");
                                    $sql = "SELECT * FROM system_logs ORDER BY created_at DESC";
                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$row['ip_address']}</td>
                                                <td>{$row['user_id']}</td>
                                                <td>{$row['action_type']}</td>
                                                <td>{$row['content']}</td>
                                                <td>" . date("Y-m-d", strtotime($row['created_at'])) . "</td>
                                                <td>" . date("H:i:s", strtotime($row['created_at'])) . "</td>
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

        </div>
    </main>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="../javascript/logout.js"></script>

<script>
    function setActiveLink(activeLinkId, activeLinkIdMobile) {
        $(".sidebar .nav-link, .offcanvas-body .nav-link").removeClass("active");
        $("#" + activeLinkId).addClass("active");
        $("#" + activeLinkIdMobile).addClass("active");
    }

    function showSystemLogs() {
        document.getElementById("dashboardSection").style.display = "none";
        document.getElementById("systemLogsSection").style.display = "block";
        setActiveLink("systemLogsLink", "systemLogsLinkMobile");
    }

    function showDashboard() {
        document.getElementById("dashboardSection").style.display = "block";
        document.getElementById("systemLogsSection").style.display = "none";
        setActiveLink("applicationLink", "applicationLinkMobile");
    }

    $(document).ready(function () {
        // Initialize DataTable
        $('#logsTable').DataTable({
            "order": [[4, "desc"]]
        });

        // Highlight Application Management on initial page load
        setActiveLink("applicationLink", "applicationLinkMobile");
    });
</script>
</body>

</html>