<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Common fields ---
    $permit_type = !empty($_POST['permit_type']) ? $_POST['permit_type'] : 'building';
    $full_name = trim($_POST['full_name'] ?? $_POST['ownerName'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? $_POST['contactNumber'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? $_POST['ownerAddress'] ?? '');
    $project_location = trim($_POST['project_location'] ?? $_POST['buildingLocation'] ?? '');
    $additional_notes = trim($_POST['additional_notes'] ?? $_POST['additionalNotes'] ?? '');

    // --- Permit-specific fields ---
    $construction_type = !empty($_POST['construction_type']) ? $_POST['construction_type'] : null;
    $estimated_cost = isset($_POST['estimated_cost']) && $_POST['estimated_cost'] !== "" ? (float) $_POST['estimated_cost'] : null;
    $installation_type = !empty($_POST['installation_type']) ? $_POST['installation_type'] : null;
    $work_scope = !empty($_POST['work_scope']) ? $_POST['work_scope'] : null;
    $permit_purpose = !empty($_POST['permit_purpose']) ? $_POST['permit_purpose'] : null;
    $date_issued = !empty($_POST['date_issued']) ? $_POST['date_issued'] : null;

    // --- Generate new application_number (6-digit sequence) ---
    $lastQuery = $conn->query("SELECT id FROM permit_applications ORDER BY id DESC LIMIT 1");

    if ($lastQuery && $lastQuery->num_rows > 0) {
        $row = $lastQuery->fetch_assoc();
        $lastSeq = intval($row['id']);
        $newSeq = str_pad($lastSeq + 1, 6, "0", STR_PAD_LEFT);
    } else {
        $newSeq = "000001";
    }

    $newAppNo = $newSeq; // Example: 000001, 000002 ...

    // --- Insert application ---
    $sql = "INSERT INTO permit_applications
            (application_number, permit_type, full_name, contact_number, email, address, project_location,
             construction_type, estimated_cost, installation_type, work_scope,
             permit_purpose, date_issued, additional_notes, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Debugging
    }

    $stmt->bind_param(
        "ssssssssdsssss",
        $newAppNo,           // s - application_number
        $permit_type,        // s - permit_type
        $full_name,          // s - full_name
        $contact_number,     // s - contact_number
        $email,              // s - email
        $address,            // s - address
        $project_location,   // s - project_location
        $construction_type,  // s - construction_type
        $estimated_cost,     // d - estimated_cost
        $installation_type,  // s - installation_type
        $work_scope,         // s - work_scope
        $permit_purpose,     // s - permit_purpose
        $date_issued,        // s - date_issued
        $additional_notes    // s - additional_notes
    );


    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error); // Debugging
    }

    $application_id = $stmt->insert_id;
    $stmt->close();

    // --- File upload handling ---
    $upload_dir = __DIR__ . "/../upload/";
    if (!is_dir($upload_dir))
        mkdir($upload_dir, 0777, true);

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
    $content = "Submitted $permit_type permit application (#$newAppNo) by $full_name";

    $log = $conn->prepare("INSERT INTO system_logs (user_id, role, ip_address, action_type, content) VALUES (?, ?, ?, ?, ?)");
    $log->bind_param("issss", $user_id, $role, $ip, $action_type, $content);
    $log->execute();
    $log->close();

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => "Permit application (#$newAppNo) submitted successfully!",
        'application_number' => $newAppNo
    ]);
    exit;

}
?>