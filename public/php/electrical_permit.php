
<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    header("Location: loginform.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electrical Permit Application</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS for Permit Form -->
    <link rel="stylesheet" href="../css/permit.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm" style="background: white;">
        <div class="container-fluid py-2">

            <!-- Left-aligned group -->
            <div class="d-flex align-items-center">
                <!-- Back Button -->
                <button class="btn btn-light rounded-circle me-2" onclick="goBack()">
                    <i class="bi bi-arrow-left"></i>
                </button>

                <!-- Icon + Title -->
                <div class="d-flex align-items-center">
                    <div class="bg-gradient rounded-3 p-2 me-2"
                        style="background: linear-gradient(135deg, rgb(59, 130, 246), rgb(147, 197, 253));">
                        <i class="bi bi-lightning-charge text-white fs-5"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold">Electrical Permit Application</h4>
                        <small class="text-muted d-none d-sm-block">Permit application for electrical works</small>
                    </div>
                </div>
            </div>

        </div>
    </nav>

    <!-- Electrical Permit Form -->
    <div class="container py-4">
        <form id="electricalPermitForm" class="mx-auto" style="max-width: 1000px;" method="POST"
            action="permit_submit.php" enctype="multipart/form-data">

            <!-- Hidden input to identify permit type -->
            <input type="hidden" name="permit_type" value="electrical">

            <!-- Applicant Information -->
            <div class="card form-card mb-4">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(59, 130, 246);">
                        <i class="bi bi-person-badge me-2"></i>
                        Applicant Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Full Name *</label>
                            <input type="text" class="form-control" name="full_name" placeholder="Enter your full name"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Contact Number *</label>
                            <input type="tel" class="form-control" name="contact_number" placeholder="+63 XXX XXX XXXX"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email Address *</label>
                            <input type="email" class="form-control" name="email" placeholder="your.email@example.com"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Address *</label>
                            <input type="text" class="form-control" name="address" placeholder="Complete address"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Electrical Project Details -->
            <div class="card form-card mb-4">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(142, 69, 238);">
                        <i class="bi bi-lightning-charge me-2"></i>
                        Electrical Project Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium">Project Location *</label>
                            <textarea class="form-control" name="project_location" rows="3"
                                placeholder="Complete address including lot, block, subdivision" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Types of Installation *</label>
                            <select class="form-select" name="installation_type" required>
                                <option value="">Select installation type</option>
                                <option value="New Installation">New Installation</option>
                                <option value="Extension of Existing Installation">Extension of Existing Installation
                                </option>
                                <option value="Upgrading / Modernization">Upgrading / Modernization</option>
                                <option value="Temporary Installation">Temporary Installation</option>
                                <option value="Repair / Maintenance">Repair / Maintenance</option>
                            </select>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label fw-medium">Electrical Work Scope *</label>
                            <select class="form-select" name="work_scope" required>
                                <option value="">Select work scope</option>
                                <option value="residential">Residential Wiring</option>
                                <option value="commercial">Commercial / Office Wiring</option>
                                <option value="industrial">Industrial Installation</option>
                                <option value="lighting">Lighting System</option>
                                <option value="power">Power Distribution</option>
                                <option value="generator">Generator / Backup System</option>
                                <option value="others">Others (Specify in Notes)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Required Documents -->
            <div class="card form-card mb-4">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(16, 185, 129);">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Required Documents
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Upload all necessary documents for electrical permit processing.</p>

                    <!-- Certificate of Completion -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Electrical Permit Application Form *</label>
                        <div class="upload-area" onclick="document.getElementById('completionCert').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Certificate of Completion</p>
                            <small class="text-muted">PDF, JPG, PNG up to 10MB</small>
                            <input type="file" id="completionCert" name="completion_cert" class="d-none"
                                accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'completionCertPreview')"
                                required>
                        </div>
                        <div id="completionCertPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- As-Built Plans -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Approved Electrical Plans *</label>
                        <div class="upload-area" onclick="document.getElementById('asBuiltPlans').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload As-Built Plans</p>
                            <small class="text-muted">Signed and sealed by licensed professional</small>
                            <input type="file" id="asBuiltPlans" name="as_built_plans" class="d-none"
                                accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'asBuiltPlansPreview')"
                                required>
                        </div>
                        <div id="asBuiltPlansPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Barangay Clearance -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Barangay Clearance *</label>
                        <div class="upload-area" onclick="document.getElementById('electricalCert').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Barangay Clearance</p>
                            <small class="text-muted">Certificate from local barangay</small>
                            <input type="file" id="electricalCert" name="electrical_certificate" class="d-none"
                                accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'electricalCertPreview')"
                                required>
                        </div>
                        <div id="electricalCertPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Building Permit -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Building Permit (if required) *</label>
                        <div class="upload-area" onclick="document.getElementById('plumbingCert').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Building Permit</p>
                            <small class="text-muted">Certificate from licensed professional</small>
                            <input type="file" id="plumbingCert" name="building_permit" class="d-none"
                                accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'plumbingCertPreview')"
                                required>
                        </div>
                        <div id="plumbingCertPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Electrical Inspection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Electrical Inspection Report *</label>
                        <div class="upload-area" onclick="document.getElementById('fireSafetyCert').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Electrical Inspection Report</p>
                            <small class="text-muted">Certificate from Fire or Electrical Bureau</small>
                            <input type="file" id="fireSafetyCert" name="inspection_report" class="d-none"
                                accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'fireSafetyCertPreview')"
                                required>
                        </div>
                        <div id="fireSafetyCertPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="card form-card mb-4">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold text-secondary">
                        <i class="bi bi-journal-text me-2"></i>
                        Additional Notes
                    </h5>
                </div>
                <div class="card-body p-4">
                    <textarea class="form-control" name="additional_notes" rows="4"
                        placeholder="Any additional information or special circumstances regarding your electrical work..."></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-gradient btn-lg px-5" id="submitBtn">
                    <i class="bi bi-send me-2"></i>
                    Submit Application
                </button>
            </div>
        </form>
    </div>

    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../javascript/permit.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>