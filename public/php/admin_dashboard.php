<?php
session_start();
include("db.php"); // Include your DB connection

// Prevent caching (for logout/back button issue)
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
        @media (max-width: 576px) {
            .navbar .dashboard-title {
                display: none;
            }
        }

        body {
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Table column widths */
        .table th.id {
            width: 15%;
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
            width: 10%;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container-fluid p-0">
        <!-- Navbar -->
        <nav class="navbar navbar-light bg-light shadow-sm py-2">
            <div class="container-fluid d-flex justify-content-between align-items-center" style="flex-wrap: nowrap;">

                <!-- Left: House Icon + Dashboard Title (hidden on mobile) -->
                <div class="d-flex align-items-center" style="min-width: 0;">
                    <img src="../images/house.png" alt="Building Icon" class="me-2"
                        style="width:50px; height:50px; flex-shrink: 0;">
                    <div class="d-none d-sm-flex flex-column dashboard-title" style="min-width: 0;">
                        <span style="font-size: clamp(0.9rem, 2vw, 1.25rem);" class="fw-bold mb-0">OBO Admin
                            Dashboard</span>
                        <small style="font-size: clamp(0.7rem, 1.5vw, 0.9rem);" class="text-muted">Office of the
                            Building Official - Staff Portal</small>
                    </div>
                </div>

                <!-- Right: Role + Dropdown Arrow -->
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

        <!-- Stats Cards -->
        <div class="row text-center g-3 my-3 px-3">
            <div class="col-6 col-md-3">
                <div class="card text-success bg-success bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>Pending Review</h5>
                    <p class="h3">
                        <?php echo count(array_filter($applications, fn($a) => $a['permit_type'] === 'building')); ?>
                    </p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-warning bg-warning bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>For Inspection</h5>
                    <p class="h3">
                        <?php echo count(array_filter($applications, fn($a) => $a['permit_type'] === 'electrical')); ?>
                    </p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-info bg-info bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>Approved Today</h5>
                    <p class="h3">
                        <?php $today = date('Y-m-d');
                        echo count(array_filter($applications, fn($a) => substr($a['created_at'], 0, 10) === $today)); ?>
                    </p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-primary bg-primary bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>Total Applications</h5>
                    <p class="h3"><?php echo count($applications); ?></p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row g-2 px-3 mb-3">
            <div class="col-12 col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="all" selected>Filter by Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select" id="typeFilter">
                    <option value="all" selected>Permit Type</option>
                    <option value="building">Building</option>
                    <option value="electrical">Electrical</option>
                    <option value="plumbing">Plumbing</option>
                    <option value="occupancy">Occupancy</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by Application num">
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-primary w-100" id="searchBtn">Search</button>
            </div>
        </div>

        <!-- Permit Applications Table -->
        <div class="row px-3 mb-5" style="margin-top: 80px;">
            <h4>Permit Applications</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle mt-2" id="applicationsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="id">ID</th>
                            <th class="applicant">Applicant</th>
                            <th class="type">Type</th>
                            <th class="contact">Contact</th>
                            <th class="email">Email</th>
                            <th class="date">Date Submitted</th>
                            <th class="status">Status</th>
                            <th class="action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($applications)): ?>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <!-- Formatted ID -->
                                    <td data-id="<?php echo str_pad($app['id'], 6, '0', STR_PAD_LEFT); ?>">
                                        <?php
                                        $prefix = '';
                                        $type_text = '';
                                        switch ($app['permit_type']) {
                                            case 'building':
                                                $prefix = 'BP';
                                                $type_text = $app['construction_type'];
                                                break;
                                            case 'electrical':
                                                $prefix = 'EP';
                                                $type_text = $app['installation_type'];
                                                break;
                                            case 'occupancy':
                                                $prefix = 'OP';
                                                $type_text = $app['construction_type'];
                                                break;
                                            case 'plumbing':
                                                $prefix = 'PP';
                                                $type_text = $app['installation_type'];
                                                break;
                                        }
                                        $formatted_id = str_pad($app['id'], 6, '0', STR_PAD_LEFT);
                                        echo $prefix . '-' . date('Y') . '-' . $formatted_id . '-' . htmlspecialchars($type_text);
                                        ?>
                                    </td>

                                    <!-- Applicant Name Only -->
                                    <td><?php echo htmlspecialchars($app['full_name']); ?></td>

                                    <td><?php echo ucfirst(htmlspecialchars($app['permit_type'])); ?></td>
                                    <td><?php echo htmlspecialchars($app['contact_number']); ?></td>
                                    <td><?php echo htmlspecialchars($app['email']); ?></td>
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
                                <td colspan="8" class="text-center">No applications found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../javascript/logout.js"></script>

    <script>
        // Filter by permit type
        document.getElementById("typeFilter").addEventListener("change", function () {
            let filterValue = this.value.toLowerCase();
            let rows = document.querySelectorAll("#applicationsTable tbody tr");

            rows.forEach(row => {
                let typeCell = row.cells[2].textContent.toLowerCase();
                if (filterValue === "all" || typeCell === filterValue) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Search by numeric Application Number only
        document.getElementById("searchBtn").addEventListener("click", function () {
            let searchValue = document.getElementById("searchInput").value.trim().toLowerCase();
            let rows = document.querySelectorAll("#applicationsTable tbody tr");

            rows.forEach(row => {
                let appNum = row.querySelector("td[data-id]").getAttribute("data-id").toLowerCase();
                if (appNum.includes(searchValue) || searchValue === "") {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Instant search on typing
        document.getElementById("searchInput").addEventListener("keyup", function () {
            document.getElementById("searchBtn").click();
        });
    </script>

</body>

</html>