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
</head>


<body>

    <!-- Header Section -->
    <section class="bg-primary text-white text-center py-5">
        <div class="container">
            <h2 class="fw-bold">Building Official’s Online Permit Services</h2>
            <p>Fast, efficient, and transparent permit processing for projects and businesses</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="public/php/loginform.php" class="btn btn-light">Apply for permit</a>
                <a href="#faq" class="btn btn-outline-light">Track application</a>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h4 class="mb-4">Quick Actions</h4>
            <div class="row g-4">

                <!-- New Permit -->
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">New Permit</h5>
                            <p class="card-text">Apply for a new building permit online quickly and securely.</p>
                            <a href="#" class="text-decoration-none fw-bold text-primary">Apply Now →</a>
                        </div>
                    </div>
                </div>

                <!-- Track Status -->
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Track Status</h5>
                            <p class="card-text">Check the progress of your ongoing permit applications.</p>
                            <a href="#" class="text-decoration-none fw-bold text-primary">Track →</a>
                        </div>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Schedule</h5>
                            <p class="card-text">Book an inspection or schedule appointments with officials.</p>
                            <a href="#" class="text-decoration-none fw-bold text-primary">Book Now →</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- FAQ -->
    <section id="faq" class="py-5">
        <div class="container">
            <h4 class="text-center mb-4">Frequently Asked Questions</h4>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="q1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#a1">
                            How long does permit approval take?
                        </button>
                    </h2>
                    <div id="a1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Most applications are processed within 5–10 business days, while commercial permits may take
                            2–3 weeks.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="q2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#a2">
                            What documents do I need to submit?
                        </button>
                    </h2>
                    <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Site development plans, legal property documents, construction drawings, and related
                            calculations.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="q3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#a3">
                            How much do permits cost?
                        </button>
                    </h2>
                    <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Fees vary depending on the scope of work. Use our Permit Calculator or contact our office
                            for details.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <!-- Contact Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5 text-dark">Contact Us</h2>
            <div class="row g-4 justify-content-center">

                <!-- Office Information -->
                <?php if (isset($contacts['Office'])):
                    $office = $contacts['Office']; ?>
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-lg border-0 h-100 rounded-3">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-building fs-1 text-primary"></i>
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

                <!-- Emergency Contact -->
                <?php if (isset($contacts['Emergency'])):
                    $emergency = $contacts['Emergency']; ?>
                    <div class="col-md-6 col-lg-5">
                        <div class="card shadow-lg border-0 h-100 rounded-3">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="bi bi-exclamation-triangle-fill fs-1 text-danger"></i>
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

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 position-relative">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <h6>Office of the Building Official</h6>
                    <p class="small">Regulating and ensuring safe building practices for all projects.</p>
                </div>
                <div class="col-4">
                    <h6>Frequently Asked Questions</h6>
                    <ul class="list-unstyled">
                        <li><a href="" class="text-white text-decoration-none small">Apply for Permit</a></li>
                        <li><a href="#" class="text-white text-decoration-none small">Track Status</a></li>
                        <li><a href="#" class="text-white text-decoration-none small">Schedule</a></li>
                    </ul>
                </div>
                <div class="col-4">
                    <h6>Contact Info</h6>
                    <p class="small mb-1">123 City Hall St, Springfield</p>
                    <p class="small mb-0">Email: cityhall@springfield.gov</p>
                </div>
            </div>
        </div>
        <!-- Full-width HR -->
        <hr class="custom-hr">

        <div class="container">
            <p class="mt-3 mb-0 small">&copy; 2025 City of Springfield. All rights reserved.</p>
        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>