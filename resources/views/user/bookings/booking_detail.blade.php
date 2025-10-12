@php
  $status_arr = [
    'Pending',
    'Approved',
    'Canceled/Declined',
    'Canceled/Declined',
  ];
  $status_color = [
    'warning',
    'success',
    'danger',
    'danger',
  ];
@endphp
<div class="modal-content">
    <!-- Example of booking detail modal content -->
    <div class="modal-header">
        <h5 class="modal-title fw-bold">{{ Str::ucfirst($bookingDetail->transaction_number) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="w-100">
              @if ($bookingDetail->status == 0)
                <div
                  class="alert alert-warning"
                  role="alert"
                >
                  <strong>Alert:</strong> request is still on review/progress.
                </div>
              @endif
              @if ($bookingDetail->status == 1)
                <div
                  class="alert alert-primary"
                  role="alert"
                >
                  <strong>Alert:</strong> your visitation was approved
                </div>
              @endif
              @if ($bookingDetail->status == 2 || $bookingDetail->status == 3)
                <div
                  class="alert alert-danger"
                  role="alert"
                >
                  <strong>Alert:</strong> your visitation was canceled/declined
                </div>
              @endif
            </div>
          </div>
            <div class="col-lg-4 mb-3 px-3 d-flex align-items-center justify-content-center">
                <div class="w-100 text-center">
                    {!! QrCode::size(150)->generate($bookingDetail->transaction_number) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <table class="table table-sm">
                    <tr>
                      <td>PDL Name:</td>
                      <td>{{ $bookingDetail->pdl_name }}</td>
                    </tr>
                    <tr>
                      <td>Date visit:</td>
                      <td>{{ $bookingDetail->date }}</td>
                    </tr>
                    <tr>
                      <td>Time visit:</td>
                      <td>{{ $bookingDetail->time }}</td>
                    </tr>
                    <tr>
                      <td>Status:</td>
                      <td class="bg-{{ $status_color[$bookingDetail->status] }}">{{ $status_arr[$bookingDetail->status] }}</td>
                    </tr>
                    <tr>
                        <td>Visitation Type:</td>
                        <td>{{ $bookingDetail->type == "Physical" ? "Onsite" : $bookingDetail->type  }}</td>
                    </tr>
                    @if ($bookingDetail->type == "Virtual")
                      <tr>
                        <td>Meeting Type:</td>
                        <td>{{ $bookingDetail->link_type == "code" ? "SmartSched meeting service" : $bookingDetail->link_type }}</td>
                      </tr>
                      <tr>
                        <td>Link/Code:</td>
                        <td>{{ $bookingDetail->meeting_link }}</td>
                      </tr> 
                    @endif
                    @if ($bookingDetail->link_type == "google")
                      <tr>
                        <td colspan="2" class="fw-bold text-center text-white" style="background-color: #2D8BBA">
                          <a class="text-white" href="{{ $bookingDetail->meeting_link }}"> Click to join the meeting</a>
                        </td>
                      </tr>
                    @elseif($bookingDetail->link_type == "code")
                      <tr>
                        <td colspan="2" class="fw-bold text-center text-white" style="background-color: #2D8BBA">
                          <a class="text-white" href="{{ url('/videocall/'.$bookingDetail->meeting_link) }}"> Click to join the meeting</a>
                        </td>
                      </tr>
                    @endif
                </table>
            </div>
            <div class="col-lg-12">
              <table class="table table-sm">
                <tr>
                  <td class="fw-bold text-white text-center table" style="background-color: #2D8BBA">Requirement for visitation</td>
                </tr>
                <tr>
                  <td>- {{ $bookingDetail->valid_id }}</td>
                </tr>
                <tr>
                  <td>
                    <a href="{{ asset('storage/' . $bookingDetail->verification_docs) }}" target="__blank">
                    - Click to view document
                    </a>
                  </td>
                </tr>
                <tr>
                  <td class="fw-bold text-white text-center table" style="background-color: #2D8BBA">Visitor(s):</td>
                </tr>
                
                @foreach ($visitors as $visitor)
                <tr>
                  <td>- {{ $visitor->name }} <br> <small> -- Relationship : {{ $visitor->family_position }}</small></td>
                </tr>
                @endforeach
              </table>
            </div>
        </div>
        <!-- Add more details as needed -->
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
    </div>

</div>
