@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Users Logging / Audit trails</h1>
        
    </div><!-- End Page Title -->
    <section class="section user_management">
        <div class="card">
            <div class="card-body py-3 table-responsive">

                <div class="row mb-3">
                    <div class="col-lg-3">
                        <label for="audit_date_from" class="form-label">Date From</label>
                        <input type="date" id="audit_date_from" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                            class="form-control" placeholder="From">
                    </div>
                    <div class="col-lg-3">
                        <label for="audit_date_to" class="form-label">Date To</label>
                        <input type="date" id="audit_date_to" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                            class="form-control" placeholder="To">
                    </div>
                </div>
                <table class="table" id="audit_table">
                    <thead>
                        <tr>
                            <th>IP</th>
                            <th>User ID</th>
                            <th>Type</th>
                            <th>Content</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</main>

@include('admin.partials.__footer')