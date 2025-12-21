<?php
session_start();
include "../db.php";

/* Admin check */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if (isset($_POST['approve'])) {
        $status = "Approved";
    } elseif (isset($_POST['reject'])) {
        $status = "Rejected";
    } else {
        header("Location: admin_gatepass_leave.php");
        exit();
    }

    $stmt = $conn->prepare("
        UPDATE gatepass_leave
        SET status = ?
        WHERE id = ?
    ");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

header("Location: admin_gatepass_leave.php");
exit();
