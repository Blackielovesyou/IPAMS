<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Common fields ---
    $permit_type = $_POST['permit_type'] ?? 'building';
    $full_name = trim($_POST['full_name'] ?? $_POST['ownerName'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? $_POST['contactNumber'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? $_POST['ownerAddress'] ?? '');
    $project_location = trim($_POST['project_location'] ?? $_POST['buildingLocation'] ?? '');
    $additional_notes = trim($_POST['additional_notes'] ?? $_POST['additionalNotes'] ?? '');

    // --- Permit-specific fields ---
    $construction_type = $_POST['construction_type'] ?? null;
    $estimated_cost = isset($_POST['estimated_cost']) ? (float) $_POST['estimated_cost'] : null;
    $installation_type = $_POST['installation_type'] ?? null;
    $work_scope = $_POST['work_scope'] ?? null;
    $permit_purpose = $_POST['permit_purpose'] ?? null;
    $date_issued = $_POST['date_issued'] ?? null;

    // --- Insert application ---
    $sql = "INSERT INTO permit_applications
            (permit_type, full_name, contact_number, email, address, project_location,
             construction_type, estimated_cost, installation_type, work_scope,
             permit_purpose, date_issued, additional_notes, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Log the error
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_id = $_SESSION['id'] ?? null;
        $role = $_SESSION['role'] ?? 'applicant';
        $action_type = "submit_permit_failed";
        $content = "Database error while submitting $permit_type permit: " . $conn->error;

        $log = $conn->prepare("INSERT INTO system_logs (user_id, role, ip_address, action_type, content) VALUES (?, ?, ?, ?, ?)");
        $log->bind_param("issss", $user_id, $role, $ip, $action_type, $content);
        $log->execute();
        $log->close();

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
        // Log failed insert
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_id = $_SESSION['id'] ?? null;
        $role = $_SESSION['role'] ?? 'applicant';
        $action_type = "submit_permit_failed";
        $content = "Error inserting $permit_type permit: " . $stmt->error;

        $log = $conn->prepare("INSERT INTO system_logs (user_id, role, ip_address, action_type, content) VALUES (?, ?, ?, ?, ?)");
        $log->bind_param("issss", $user_id, $role, $ip, $action_type, $content);
        $log->execute();
        $log->close();

        $_SESSION['error'] = "Error inserting application: " . $stmt->error;
        header("Location: permit_error.php");
        exit;
    }

    $application_id = $stmt->insert_id;

    // --- Get the generated Application number ---
    $app_query = $conn->prepare("SELECT Application FROM permit_applications WHERE id = ?");
    $app_query->bind_param("i", $application_id);
    $app_query->execute();
    $app_query->bind_result($application_number);
    $app_query->fetch();
    $app_query->close();

    // --- File upload handling ---
    $upload_dir = __DIR__ . "/../upload/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $file_labels = [
        'completionCert' => 'Plumbing Permit Application Form',
        'asBuiltPlans' => 'Approved Plumbing Plans signed by a Sanitary Engineer or Master Plumber',
        'electricalCert' => 'Barangay Clearance',
        'plumbingCert' => 'Building Permit (sometimes required)',
        'fireSafetyCert' => 'Plumbing Inspection Report',
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

    // --- Log success ---
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_id = $_SESSION['id'] ?? null;
    $role = $_SESSION['role'] ?? 'applicant';
    $action_type = "submit_permit";
    $content = "Submitted $permit_type permit application (#$application_number) by $full_name";

    $log = $conn->prepare("INSERT INTO system_logs (user_id, role, ip_address, action_type, content) VALUES (?, ?, ?, ?, ?)");
    $log->bind_param("issss", $user_id, $role, $ip, $action_type, $content);
    $log->execute();
    $log->close();

    $_SESSION['success'] = true;
    header("Location: permit_success.php?application=" . $application_number);
    exit;
}
?>
