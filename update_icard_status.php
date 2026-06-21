<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();
csrf_verify();

if (isset($_POST['approve'])) {
    $regno = trim($_POST['regno'] ?? '');

    if ($regno !== '') {
        $stmt = $conn->prepare("UPDATE icard_requests SET status = 'Approved' WHERE regno = ?");
        $stmt->bind_param("s", $regno);
        $stmt->execute();
    }
}

header("Location: admin_icard.php");
exit();
