<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

if (isset($_POST['id'])) {
    csrf_verify();

    $id = (int)$_POST['id'];

    if (isset($_POST['approve'])) {
        $status = "Approved";
    } elseif (isset($_POST['reject'])) {
        $status = "Rejected";
    } else {
        header("Location: admin_gatepass_leave.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE gatepass_leave SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

header("Location: admin_gatepass_leave.php");
exit();
