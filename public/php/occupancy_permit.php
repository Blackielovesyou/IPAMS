<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupancy Permit Application - Municipal Permit System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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
                        <i class="bi bi-house-check text-white fs-5"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold">Occupancy Permit Application</h4>
                        <small class="text-muted d-none d-sm-block">Certificate of occupancy for completed
                            buildings</small>
                    </div>
                </div>
            </div>

        </div>
    </nav>


    <!-- Form Content -->
    <div class="container py-4">
<form id="occupancyPermitForm" class="mx-auto" style="max-width: 1000px" method="POST" action="permit_submit.php" enctype="multipart/form-data">
    <input type="hidden" name="permit_type" value="occupancy">

    <!-- Building Owner Information -->
    <div class="card form-card mb-4">
        <div class="section-header">
            <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(59, 130, 246);">
                <i class="bi bi-person-badge me-2"></i>
                Application Information
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Owner's Full Name *</label>
                    <input type="text" class="form-control" name="full_name"
                        placeholder="Enter building owner's full name" required>
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
                    <label class="form-label fw-medium">Owner's Address *</label>
                    <input type="text" class="form-control" name="address"
                        placeholder="Complete address of the owner" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Building Information -->
    <div class="card form-card mb-4">
        <div class="section-header">
            <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(142, 69, 238);">
                <i class="bi bi-geo-alt me-2"></i>
                Project Details
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Project Location *</label>
                    <textarea class="form-control" name="project_location" rows="3"
                        placeholder="Complete address of the building including lot number, block number, and subdivision"
                        required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Type of Construction *</label>
                    <select class="form-select" name="construction_type" required>
                        <option value="">Select construction type</option>
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                        <option value="industrial">Industrial</option>
                        <option value="institutional">Institutional</option>
                        <option value="mixed-use">Mixed Use</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Date Issued *</label>
                    <input type="date" class="form-control" name="date_issued" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Documents (UNCHANGED) -->
    <div class="card form-card mb-4">
        <div class="section-header">
            <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(16, 185, 129);">
                <i class="bi bi-file-earmark-text me-2"></i>
                Required Documents
            </h5>
        </div>
        <div class="card-body p-4">
            <p class="text-muted mb-4">Upload clear copies of all required documents for occupancy permit
                processing.</p>

            <!-- Certificate of Completion -->
            <div class="mb-4">
                <label class="form-label fw-medium">Application Form Occupancy Permit *</label>
                <div class="upload-area" onclick="document.getElementById('completionCert').click()">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                    <p class="mb-1 fw-medium">Click to upload Certificate of Completion</p>
                    <small class="text-muted">PDF, JPG, PNG up to 10MB</small>
                    <input type="file" name="completionCert" id="completionCert" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'completionCertPreview')" required>
                </div>
                <div id="completionCertPreview" class="file-preview">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span class="fw-medium">File uploaded successfully!</span>
                </div>
            </div>

            <!-- As-Built Plans -->
            <div class="mb-4">
                <label class="form-label fw-medium">Certificate of Completion issued by the Building Official *</label>
                <div class="upload-area" onclick="document.getElementById('asBuiltPlans').click()">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                    <p class="mb-1 fw-medium">Click to upload As-Built Plans</p>
                    <small class="text-muted">Signed and sealed by licensed professional</small>
                    <input type="file" name="asBuiltPlans" id="asBuiltPlans" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'asBuiltPlansPreview')" required>
                </div>
                <div id="asBuiltPlansPreview" class="file-preview">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span class="fw-medium">File uploaded successfully!</span>
                </div>
            </div>

            <!-- Electrical Certificate -->
            <div class="mb-4">
                <label class="form-label fw-medium">As-Built Plans(updated plans after construction) *</label>
                <div class="upload-area" onclick="document.getElementById('electricalCert').click()">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                    <p class="mb-1 fw-medium">Click to upload Electrical Certificate</p>
                    <small class="text-muted">Certificate from licensed electrician</small>
                    <input type="file" name="electricalCert" id="electricalCert" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'electricalCertPreview')" required>
                </div>
                <div id="electricalCertPreview" class="file-preview">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span class="fw-medium">File uploaded successfully!</span>
                </div>
            </div>

            <!-- Plumbing Certificate -->
            <div class="mb-4">
                <label class="form-label fw-medium">Final Electrical Inspection Report *</label>
                <div class="upload-area" onclick="document.getElementById('plumbingCert').click()">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                    <p class="mb-1 fw-medium">Click to upload Plumbing Certificate</p>
                    <small class="text-muted">Certificate from licensed plumber</small>
                    <input type="file" name="plumbingCert" id="plumbingCert" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'plumbingCertPreview')" required>
                </div>
                <div id="plumbingCertPreview" class="file-preview">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span class="fw-medium">File uploaded successfully!</span>
                </div>
            </div>

            <!-- Fire Safety Certificate -->
            <div class="mb-4">
                <label class="form-label fw-medium">Final Plumbing Inspection Report *</label>
                <div class="upload-area" onclick="document.getElementById('fireSafetyCert').click()">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                    <p class="mb-1 fw-medium">Click to upload Fire Safety Certificate</p>
                    <small class="text-muted">Certificate from Fire Bureau</small>
                    <input type="file" name="fireSafetyCert" id="fireSafetyCert" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'fireSafetyCertPreview')" required>
                </div>
                <div id="fireSafetyCertPreview" class="file-preview">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span class="fw-medium">File uploaded successfully!</span>
                </div>
            </div>

            <!-- Building Permit Original -->
            <div class="mb-4">
                <label class="form-label fw-medium">Clearance from Fire Department and other relevant offices *</label>
                <div class="upload-area" onclick="document.getElementById('buildingPermitOrig').click()">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                    <p class="mb-1 fw-medium">Click to upload Building Permit</p>
                    <small class="text-muted">Original copy of approved building permit</small>
                    <input type="file" name="buildingPermitOrig" id="buildingPermitOrig" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'buildingPermitOrigPreview')" required>
                </div>
                <div id="buildingPermitOrigPreview" class="file-preview">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span class="fw-medium">File uploaded successfully!</span>
                </div>
            </div>

            <!-- Approved Building Permit -->
            <div class="mb-4">
                <label class="form-label fw-medium">Approved Building Permit and related documents*</label>
                <div class="upload-area" onclick="document.getElementById('approvedBuildingPermit').click()">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                    <p class="mb-1 fw-medium">Click to upload Building Permit</p>
                    <small class="text-muted">Original copy of approved building permit</small>
                    <input type="file" name="approvedBuildingPermit" id="approvedBuildingPermit" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'approvedBuildingPermitPreview')" required>
                </div>
                <div id="approvedBuildingPermitPreview" class="file-preview">
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
                placeholder="Any additional information or special circumstances regarding your building..."></textarea>
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

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 text-center">
                    <div class="w-100">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-check-lg text-white fs-3"></i>
                        </div>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <h5 class="fw-bold mb-2">Application Submitted Successfully!</h5>
                    <p class="text-muted mb-0">Your occupancy permit application has been received. You'll receive a
                        confirmation email shortly with your tracking number.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-gradient" onclick="goBack()">
                        <i class="bi bi-arrow-left me-2"></i>Back to Home
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../javascript/permit.js"></script>
</body>

</html>