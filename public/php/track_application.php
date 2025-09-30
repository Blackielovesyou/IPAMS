<?php
// Include db.php (make sure path is correct)
include __DIR__ . "/db.php";

$appData = null;
$error = null;

if (isset($_GET['tracking_number'])) {
    $tracking_number = trim($_GET['tracking_number']);

    $sql = "SELECT pa.*, 
               u.id AS user_id, u.first_name, u.last_name, u.email,
               i.inspection_date, i.inspection_time, i.inspector_name, i.status AS inspection_status
        FROM permit_applications pa
        JOIN users u ON pa.email = u.email
        LEFT JOIN inspections i ON i.application_id = pa.id
        WHERE pa.application_number = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $tracking_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $appData = $row;
        } else {
            $error = "❌ Invalid tracking number. Please try again.";
        }

        $stmt->close();
    } else {
        $error = "⚠️ Database query failed. Please contact support.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Application</title>
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease-in;
            overflow-x: hidden;
            overflow-y: auto;
        }

        body.loaded {
            visibility: visible;
            opacity: 1;
        }

        .navbar {
            flex-wrap: nowrap;
            z-index: 3000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 60px;
        }

        .dashboard-title {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: -280px;
            width: 280px;
            height: 100%;
            background: #fff;
            border-right: 1px solid #ddd;
            transition: left 0.3s ease;
            z-index: 2000;
            overflow-y: auto;
            padding: 2rem 1.5rem;
        }

        body.sidebar-visible .sidebar {
            left: 0;
        }

        .sidebar .nav-link {
            font-weight: 600;
            color: #000 !important;
            padding: 12px 20px;
            display: block;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
            padding: 80px 15px 20px 15px;
        }

        body.sidebar-visible .main-content {
            margin-left: 280px;
        }

        #sidebarToggle {
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0.25rem 0.5rem;
        }

        #sidebarToggle .bi-list {
            font-size: 1.2rem;
        }

        /* Custom card styling */
        .summary-card {
            border-radius: 15px;
        }

        .remarks-box {
            background-color: #d9d6f9;
            border-radius: 12px;
        }

        /* Timeline Styling */
        .timeline {
            position: relative;
            padding-left: 40px;
            margin-top: 20px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            top: 0;
            left: 18px;
            width: 4px;
            height: 100%;
            background: #ddd;
        }

        .timeline-step {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-step:last-child {
            margin-bottom: 0;
        }

        .timeline-step .circle {
            position: absolute;
            left: -2px;
            top: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
            z-index: 1;
        }

        .timeline-step .step-content {
            margin-left: 50px;
        }

        .schedule-box {
            background: #17a2b8;
            color: #fff;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 8px;
            display: inline-block;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white border-bottom shadow-sm fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center" style="min-width: 0;">
                <button class="btn btn-outline-secondary me-2" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-none d-sm-flex flex-column dashboard-title" style="min-width: 0;">
                    Track Application Status
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="#" class="nav-link active" data-section="summary">Application Summary</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-section="progress">Application Progress</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-section="history">Activity History</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?= $error ?></div>
            <?php elseif ($appData): ?>

                <!-- Application Summary Card -->
                <div class="card shadow-sm p-4 summary-card mb-4">
                    <h3 class="mb-4">Application Summary</h3>
                    <div class="row g-4 align-items-start">
                        <!-- Left side -->
                        <div class="col-md-8">
                            <!-- Application Info -->
                            <div>
                                <p class="mb-1 fw-bold">Permit Type</p>
                                <p><?= ucfirst(htmlspecialchars($appData['permit_type'])) ?> Permit</p>

                                <p class="mb-1 fw-bold">Application Number</p>
                                <?php
                                $prefix = '';
                                switch ($appData['permit_type']) {
                                    case 'building': $prefix = 'BP'; break;
                                    case 'electrical': $prefix = 'EP'; break;
                                    case 'plumbing': $prefix = 'PP'; break;
                                    case 'occupancy': $prefix = 'OP'; break;
                                    default: $prefix = 'GEN';
                                }
                                $year = date("Y", strtotime($appData['created_at']));
                                $formattedAppNumber = $prefix . '-' . $year . '-' . $appData['application_number'];
                                ?>
                                <p><?= htmlspecialchars($formattedAppNumber) ?></p>

                                <p class="mb-1 fw-bold">Date Submitted</p>
                                <p><?= htmlspecialchars(date("F d, Y", strtotime($appData['created_at']))) ?></p>
                            </div>
                        </div>

                        <!-- Right side -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <button class="btn btn-info w-100 fw-semibold" style="border-radius: 12px;">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    <?= htmlspecialchars($appData['status']) ?>
                                </button>
                            </div>

                            <!-- Remarks -->
                            <div class="p-3 remarks-box mb-3">
                                <h6 class="fw-bold">Remarks from Office</h6>
                                <?php if (!empty($appData['inspection_date'])): ?>
                                    <p class="mb-1">
                                        A site inspection has been scheduled for
                                        <strong><?= date("F d, Y", strtotime($appData['inspection_date'])) ?></strong>
                                        at <strong><?= date("h:i A", strtotime($appData['inspection_time'])) ?></strong>,
                                        to be conducted by <strong><?= htmlspecialchars($appData['inspector_name']) ?></strong>.
                                    </p>
                                    <p class="mb-1">
                                        Kindly ensure that the construction site is accessible and that all required safety
                                        measures are in place prior to the scheduled inspection.
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($appData['remarks'])): ?>
                                    <p class="mb-0" style="white-space: pre-line;">
                                        <?= htmlspecialchars($appData['remarks']) ?>
                                    </p>
                                <?php elseif (empty($appData['inspection_date'])): ?>
                                    <p class="text-muted mb-0">No remarks available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Progress Timeline -->
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold mb-4">Application Progress</h5>
                    <div class="timeline">
                        <!-- Step 1 -->
                        <div class="timeline-step">
                            <div class="circle bg-primary">1</div>
                            <div class="step-content">
                                <h6 class="fw-bold">Application Submitted</h6>
                                <p class="mb-1">Form and requirements successfully uploaded</p>
                                <p class="text-muted"><?= date("F d, Y - h:i A", strtotime($appData['created_at'])) ?></p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="timeline-step">
                            <div class="circle bg-primary">2</div>
                            <div class="step-content">
                                <h6 class="fw-bold">Under Review</h6>
                                <p class="mb-1">Documents being verified by OBO staff</p>
                                <p class="text-muted">
                                    <?= !empty($appData['reviewed_at']) ? date("F d, Y - h:i A", strtotime($appData['reviewed_at'])) : "Pending" ?>
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="timeline-step">
                            <div class="circle bg-success">3</div>
                            <div class="step-content">
                                <h6 class="fw-bold">Inspection Scheduled</h6>
                                <p class="mb-1">Site inspection appointment set</p>
                                <p class="text-muted">
                                    <?= !empty($appData['inspection_date']) ? date("F d, Y - h:i A", strtotime($appData['inspection_date'])) : "Pending" ?>
                                </p>
                                <?php if (!empty($appData['inspection_date'])): ?>
                                    <div class="schedule-box">
                                        <i class="bi bi-calendar-event me-2"></i>
                                        Scheduled: <?= date("F d, Y", strtotime($appData['inspection_date'])) ?> at <?= date("h:i A", strtotime($appData['inspection_time'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="timeline-step">
                            <div class="circle bg-secondary">4</div>
                            <div class="step-content">
                                <h6 class="fw-bold">Final Decision</h6>
                                <p class="mb-1">Approval or rejection with remarks</p>
                                <p class="text-muted">
                                    <?= !empty($appData['finalized_at']) ? date("F d, Y - h:i A", strtotime($appData['finalized_at'])) : "Pending inspection completion" ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <h2>Welcome to the Track Application Page</h2>
                <p>Enter your tracking number to view application details.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.body.classList.add("loaded");

            const sidebarToggle = document.getElementById("sidebarToggle");
            const sidebar = document.querySelector(".sidebar");

            sidebarToggle.addEventListener("click", function () {
                document.body.classList.toggle("sidebar-visible");
                localStorage.setItem("sidebarState", document.body.classList.contains("sidebar-visible") ? "open" : "closed");
            });

            if (localStorage.getItem("sidebarState") === "open") {
                document.body.classList.add("sidebar-visible");
            }

            document.addEventListener("click", function (e) {
                if (window.innerWidth < 992 && sidebar && sidebarToggle) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        document.body.classList.remove("sidebar-visible");
                        localStorage.setItem("sidebarState", "closed");
                    }
                }
            });

            document.querySelectorAll(".sidebar .nav-link").forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    document.querySelectorAll(".sidebar .nav-link").forEach(l => l.classList.remove("active"));
                    this.classList.add("active");
                });
            });
        });
    </script>
</body>

</html>
