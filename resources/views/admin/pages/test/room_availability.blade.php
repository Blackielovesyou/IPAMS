@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>TEST SHOW AVALIABLE SLOT FILTER BE ROOM</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item active">Room Schedule</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="mb-4">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Tip:</strong> Use the filters below to view class availabke slot by room name. Click on
                    a class in the calendar to view its details.
                </div>
            </div>
            <button class="btn btn-outline-primary mb-3" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas"
                aria-labelledby="filterOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 id="filterOffcanvasLabel">Filter Room Schedules</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <form class="row g-3" id="filterRoomSchedule">
                        <input type="hidden" name="type" value="2" id="type">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Room name</label>
                            <select class="form-select" required name="room_id">
                                <option selected>Select Room</option>
                                @foreach($rooms as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">School Year</label>
                            <select class="form-select" name="school_year" id="school_year" required>
                                <option value="">Loading...</option>
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