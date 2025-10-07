<?php
session_start();
include("db.php"); // DB connection

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Session check
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    echo "<script>
        localStorage.setItem('lastPage', window.location.href);
        window.history.back();
    </script>";
    exit;
}

$userRole = $_SESSION['role'];

// 🔹 Fetch permit applications
$applications = [];
$sql = "SELECT pa.*, 
            COALESCE(pa.full_name, CONCAT(u.first_name,' ',u.last_name)) AS full_name
        FROM permit_applications pa
        LEFT JOIN users u ON pa.user_id = u.id
        ORDER BY pa.created_at DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}

// 🔹 App name
$appName = "MyApp";
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

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
        body { 
            min-height: 100vh; 
            visibility: hidden; 
            opacity: 0; 
            transition: opacity 0.3s; 
            overflow-x: hidden; 
            background: #f8f9fa;
        }
        body.loaded { visibility: visible; opacity: 1; }

        /* Sidebar */
        .sidebar { 
            position: fixed; 
            top: 56px; 
            left: -280px; 
            width: 280px; 
            height: 100%; 
            background: #fff; 
            border-right: 1px solid #ddd; 
            transition: left 0.3s; 
            z-index: 2000; 
            padding: 1rem 0; 
        }
        body.sidebar-visible .sidebar { left: 0; }

        .sidebar .nav-link { 
            font-weight: 600; 
            color: #000 !important; 
            padding: 12px 20px; 
            border-radius: 8px; 
        }
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active { 
            background-color: #0d6efd; 
            color: #fff !important; 
        }

        /* Main content */
        .main-content { 
            margin-left: 0; 
            transition: margin-left 0.3s; 
            padding: 70px 15px 20px 15px; 
        }
        @media (min-width: 992px) { 
            body.sidebar-visible .main-content { margin-left: 280px; } 
        }

        /* Dashboard cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: #333;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .stat-card h6 { font-weight: 600; margin-bottom: 10px; }
        .stat-card p { font-size: 1.8rem; font-weight: bold; margin: 0; }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white border-bottom shadow-sm fixed-top">
        <div class="container-fluid d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary me-2" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <span class="fw-bold"><?= htmlspecialchars($appName) ?></span>
            </div>
            <div class="dropdown">
                <a class="fw-semibold text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($userRole) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">⚙️ Settings</a></li>
                    <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar bg-white text-dark p-3">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="#" class="nav-link active" data-section="dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <?php
            if ($userRole === 'admin') {
                $stmt = $conn->prepare("SELECT module_name FROM module_access_data WHERE user_id = ? AND has_access = 1");
                $stmt->bind_param("i", $_SESSION['id']);
                $stmt->execute();
                $resultAccess = $stmt->get_result();
                while ($access = $resultAccess->fetch_assoc()) {
                    $module = $access['module_name'];
                    $secId = strtolower(str_replace(' ', '_', $module));
                    echo '<li class="nav-item"><a href="#" class="nav-link" data-section="' . $secId . '"><i class="bi bi-folder"></i> ' . htmlspecialchars($module) . '</a></li>';
                }
            }
            ?>
        </ul>
    </nav>

    <div class="main-content">
        <!-- Dashboard -->
        <div id="dashboard" class="content-section">
            <h4 class="fw-bold mb-4">📊 Dashboard Overview</h4>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-success bg-opacity-10">
                        <h6>Pending Review</h6>
                        <p><?= count(array_filter($applications, fn($a) => $a['permit_type'] == 'building')); ?></p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-warning bg-opacity-10">
                        <h6>For Inspection</h6>
                        <p><?= count(array_filter($applications, fn($a) => $a['permit_type'] == 'electrical')); ?></p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-info bg-opacity-10">
                        <h6>Approved Today</h6>
                        <p><?php $today = date('Y-m-d'); echo count(array_filter($applications, fn($a) => substr($a['created_at'], 0, 10) == $today)); ?></p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-primary bg-opacity-10">
                        <h6>Total Applications</h6>
                        <p><?= count($applications); ?></p>
                    </div>
                </div>
            </div>

            
        </div>

        <!-- 🔹 Dynamic modules -->
        <?php
        if ($userRole === 'admin') {
            $stmt = $conn->prepare("SELECT module_name FROM module_access_data WHERE user_id = ? AND has_access = 1");
            $stmt->bind_param("i", $_SESSION['id']);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $module = $row['module_name'];
                $secId = strtolower(str_replace(' ', '_', $module));
                echo '<div id="' . $secId . '" class="content-section d-none">';
                $file = $secId . "_section.php"; 
                if (file_exists($file)) include $file; 
                else echo "<p class='text-muted'>Section file <code>$file</code> not found.</p>";
                echo '</div>';
            }
        }
        ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../javascript/logout.js"></script>

    <script>
        $(function () {
            $("body").addClass("loaded");

            // Sidebar toggle
            $("#sidebarToggle").on("click", () => {
                $("body").toggleClass("sidebar-visible");
                localStorage.setItem("sidebarState", $("body").hasClass("sidebar-visible") ? "open" : "closed");
            });
            if (localStorage.getItem("sidebarState") === "open") $("body").addClass("sidebar-visible");

            // Switch sections
            $(".sidebar .nav-link").on("click", function (e) {
                e.preventDefault();
                $(".sidebar .nav-link").removeClass("active");
                $(this).addClass("active");
                $(".content-section").addClass("d-none");
                $("#" + $(this).data("section")).removeClass("d-none");

                if ($(this).data("section") === "permit_applications") {
                    initApplicationsTable();
                }
            });

            // DataTable init
            $("#latestApplicationsTable").DataTable({
                pageLength: 5,
                lengthChange: false,
                ordering: true,
                language: { search: "Search:" }
            });
        });
    </script>
</body>
</html>
