@include('user.partials.__header')
@include('user.partials.__nav')

<style>
    body {
        background-color: #f8fafc;
        font-family: "Poppins", sans-serif;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .navbar {
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-width: 900px;
    }

    .welcome-banner h3 span {
        color: #ffeb3b;
    }

    .card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
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

    footer {
        font-size: 0.9rem;
        color: #6c757d;
        text-align: center;
        padding: 1rem 0;
        border-top: 1px solid #e9ecef;
        margin-top: auto;
        background: #fff;
        box-shadow: 0 -1px 4px rgba(0, 0, 0, 0.05);
    }
</style>

<main id="main" class="main">

    <div class="container">
        <div class="welcome-banner">
            <h3 class="fw-bold mb-2">Welcome, <span>{{ Auth::user()->first_name }}</span>!</h3>
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
                        <a href="{{ route('user.permit', ['type' => 'building']) }}" class="text-primary">Apply Now →</a>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <i class="bi bi-house-door-fill text-success"></i>
                        <h5>Occupancy Permit</h5>
                        <p>Get certificates of occupancy for completed buildings.</p>
                        <a href="{{ route('user.permit', ['type' => 'occupancy']) }}" class="text-success">Apply Now →</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <h5>Electrical Permit</h5>
                        <p>Apply for electrical installations and repairs.</p>
                        <a href="{{ route('user.permit', ['type' => 'electrical']) }}" class="text-warning">Apply Now →</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <i class="bi bi-tools text-danger"></i>
                        <h5>Plumbing Permit</h5>
                        <p>Apply for plumbing systems and fixture installations.</p>
                        <a href="{{ route('user.permit', ['type' => 'plumbing']) }}" class="text-danger">Apply Now →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('user.partials.__footer')
