@include('user.partials.__header')
@include('user.partials.__nav')

<main id="main" class="main">
    <div class="container py-4">

        @php
            $type = $type ?? request()->query('type');
        @endphp

        {{-- ✅ Building Permit --}}
        @if ($type === 'building')
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="fw-bold mb-0 d-flex align-items-center" style="color: rgb(142, 69, 238);">
                        <i class="bi bi-building me-2"></i> Building Permit Application
                    </h4>
                    <p class="text-muted small mb-0">New construction, renovation, or structural modifications</p>
                </div>

                <div class="card-body p-4">
                    <form>
                        <!-- Applicant Information -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-primary mb-3">
                                <i class="bi bi-person-badge me-2"></i> Applicant Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Full Name</label>
                                    <input type="text" class="form-control" name="full_name"
                                        placeholder="Enter your full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number"
                                        placeholder="+63 XXX XXX XXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="your.email@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Address</label>
                                    <input type="text" class="form-control" name="address" placeholder="Complete address">
                                </div>
                            </div>
                        </div>

                        <!-- Project Details -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-info mb-3">
                                <i class="bi bi-hammer me-2"></i> Project Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Project Location</label>
                                    <textarea class="form-control" name="project_location" rows="3"
                                        placeholder="Complete address including lot and block number"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Type of Construction</label>
                                    <select class="form-select" name="construction_type">
                                        <option value="">Select construction type</option>
                                        <option value="Residential">Residential</option>
                                        <option value="Commercial">Commercial</option>
                                        <option value="Industrial">Industrial</option>
                                        <option value="Institutional">Institutional</option>
                                        <option value="Mixed Use">Mixed Use</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Estimated Project Cost</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="estimated_cost"
                                            placeholder="Enter amount">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-success mb-3">
                                <i class="bi bi-file-earmark-text me-2"></i> Required Documents
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Application Form</label>
                                    <input type="file" class="form-control" name="application_form">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Location/Lot Plan</label>
                                    <input type="file" class="form-control" name="location_plan">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Tax Declaration of Property</label>
                                    <input type="file" class="form-control" name="tax_declaration">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Architectural Plans & Specifications</label>
                                    <input type="file" class="form-control" name="architectural_plans">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Barangay Clearance</label>
                                    <input type="file" class="form-control" name="barangay_clearance">
                                </div>
                            </div>
                        </div>

                        <!-- Optional Documents -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-secondary mb-3">
                                <i class="bi bi-folder-plus me-2"></i> Optional Documents
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Environmental Clearance</label>
                                    <input type="file" class="form-control" name="environmental_clearance">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Contract of Lease</label>
                                    <input type="file" class="form-control" name="contract_of_lease">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-muted mb-3">
                                <i class="bi bi-journal-text me-2"></i> Additional Notes
                            </h5>
                            <textarea class="form-control" name="additional_notes" rows="4"
                                placeholder="Any additional information or remarks..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="button" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-send me-2"></i> Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ✅ Occupancy Permit --}}
        @elseif ($type === 'occupancy')
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="fw-bold mb-0 d-flex align-items-center" style="color: rgb(59, 130, 246);">
                        <i class="bi bi-house-check me-2"></i> Occupancy Permit Application
                    </h4>
                    <p class="text-muted small mb-0">Certificate of occupancy for completed buildings</p>
                </div>

                <div class="card-body p-4">
                    <form>
                        <!-- Applicant Information -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-primary mb-3">
                                <i class="bi bi-person-badge me-2"></i> Applicant Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" placeholder="Enter full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Contact Number</label>
                                    <input type="tel" class="form-control" name="contact_number"
                                        placeholder="+63 XXX XXX XXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="your.email@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Address</label>
                                    <input type="text" class="form-control" name="address" placeholder="Complete address ">
                                </div>
                            </div>
                        </div>

                        <!-- Project Details -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-info mb-3">
                                <i class="bi bi-geo-alt me-2"></i> Project Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Project Location</label>
                                    <textarea class="form-control" name="project_location" rows="3"
                                        placeholder="Building address including lot/block/subdivision"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Construction Type</label>
                                    <select class="form-select" name="construction_type">
                                        <option value="">Select construction type</option>
                                        <option>Residential</option>
                                        <option>Commercial</option>
                                        <option>Industrial</option>
                                        <option>Institutional</option>
                                        <option>Mixed Use</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Date Issued</label>
                                    <input type="date" class="form-control" name="date_issued">
                                </div>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-success mb-3">
                                <i class="bi bi-file-earmark-text me-2"></i> Required Documents
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Application Form (Occupancy)</label>
                                    <input type="file" class="form-control" name="completionCert">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Certificate of Completion</label>
                                    <input type="file" class="form-control" name="asBuiltPlans">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">As-Built Plans</label>
                                    <input type="file" class="form-control" name="electricalCert">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Final Electrical Inspection Report</label>
                                    <input type="file" class="form-control" name="plumbingCert">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Final Plumbing Inspection Report</label>
                                    <input type="file" class="form-control" name="fireSafetyCert">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Approved Building Permit</label>
                                    <input type="file" class="form-control" name="approvedBuildingPermit">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-muted mb-3">
                                <i class="bi bi-journal-text me-2"></i> Additional Notes
                            </h5>
                            <textarea class="form-control" name="additional_notes" rows="4"
                                placeholder="Any additional info or remarks..."></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="text-center">
                            <button type="button" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-send me-2"></i> Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ✅ Electrical Permit --}}
        @elseif ($type === 'electrical')
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="fw-bold mb-0 d-flex align-items-center" style="color: rgb(255, 193, 7);">
                        <i class="bi bi-lightning-charge me-2"></i> Electrical Permit Application
                    </h4>
                    <p class="text-muted small mb-0">Permit application for electrical works and installations</p>
                </div>

                <div class="card-body p-4">
                    <form>
                        <!-- Applicant Information -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-primary mb-3">
                                <i class="bi bi-person-badge me-2"></i> Applicant Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Full Name</label>
                                    <input type="text" class="form-control" name="full_name"
                                        placeholder="Enter your full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number"
                                        placeholder="+63 XXX XXX XXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="your.email@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Address</label>
                                    <input type="text" class="form-control" name="address" placeholder="Complete address">
                                </div>
                            </div>
                        </div>

                        <!-- Electrical Project Details -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-warning mb-3">
                                <i class="bi bi-lightning me-2"></i> Electrical Project Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Project Location</label>
                                    <textarea class="form-control" name="project_location" rows="3"
                                        placeholder="Complete address including lot, block, subdivision"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Type of Installation</label>
                                    <select class="form-select" name="installation_type">
                                        <option value="">Select installation type</option>
                                        <option>New Installation</option>
                                        <option>Extension of Existing Installation</option>
                                        <option>Upgrading / Modernization</option>
                                        <option>Temporary Installation</option>
                                        <option>Repair / Maintenance</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Work Scope</label>
                                    <select class="form-select" name="work_scope">
                                        <option value="">Select work scope</option>
                                        <option>Residential Wiring</option>
                                        <option>Commercial / Office Wiring</option>
                                        <option>Industrial Installation</option>
                                        <option>Lighting System</option>
                                        <option>Power Distribution</option>
                                        <option>Generator / Backup System</option>
                                        <option>Others (Specify in Notes)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-success mb-3">
                                <i class="bi bi-file-earmark-text me-2"></i> Required Documents
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Electrical Permit Application Form</label>
                                    <input type="file" class="form-control" name="completion_cert">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Approved Electrical Plans</label>
                                    <input type="file" class="form-control" name="as_built_plans">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Barangay Clearance</label>
                                    <input type="file" class="form-control" name="barangay_clearance">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Building Permit (if required)</label>
                                    <input type="file" class="form-control" name="building_permit">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Electrical Inspection Report</label>
                                    <input type="file" class="form-control" name="inspection_report">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-muted mb-3">
                                <i class="bi bi-journal-text me-2"></i> Additional Notes
                            </h5>
                            <textarea class="form-control" name="additional_notes" rows="4"
                                placeholder="Any additional information or special circumstances..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="button" class="btn btn-warning px-5 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-send me-2"></i> Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            {{-- ✅ Plumbing Permit --}}
        @elseif ($type === 'plumbing')
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="fw-bold mb-0 d-flex align-items-center" style="color: rgb(34, 197, 94);">
                        <i class="bi bi-droplet-half me-2"></i> Plumbing Permit Application
                    </h4>
                    <p class="text-muted small mb-0">Permit application for plumbing works and water system installations
                    </p>
                </div>

                <div class="card-body p-4">
                    <form>
                        <!-- Applicant Information -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-primary mb-3">
                                <i class="bi bi-person-badge me-2"></i> Applicant Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" placeholder="Enter full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number"
                                        placeholder="+63 XXX XXX XXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="your.email@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Address</label>
                                    <input type="text" class="form-control" name="address" placeholder="Complete address">
                                </div>
                            </div>
                        </div>

                        <!-- Plumbing Project Details -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-success mb-3">
                                <i class="bi bi-pipe me-2"></i> Plumbing Project Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Project Location</label>
                                    <textarea class="form-control" name="project_location" rows="3"
                                        placeholder="Complete address including lot/block/subdivision"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Type of Installation</label>
                                    <select class="form-select" name="work_type">
                                        <option value="">Select installation type</option>
                                        <option>New Installation</option>
                                        <option>Repair / Replacement</option>
                                        <option>Extension / Modification</option>
                                        <option>Demolition / Removal</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Building Type</label>
                                    <select class="form-select" name="building_type">
                                        <option value="">Select building type</option>
                                        <option>Residential</option>
                                        <option>Commercial</option>
                                        <option>Industrial</option>
                                        <option>Institutional</option>
                                        <option>Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-success mb-3">
                                <i class="bi bi-file-earmark-text me-2"></i> Required Documents
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Plumbing Permit Application Form</label>
                                    <input type="file" class="form-control" name="application_form">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Approved Plumbing Plans</label>
                                    <input type="file" class="form-control" name="approved_plans">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Barangay Clearance</label>
                                    <input type="file" class="form-control" name="barangay_clearance">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Sanitary Permit (if required)</label>
                                    <input type="file" class="form-control" name="sanitary_permit">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Building Permit (if required)</label>
                                    <input type="file" class="form-control" name="building_permit">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-5">
                            <h5 class="fw-semibold text-muted mb-3">
                                <i class="bi bi-journal-text me-2"></i> Additional Notes
                            </h5>
                            <textarea class="form-control" name="additional_notes" rows="4"
                                placeholder="Any additional details or remarks..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="button" class="btn btn-success px-5 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-send me-2"></i> Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Default message --}}
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-info-circle fs-1 mb-3"></i>
                <p>No permit selected. Please go back to <a href="{{ url('user/home') }}">Home</a>.</p>
            </div>
        @endif

    </div>
</main>

@include('user.partials.__footer')