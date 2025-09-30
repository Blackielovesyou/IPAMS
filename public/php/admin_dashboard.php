<?php
session_start();
include("db.php"); // Include your DB connection

// Prevent caching (for logout/back button issue)
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

// Only supeadmin can access
if ($_SESSION['role'] !== 'admin') {
    echo "<script>
        localStorage.setItem('lastPage', window.location.href);
        window.history.back();
    </script>";
    exit;
}

// Get the role from session
$userRole = $_SESSION['role'];

// Fetch all permit applications from database
$applications = [];
$sql = "SELECT * FROM permit_applications ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

// Fetch app name from database
$appName = "MyApp"; // fallback
$sqlApp = "SELECT appname FROM app_info ORDER BY id LIMIT 1";
$resultApp = $conn->query($sqlApp);

if ($resultApp && $resultApp->num_rows > 0) {
    $rowApp = $resultApp->fetch_assoc();
    $appName = $rowApp['appname'];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBO Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ✅ Hide page until fully styled */
        body {
            min-height: 100vh;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease-in;
            overflow-x: hidden;
            overflow-y: auto;
        }

        body.loaded {
            visibility: visible;
            opacity: 1;
        }

        /* ✅ Sidebar */
        .sidebar {
            position: fixed;
            top: 56px;
            /* navbar height */
            left: -280px;
            /* hidden by default */
            width: 280px;
            height: 100%;
            background: #fff;
            border-right: 1px solid #ddd;
            transition: left 0.3s ease;
            z-index: 2000;
            /* above main content */
            overflow-y: auto;
            padding: 1rem 0;
        }

        body.sidebar-visible .sidebar {
            left: 0;
        }

        /* ✅ Sidebar links */
        .sidebar .nav-link {
            font-weight: 600;
            color: #000 !important;
            padding: 12px 20px;
            display: block;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
        }

        /* ✅ Main content */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
            padding: 70px 15px 20px 15px;
            /* space for navbar */
        }

        @media (min-width: 992px) {
            body.sidebar-visible .main-content {
                margin-left: 280px;
            }
        }

        /* ✅ Responsive Sidebar for Mobile */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -250px;
                width: 250px;
                height: calc(100% - 56px);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            }

            body.sidebar-visible .sidebar {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 70px 15px 20px 15px;
            }
        }

        /* ✅ Dashboard cards stack on mobile */
        @media (max-width: 575.98px) {
            .row .col-6.col-lg-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* ✅ Table column widths */
        .table th.id {
            width: 5%;
        }

        .table th.applicant {
            width: 15%;
        }

        .table th.type {
            width: 10%;
        }

        .table th.contact {
            width: 10%;
        }

        .table th.email {
            width: 18%;
        }

        .table th.date {
            width: 15%;
        }

        .table th.status {
            width: 10%;
        }

        .table th.action {
            width: 0%;
        }

        /* ✅ Remove DataTables sorting arrows */
        table.dataTable thead th::after {
            content: "" !important;
            display: none !important;
        }

        /* ✅ Table buttons wrap on small screens */
        .table td .btn {
            white-space: nowrap;
            margin-bottom: 5px;
        }

        /* ✅ Modals & Forms mobile fix */
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

        /* ✅ Navbar dashboard title hidden on XS */
        @media (max-width: 576px) {
            .navbar .dashboard-title {
                display: none;
            }
        }
    </style>


</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white border-bottom shadow-sm fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center" style="flex-wrap: nowrap;">

            <!-- ✅ Left: Burger Button + Title -->
            <div class="d-flex align-items-center" style="min-width: 0;">
                <button class="btn btn-outline-secondary me-2" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-none d-sm-flex flex-column dashboard-title" style="min-width: 0;">
                    <span style="font-size: clamp(0.9rem, 2vw, 1.25rem);" class="fw-bold mb-0">
                        <?php echo htmlspecialchars($appName); ?>
                    </span>
                </div>

            </div>

            <!-- ✅ Right: Role Dropdown -->
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


    <!-- Sidebar -->
    <nav class="sidebar bg-white text-dark p-3">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="#" class="nav-link active" data-section="dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-section="applications">Permit Applications</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-section="reports">Reports</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-section="settings">Settings</a>
        </li>
    </ul>
</nav>


    <div class="main-content">

        <!-- ✅ Dashboard Section -->
        <div id="dashboard" class="content-section">

            <!-- ✅ Dashboard Title -->
            <div class="row px-3">
                <h4 class="fw-bold mb-3">Dashboard Overview</h4>
            </div>

            <!-- ✅ Stats Cards -->
            <div class="row text-center g-3 mb-4 px-3">
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm text-success bg-success bg-opacity-10 border-0 p-4 h-100">
                        <h6 class="fw-bold mb-2">Pending Review</h6>
                        <p class="h3 mb-0">
                            <?php echo count(array_filter($applications, fn($a) => $a['permit_type'] === 'building')); ?>
                        </p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm text-warning bg-warning bg-opacity-10 border-0 p-4 h-100">
                        <h6 class="fw-bold mb-2">For Inspection</h6>
                        <p class="h3 mb-0">
                            <?php echo count(array_filter($applications, fn($a) => $a['permit_type'] === 'electrical')); ?>
                        </p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm text-info bg-info bg-opacity-10 border-0 p-4 h-100">
                        <h6 class="fw-bold mb-2">Approved Today</h6>
                        <p class="h3 mb-0">
                            <?php
                            $today = date('Y-m-d');
                            echo count(array_filter($applications, fn($a) => substr($a['created_at'], 0, 10) === $today));
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm text-primary bg-primary bg-opacity-10 border-0 p-4 h-100">
                        <h6 class="fw-bold mb-2">Total Applications</h6>
                        <p class="h3 mb-0"><?php echo count($applications); ?></p>
                    </div>
                </div>
            </div>

            <!-- ✅ Latest Applications Table -->
            <div class="row px-3 mt-5 mb-5">
                <h4 class="fw-bold mb-3">Latest Permit Applications</h4>
                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-striped table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="id">ID</th>
                                <th class="applicant">Applicant</th>
                                <th class="type">Type</th>
                                <th class="date">Date Submitted</th>
                                <th class="status">Status</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody><?php
                        if (!empty($applications)):
                            $latestFive = array_slice($applications, 0, 5);
                            foreach ($latestFive as $app): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $year = date("Y", strtotime($app['created_at']));
                                            $prefix = '';
                                            $extra = '';

                                            switch ($app['permit_type']) {
                                                case 'building':
                                                    $prefix = 'BP';
                                                    $extra = $app['construction_type'] ?? '';
                                                    break;
                                                case 'electrical':
                                                    $prefix = 'EP';
                                                    $extra = $app['installation_type'] ?? '';
                                                    break;
                                                case 'plumbing':
                                                    $prefix = 'PP';
                                                    $extra = $app['installation_type'] ?? '';
                                                    break;
                                                case 'occupancy':
                                                    $prefix = 'OP';
                                                    $extra = $app['construction_type'] ?? '';
                                                    break;
                                            }

                                            // Only include extra if it exists
                                            $formatted = $prefix . '-' . $year . '-' . $app['application_number'];
                                            if (!empty($extra)) {
                                                $formatted .= '-' . $extra;
                                            }

                                            echo htmlspecialchars($formatted);
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($app['permit_type'])); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($app['created_at'])); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($app['status'])); ?></td>
                                        <td>
                                            <a href="review.php?id=<?php echo $app['id']; ?>"
                                                class="btn btn-primary btn-sm">Review</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No recent applications.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

        <!-- ✅ Permit Applications Section -->
        <div id="applications" class="content-section d-none">
            <div class="row px-3 mb-5">
                <h4 class="fw-bold mb-3">All Permit Applications</h4>
                <div class="table-responsive shadow-sm rounded">
                    <table id="applicationsTable"
                        class="table table-striped table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="id">ID</th>
                                <th class="applicant">Applicant</th>
                                <th class="type">Type</th>
                                <th class="date">Date Submitted</th>
                                <th class="status">Status</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody><?php if (!empty($applications)):
                            foreach ($applications as $app): ?>
                                    <tr>
                                        <td data-id="<?php echo htmlspecialchars($app['application_number']); ?>">
                                            <?php
                                            $year = date("Y", strtotime($app['created_at']));
                                            $prefix = '';
                                            $extra = '';

                                            switch ($app['permit_type']) {
                                                case 'building':
                                                    $prefix = 'BP';
                                                    $extra = $app['construction_type'] ?? '';
                                                    break;
                                                case 'electrical':
                                                    $prefix = 'EP';
                                                    $extra = $app['installation_type'] ?? '';
                                                    break;
                                                case 'plumbing':
                                                    $prefix = 'PP';
                                                    $extra = $app['installation_type'] ?? '';
                                                    break;
                                                case 'occupancy':
                                                    $prefix = 'OP';
                                                    $extra = $app['construction_type'] ?? '';
                                                    break;
                                            }

                                            $formatted = $prefix . '-' . $year . '-' . $app['application_number'];
                                            if (!empty($extra)) {
                                                $formatted .= '-' . $extra;
                                            }

                                            echo htmlspecialchars($formatted);
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($app['permit_type'])); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($app['created_at'])); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($app['status'])); ?></td>
                                        <td>
                                            <a href="review.php?id=<?php echo $app['id']; ?>" class="btn btn-primary btn-sm">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;
                        else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No applications found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- ✅ Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../javascript/logout.js"></script>

        <!-- ✅ Full Updated Script -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.body.classList.add("loaded")
                const sidebarToggle = document.getElementById("sidebarToggle")
                const sidebar = document.querySelector(".sidebar")
                const tableRows = document.querySelectorAll("#applicationsTable tbody tr")

                if (sidebarToggle) {
                    sidebarToggle.addEventListener("click", function () {
                        document.body.classList.toggle("sidebar-visible")
                        localStorage.setItem("sidebarState", document.body.classList.contains("sidebar-visible") ? "open" : "closed")
                    })
                }

                if (localStorage.getItem("sidebarState") === "open") {
                    document.body.classList.add("sidebar-visible")
                }

                document.addEventListener("click", function (e) {
                    if (window.innerWidth < 992 && sidebar && sidebarToggle) {
                        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                            document.body.classList.remove("sidebar-visible")
                            localStorage.setItem("sidebarState", "closed")
                        }
                    }
                })

                tableRows.forEach((row, index) => { if (index >= 5) row.style.display = "none" })

                document.querySelectorAll(".sidebar .nav-link").forEach(link => {
                    link.addEventListener("click", function (e) {
                        e.preventDefault()
                        document.querySelectorAll(".sidebar .nav-link").forEach(l => l.classList.remove("active"))
                        this.classList.add("active")
                        document.querySelectorAll(".content-section").forEach(sec => sec.classList.add("d-none"))
                        const section = this.getAttribute("data-section")
                        const target = document.getElementById(section)
                        if (target) target.classList.remove("d-none")
                        if (section === "applications") { tableRows.forEach(row => row.style.display = "") }
                        if (section === "dashboard") { tableRows.forEach((row, index) => { row.style.display = index < 5 ? "" : "none" }) }
                    })
                })

                const typeFilter = document.getElementById("typeFilter")
                const statusFilter = document.getElementById("statusFilter")
                const searchBtn = document.getElementById("searchBtn")
                const searchInput = document.getElementById("searchInput")
                const resetRows = () => tableRows.forEach(row => (row.style.display = ""))

                if (typeFilter) {
                    typeFilter.addEventListener("change", function () {
                        resetRows()
                        const filterValue = this.value.toLowerCase()
                        tableRows.forEach(row => {
                            const typeCell = row.cells[2]?.textContent.toLowerCase() || ""
                            if (filterValue !== "all" && typeCell !== filterValue) { row.style.display = "none" }
                        })
                    })
                }

                if (statusFilter) {
                    statusFilter.addEventListener("change", function () {
                        resetRows()
                        const filterValue = this.value.toLowerCase()
                        tableRows.forEach(row => {
                            const statusCell = row.cells[6]?.textContent.toLowerCase() || ""
                            if (filterValue !== "all" && statusCell !== filterValue) { row.style.display = "none" }
                        })
                    })
                }

                if (searchBtn && searchInput) {
                    const doSearch = () => {
                        const searchValue = searchInput.value.trim().toLowerCase()
                        tableRows.forEach(row => {
                            const appNum = row.querySelector("td[data-id]")?.getAttribute("data-id")?.toLowerCase() || ""
                            row.style.display = (searchValue === "" || appNum.includes(searchValue)) ? "" : "none"
                        })
                    }
                    searchBtn.addEventListener("click", doSearch)
                    searchInput.addEventListener("keyup", doSearch)
                }
            })
        </script>

</body>

</html>