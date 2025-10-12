@include('partials.__header')

<main>
    @include('partials.__nav')

    <!-- Hero Section -->
    <section class="py-5 bg-gradient-to-r from-blue-50 to-white text-center">
        <div class="container">
            <h1 class="fw-bold mb-3 display-5">Building Official’s Online Permit Services</h1>
            <p class="mb-4 text-secondary fs-5">Fast, efficient, and transparent permit processing for projects and businesses.</p>
            <a href="/login" class="btn btn-primary btn-lg shadow-lg px-5 py-3 rounded-pill">
                <i class="bi bi-file-earmark-text me-2"></i> Apply for Permit
            </a>
        </div>  
    </section>

    <!-- Tracking Search Section -->
    <section class="py-5 bg-light">
        <div class="container d-flex justify-content-center">
            <form id="trackingForm" class="d-flex w-100 shadow-lg rounded-pill overflow-hidden" style="max-width: 600px;">
                <input type="text" id="trackingNumber" name="tracking_number" class="form-control form-control-lg border-0 px-4"
                    placeholder="Enter Tracking Number" required>
                <button type="submit" class="btn btn-primary btn-lg px-4 rounded-end">
                    <i class="bi bi-search me-1"></i> Track
                </button>
            </form>
        </div>
    </section>

    <!-- Quick Actions Section -->
    <section class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-5">Quick Actions</h2>
            <div class="row g-4 justify-content-center">

                <div class="col-md-4">
                    <div class="card shadow border-0 h-100 rounded-5 hover-shadow-lg">
                        <div class="card-body p-5 text-center">
                            <i class="bi bi-file-earmark-plus text-primary fs-1 mb-4"></i>
                            <h5 class="card-title fw-bold mb-3">New Permit</h5>
                            <p class="card-text text-secondary mb-4">Apply for a new building permit online quickly and securely.</p>
                            <a href="#" class="text-primary fw-bold text-decoration-none">Apply Now →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow border-0 h-100 rounded-5 hover-shadow-lg">
                        <div class="card-body p-5 text-center">
                            <i class="bi bi-clock-history text-primary fs-1 mb-4"></i>
                            <h5 class="card-title fw-bold mb-3">Track Status</h5>
                            <p class="card-text text-secondary mb-4">Check the progress of your ongoing permit applications.</p>
                            <a href="#" class="text-primary fw-bold text-decoration-none">Track →</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow border-0 h-100 rounded-5 hover-shadow-lg">
                        <div class="card-body p-5 text-center">
                            <i class="bi bi-calendar-check text-primary fs-1 mb-4"></i>
                            <h5 class="card-title fw-bold mb-3">Schedule</h5>
                            <p class="card-text text-secondary mb-4">Book an inspection or schedule appointments with officials.</p>
                            <a href="#" class="text-primary fw-bold text-decoration-none">Book Now →</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-gradient-to-r from-gray-100 to-white">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Contact Us</h2>
            <div class="row g-5 justify-content-center">

                <!-- Office Contact -->
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow border-0 rounded-5 h-100 text-center p-4 hover-shadow-lg">
                        <i class="bi bi-building text-primary fs-2 mb-3"></i>
                        <h5 class="fw-bold mb-3 text-primary">Office of Building Official</h5>
                        <p class="mb-2 text-secondary"><i class="bi bi-geo-alt-fill me-2 text-primary"></i> 123 Main Street, Springfield</p>
                        <p class="mb-2"><i class="bi bi-telephone-fill me-2 text-primary"></i> (555) 123-4567</p>
                        <p class="mb-2"><i class="bi bi-envelope-fill me-2 text-primary"></i> office@springfield.gov</p>
                        <p class="mb-0"><i class="bi bi-clock-fill me-2 text-primary"></i> Mon-Fri 8:00 AM - 5:00 PM</p>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow border-0 rounded-5 h-100 text-center p-4 hover-shadow-lg">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-2 mb-3"></i>
                        <h5 class="fw-bold mb-3 text-danger">Emergency Services</h5>
                        <p class="mb-2 text-secondary"><i class="bi bi-info-circle-fill me-2 text-danger"></i> For urgent building hazards or fire</p>
                        <p class="mt-2 text-dark"><i class="bi bi-telephone-inbound-fill me-2 text-danger"></i> (555) 987-6543</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-5">
        <div class="container">
            <p class="mb-0 small">&copy; 2025 City of Springfield. All rights reserved.</p>
        </div>
    </footer>

    <!-- JS for Tracking -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("trackingForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const trackingNumber = document.getElementById("trackingNumber").value.trim();
            if (!trackingNumber) return;

            Swal.fire({
                icon: "info",
                title: "Tracking Disabled",
                text: "Tracking is disabled in static version.",
                confirmButtonColor: "#0d6efd"
            });
        });
    </script>
</main>

@include('partials.__footer')
