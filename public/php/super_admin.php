<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBO SuperAdmin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            /* prevent body scroll */
        }

        /* Fix navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            border-bottom: 1px solid #dee2e6;
            /* 👈 line instead of shadow */
        }

        /* Fix sidebar */
        .sidebar {
            position: fixed;
            top: 56px;
            /* height of navbar */
            left: 0;
            bottom: 0;
            width: 240px;
            /* widened for longer text */
            background: #fff;
            border-right: 1px solid #dee2e6;
            padding: 1rem;
        }

        /* Sidebar links stay in one line with ellipsis */
        /* Sidebar links – allow wrapping, no cutoff */
        .sidebar .nav-link,
        #offcanvasSidebar .nav-link {
            white-space: normal;
            /* allow wrapping */
            overflow: visible;
            /* no hidden text */
            text-overflow: unset;
            /* remove ellipsis */
            display: block;
            max-width: 100%;
            line-height: 1.3;
            /* tighter spacing for wrapped text */
        }


        .main-content {
            position: absolute;
            top: 56px;
            /* below navbar */
            left: 0;
            right: 0;
            bottom: 0;
            overflow-y: auto;
            padding: 2rem 1rem 1rem;
            /* default desktop spacing */
        }

        @media (min-width: 992px) {
            .main-content {
                left: 240px;
                /* match widened sidebar */
            }
        }

        /* More breathing room on mobile */
        @media (max-width: 991.98px) {
            .main-content {
                padding-top: 4rem;
                /* increase gap below navbar */
            }
        }


        #offcanvasSidebar {
            width: 65% !important;
            /* reduced */
            max-width: 300px;
            /* keeps it tidy on larger phones/tablets */
            border-radius: 0 10px 10px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.15);
        }
    </style>

</head>

<body class="bg-light">

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center" style="flex-wrap: nowrap;">

            <!-- Left: Icon / Mobile toggle + Text -->
            <div class="d-flex align-items-center" style="min-width: 0;">
                <!-- Desktop: building icon -->
                <img src="https://img.icons8.com/ios-filled/50/000000/building.png" alt="Building Icon" width="40"
                    class="me-2 d-none d-lg-inline-block">

                <!-- Mobile: burger -->
                <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Title -->
                <div class="d-flex flex-column">
                    <span class="fw-bold mb-0" style="font-size: clamp(0.9rem, 2vw, 1.25rem);">System Overview</span>
                    <small class="text-muted" style="font-size: clamp(0.7rem, 1.5vw, 0.9rem);">
                        Complete system statistics and management controls
                    </small>
                </div>
            </div>

            <!-- Right: User Info + Logout -->
            <div class="d-flex flex-column flex-lg-row align-items-end align-items-lg-center gap-1 gap-lg-2">
                <div class="text-end">
                    <div class="fw-semibold" style="font-size: clamp(0.9rem, 2vw, 1rem);">Administrator</div>
                    <small class="text-muted" style="font-size: clamp(0.7rem, 1.5vw, 0.85rem);">OBO Staff</small>
                </div>
                <button class="btn btn-primary btn-sm flex-shrink-0">Logout</button>
            </div>
        </div>
    </nav>

    <!-- Sidebar (desktop) -->
    <nav class="sidebar d-none d-lg-block">
        <div class="nav flex-column">
            <a href="#" class="nav-link text-dark d-flex align-items-center mb-2"><i
                    class="bi bi-grid-fill me-2"></i>Dashboard Overview</a>
            <a href="#" class="nav-link text-dark d-flex align-items-center mb-2"><i
                    class="bi bi-file-earmark-text-fill me-2"></i>Application Management</a>
            <a href="#" class="nav-link text-dark d-flex align-items-center mb-2"><i
                    class="bi bi-person-fill me-2"></i>User Management</a>
            <a href="#" class="nav-link text-dark d-flex align-items-center mb-2"><i
                    class="bi bi-gear-fill me-2"></i>System Settings</a>
            <a href="#" class="nav-link text-dark d-flex align-items-center mb-2"><i
                    class="bi bi-shield-lock-fill me-2"></i>Security & Access</a>
            <a href="#" class="nav-link text-dark d-flex align-items-center mb-2"><i
                    class="bi bi-journal-text me-2"></i>System Logs</a>
            <a href="#" class="nav-link text-dark d-flex align-items-center mb-2"><i
                    class="bi bi-bar-chart-fill me-2"></i>Reports & Analytics</a>
        </div>
    </nav>

    <!-- Offcanvas Sidebar (mobile) -->
    <div class="offcanvas offcanvas-start bg-white text-dark" tabindex="-1" id="offcanvasSidebar"
        aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title d-flex align-items-center gap-2" id="offcanvasSidebarLabel">
                <img src="https://img.icons8.com/ios-filled/30/000000/building.png" alt="Icon" width="30"> Menu
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="nav flex-column">
                <a href="#" class="nav-link d-flex align-items-center mb-2"><i
                        class="bi bi-grid-fill me-2"></i>Dashboard Overview</a>
                <a href="#" class="nav-link d-flex align-items-center mb-2"><i
                        class="bi bi-file-earmark-text-fill me-2"></i>Application Management</a>
                <a href="#" class="nav-link d-flex align-items-center mb-2"><i class="bi bi-person-fill me-2"></i>User
                    Management</a>
                <a href="#" class="nav-link d-flex align-items-center mb-2"><i class="bi bi-gear-fill me-2"></i>System
                    Settings</a>
                <a href="#" class="nav-link d-flex align-items-center mb-2"><i
                        class="bi bi-shield-lock-fill me-2"></i>Security & Access</a>
                <a href="#" class="nav-link d-flex align-items-center mb-2"><i
                        class="bi bi-journal-text me-2"></i>System Logs</a>
                <a href="#" class="nav-link d-flex align-items-center mb-2"><i
                        class="bi bi-bar-chart-fill me-2"></i>Reports & Analytics</a>
            </div>
        </div>
    </div>

    <!-- Main Content (scrollable) -->
    <main class="main-content">
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <h6>Total Applications</h6>
                    <h4>1,247</h4>
                    <small class="text-success">+12% this month</small>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <h6>Active Users</h6>
                    <h4>89</h4>
                    <small>15 online now</small>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <h6>Admin Staff</h6>
                    <h4>12</h4>
                    <small>8 active today</small>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <h6>System Health</h6>
                    <h4 class="text-success">98%</h4>
                    <small class="text-success">All systems operational</small>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-success d-flex align-items-center justify-content-center"><i
                        class="bi bi-person-plus me-2"></i>Create New Admin</button>
                <button class="btn btn-success d-flex align-items-center justify-content-center"><i
                        class="bi bi-phone me-2"></i>System Backup</button>
                <button class="btn btn-success d-flex align-items-center justify-content-center"><i
                        class="bi bi-journal-text me-2"></i>View System Logs</button>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>