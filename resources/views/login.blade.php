@include('partials.__header')

<!-- ✅ Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    body {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    main {
        width: 100%;
        padding-top: 100px;
        padding-bottom: 40px;
        display: flex;
        justify-content: center;
    }

    .auth-card {
        background: #fff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 900px;
        animation: fadeInUp 0.6s ease;
        padding: 2.5rem;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .auth-header h4 {
        font-weight: 700;
        color: #212529;
    }

    .auth-header p {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .nav-tabs {
        border-bottom: none;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 50px;
        padding: 0.6rem 1.8rem;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ced4da;
        padding: 0.75rem 1rem;
        background-color: #f8f9fa;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
        background-color: #fff;
    }

    .btn {
        border-radius: 10px;
        padding: 0.8rem;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<main>
    @include('partials.__nav')

    <div class="auth-card">
        <div class="auth-header">
            <h4>ADMIN PORTAL</h4>
            <p>Welcome! Please login or register to continue.</p>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="authTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login"
                    type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup"
                    type="button" role="tab" aria-controls="signup" aria-selected="false">Sign Up</button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="authTabContent">
    <!-- ✅ Login Tab -->
    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
        <form id="adminLoginForm">
            @csrf
            <div class="mb-3">
                <label for="login_counselor_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="login_counselor_email" name="email"
                    placeholder="Enter your email">
            </div>

            <div class="mb-2">
                <label for="login_counselor_password" class="form-label">Password</label>
                <input type="password" class="form-control" id="login_counselor_password" name="password"
                    placeholder="Enter your password">
            </div>

            <div class="d-flex justify-content-end mb-3">
                <a href="#" class="small text-decoration-none">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <!-- ✅ Signup Tab -->
    <div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
        <form id="registerform">
            @csrf
            <div class="row g-3">

                <!-- First Name -->
                <div class="col-12 col-md-4">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="firstname"
                        placeholder="Juan" required>
                </div>

                <!-- Middle Name -->
                <div class="col-12 col-md-4">
                    <label class="form-label">Middle Name <small class="text-muted">(Optional)</small></label>
                    <input type="text" class="form-control" id="middle_name" name="middlename"
                        placeholder="Santos">
                </div>

                <!-- Last Name -->
                <div class="col-12 col-md-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="last_name" name="lastname"
                        placeholder="Dela Cruz" required>
                </div>

                <!-- Email -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="signup_email" name="email"
                        placeholder="example@email.com" required>
                </div>

                <!-- Contact Number -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-secondary text-white">+63</span>
                        <input type="tel" class="form-control" id="contact_number" name="contact"
                            placeholder="9123456789" pattern="[0-9]{10}" minlength="10" maxlength="10" required>
                    </div>
                    <small class="text-muted">Enter a valid 10-digit mobile number.</small>
                </div>

                <!-- Password -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="signup_password" name="password"
                        placeholder="Enter a strong password" required>
                    <small class="text-muted">Use at least 8 characters with letters and numbers.</small>
                </div>

                <!-- Confirm Password -->
                <div class="col-12 col-md-6">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="signup_password_confirmation"
                        name="password_confirmation" placeholder="Re-enter your password" required>
                </div>

                <!-- Terms -->
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a>
                            <span class="text-danger">*</span>
                        </label>
                    </div>
                </div>

                <!-- Security Reminder -->
                <div class="col-12">
                    <div class="alert alert-warning p-2 mb-0">
                        <strong>Security Reminder:</strong> Keep your password secure and never share your login credentials.
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-12 d-grid mt-3">
                    <button type="submit" class="btn btn-success btn-lg">Sign Up</button>
                </div>

            </div>
        </form>
    </div>
</div>

    </div>
</main>

@include('partials.__footer')
