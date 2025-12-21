<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['solve'])) {
    $id = $_POST['id'];

    $sql = "UPDATE maintenance 
            SET status='Solved', updated_at=NOW() 
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: maintenance_requests.php");
exit();
