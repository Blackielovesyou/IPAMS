<?php
include("db.php"); // Make sure path is correct

// Fetch all permit applications with applicant full name if available
$applications = [];
$sql = "SELECT pa.*, 
               COALESCE(pa.full_name, CONCAT(u.first_name,' ',u.last_name)) AS full_name
        FROM permit_applications pa
        LEFT JOIN users u ON pa.user_id = u.id
        ORDER BY pa.created_at DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}
?>

<div id="permitApplicationsTableWrapper" class="mt-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-file-earmark-text"></i> Permit Applications</h4>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body bg-light bg-opacity-75 rounded-bottom-4">
            <div class="table-responsive">
                <table id="applicationsTable" class="table table-hover table-bordered align-middle mb-0">
                    <thead style="background: linear-gradient(90deg, #007bff 0%, #6610f2 100%); color: #fff;">
                        <tr>
                            <th>Application No.</th>
                            <th>Applicant</th>
                            <th>Permit Type</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($applications)): ?>
                            <?php foreach ($applications as $app): ?>
                                <tr id="appRow-<?= $app['id'] ?>">
                                    <td>
                                        <?php
                                        $year = date("Y", strtotime($app['created_at']));
                                        $prefix = match ($app['permit_type']) {
                                            'building' => 'BP',
                                            'electrical' => 'EP',
                                            'plumbing' => 'PP',
                                            'occupancy' => 'OP',
                                            default => ''
                                        };
                                        echo htmlspecialchars($prefix . '-' . $year . '-' . $app['application_number']);
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($app['full_name']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($app['permit_type'])) ?></td>
                                    <td>
                                        <span class="badge rounded-pill bg-<?php
                                        echo $app['status'] === 'approved' ? 'success' :
                                            ($app['status'] === 'rejected' ? 'danger' : 'warning');
                                        ?> px-3 py-2 shadow-sm" id="status-<?= $app['id'] ?>">
                                            <?= ucfirst(htmlspecialchars($app['status'])) ?>
                                        </span>
                                    </td>
                                    <td><?= date("M d, Y", strtotime($app['created_at'])) ?></td>
                                    <td class="text-center" id="actions-<?= $app['id'] ?>">
                                        <?php if ($app['status'] !== 'rejected'): ?>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary view-btn" data-id="<?= $app['id'] ?>"
                                                    title="View" onclick="window.location.href='review.php?id=<?= $app['id'] ?>'">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success approve-btn"
                                                    data-id="<?= $app['id'] ?>" title="Approve">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger reject-btn" data-id="<?= $app['id'] ?>"
                                                    title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No permit applications found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reject this application?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRejectBtn">Reject</button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedAppId = null;

    // Store the selected application ID when reject button clicked
    $(document).on('click', '.reject-btn', function () {
        selectedAppId = $(this).data('id');
    });

    // Confirm rejection
    $('#confirmRejectBtn').on('click', function () {
        if (!selectedAppId) return;

        $.ajax({
            url: 'update_permit_status.php',
            method: 'POST',
            data: { id: selectedAppId, status: 'rejected' },
            success: function (response) {
                if (response === 'success') {
                    // Update status badge
                    $('#status-' + selectedAppId)
                        .removeClass('bg-warning bg-success')
                        .addClass('bg-danger')
                        .text('Rejected');

                    // Remove all buttons from actions
                    $('#actions-' + selectedAppId).html('');

                    // Close modal
                    $('#rejectModal').modal('hide');
                } else {
                    alert('Failed to reject the application.');
                }
            }
        });
    });

</script>

<style>
    #permitApplicationsTableWrapper .card {
        border-radius: 16px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }

    #permitApplicationsTableWrapper .table thead th {
        font-weight: 600;
        border: none;
        text-align: center;
    }

    #permitApplicationsTableWrapper .table tbody tr:hover {
        background-color: #f4f8ff;
        transition: 0.2s;
    }

    #permitApplicationsTableWrapper .btn-group .btn {
        border-radius: 6px !important;
        transition: 0.2s;
    }

    #permitApplicationsTableWrapper .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    }
</style>