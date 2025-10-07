<?php
session_start();
include("db.php");

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Authentication checks
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    echo "<script>window.history.back();</script>";
    exit;
}

if ($_SESSION['role'] !== 'Applicant') {
    echo "<script>window.history.back();</script>";
    exit;
}

// User details
$userId = $_SESSION['id'];
$userRole = $_SESSION['role'];

$userFirstName = "User";
$stmtUser = $conn->prepare("SELECT first_name FROM users WHERE id = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
if ($rowUser = $resultUser->fetch_assoc()) {
    $userFirstName = $rowUser['first_name'];
}

// App name
$appName = "MyApp";
$resultApp = $conn->query("SELECT appname FROM app_info ORDER BY id LIMIT 1");
if ($resultApp && $resultApp->num_rows > 0) {
    $appName = $resultApp->fetch_assoc()['appname'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>(IPAMS) Integrated Permit Application and Monitoring System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8fafc;
      font-family: "Poppins", sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Navbar */
    .navbar {
      background-color: #ffffff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
    }

    .navbar-brand {
      font-weight: 600;
      color: #0d4166 !important;
      letter-spacing: 0.5px;
    }

    .navbar .dropdown-toggle::after {
      margin-left: 0.35rem;
    }

    .navbar .dropdown-menu {
      min-width: 180px;
    }

    /* Welcome Section */
    .welcome-banner {
      background: linear-gradient(90deg, #0d4166, #1976d2);
      color: white;
      border-radius: 10px;
      margin: 2rem auto;
      padding: 2rem 1rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 900px;
    }

    /* Card Styling */
    .card {
      transition: all 0.3s ease;
      border: none;
      border-radius: 15px;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0,0,0,0.08);
      height: 100%;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    .card-body {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      justify-content: center;
      padding: 2rem 1.5rem;
    }

    .card-body i {
      font-size: 2.5rem;
      border-radius: 50%;
      background-color: #f1f5f9;
      padding: 12px;
      margin-bottom: 10px;
    }

    .card h5 {
      margin-top: 0.5rem;
      font-weight: 600;
    }

    .card p {
      margin-bottom: 1rem;
      color: #6c757d;
      font-size: 0.95rem;
    }

    .card a {
      display: inline-block;
      font-weight: 600;
      transition: color 0.2s ease;
    }

    /* Footer */
    footer {
      font-size: 0.9rem;
      color: #6c757d;
      text-align: center;
      padding: 1rem 0;
      border-top: 1px solid #e9ecef;
      margin-top: auto;
      background: #fff;
      box-shadow: 0 -1px 4px rgba(0,0,0,0.05);
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
      <a class="navbar-brand" href="#"><?php echo htmlspecialchars($appName); ?></a>

      <div class="d-flex align-items-center ms-auto">
        <i class="bi bi-bell fs-5 me-3 text-secondary"></i>
        <div class="dropdown">
          <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold"
              style="width: 40px; height: 40px;">
              <?php echo strtoupper(substr($userFirstName, 0, 2)); ?>
            </div>
            <div class="d-none d-sm-block">
              <div class="fw-semibold lh-1"><?php echo htmlspecialchars($userFirstName); ?></div>
              <small class="text-muted"><?php echo htmlspecialchars($userRole); ?></small>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a id="logoutBtn" class="dropdown-item text-danger" href="#">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Welcome Banner -->
  <div class="container">
    <div class="welcome-banner">
      <h3 class="fw-bold mb-2">Welcome, <?php echo htmlspecialchars($userFirstName); ?> 👋</h3>
      <p class="mb-0">Please select the type of permit you wish to apply for below.</p>
    </div>
  </div>

  <!-- Permit Options -->
  <div class="container mb-5">
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <i class="bi bi-building text-primary"></i>
            <h5>Building Permit</h5>
            <p>Apply for new constructions and major renovations.</p>
            <a href="building_permit.php" class="text-primary">Apply Now →</a>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <i class="bi bi-house-door-fill text-success"></i>
            <h5>Occupancy Permit</h5>
            <p>Get certificates of occupancy for completed buildings.</p>
            <a href="occupancy_permit.php" class="text-success">Apply Now →</a>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <h5>Electrical Permit</h5>
            <p>Apply for electrical installations and repairs.</p>
            <a href="electrical_permit.php" class="text-warning">Apply Now →</a>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card">
          <div class="card-body">
            <i class="bi bi-tools text-danger"></i>
            <h5>Plumbing Permit</h5>
            <p>Apply for plumbing systems and fixture installations.</p>
            <a href="plumbing_permit.php" class="text-danger">Apply Now →</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    © <?php echo date("Y"); ?> <?php echo htmlspecialchars($appName); ?>. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../javascript/logout.js"></script>
</body>
</html>
