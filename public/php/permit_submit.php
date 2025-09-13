<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $permit_type = $_POST['permit_type'] ?? 'building';
    $full_name = trim($_POST['full_name'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $project_location = trim($_POST['project_location'] ?? '');
    $construction_type = !empty($_POST['construction_type']) ? $_POST['construction_type'] : null;
    $estimated_cost = !empty($_POST['estimated_cost']) ? (float) $_POST['estimated_cost'] : null;
    $notes = trim($_POST['additional_notes'] ?? '');

    $sql = "INSERT INTO permit_applications 
            (permit_type, full_name, contact_number, email, address, project_location, 
             construction_type, estimated_cost, additional_notes, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: permit_error.php");
        exit;
    }

    // ✅ 9 variables → 9 placeholders
    // permit_type(s), full_name(s), contact_number(s), email(s),
    // address(s), project_location(s), construction_type(s),
    // estimated_cost(d), notes(s)
    $stmt->bind_param(
        "sssssssds",  // ✅ 9 placeholders, no space
        $permit_type,
        $full_name,
        $contact_number,
        $email,
        $address,
        $project_location,
        $construction_type,
        $estimated_cost,
        $notes
    );

if ($stmt->execute()) {
    $application_id = $stmt->insert_id;

    // Use existing "files" folder inside /public
    $upload_dir = __DIR__ . "/../upload/"; // ✅ go up from /php to /public/files
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // optional safeguard, but your folder already exists
    }

    // Save uploaded documents
    foreach ($_FILES as $field_name => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $filename = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $file['name']);
            $target_path = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // ✅ Save relative path so it can be accessed via browser
                $db_path = "files/" . $filename;

                $doc_sql = "INSERT INTO permit_documents 
                            (application_id, document_type, file_path, uploaded_at) 
                            VALUES (?, ?, ?, NOW())";
                $doc_stmt = $conn->prepare($doc_sql);
                $doc_stmt->bind_param("iss", $application_id, $field_name, $db_path);
                $doc_stmt->execute();
                $doc_stmt->close();
            }
        }
    }

    // ✅ Success redirect
    $_SESSION['success'] = true;
    header("Location: permit_success.php?id=" . $application_id);
    exit;
}
else {
        $_SESSION['error'] = "Error inserting application: " . $stmt->error;
        header("Location: permit_error.php");
        exit;
    }



}
?>