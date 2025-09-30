<?php
session_start();
include("db.php");

// ✅ Only admin can schedule inspections
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appId = intval($_POST['application_id']);
    $date = trim($_POST['inspection_date']);
    $time = trim($_POST['inspection_time']);
    $inspectorName = trim($_POST['inspector']);

    // ✅ Validation
    if (empty($appId) || empty($date) || empty($time) || empty($inspectorName)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // ✅ Insert or update inspection schedule
    $stmt = $conn->prepare("
        INSERT INTO inspections (application_id, inspection_date, inspection_time, inspector_name, status)
        VALUES (?, ?, ?, ?, 'scheduled')
        ON DUPLICATE KEY UPDATE 
            inspection_date = VALUES(inspection_date), 
            inspection_time = VALUES(inspection_time),
            inspector_name = VALUES(inspector_name),
            status = 'scheduled'
    ");

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . htmlspecialchars($conn->error)]);
        exit;
    }

    $stmt->bind_param("isss", $appId, $date, $time, $inspectorName);

    if ($stmt->execute()) {
        // ✅ Update the main application status to "scheduled"
        $update = $conn->prepare("UPDATE permit_applications SET status = 'scheduled' WHERE id = ?");
        $update->bind_param("i", $appId);
        $update->execute();
        $update->close();

        echo json_encode(['success' => true, 'message' => 'Inspection scheduled successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to schedule inspection: ' . htmlspecialchars($stmt->error)]);
    }

    $stmt->close();
    exit;
}
?>
