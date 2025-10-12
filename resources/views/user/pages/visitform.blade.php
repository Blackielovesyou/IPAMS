@include('user.partials.__header')
@include('user.partials.__nav')
@php
    $timeSlots = [
        '1' => ['start' => '8:00 am', 'end' => '8:30 am'],
        '2' => ['start' => '8:30 am', 'end' => '9:00 am'],
        '3' => ['start' => '9:00 am', 'end' => '9:30 am'],
        '4' => ['start' => '9:30 am', 'end' => '10:00 am'],
        '5' => ['start' => '10:00 am', 'end' => '10:30 am'],
        '6' => ['start' => '10:30 am', 'end' => '11:00 am'],
        '7' => ['start' => '11:00 am', 'end' => '11:30 am'],
        '8' => ['start' => '11:30 am', 'end' => '12:00 pm'],
        '9' => ['start' => '1:00 pm', 'end' => '1:30 pm'],
        '10' => ['start' => '1:30 pm', 'end' => '2:00 pm'],
        '11' => ['start' => '2:00 pm', 'end' => '2:30 pm'],
        '12' => ['start' => '2:30 pm', 'end' => '3:00 pm'],
        '13' => ['start' => '3:00 pm', 'end' => '3:30 pm'],
        '14' => ['start' => '3:30 pm', 'end' => '4:00 pm'],
        '15' => ['start' => '4:00 pm', 'end' => '4:30 pm'],
        '16' => ['start' => '4:30 pm', 'end' => '5:00 pm'],
    ];
@endphp
<main id="main" class="main">

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-5">
                <form id="add_booking_form" class="card">
                    <div class="card-body p-3">
                        <div class="text-center">
                            <h4 class="fw-bold">VISITATION FORM</h4>
                        </div>
                        <div class="col-lg-12 mb-3">
                            Name of PDL (Person Deprived of Liberty):
                            <select name="pdl_id" id="pdl_id" onchange="get_list_visitor(this.value)"
                                class="form-select" required>
                                <option value="">--Choose PDL--</option>
                                @if ($list_pdl->isNotEmpty())
                                    @foreach ($list_pdl as $pdl_data)
                                        <option value="{{ $pdl_data->pdlID }}">{{ $pdl_data->pdlName }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            Type of visitation:
                            <select name="type_visit" id="type_visit" class="form-select" required>
                                <option value="Physical" {{ $type == 'Physical' ? 'selected' : '' }}>Onsite
                                </option>
                                <option value="Virtual" {{ $type == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                Date:
                                <input type="date" name="date_visit" class="form-control" id="date_visit">
                            </div>
                            <div class="col-lg-6 mb-3">
                                Time:
                                <select name="time_visit" id="time_visit" class="form-select" required>
                                    <option value="">Select time</option>
                                    @foreach ($timeSlots as $key => $item)
                                        <option value="{{ $key }}">{{ $item['start'] }} to
                                            {{ $item['end'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            Name of Visitors:
                            <select id="visitor_select" name="list_visitor[]" class="form-select border-0"
                                style="width: 100%;" multiple required>
                                <option value="">--Choose Visitor--</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-12 mb-3">
                            Upload any proof of relation:
                            <input type="file" name="verification_docs" class="form-control" id="proof" required>
                        </div>
                        
                        <div class="col-lg-12 text-end">
                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                        </div>
                        @csrf
                    </div>
                </form>
            </div>
            <div class="col-lg-7 d-none d-sm-block">
                <div class="card">
                    <div class="card-body py-3">
                        <div id="showcalendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

@include('user.partials.__footer')
