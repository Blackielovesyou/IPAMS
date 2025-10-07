<?php
// Enable error reporting (for debugging)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include db.php (same folder)
include __DIR__ . "/db.php";

header('Content-Type: application/json');

$response = ["exists" => false];

if (isset($_GET['tracking_number']) && !empty($_GET['tracking_number'])) {
    $tracking_number = trim($_GET['tracking_number']);

    $sql = "SELECT id FROM permit_applications WHERE application_number = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $tracking_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $response["exists"] = true;
        }

        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $conn->error]);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Missing tracking_number parameter"]);
    exit;
}

echo json_encode($response);
$conn->close();
