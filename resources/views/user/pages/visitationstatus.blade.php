@include('user.partials.__header')
@include('user.partials.__nav')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Visitaion Status</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/user') }}">Home</a></li>
                <li class="breadcrumb-item active">Visitaion Status</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section user_management">
        <div class="card">
            <div class="card-body py-3 table-responsive">
                <table class="table" id="bookingTable">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>PDL Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</main>
<div
  class="modal fade"
  id="bookdetailmodal"
  tabindex="-1"
  data-bs-backdrop="static"
  data-bs-keyboard="false"
  
  role="dialog"
  aria-labelledby="bookdetailtitle"
  aria-hidden="true"
>
  <div
    class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
    role="document"
  >
  </div>
</div>
<div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cancelBookingForm">
                    @csrf
                    <input type="hidden" name="bookID">
                    <input type="hidden" name="transaction_number">
                    <div class="form-group mb-3">
                        <label for="reason">Reason for Cancellation</label>
                        <textarea class="form-control" id="reason" name="reason" placeholder="Type your reason" rows="3" required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-danger btn-sm">Cancel Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('user.partials.__footer')
