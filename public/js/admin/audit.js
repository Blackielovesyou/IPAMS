function audit_table() {    
    let audit_date_from = $('#audit_date_from').val();
    let audit_date_to = $('#audit_date_to').val();
    $.ajax({
        type: "GET",
        url: "/admin/get_audit",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: { 
            date_from: audit_date_from,
            date_to: audit_date_to,
        },
        success: function (response) {
            $("#audit_table").DataTable().destroy();
            var table = $("#audit_table").DataTable({
                responsive: true,
                data: response,
                columns: [
                    { data: "ip_address" },
                    { data: "userID" },
                    { data: "action" },
                    {
                        data: "description",
                        render: function (data, type, row) {
                            if (type === 'display' && data.length > 30) {
                                return `<span data-bs-toggle="tooltip" title="${escapeHtml(data)}">${escapeHtml(data.substr(0, 30))}...</span>`;
                            }
                            return escapeHtml(data);
                        }
                    },
                    {
                        data: "created_at",
                        render: function (data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                return new Date(data).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});
                            }
                            return data;
                        }
                    },
                    {
                        data: "created_at",
                        render: function (data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                return new Date(data).toLocaleTimeString();
                            }
                            return data;
                        }
                    }
                ],
                order: [5, 'desc'],
                drawCallback: function () {
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
        },
    });
}

// Trigger on any filter change
$(document).on('change', '#audit_date_from, #audit_date_to, #audit_user, #audit_action', function(e) {
    e.preventDefault();
    audit_table();
});

audit_table();