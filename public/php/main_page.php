<?php
session_start();
include("db.php"); // include your DB connection

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// If not logged in → block (go back, no redirect URL shown)
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
  echo "<script>window.history.back();</script>";
  exit;
}

// Only Applicant can access
if ($_SESSION['role'] !== 'Applicant') {
  // Stay on current page instead of redirecting
  echo "<script>window.history.back();</script>";
  exit;
}

// ✅ If reached here → Applicant is allowed
$userRole = $_SESSION['role'];

// Fetch logged-in user's first name
$userFirstName = "User"; // default fallback
$userId = $_SESSION['id'];

$sqlUser = "SELECT first_name FROM users WHERE id = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($rowUser = $resultUser->fetch_assoc()) {
  $userFirstName = $rowUser['first_name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>(IPAMS) Integrated Permit Application and Monitoring System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom px-3">
    <div class="container-fluid">

      <!-- Left Section -->
      <div class="d-flex align-items-center">
        <div class="fw-bold">IPAMS</div>
      </div>

      <!-- Right Section -->
      <div class="d-flex align-items-center ms-auto">
        <i class="bi bi-bell fs-4 me-3"></i>
        <div class="dropdown">
          <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
              style="width:40px; height:40px;">ICS</div>
            <div class="d-none d-sm-block">
              <div class="text-muted"><?php echo htmlspecialchars($userRole); ?></div>
            </div>

          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a id="logoutBtn" class="dropdown-item" href="#">Logout</a></li>
          </ul>
        </div>
      </div>

    </div>
  </nav>

  <!-- Welcome Section -->
  <div class="bg-primary text-white text-center py-4 mb-4 mt-3">
    <h3 class="mb-1">Welcome, <?php echo htmlspecialchars($userFirstName); ?></h3>
    <p class="mb-0">Piliin po ang permit na nais ninyong i-apply:</p>
  </div>



  <!-- Permit Options -->
  <div class="container mb-5">
    <div class="row g-3 mt-md-5"> <!-- margin top only on md and up -->

      <!-- Building Permit -->
      <div class="col-12 col-md-6 mt-md-3">
        <div class="card h-100 border shadow">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-building text-primary fs-2 me-2"></i>
              <h5 class="card-title mb-0">Building Permit</h5>
            </div>
            <p class="card-text">Apply for new building constructions and major renovations</p>
            <a href="building_permit.php" class="fw-bold text-decoration-none text-primary">Apply Now →</a>
          </div>
        </div>
      </div>

      <!-- Occupancy Permit -->
      <div class="col-12 col-md-6 mt-md-3">
        <div class="card h-100 border shadow">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-house-door-fill text-success fs-2 me-2"></i>
              <h5 class="card-title mb-0">Occupancy Permit</h5>
            </div>
            <p class="card-text">Get certificate of occupancy for completed buildings</p>
            <a href="occupancy_permit.php" class="fw-bold text-decoration-none text-success">Apply Now →</a>
          </div>
        </div>
      </div>

      <!-- Electrical Permit -->
      <div class="col-12 col-md-6 mt-md-3">
        <div class="card h-100 border shadow">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-lightning-charge-fill text-warning fs-2 me-2"></i>
              <h5 class="card-title mb-0">Electrical Permit</h5>
            </div>
            <p class="card-text">Apply for electrical installations and repairs</p>
            <a href="electrical_permit.php" class="fw-bold text-decoration-none text-warning">Apply Now →</a>
          </div>
        </div>
      </div>

      <!-- Plumbing Permit -->
      <div class="col-12 col-md-6 mt-md-3">
        <div class="card h-100 border shadow">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-tools text-danger fs-2 me-2"></i>
              <h5 class="card-title mb-0">Plumbing Permit</h5>
            </div>
            <p class="card-text">Apply for plumbing systems and fixture installations</p>
            <a href="plumbing_permit.php" class="fw-bold text-decoration-none text-danger">Apply Now →</a>
          </div>
        </div>
      </div>

    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../javascript/logout.js"></script>
</body>

</html>