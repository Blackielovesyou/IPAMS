@include('admin.partials.__header')
@include('admin.partials.__nav')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>TEST MOVE SCHEDULE</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item active">TEST MOVE SCHEDULE</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="container">
                <h2 class="mb-4">Schedule List</h2>

                <!-- First Table: Schedule List -->
                <table class="table table-bordered" id="move-schedule-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Instructor</th>
                            <th>Subject</th>
                            <th>Section</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $sched)
                            <tr class="schedule-row" data-id="{{ $sched->id }}">
                                <td>{{ $sched->id }}</td>
                                <td>{{ $sched->instructor_name ?? '' }}</td>
                                <td>{{ $sched->subject_name ?? '' }}</td>
                                <td>{{ $sched->section_name ?? '' }}</td>
                                <td>{{ $sched->schedule_day }}</td>
                                <td>{{ $sched->start_time }} - {{ $sched->end_time }}</td>
                                <td>{{ $sched->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3 class="mt-5 mb-3">Available Time Slots for Selected Schedule</h3>
                <!-- Second Table: Available Slots -->
                <table class="table table-bordered" id="available-slots-table">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <h3 class="mt-5 mb-3">Tradeable Schedules</h3>
                <table class="table table-bordered" id="tradeable-schedules-table">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Instructor</th>
                            <th>Subject</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filled by JS -->
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</main>

@include('admin.partials.__footer')