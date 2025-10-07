<?php
include "public/php/db.php"; // correct path to db.php

// Fetch all contact info
$sql = "SELECT * FROM app_info WHERE category IN ('Office', 'Emergency')";
$result = $conn->query($sql);

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[$row['category']] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Official's Online Permit Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f9fafb;
        }

        section.bg-primary {
            background: linear-gradient(135deg, #0d6efd, #0b4ea2);
        }

        .btn-light {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .card {
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.1);
        }

        .card-body h5 {
            color: #0d4166;
        }

        #faq {
            background-color: #ffffff;
        }

        #faq h4 {
            font-weight: 700;
            color: #0d4166;
        }

        .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            color: #0b4ea2;
        }

        #contact {
            background-color: #f8f9fa;
        }

        #contact h2 {
            color: #0d4166;
        }

        .card i {
            font-size: 2rem;
        }

        footer {
            font-size: 0.9rem;
        }

        footer h6 {
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        footer .custom-hr {
            border: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            footer .col-4 {
                width: 100%;
                text-align: center;
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <section class="bg-primary text-white text-center py-5">
        <div class="container">
            <h2 class="fw-bold mb-3">Building Official’s Online Permit Services</h2>
            <p class="mb-4">Fast, efficient, and transparent permit processing for projects and businesses</p>
            <div class="d-flex flex-column align-items-center gap-3">
                <a href="public/php/loginform.php" class="btn btn-light shadow-sm px-4 py-2">
                    <i class="bi bi-file-earmark-text me-2"></i> Apply for Permit
                </a>
            </div>
        </div>
    </section>

    <!-- Tracking Search Bar Section -->
    <section class="py-5 bg-light">
        <div class="container d-flex justify-content-center">
            <form id="trackingForm" class="d-flex w-100 shadow-sm rounded-3 overflow-hidden"
                style="max-width: 550px;">
                <input type="text" id="trackingNumber" name="tracking_number" class="form-control form-control-lg border-0"
                    placeholder="Enter Tracking Number" required>
                <button type="submit" class="btn btn-primary btn-lg text-nowrap px-4">
                    <i class="bi bi-search me-1"></i> Track
                </button>
            </form>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="py-5 bg-white">
        <div class="container text-center">
            <h4 class="fw-bold text-primary mb-5">Quick Actions</h4>
            <div class="row g-4">

                <div class="col-12 col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="mb-3"><i class="bi bi-file-earmark-plus text-primary fs-2"></i></div>
                            <h5 class="card-title">New Permit</h5>
                            <p class="card-text text-muted">Apply for a new building permit online quickly and securely.</p>
                            <a href="#" class="fw-bold text-primary text-decoration-none">Apply Now →</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="mb-3"><i class="bi bi-clock-history text-primary fs-2"></i></div>
                            <h5 class="card-title">Track Status</h5>
                            <p class="card-text text-muted">Check the progress of your ongoing permit applications.</p>
                            <a href="#" class="fw-bold text-primary text-decoration-none">Track →</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="mb-3"><i class="bi bi-calendar-check text-primary fs-2"></i></div>
                            <h5 class="card-title">Schedule</h5>
                            <p class="card-text text-muted">Book an inspection or schedule appointments with officials.</p>
                            <a href="#" class="fw-bold text-primary text-decoration-none">Book Now →</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Contact Us</h2>
            <div class="row g-4 justify-content-center">
                <?php if (isset($contacts['Office'])):
                    $office = $contacts['Office']; ?>
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow border-0 h-100 rounded-4">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-building text-primary"></i>
                                </div>
                                <h5 class="fw-bold mb-3 text-primary"><?= htmlspecialchars($office['title']) ?></h5>
                                <?php if ($office['address']): ?>
                                    <p class="mb-2 text-muted d-flex align-items-center justify-content-center">
                                        <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                                        <?= htmlspecialchars($office['address']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($office['phone']): ?>
                                    <p class="mb-2 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-telephone-fill me-2 text-primary"></i>
                                        <?= htmlspecialchars($office['phone']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($office['email']): ?>
                                    <p class="mb-2 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope-fill me-2 text-primary"></i>
                                        <?= htmlspecialchars($office['email']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($office['hours']): ?>
                                    <p class="mb-0 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-clock-fill me-2 text-primary"></i>
                                        <?= htmlspecialchars($office['hours']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($contacts['Emergency'])):
                    $emergency = $contacts['Emergency']; ?>
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow border-0 h-100 rounded-4">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                </div>
                                <h5 class="fw-bold mb-3 text-danger"><?= htmlspecialchars($emergency['title']) ?></h5>
                                <?php if ($emergency['notes']): ?>
                                    <p class="mb-2 text-muted d-flex align-items-center justify-content-center">
                                        <i class="bi bi-info-circle-fill me-2 text-danger"></i>
                                        <?= htmlspecialchars($emergency['notes']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($emergency['phone']): ?>
                                    <p class="mt-2 d-flex align-items-center justify-content-center text-dark">
                                        <i class="bi bi-telephone-inbound-fill me-2 text-danger"></i>
                                        <?= htmlspecialchars($emergency['phone']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-5 position-relative">
        <div class="container">
            <p class="mt-3 mb-0 small">&copy; 2025 City of Springfield. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap + JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("trackingForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const trackingNumber = document.getElementById("trackingNumber").value.trim();

            if (trackingNumber === "") return;

            fetch("public/php/check_tracking.php?tracking_number=" + encodeURIComponent(trackingNumber))
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        // Redirect to the actual tracking page
                        window.location.href = "public/php/track_application.php?tracking_number=" + encodeURIComponent(trackingNumber);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Invalid Tracking Number",
                            text: "The tracking number you entered does not exist. Please check and try again.",
                            confirmButtonColor: "#0d6efd"
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: "Something went wrong while checking the tracking number.",
                        confirmButtonColor: "#0d6efd"
                    });
                });
        });
    </script>
</body>
</html>
