<?php
session_start();
include("db.php");

// Allow only admins
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginform.php");
    exit;
}

// Validate application ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid application ID.");
}
$appId = intval($_GET['id']);

// ✅ Update status to "under review" if currently "submitted"
$updateStatus = $conn->prepare("UPDATE permit_applications SET status = 'under review' WHERE id = ? AND status IN ('submitted','pending')");
$updateStatus->bind_param("i", $appId);
$updateStatus->execute();
$updateStatus->close();

// Fetch application info
$appQuery = $conn->prepare("SELECT * FROM permit_applications WHERE id = ?");
$appQuery->bind_param("i", $appId);
$appQuery->execute();
$appResult = $appQuery->get_result();

if ($appResult->num_rows === 0) {
    die("Application not found.");
}
$application = $appResult->fetch_assoc();
$appQuery->close();

// Fetch uploaded documents
$docQuery = $conn->prepare("SELECT * FROM permit_documents WHERE application_id = ?");
$docQuery->bind_param("i", $appId);
$docQuery->execute();
$documents = $docQuery->get_result()->fetch_all(MYSQLI_ASSOC);
$docQuery->close();

// Fetch inspection schedule
$inspectQuery = $conn->prepare("SELECT * FROM inspections WHERE application_id = ?");
$inspectQuery->bind_param("i", $appId);
$inspectQuery->execute();
$inspection = $inspectQuery->get_result()->fetch_assoc();
$inspectQuery->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Application #<?php echo htmlspecialchars($application['application_number']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Top Navigation Bar -->
<nav class="navbar navbar-dark bg-primary shadow-sm mb-4 py-3">
    <div class="container-fluid d-flex align-items-center">
        <!-- Back Button -->
        <a href="admin_dashboard.php" 
           class="btn btn-light btn-sm d-flex align-items-center px-3 py-1 fw-semibold rounded-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                 class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
                <path fill-rule="evenodd" 
                      d="M15 8a.5.5 0 0 1-.5.5H2.707l4.147 
                         4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 
                         0 0 1 0-.708l5-5a.5.5 
                         0 0 1 .708.708L2.707 7.5H14.5A.5.5 
                         0 0 1 15 8z"/>
            </svg>
            Back
        </a>

        <!-- Page Title -->
        <span class="navbar-text text-white fw-bold ms-3 fs-5">
            Review Application <span class="text-warning">
            #<?php echo htmlspecialchars($application['application_number']); ?></span>
        </span>
    </div>
</nav>

<div class="container py-5">

    <!-- Applicant Information -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Applicant Information</h5>
        </div>
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr><th width="200">Applicant</th><td><?php echo htmlspecialchars($application['full_name']); ?></td></tr>
                <tr><th>Contact</th><td><?php echo htmlspecialchars($application['contact_number']); ?> | <?php echo htmlspecialchars($application['email']); ?></td></tr>
                <tr><th>Address</th><td><?php echo htmlspecialchars($application['address']); ?></td></tr>
                <tr><th>Project Location</th><td><?php echo htmlspecialchars($application['project_location']); ?></td></tr>
                <tr><th>Permit Type</th><td><span class="badge bg-info text-dark"><?php echo ucfirst($application['permit_type']); ?></span></td></tr>
                <tr>
                    <th>Status</th>
                    <td><span class="badge bg-<?php echo $application['status'] === 'approved' ? 'success' : ($application['status'] === 'pending' ? 'warning' : 'danger'); ?>">
                        <?php echo ucfirst($application['status']); ?>
                    </span></td>
                </tr>
                <tr><th>Date Submitted</th><td><?php echo date("M d, Y", strtotime($application['created_at'])); ?></td></tr>
            </table>
        </div>
    </div>

    <!-- Application Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Application Details</h5>
        </div>
        <div class="card-body">
            <?php if ($application['permit_type'] === 'building'): ?>
                <p><strong>Construction Type:</strong> <?php echo htmlspecialchars($application['construction_type']); ?></p>
                <p><strong>Estimated Cost:</strong> ₱<?php echo number_format($application['estimated_cost'], 2); ?></p>
            <?php elseif ($application['permit_type'] === 'electrical'): ?>
                <p><strong>Installation Type:</strong> <?php echo htmlspecialchars($application['installation_type']); ?></p>
                <p><strong>Work Scope:</strong> <?php echo htmlspecialchars($application['work_scope']); ?></p>
            <?php elseif ($application['permit_type'] === 'plumbing'): ?>
                <p><strong>Installation Type:</strong> <?php echo htmlspecialchars($application['installation_type']); ?></p>
                <p><strong>Permit Purpose:</strong> <?php echo htmlspecialchars($application['permit_purpose']); ?></p>
            <?php elseif ($application['permit_type'] === 'occupancy'): ?>
                <p><strong>Date Issued:</strong> <?php echo htmlspecialchars($application['date_issued']); ?></p>
            <?php endif; ?>

            <?php if (!empty($application['additional_notes'])): ?>
                <div class="alert alert-info mt-3">
                    <strong>Notes:</strong><br>
                    <?php echo nl2br(htmlspecialchars($application['additional_notes'])); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Uploaded Documents -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Uploaded Documents</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($documents)): ?>
                <div class="row">
                    <?php foreach ($documents as $doc): 
                        $filePath = "../upload/" . basename($doc['file_path']);
                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="card-title mb-3"><?php echo htmlspecialchars($doc['document_type']); ?></h6>
                                    <?php if (in_array($ext, ['jpg','jpeg','png','gif','webp'])): ?>
                                        <img src="<?php echo $filePath; ?>" class="img-fluid rounded mb-2" alt="Document Image">
                                        <a href="<?php echo $filePath; ?>" target="_blank" class="btn btn-sm btn-outline-primary">View Full</a>
                                    <?php elseif ($ext === 'pdf'): ?>
                                        <embed src="<?php echo $filePath; ?>" type="application/pdf" width="100%" height="200px" class="mb-2"/>
                                        <a href="<?php echo $filePath; ?>" target="_blank" class="btn btn-sm btn-outline-primary">Open PDF</a>
                                    <?php else: ?>
                                        <a href="<?php echo $filePath; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Download File</a>
                                    <?php endif; ?>
                                    <p class="text-muted small mt-2 mb-0">
                                        Uploaded: <?php echo date("M d, Y h:i A", strtotime($doc['uploaded_at'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No documents uploaded.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Inspection Schedule -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Inspection Schedule</h5>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal" 
                <?php echo $inspection ? 'disabled' : ''; ?>>
                + Schedule Inspection
            </button>
        </div>
        <div class="card-body">
            <?php if ($inspection): ?>
                <p><strong>Date:</strong> <?php echo date("M d, Y", strtotime($inspection['inspection_date'])); ?></p>
                <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($inspection['inspection_time'])); ?></p>
                <p><strong>Inspector:</strong> <?php echo htmlspecialchars($inspection['inspector_name']); ?></p>
                <p><strong>Status:</strong> <span class="badge bg-info"><?php echo ucfirst($inspection['status']); ?></span></p>
            <?php else: ?>
                <p class="text-muted">No inspection scheduled yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for Scheduling Inspection -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="scheduleForm" method="POST" action="schedule_inspection.php">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Schedule Inspection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="application_id" value="<?php echo $appId; ?>">

                        <div class="mb-3">
                            <label class="form-label">Inspection Date</label>
                            <input type="date" name="inspection_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Inspection Time</label>
                            <input type="time" name="inspection_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Inspector</label>
                            <input type="text" name="inspector" class="form-control" placeholder="Enter inspector's name" required>
                        </div>
                        <div id="scheduleMessage" class="alert d-none mt-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Schedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("scheduleForm");
    const messageDiv = document.getElementById("scheduleMessage");

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch(form.action, { method: "POST", body: formData })
        .then(response => response.json())
.then(data => {
    if (data.success) {
        messageDiv.classList.remove("d-none", "alert-danger");
        messageDiv.classList.add("alert-success");
        messageDiv.textContent = "Schedule saved successfully!";

        // Disable schedule button
        const scheduleBtn = document.querySelector('[data-bs-target="#scheduleModal"]');
        if (scheduleBtn) scheduleBtn.disabled = true;

        // Update the status badge dynamically
        const statusBadge = document.querySelector('td span.badge');
        if (statusBadge) {
            statusBadge.textContent = "Scheduled";
            statusBadge.className = "badge bg-info"; // or any color you want
        }

        setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById("scheduleModal"));
            modal.hide();
        }, 1500);
    } else {
        messageDiv.classList.remove("d-none", "alert-success");
        messageDiv.classList.add("alert-danger");
        messageDiv.textContent = data.message || "Failed to save schedule.";
    }
})

        .catch(err => {
            console.error(err);
            messageDiv.classList.remove("d-none", "alert-success");
            messageDiv.classList.add("alert-danger");
            messageDiv.textContent = "An error occurred. Try again.";
        });
    });
});
</script>
</body>
</html>
