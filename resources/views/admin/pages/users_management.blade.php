@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main users-management">

    <div class="pagetitle">
        <h1>User Management</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs flex-grow-1" id="userTabs" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold choose-type active" id="staff-tab" data-bs-toggle="tab"
                                data-bs-target="#staff" type="button" role="tab" aria-controls="staff"
                                aria-selected="true">
                                Staff
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold choose-type" id="inspector-tab" data-bs-toggle="tab"
                                data-bs-target="#inpector" type="button" role="tab" aria-controls="inpector"
                                aria-selected="false">
                                Inpector
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold choose-type" id="applicant-tab" data-bs-toggle="tab"
                                data-bs-target="#applicant" type="button" role="tab" aria-controls="applicant"
                                aria-selected="false">
                                Applicant
                            </button>
                        </li>
                    </ul>

                    <!-- Add Account Button -->
                    <a href="{{ url('admin/add_staff_account') }}" class="btn btn-primary btn-sm ms-3"
                        id="addAccountBtn" data-staff-url="{{ url('admin/add_staff_account') }}"
                        data-inspector-url="{{ url('admin/add_inspector_account') }}"
                        data-applicant-url="{{ url('admin/add_applicant_account') }}">
                        <i class="bi bi-plus-circle me-1"></i> Add Account
                    </a>
                </div>

                <!-- User Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle shadow-sm rounded" id="users_table">
                        <thead class="table-light">
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- User Profile Modal --}}
    <div class="modal fade" id="userProfileModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="userProfileTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"></div>
    </div>

    {{-- Module Access Modal --}}
    <div class="modal fade" id="moduleAccessModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="moduleAccessTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"></div>
    </div>

    {{-- Confirm Password Modal --}}
    <div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-labelledby="confirmPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <form id="confirm_password_form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Confirm Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Please enter your password to confirm this action.</p>
                        <input type="password" name="superadmin_password" class="form-control" id="superAdminPassword"
                            placeholder="Enter your password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Access Modal --}}
    <div class="modal fade" id="deleteAccessConfirmModal" tabindex="-1" aria-labelledby="deleteAccessLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <form id="delete_access_form">
                    @csrf
                    <input type="hidden" id="deleteAccessId">
                    <input type="hidden" id="deleteUserId">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">To delete this module access, please enter your password:</p>
                        <input type="password" class="form-control" id="deleteAccessPassword"
                            placeholder="Enter your password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

@include('admin.partials.__footer')