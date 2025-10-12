@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main">

    <div class="pagetitle mb-3">
        <h1>Settings</h1>
    </div><!-- End Page Title -->

    <section class="section settings">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="row">
                    <!-- Vertical tab navigation -->
                    <div class="col-md-3 border-end">
                        <div class="nav flex-column nav-pills" id="settings-tab" role="tablist"
                            aria-orientation="vertical">
                            <button
                                class="nav-link active mb-2 text-start d-flex align-items-center justify-content-between"
                                id="frontpage-tab" data-bs-toggle="pill" data-bs-target="#frontpage" type="button"
                                role="tab" aria-controls="frontpage" aria-selected="true">
                                <span>App Name</span>
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button class="nav-link mb-2 text-start d-flex align-items-center justify-content-between"
                                id="changepassword-tab" data-bs-toggle="pill" data-bs-target="#changepassword"
                                type="button" role="tab" aria-controls="changepassword" aria-selected="false">
                                <span>Change Password</span>
                                <i class="bi bi-lock-fill"></i>
                            </button>

                            <!-- Payment Tab -->
                            <button class="nav-link mb-2 text-start d-flex align-items-center justify-content-between"
                                id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button"
                                role="tab" aria-controls="payment" aria-selected="false">
                                <span>Payment</span>
                                <i class="bi bi-credit-card-2-front-fill"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tab content -->
                    <div class="col-md-9">
                        <div class="tab-content" id="settings-tabContent">
                            <!-- App Name tab -->
                            <div class="tab-pane fade show active p-3 rounded shadow-sm border bg-white" id="frontpage"
                                role="tabpanel" aria-labelledby="frontpage-tab">
                                @include('admin.pages.settings.frontpage')
                            </div>

                            <!-- Change Password tab -->
                            <div class="tab-pane fade p-3 rounded shadow-sm border bg-white" id="changepassword"
                                role="tabpanel" aria-labelledby="changepassword-tab">
                                @include('admin.pages.settings.changepassword')
                            </div>

                            <!-- Payment tab content -->
                            <div class="tab-pane fade p-3 rounded shadow-sm border bg-white" id="payment"
                                role="tabpanel" aria-labelledby="payment-tab">

                                <!-- Payment Form -->
                                <form action="{{ route('admin.settings.payment.save') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <input type="text" class="form-control" id="payment_method"
                                            name="payment_method" placeholder="e.g., GCash" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_number" class="form-label">Payment Number</label>
                                        <input type="text" class="form-control" id="payment_number"
                                            name="payment_number" placeholder="Enter payment number" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_qr" class="form-label">QR Code (Image)</label>
                                        <input type="file" class="form-control" id="payment_qr" name="payment_qr"
                                            accept="image/*">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Payment Option</button>
                                </form>

                                <hr>

                                <!-- List of Payments -->
                                <h5 class="mt-3">Saved Payments</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Method</th>
                                                <th>Number</th>
                                                <th>QR Code</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($payments as $index => $payment)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $payment->method }}</td>
                                                    <td>{{ $payment->number }}</td>
                                                    <td>
                                                        @if($payment->qr)
                                                            <img src="{{ asset('storage/' . $payment->qr) }}" alt="QR"
                                                                style="max-height:80px;">
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form
                                                            action="{{ route('admin.settings.payment.delete', $payment->id) }}"
                                                            method="POST" onsubmit="return confirm('Delete this payment?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="modal" data-bs-target="#deletePaymentModal"
                                                                data-action="{{ route('admin.settings.payment.delete', $payment->id) }}">
                                                                Delete
                                                            </button>

                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No payments saved yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deletePaymentModal" tabindex="-1" aria-labelledby="deletePaymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deletePaymentForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePaymentModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this payment option?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


@include('admin.partials.__footer')