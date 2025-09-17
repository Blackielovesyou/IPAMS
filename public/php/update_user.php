<?php
header('Content-Type: application/json');
include("db.php");

$id = $_POST['id'] ?? null;
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');

if (!$id || !$fullname || !$email) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Split full name into first & last
list($first, $last) = explode(' ', $fullname, 2);

$sql = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("sssi", $first, $last, $email, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $stmt->error]);
}
?>
