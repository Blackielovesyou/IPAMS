@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>User Verification</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item active">User Verification</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-4">
                <div class="card info-card cardiconbg cardborderleft-blue">
                    <div class="card-body">
                        <h5 class="card-title">Approved</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-hand-thumbs-up"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="count_approved_verification">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card info-card cardiconbg cardborderleft-red">
                    <div class="card-body">
                        <h5 class="card-title">Declined</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-hand-thumbs-down"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="count_declined_verification">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card info-card cardiconbg cardborderleft-yellow">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-app-indicator"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="count_pending_verification">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body py-3 table-responsive">
                <table class="table" id="users_verification_table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>File Link</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
    <div class="modal fade" id="userProfileModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="userProfileTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document"></div>
    </div>
    <div class="modal fade" id="moduleAccessModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="moduleAccessTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        </div>
    </div>
</main>

@include('admin.partials.__footer')
