const bookingTable = () => {
    $.ajax({
        type: "GET",
        url: "/user/get-booking",
        headers: {
            "X-CSRF-TOKEN": csrfToken, // Add CSRF token to the request headers
        },
        success: function (response) {
            $("#bookingTable").DataTable().destroy();
            var booking_table = $("#bookingTable").DataTable({
                responsive: true, // Enable responsiveness
                data: response,
                columns: [
                    { data: "transaction_number" },
                    { data: "pdl_name" },
                    { data: "date", type: 'date' },
                    { data: "time" },
                    { data: "type" },
                    { data: "status", 
                      render: function (data, type, row) { 
                        return status_arr[row.status]
                      }
                    },
                    {
                      data: "bookID",
                      render: function (data, type, row) {
                          let action = `<button class="btn btn-sm btn-success ViewBookingDetails" 
                                          data-bookID="${row.bookID}"  
                                          data-transaction_number="${row.transaction_number}"
                                          data-bs-toggle="tooltip" title="View details">
                                          <i class="bi bi-eye"></i>
                                      </button>`;
                          let cancelbtn = '';
                              if(row.status == 0) {
                                cancelbtn = `<button class="btn btn-sm btn-danger CancelVisit" 
                                          data-bookID="${row.bookID}"  
                                          data-transaction_number="${row.transaction_number}"
                                          data-bs-toggle="tooltip" title="Cancel visit">
                                          <i class="bi bi-x"></i>
                                      </button>`
                              }
                          return `${action} ${cancelbtn}`;
                      },
                  },
                ],
                order: [[2, 'desc'], [5, 'asc']], 
                drawCallback: function () {
                    // Initialize tooltips after table draw
                    var tooltipTriggerList = [].slice.call(
                        document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    );
                    var tooltipList = tooltipTriggerList.map(function (
                        tooltipTriggerEl
                    ) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                },
            });
        },
        error: function (xhr, status, error) {
            console.error("An error occurred: " + status + " - " + error);
            // Additional error handling here
        },
    });
};
let booking = document.getElementById("bookingTable");
if (booking) {
    bookingTable();
}

$(document).on('click', '.ViewBookingDetails', function (e) { 
  let bookID = $.trim($(this).data('bookid'));
  let transaction_number = $.trim($(this).data('transaction_number'));
  $.ajax({
    type: "GET",
    url: `/user/get-booking/${transaction_number}/${bookID}`,
    success: function (response) {
      $('#bookdetailmodal .modal-dialog').html(response);
      $('#bookdetailmodal').modal('show');
    },
    error: function (xhr, status, error) {
        // Handle error here
        console.error(xhr.responseText);
    }
  });
});

$(document).on('click', '.CancelVisit', function (e) {
    let bookID = $.trim($(this).data('bookid'));
    let transaction_number = $.trim($(this).data('transaction_number'));
    $('#cancelBookingModal').find('input[name="bookID"]').val(bookID);
    $('#cancelBookingModal').find('input[name="transaction_number"]').val(transaction_number);
    $('#cancelBookingModal').modal('show');
});

$(document).on('click', '[data-dismiss="modal"]', function (e) {
  $('#bookdetailmodal').modal('hide');
  $('#cancelBookingModal').modal('hide');
});

// Handle cancel booking form submission
$('#cancelBookingForm').on('submit', function (e) {
    e.preventDefault();
    let bookID = $(this).find('input[name="bookID"]').val();
    let transaction_number = $(this).find('input[name="transaction_number"]').val();
    let reason = $(this).find('textarea[name="reason"]').val();
    showLoading();
    $.ajax({
        type: "POST",
        url: `/user/cancel-booking`,
        data: {
            bookID: bookID,
            transaction_number: transaction_number,
            reason: reason
        },
        headers: {
            "X-CSRF-TOKEN": csrfToken
        },
        success: function (response) {
            hideLoading();
            $('#cancelBookingModal').modal('hide');
            $('#cancelBookingForm')[0].reset();
            global_showalert(response.message, 'Cancel success', 'blue');
            bookingTable(); // Refresh the booking table
        },
        error: function (xhr, status, error) {
            hideLoading();
            console.error(xhr.responseText);
            // Handle error here
        }
    });
});