<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];

if (isset($_POST['request_icard'])) {
    csrf_verify();

    $stmt = $conn->prepare("SELECT id FROM icard_requests WHERE regno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO icard_requests (regno, status) VALUES (?, 'Pending')");
        $stmt->bind_param("s", $regno);
        $stmt->execute();
    }

    header("Location: icard.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM icard WHERE regno = ?");
$stmt->bind_param("s", $regno);
$stmt->execute();
$icard = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM icard_requests WHERE regno = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $regno);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();

$activePage = 'icard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>I-Card</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>I-Card</h2>
        <p class="page-subtitle">View your issued I-Card details or request a new one.</p>

        <div class="table-wrap" style="margin-bottom:20px;">
            <table>
                <tr>
                    <th>Registration No</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td><?= e($regno) ?></td>
                    <td><?= e($icard['name'] ?? '-') ?></td>
                    <td><?= e($icard['year'] ?? '-') ?></td>
                    <td><?= status_badge($request['status'] ?? 'Not Requested') ?></td>
                </tr>
            </table>
        </div>

        <form method="post">
            <?= csrf_field() ?>
            <?php if (!$request) { ?>
                <button type="submit" name="request_icard" class="btn-amber">Request For I-Card</button>
            <?php } else { ?>
                <button type="button" disabled>Request Submitted</button>
            <?php } ?>
        </form>
    </div>
</div>

</body>
</html>
