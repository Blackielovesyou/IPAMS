<?php
include("db.php");

if(isset($_POST['id'], $_POST['status'])){
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE permit_applications SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    if($stmt->execute()){
        echo "success";
    } else {
        echo "error";
    }
}
?>
