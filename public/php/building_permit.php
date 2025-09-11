<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Permit Application - Municipal Permit System</title>

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
                        style="background: linear-gradient(135deg, rgb(142, 69, 238), rgb(168, 85, 247));">
                        <i class="bi bi-building text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold">Building Permit Application</h5>
                        <small class="text-muted d-none d-sm-block">New construction, renovation, or structural
                            modifications</small>
                    </div>
                </div>
            </div>

        </div>
    </nav>

    <!-- Form Content -->
    <div class="container py-4">
        <form id="buildingPermitForm" class="mx-auto" style="max-width: 1000px;">

            <!-- Applicant Information -->
            <div class="card form-card mb-4">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(142, 69, 238);">
                        <i class="bi bi-person-badge me-2"></i>
                        Applicant Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Full Name *</label>
                            <input type="text" class="form-control" name="fullName" placeholder="Enter your full name"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Contact Number *</label>
                            <input type="tel" class="form-control" name="contactNumber" placeholder="+63 XXX XXX XXXX"
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

            <!-- Project Details -->
            <div class="card form-card mb-4">
                <div class="section-header">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center" style="color: rgb(59, 130, 246);">
                        <i class="bi bi-building me-2"></i>
                        Project Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium">Project Location *</label>
                            <textarea class="form-control" name="projectLocation" rows="3"
                                placeholder="Complete address including lot number, block number, and subdivision"
                                required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Type of Construction *</label>
                            <select class="form-select" name="constructionType" required>
                                <option value="">Select construction type</option>
                                <option value="residential">Residential</option>
                                <option value="commercial">Commercial</option>
                                <option value="industrial">Industrial</option>
                                <option value="institutional">Institutional</option>
                                <option value="mixed-use">Mixed Use</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Estimated Project Cost *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" name="estimatedCost" min="1"
                                    placeholder="Enter estimated cost" required>
                            </div>
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
                    <p class="text-muted mb-4">Upload clear copies of all required documents. Accepted formats: PDF,
                        JPG, PNG (max 10MB each)</p>

                    <!-- Application Form -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Application Form *</label>
                        <div class="upload-area" onclick="document.getElementById('applicationForm').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Application Form</p>
                            <small class="text-muted">PDF, JPG, PNG up to 10MB</small>
                            <input type="file" id="applicationForm" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="handleFileUpload(this, 'applicationFormPreview')" required>
                        </div>
                        <div id="applicationFormPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Location Plan -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Location/Lot Plan *</label>
                        <div class="upload-area" onclick="document.getElementById('locationPlan').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Location/Lot Plan</p>
                            <small class="text-muted">Original or certified copy</small>
                            <input type="file" id="locationPlan" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="handleFileUpload(this, 'locationPlanPreview')" required>
                        </div>
                        <div id="locationPlanPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Tax Declaration -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Tax Declaration of Property *</label>
                        <div class="upload-area" onclick="document.getElementById('taxDeclaration').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Tax Declaration</p>
                            <small class="text-muted">Current tax declaration of the property</small>
                            <input type="file" id="taxDeclaration" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="handleFileUpload(this, 'taxDeclarationPreview')" required>
                        </div>
                        <div id="taxDeclarationPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Architectural Plans -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Architectural Plans & Specifications *</label>
                        <div class="upload-area" onclick="document.getElementById('architecturalPlans').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Architectural Plans</p>
                            <small class="text-muted">Signed and sealed by licensed architect/engineer</small>
                            <input type="file" id="architecturalPlans" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="handleFileUpload(this, 'architecturalPlansPreview')" required>
                        </div>
                        <div id="architecturalPlansPreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Barangay Clearance -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Barangay Clearance *</label>
                        <div class="upload-area" onclick="document.getElementById('barangayClearance').click()">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                            <p class="mb-1 fw-medium">Click to upload Barangay Clearance</p>
                            <small class="text-muted">Valid barangay clearance for construction</small>
                            <input type="file" id="barangayClearance" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="handleFileUpload(this, 'barangayClearancePreview')" required>
                        </div>
                        <div id="barangayClearancePreview" class="file-preview">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-medium">File uploaded successfully!</span>
                        </div>
                    </div>

                    <!-- Optional Documents -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Optional Documents (if applicable)</label>
                        <div class="row g-3">
                            <!-- Environmental Clearance -->
                            <div class="col-md-6">
                                <div class="upload-area"
                                    onclick="document.getElementById('environmentalClearance').click()">
                                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                                    <p class="mb-1 fw-medium">Click to upload Environmental Clearance</p>
                                    <small class="text-muted">PDF, JPG, PNG up to 10MB</small>
                                    <input type="file" id="environmentalClearance" class="d-none"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        onchange="handleFileUpload(this, 'environmentalClearancePreview')">
                                </div>
                                <div id="environmentalClearancePreview" class="file-preview">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <span class="fw-medium">File uploaded successfully!</span>
                                </div>
                            </div>

                            <!-- Contract of Lease -->
                            <div class="col-md-6">
                                <div class="upload-area" onclick="document.getElementById('contractOfLease').click()">
                                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary mb-2"></i>
                                    <p class="mb-1 fw-medium">Click to upload Contract of Lease</p>
                                    <small class="text-muted">PDF, JPG, PNG up to 10MB</small>
                                    <input type="file" id="contractOfLease" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                                        onchange="handleFileUpload(this, 'contractOfLeasePreview')">
                                </div>
                                <div id="contractOfLeasePreview" class="file-preview">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <span class="fw-medium">File uploaded successfully!</span>
                                </div>
                            </div>
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
                    <textarea class="form-control" name="additionalNotes" rows="4"
                        placeholder="Any additional information or special circumstances regarding your project..."></textarea>
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
                    <p class="text-muted mb-0">Your building permit application has been received. You'll receive a
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