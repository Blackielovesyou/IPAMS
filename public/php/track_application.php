<?php
include __DIR__ . "/db.php";

$appData = null;
$error = null;
$contacts = [];

// Fetch Office & Emergency contact info
$contactSql = "SELECT category, phone, email, address, hours 
            FROM app_info 
            WHERE category IN ('Office','Emergency')";
$contactResult = $conn->query($contactSql);

if ($contactResult) {
    while ($row = $contactResult->fetch_assoc()) {
        $contacts[$row['category']] = $row;
    }
}

// Fetch application details if tracking number is provided
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
            background: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
            padding-top: 80px;
        }
        .navbar {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
            color: #fff;
        }
        .navbar .dashboard-title span {
            color: #fff;
            font-weight: 600;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            background: #fff;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
        }
        .summary-card h5 {
            color: #0d6efd;
        }
        .remarks-box {
            background: #f0f4ff;
            border-left: 4px solid #0d6efd;
            border-radius: 10px;
        }
        .progress-tracker {
            position: relative;
            gap: 8px;
        }
        .step-container {
            text-align: center;
            flex: 1;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 8px;
            font-weight: 600;
        }
        .step-number.active { background: #0d6efd; color: white; }
        .step-number.approved { background: #198754; }
        .step-number.rejected { background: #dc3545; }
        .step-label { font-size: 0.9rem; font-weight: 500; }
        .step-line {
            flex: none;
            width: 40px;
            height: 3px;
            background: #dee2e6;
            align-self: center;
        }
        .step-line.active {
            background: #0d6efd;
        }
        .timeline-step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
        }
        .circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .step-content h6 { margin-bottom: 4px; color: #0d4166; }
        .schedule-box {
            background: #e9f5ff;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.9rem;
            margin-top: 8px;
        }
        .btn-lg {
            border-radius: 12px;
            transition: background 0.3s ease;
        }
        .btn-lg:hover { opacity: 0.9; }
        .main-content .container {
            max-width: 950px;
        }
    </style>
</head>

<body>
<nav class="navbar fixed-top shadow-sm px-3">
    <div class="container-fluid d-flex align-items-center">
        <i class="bi bi-clipboard-check me-2 fs-4"></i>
        <div class="dashboard-title"><span>Track Application Status</span></div>
    </div>
</nav>

<main class="main-content">
    <div class="container py-4">
        <?php if ($error): ?>
            <div class="alert alert-danger text-center shadow-sm"><?= $error ?></div>
        <?php elseif ($appData): ?>

            <!-- Application Summary -->
            <div class="card summary-card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-2"></i>Application Summary</h5>
                <div class="row g-4 align-items-start">
                    <div class="col-md-8">
                        <p class="fw-semibold mb-1">Permit Type</p>
                        <p><?= ucfirst(htmlspecialchars($appData['permit_type'])) ?> Permit</p>

                        <p class="fw-semibold mb-1">Application Number</p>
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

                        <p class="fw-semibold mb-1">Date Submitted</p>
                        <p><?= htmlspecialchars(date("F d, Y", strtotime($appData['created_at']))) ?></p>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-info w-100 fw-semibold text-white mb-3">
                            <i class="bi bi-hourglass-split me-2"></i><?= htmlspecialchars($appData['status']) ?>
                        </button>
                        <div class="p-3 remarks-box">
                            <h6 class="fw-bold mb-2">Remarks from Office</h6>
                            <?php if (!empty($appData['inspection_date'])): ?>
                                <p class="mb-1">
                                    Site inspection on
                                    <strong><?= date("F d, Y", strtotime($appData['inspection_date'])) ?></strong>
                                    at <strong><?= date("h:i A", strtotime($appData['inspection_time'])) ?></strong>,
                                    by <strong><?= htmlspecialchars($appData['inspector_name']) ?></strong>.
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($appData['remarks'])): ?>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($appData['remarks'])) ?></p>
                            <?php elseif (empty($appData['inspection_date'])): ?>
                                <p class="text-muted mb-0">No remarks available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Progress -->
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Application Progress</h5>
                <?php
                $status = $appData['status'];
                $currentStep = 1;
                if ($status === 'under review') $currentStep = 2;
                elseif ($status === 'scheduled') $currentStep = 3;
                elseif (in_array($status, ['approved', 'rejected'])) $currentStep = 4;
                $labels = ["Pending", "Reviewing", "Scheduled", "Final Decision"];
                ?>
                <div class="progress-tracker d-flex justify-content-between mb-4">
                    <?php for ($i = 1; $i <= 4; $i++):
                        $isActive = $i <= $currentStep;
                        $isApproved = ($i === 4 && $status === 'approved');
                        $isRejected = ($i === 4 && $status === 'rejected');
                    ?>
                        <div class="step-container">
                            <div class="step-number <?= $isActive ? 'active' : '' ?> <?= $isApproved ? 'approved' : '' ?> <?= $isRejected ? 'rejected' : '' ?>"><?= $i ?></div>
                            <div class="step-label"><?= $labels[$i - 1] ?></div>
                        </div>
                        <?php if ($i < 4): ?><div class="step-line <?= $i < $currentStep ? 'active' : '' ?>"></div><?php endif; ?>
                    <?php endfor; ?>
                </div>

                <!-- Timeline -->
                <div class="timeline">
                    <div class="timeline-step">
                        <div class="circle bg-primary">1</div>
                        <div class="step-content">
                            <h6>Application Submitted</h6>
                            <p>Form and requirements successfully uploaded</p>
                            <p class="text-muted"><?= date("F d, Y - h:i A", strtotime($appData['created_at'])) ?></p>
                        </div>
                    </div>

                    <?php if (in_array($status, ['under review','scheduled','approved','rejected'])): ?>
                    <div class="timeline-step">
                        <div class="circle bg-primary">2</div>
                        <div class="step-content">
                            <h6>Under Review</h6>
                            <p>Documents verified by OBO staff</p>
                            <p class="text-muted"><?= !empty($appData['reviewed_at']) ? date("F d, Y - h:i A", strtotime($appData['reviewed_at'])) : "Pending" ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (in_array($status, ['scheduled','approved','rejected'])): ?>
                    <div class="timeline-step">
                        <div class="circle bg-info">3</div>
                        <div class="step-content">
                            <h6>Inspection Scheduled</h6>
                            <p>Site inspection appointment set</p>
                            <p class="text-muted"><?= !empty($appData['inspection_date']) ? date("F d, Y - h:i A", strtotime($appData['inspection_date'])) : "Pending" ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (in_array($status, ['approved','rejected'])): ?>
                    <div class="timeline-step">
                        <div class="circle <?= $status === 'approved' ? 'bg-success' : 'bg-danger' ?>">4</div>
                        <div class="step-content">
                            <h6>Final Decision</h6>
                            <p><?= $status === 'approved' ? 'Application Approved' : 'Application Rejected' ?></p>
                            <p class="text-muted"><?= !empty($appData['finalized_at']) ? date("F d, Y - h:i A", strtotime($appData['finalized_at'])) : "Pending" ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-gear me-2"></i>Actions</h5>
                <div class="d-grid gap-3">
                    <button class="btn btn-primary btn-lg fw-semibold"><i class="bi bi-printer me-2"></i> Print Summary</button>
                    <button class="btn btn-success btn-lg fw-semibold"><i class="bi bi-upload me-2"></i> Upload Files</button>
                    <button class="btn btn-danger btn-lg fw-semibold"><i class="bi bi-x-lg me-2"></i> Cancel Application</button>
                </div>
            </div>

            <!-- Activity History -->
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Activity History</h5>
                <?php
                $activities = [];
                $statusOrder = ['pending'=>0,'under review'=>1,'scheduled'=>2,'approved'=>3,'rejected'=>3];
                $currentStatusKey = strtolower($appData['status'] ?? 'pending');
                $currentStep = $statusOrder[$currentStatusKey] ?? 0;
                $prefix = match ($appData['permit_type']) {
                    'building'=>'BP','electrical'=>'EP','plumbing'=>'PP','occupancy'=>'OP',default=>'GEN',
                };
                $year = date("Y", strtotime($appData['created_at']));
                $formattedAppNumber = $prefix . '-' . $year . '-' . $appData['application_number'];

                $activities[] = [
                    'title'=>"Application Submitted",
                    'datetime'=>date("F d, Y - h:i A", strtotime($appData['created_at'])),
                    'details'=>"Application {$formattedAppNumber} submitted successfully."
                ];
                if ($currentStep>=1) {
                    $activities[]=[
                        'title'=>"Under Review",
                        'datetime'=>!empty($appData['reviewed_at'])?date("F d, Y - h:i A",strtotime($appData['reviewed_at'])):'Not recorded',
                        'details'=>"Application is under review by OBO staff."
                    ];
                }
                if ($currentStep>=2) {
                    $activities[]=[
                        'title'=>"Inspection Scheduled",
                        'datetime'=>!empty($appData['inspection_date'])?date("F d, Y - h:i A",strtotime($appData['inspection_date'])):'Not recorded',
                        'details'=>"Site visit scheduled."
                    ];
                }
                if ($currentStep>=3 && strtolower($appData['status'])==='approved') {
                    $activities[]=[
                        'title'=>"Application Approved",
                        'datetime'=>!empty($appData['finalized_at'])?date("F d, Y - h:i A",strtotime($appData['finalized_at'])):'Not recorded',
                        'details'=>"Application {$formattedAppNumber} approved."
                    ];
                }
                ?>
                <?php foreach ($activities as $activity): ?>
                    <div class="p-3 mb-3" style="background:#f8f9fa; border-radius:10px;">
                        <p class="fw-semibold mb-1"><?= htmlspecialchars($activity['title']) ?></p>
                        <p class="mb-1 text-muted"><?= htmlspecialchars($activity['datetime']) ?></p>
                        <p class="mb-0"><?= htmlspecialchars($activity['details']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="text-center p-5 bg-white rounded shadow-sm">
                <i class="bi bi-search fs-1 text-primary mb-3"></i>
                <h3>Track Your Application</h3>
                <p class="text-muted">Enter your tracking number above to view application details.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
