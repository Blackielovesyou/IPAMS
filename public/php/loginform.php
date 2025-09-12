<?php
session_start();

// Redirect if already logged in BUT allow SweetAlert to show first
if (isset($_SESSION['id']) && isset($_SESSION['role']) && !isset($_SESSION['success'])) {
  if ($_SESSION['role'] == 'superadmin') {
    header("Location: super_admin.php");
    exit;
  } elseif ($_SESSION['role'] == 'admin') {
    header("Location: admin_dashboard.php");
    exit;
  } else {
    header("Location: main_page.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Integrated Permit Application and Monitoring System</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../images/house.png" alt="Logo" width="45" height="45" class="me-2">
        <div class="d-flex flex-column lh-1">
          <span class="fw-bold">City of [Municipality]</span>
          <small class="text-primary">Office of the Building Official</small>
        </div>
      </a>
    </div>
  </nav>

  <!-- Main -->
  <main class="flex-grow-1 d-flex align-items-center justify-content-center py-4">

    <!-- Login Card -->
    <div id="loginCard" class="card shadow w-100 mx-3 mx-sm-auto bg-white bg-opacity-75"
      style="max-width: 500px; backdrop-filter: blur(6px); border-radius: 1rem;">
      <div class="card-body p-4">
        <h4 class="text-center mb-3">Welcome!</h4>
        <p class="text-center text-muted">Please log in to access your permit applications and dashboard.</p>

        <!-- Login Form -->
        <form id="loginForm" action="login.php" method="POST" novalidate onsubmit="return showLoading()">

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control ..." placeholder="Enter your email"
              required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <input type="password" id="password" name="password" class="form-control ..."
                placeholder="Enter your password" required>
              <span class="input-group-text bg-transparent border border-secondary" id="togglePassword"
                style="cursor: pointer;">
                <i class="bi bi-eye" id="toggleIcon"></i>
              </span>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember">
              <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="#" class="small">Forgot Password?</a>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Log in</button>
          </div>
        </form>

        <p class="text-center mt-3">
          Don’t have an account? <a href="#" id="showRegister">Register here</a>
        </p>
      </div>
    </div>


    <!-- Register Card -->
    <div id="registerCard" class="card shadow w-100 mx-3 mx-sm-auto d-none bg-white bg-opacity-75"
      style="max-width: 900px; backdrop-filter: blur(6px); border-radius: 1rem;">
      <div class="card-body p-4 p-md-5">

        <!-- Title -->
        <h3 class="text-center fw-bold mb-2">Create an Account</h3>
        <p class="text-center text-muted mb-4">Please fill in the form below to register.</p>

        <!-- Registration Form -->
        <form id="registerForm" action="register.php" method="POST" novalidate>
          <div class="row g-3">

            <!-- First Name -->
            <div class="col-12 col-md-4">
              <label class="form-label">First Name <span class="text-danger">*</span></label>
              <input type="text" id="first_name" name="first_name"
                class="form-control bg-transparent border border-secondary" placeholder="Juan"
                style="backdrop-filter: blur(4px);" required>
            </div>

            <!-- Middle Name (Optional) -->
            <div class="col-12 col-md-4">
              <label class="form-label">Middle Name <small class="text-muted">(Optional)</small></label>
              <input type="text" id="middle_name" name="middle_name"
                class="form-control bg-transparent border border-secondary" placeholder="Santos"
                style="backdrop-filter: blur(4px);">
            </div>

            <!-- Last Name -->
            <div class="col-12 col-md-4">
              <label class="form-label">Last Name <span class="text-danger">*</span></label>
              <input type="text" id="last_name" name="last_name"
                class="form-control bg-transparent border border-secondary" placeholder="Dela Cruz"
                style="backdrop-filter: blur(4px);" required>
            </div>

            <!-- Email -->
            <div class="col-12 col-md-6">
              <label class="form-label">Email Address <span class="text-danger">*</span></label>
              <input type="email" id="email" name="email" class="form-control bg-transparent border border-secondary"
                placeholder="example@email.com" style="backdrop-filter: blur(4px);" required>
            </div>

            <!-- Password -->
            <div class="col-12 col-md-6">
              <label class="form-label">Password <span class="text-danger">*</span></label>
              <input type="password" id="password" name="password"
                class="form-control bg-transparent border border-secondary" placeholder="Enter a strong password"
                style="backdrop-filter: blur(4px);" required>
              <small class="text-muted">Use at least 8 characters with letters and numbers.</small>
            </div>

            <!-- Confirm Password -->
            <div class="col-12 col-md-6">
              <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control bg-transparent border border-secondary"
                placeholder="Re-enter your password" style="backdrop-filter: blur(4px);" required>
            </div>

            <!-- Contact Number -->
            <div class="col-12 col-md-6">
              <label class="form-label">Contact Number <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-secondary text-white">+63</span>
                <input type="tel" id="contact_number" name="contact_number"
                  class="form-control bg-transparent border border-secondary" placeholder="9123456789"
                  style="backdrop-filter: blur(4px);" pattern="[0-9]{10}" minlength="10" maxlength="10" required>
              </div>
              <small class="text-muted">Enter a valid 10-digit mobile number.</small>
            </div>

            <!-- Terms -->
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                  I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> <span
                    class="text-danger">*</span>
                </label>
              </div>
            </div>

            <!-- Security Reminder -->
            <div class="col-12">
              <div class="alert alert-warning p-2 mb-0">
                <strong>Security Reminder:</strong> Keep your password secure and never share your login credentials.
              </div>
            </div>

            <!-- Submit Button -->
            <div class="col-12 d-grid mt-3">
              <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
            </div>
          </div>
        </form>

        <!-- Login Redirect -->
        <p class="text-center mt-4 mb-0">
          Already have an account? <a href="#" id="showLogin" class="fw-semibold">Log In</a>
        </p>
      </div>
    </div>


  </main>

  <!-- JS -->
  <script>
    <?php if (isset($_SESSION['error'])): ?>
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?php echo $_SESSION['error']; ?>',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Try Again'
      });
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
      Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome back!',
        showConfirmButton: false,
        timer: 1500
      }).then(() => {
        <?php if ($_SESSION['success'] == 'superadmin'): ?>
          window.location.href = "super_admin.php";
        <?php elseif ($_SESSION['success'] == 'admin'): ?>
          window.location.href = "admin_dashboard.php";
        <?php else: ?>
          window.location.href = "main_page.php";
        <?php endif; ?>
      });
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
  </script>
  <script src="../javascript/login_register_swap.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php include("loading_modal.php"); ?>
  <script src="../javascript/loading.js"></script>
</body>

</html>