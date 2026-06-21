<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();
csrf_verify();

$allowed = ['Pending', 'Approved', 'Rejected'];

$id = (int)($_POST['id'] ?? 0);
$director = in_array($_POST['director_status'] ?? '', $allowed) ? $_POST['director_status'] : 'Pending';
$rector = in_array($_POST['rector_status'] ?? '', $allowed) ? $_POST['rector_status'] : 'Pending';
$librarian = in_array($_POST['librarian_status'] ?? '', $allowed) ? $_POST['librarian_status'] : 'Pending';
$accountant = in_array($_POST['accountant_status'] ?? '', $allowed) ? $_POST['accountant_status'] : 'Pending';

$stmt = $conn->prepare("
    UPDATE refund_requests SET
        director_status = ?,
        rector_status = ?,
        librarian_status = ?,
        accountant_status = ?
    WHERE id = ?
");
$stmt->bind_param("ssssi", $director, $rector, $librarian, $accountant, $id);
$stmt->execute();

header("Location: admin_refund.php");
exit();
