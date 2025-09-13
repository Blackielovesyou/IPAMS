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

// Restrict access to admin role only
if ($_SESSION['role'] !== 'admin') {
    header("Location: main_page.php"); // redirect non-admin users
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
    <style>
        body {
            min-height: 100vh;
        }

        /* Sidebar width adjustments */
        .sidebar {
            width: 280px;
        }

        .offcanvas-start {
            max-width: 350px;
        }

        /* Sidebar links bold black */
        .sidebar .nav-link,
        .offcanvas-body .nav-link {
            font-weight: 600;
            color: #000 !important;
        }

        /* Offcanvas menu title muted */
        .offcanvas-title {
            color: #6c757d;
        }

        /* Main content margin for desktop + reduced space from navbar */
        .main-content {
            margin-left: 0;
            padding-top: 80px;
            /* reduced from 120px */
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: 280px;
                padding-top: 80px;
                /* reduced from 120px */
            }
        }
    </style>

</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- Mobile toggle -->
            <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                <i class="bi bi-list"></i>
            </button>

            <!-- Brand -->
            <div class="d-none d-lg-flex align-items-center">
                <img src="https://img.icons8.com/ios-filled/50/000000/building.png" width="40" class="me-2"
                    alt="Building Icon">
                <div class="d-flex flex-column">
                    <span class="fw-bold mb-0">System Overview</span>
                    <small class="text-muted">Complete system statistics and management controls</small>
                </div>
            </div>

            <!-- User Info -->
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-2">
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

        </div>
    </nav>

    <!-- Sidebar (desktop) -->
    <nav class="d-none d-lg-block bg-white sidebar border-end position-fixed h-100 pt-3">
        <div class="nav flex-column px-3">
            <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-grid-fill me-2"></i>Dashboard Overview</a>
            <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-file-earmark-text-fill me-2"></i>Application
                Management</a>
            <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-person-fill me-2"></i>User Management</a>
            <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-gear-fill me-2"></i>System Settings</a>
            <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-shield-lock-fill me-2"></i>Security Access</a>
            <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-journal-text me-2"></i>System Logs</a>
            <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-bar-chart-fill me-2"></i>Reports & Analytics</a>
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
                <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-grid-fill me-2"></i>Dashboard Overview</a>
                <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-file-earmark-text-fill me-2"></i>Application
                    Management</a>
                <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-person-fill me-2"></i>User Management</a>
                <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-gear-fill me-2"></i>System Settings</a>
                <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-shield-lock-fill me-2"></i>Security Access</a>
                <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-journal-text me-2"></i>System Logs</a>
                <a href="#" class="nav-link mb-3 fs-6"><i class="bi bi-bar-chart-fill me-2"></i>Reports & Analytics</a>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <main class="main-content">
        <div class="container-fluid">

            <!-- System Overview Header -->
            <div class="mb-4 text-center">
                <h2 class="fw-bold">System Overview</h2>
                <p class="text-muted mb-0">Complete system statistics and management controls</p>
            </div>

            <!-- Dashboard Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card text-center p-3">
                        <h6>Total Applications</h6>
                        <h4>1,247</h4>
                        <small class="text-success">+12% this month</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card text-center p-3">
                        <h6>Active Users</h6>
                        <h4>89</h4>
                        <small>15 online now</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card text-center p-3">
                        <h6>Admin Staff</h6>
                        <h4>12</h4>
                        <small>8 active today</small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card text-center p-3">
                        <h6>System Health</h6>
                        <h4 class="text-success">98%</h4>
                        <small class="text-success">All systems operational</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-3">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <button class="btn btn-success d-flex align-items-center justify-content-center">
                        <i class="bi bi-person-plus me-2"></i>Create New Admin
                    </button>
                    <button class="btn btn-success d-flex align-items-center justify-content-center">
                        <i class="bi bi-phone me-2"></i>System Backup
                    </button>
                    <button class="btn btn-success d-flex align-items-center justify-content-center">
                        <i class="bi bi-journal-text me-2"></i>View System Logs
                    </button>
                </div>
            </div>

        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../javascript/logout.js"></script>

</body>

</html>