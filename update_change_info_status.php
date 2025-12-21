<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_POST['id'];
$regno = $_POST['regno'];

if (isset($_POST['approve'])) {
    $status = "Approved";
} elseif (isset($_POST['reject'])) {
    $status = "Rejected";
}

$stmt = $conn->prepare("
    UPDATE change_information_requests
    SET status=?
    WHERE id=?
");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

/*
IMPORTANT:
After approval, admin SHOULD manually update student table
Example:
UPDATE students SET mobile='new' WHERE regno='...'
(This depends on what student requested in description)
*/

header("Location: admin_changeInformation.php");
exit();
