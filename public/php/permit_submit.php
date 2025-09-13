<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Common fields ---
    $permit_type       = $_POST['permit_type'] ?? 'building';
    $full_name         = trim($_POST['full_name'] ?? $_POST['ownerName'] ?? '');
    $contact_number    = trim($_POST['contact_number'] ?? $_POST['contactNumber'] ?? '');
    $email             = trim($_POST['email'] ?? '');
    $address           = trim($_POST['address'] ?? $_POST['ownerAddress'] ?? '');
    $project_location  = trim($_POST['project_location'] ?? $_POST['buildingLocation'] ?? '');
    $additional_notes  = trim($_POST['additional_notes'] ?? $_POST['additionalNotes'] ?? '');

    // --- Permit-specific fields ---
    $construction_type = $_POST['construction_type'] ?? null; // building
    $estimated_cost    = isset($_POST['estimated_cost']) ? (float)$_POST['estimated_cost'] : null; // building
    $installation_type = $_POST['installation_type'] ?? null; // electrical/plumbing
    $work_scope        = $_POST['work_scope'] ?? null; // electrical
    $permit_purpose    = $_POST['permit_purpose'] ?? null; // plumbing
    $date_issued       = $_POST['date_issued'] ?? null; // occupancy

    // --- Insert application ---
    $sql = "INSERT INTO permit_applications
            (permit_type, full_name, contact_number, email, address, project_location,
             construction_type, estimated_cost, installation_type, work_scope,
             permit_purpose, date_issued, additional_notes, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: permit_error.php");
        exit;
    }

    $stmt->bind_param(
        "sssssssssssss",
        $permit_type,
        $full_name,
        $contact_number,
        $email,
        $address,
        $project_location,
        $construction_type,
        $estimated_cost,
        $installation_type,
        $work_scope,
        $permit_purpose,
        $date_issued,
        $additional_notes
    );

    if (!$stmt->execute()) {
        $_SESSION['error'] = "Error inserting application: " . $stmt->error;
        header("Location: permit_error.php");
        exit;
    }

    $application_id = $stmt->insert_id;

    // --- File upload handling ---
    $upload_dir = __DIR__ . "/../upload/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    // Map form input IDs to user-friendly labels per permit type
    $file_labels = [
        // Occupancy / Plumbing / Electrical
        'completionCert' => 'Plumbing Permit Application Form', // updated label
        'asBuiltPlans'   => 'Approved Plumbing Plans signed by a Sanitary Engineer or Master Plumber', // updated
        'electricalCert' => 'Barangay Clearance', // updated
        'plumbingCert'   => 'Building Permit (sometimes required)', // updated
        'fireSafetyCert' => 'Plumbing Inspection Report', // updated
        'buildingPermitOrig' => 'Clearance from Fire Dept or other offices',
        'approvedBuildingPermit' => 'Approved Building Permit & Related Documents'
    ];

    foreach ($_FILES as $field_name => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $filename = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $file['name']);
            $target_path = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $db_path = "files/" . $filename;
                $document_type = $file_labels[$field_name] ?? $field_name;

                $doc_sql = "INSERT INTO permit_documents
                            (application_id, document_type, file_path, uploaded_at)
                            VALUES (?, ?, ?, NOW())";
                $doc_stmt = $conn->prepare($doc_sql);
                $doc_stmt->bind_param("iss", $application_id, $document_type, $db_path);
                $doc_stmt->execute();
                $doc_stmt->close();
            }
        }
    }

    $_SESSION['success'] = true;
    header("Location: permit_success.php?id=" . $application_id);
    exit;
}
?>
