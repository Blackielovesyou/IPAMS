@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Feedback</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item active">Feedback</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-4">
                <div class="card info-card cardiconbg cardborderleft-blue">
                    <div class="card-body">
                        <h5 class="card-title">Happy</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-emoji-smile"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="happy">{{ $happyCount }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card info-card cardiconbg cardborderleft-yellow">
                    <div class="card-body">
                        <h5 class="card-title">Neutral</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-emoji-expressionless"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="satisfied">{{ $neutralCount }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card info-card cardiconbg cardborderleft-red">
                    <div class="card-body">
                        <h5 class="card-title">Sad</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-emoji-frown"></i>
                            </div>
                            <div class="ps-3">
                                <h6 id="sad">{{ $sadCount }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body py-3 table-responsive">
                <table class="table" id="feedback_table">
                    <thead>
                        <tr>
                            <th>Visitor</th>
                            <th>Reaction</th>
                            <th>Comment</th>
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
