<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();
csrf_verify();

$id = (int)($_POST['id'] ?? 0);

if (isset($_POST['approve'])) {
    $status = "Approved";
} elseif (isset($_POST['reject'])) {
    $status = "Rejected";
} else {
    header("Location: admin_changeInformation.php");
    exit();
}

$stmt = $conn->prepare("UPDATE change_information_requests SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

/*
 * NOTE: Approving this request only updates its status.
 * Applying the requested change to the student's actual profile
 * (name, mobile number, etc.) is a manual step for now, since the
 * request is free-text and there's no structured "field to change"
 * captured on submission. A future improvement would be to turn
 * changeInformation.php's textarea into specific fields
 * (e.g. new name, new phone) so this could be automated safely.
 */

header("Location: admin_changeInformation.php");
exit();
