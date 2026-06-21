<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

if (isset($_POST['solve'])) {
    csrf_verify();

    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("UPDATE maintenance SET status='Solved', updated_at=NOW() WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin_maintenance.php");
exit();
