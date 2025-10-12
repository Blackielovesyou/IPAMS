@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>TEST SHOW AVALIABLE SLOT FILTER By section</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item active">section Schedule</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="mb-4">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Tip:</strong> Use the filters below to view class availabke slot by section name. Click on
                    a class in the calendar to view its details.
                </div>
            </div>
            <button class="btn btn-outline-primary mb-3" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas"
                aria-labelledby="filterOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 id="filterOffcanvasLabel">Filter Class Schedules</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <form class="row g-3" id="filterRoomSchedule">
                        <input type="hidden" name="type" value="0" id="type">

                        @if (auth()->user()->user_type == 0 || (auth()->user()->user_type == 1 && auth()->user()->is_registrar == 1))
                            <div class="col-md-4">
                                <label class="form-label">Department</label>
                                <select class="form-select" name="department_id" required>
                                    <option selected disabled>Select Department</option>
                                    @foreach($department as $d)
                                        <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                        @endif
                        <div class="col-12">
                            <label class="form-label fw-semibold">School Year</label>
                            <select class="form-select" name="school_year" id="school_year" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Course</label>
                            <select class="form-select form-select-sm" name="course_id" id="courseGetSectionName"
                                required>
                                <option selected disabled>Select Course</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Year Level</label>
                            <select class="form-select" name="year_level" id="year_level" required>
                                <option selected value="1st year">1st year</option>
                                <option value="2nd year">2nd year</option>
                                <option value="3rd year">3rd year</option>
                                <option value="4th year">4th year</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Section</label>
                            <select class="form-select" name="section_id" id="sectionSelect" required>
                                <option selected disabled>Select Section</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Semester</label>
                            <select class="form-select" name="semester" required id="semester" required>
                                <option selected value="1st semester">1st semester</option>
                                <option value="2nd semester">2nd semester</option>
                                <option value="Inter semester">Inter semester</option>
                            </select>
                        </div>

                        @csrf
                        @if(Session::has('success'))
                            <div class="alert alert-success mb-3">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-danger mb-3">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="offcanvas">Apply
                                Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-header text-white fw-semibold d-flex justify-content-between align-items-center"
                    style="background-color: var(--light-primary);">
                    <span><i class="bi bi-calendar-week-fill me-2"></i>Schedule for the Week</span>
                    <button id="downloadSchedule" class="btn btn-light btn-sm fw-semibold">
                        <i class="bi bi-download me-1"></i>Download
                    </button>
                </div>
                <div class="card-body p-3 bg-light rounded-bottom">
                    <div id="calendar" style="min-height: 400px;"></div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('admin.partials.__footer')