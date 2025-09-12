<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: loginform.php");
    exit;
}

// Get role only
$userRole = $_SESSION['role'] ?? "Unknown Role";
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
</head>

<body class="bg-light overflow-hidden">

    <div class="container-fluid p-0">
        <!-- Navbar -->
        <nav class="navbar navbar-light bg-light shadow-sm py-2">
            <div class="container-fluid d-flex justify-content-between align-items-center" style="flex-wrap: nowrap;">

                <!-- Left: Icon + Title -->
                <div class="d-flex align-items-center" style="min-width: 0;">
                    <img src="../images/house.png" alt="Building Icon" class="me-2"
                        style="width:50px; height:50px; flex-shrink: 0;">
                    <div class="d-flex flex-column" style="min-width: 0;">
                        <span style="font-size: clamp(0.9rem, 2vw, 1.25rem);" class="fw-bold mb-0">OBO Admin
                            Dashboard</span>
                        <small style="font-size: clamp(0.7rem, 1.5vw, 0.9rem);" class="text-muted">Office of the
                            Building Official - Staff Portal</small>
                    </div>
                </div>

                <!-- Right: User Info + Logout -->
                <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                    <div class="text-end" style="min-width: 0;">
                        <div style="font-size: clamp(0.9rem, 2vw, 1rem);" class="fw-semibold">
                            <?php echo htmlspecialchars($userRole); ?>
                        </div>
                    </div>
                    <button id="logoutBtn" class="btn btn-primary btn-sm flex-shrink-0">Log out</button>
                </div>
            </div>
        </nav>

        <!-- Stats Cards -->
        <div class="row text-center g-3 my-3 px-3">
            <div class="col-6 col-md-3">
                <div class="card text-success bg-success bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>Pending Review</h5>
                    <p class="h3">12</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-warning bg-warning bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>For Inspection</h5>
                    <p class="h3">8</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-info bg-info bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>Approved Today</h5>
                    <p class="h3">5</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-primary bg-primary bg-opacity-10 border-0 p-4" style="min-height: 140px;">
                    <h5>Total Applications</h5>
                    <p class="h3">47</p>
                </div>
            </div>
        </div>


        <!-- Filter Section -->
        <div class="row g-2 px-3 mb-3">
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option selected>Filter by Status</option>
                    <option>Pending Review</option>
                    <option>For Inspection</option>
                    <option>Approved</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select">
                    <option selected>Permit Type</option>
                    <option>Building</option>
                    <option>Electrical</option>
                    <option>Plumbing</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <input type="text" class="form-control" placeholder="Search by Application num">
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </div>

        <!-- Permit Applications Table -->
        <div class="row px-3 mb-5" style="margin-top: 80px;">
            <h4>Permit Applications</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle mt-2">
                    <thead class="table-light">
                        <tr>
                            <th>Application</th>
                            <th>Applicant</th>
                            <th>Type</th>
                            <th>Date submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>BP-2025-000123 Residential Construction</td>
                            <td>Juan Dela Cruz<br>juan.delacruz@email.com</td>
                            <td>Building</td>
                            <td>Dec 10, 2024</td>
                            <td>Pending Review</td>
                            <td><button class="btn btn-primary btn-sm">Review</button></td>
                        </tr>
                        <tr>
                            <td>EP-2025-000089 Electrical Installation</td>
                            <td>Maria Garcia<br>maria.garcia@email.com</td>
                            <td>Electrical</td>
                            <td>Dec 8, 2024</td>
                            <td>For Inspection</td>
                            <td><button class="btn btn-primary btn-sm">Review</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../javascript/logout.js"></script>

</body>

</html>