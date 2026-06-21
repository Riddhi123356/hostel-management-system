<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];

$stmt = $conn->prepare("SELECT * FROM gatepass_leave WHERE regno = ? ORDER BY id DESC");
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();

$activePage = 'leave.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gate Pass & Leave</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Gate Pass & Leave</h2>
        <p class="page-subtitle">Your gate pass and leave request history.</p>

        <a href="gatepass_form.php" class="btn btn-amber" style="margin-bottom:18px;display:inline-block;">+ New Gate Pass / Leave Request</a>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No.</th>
                    <th>Type</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>Return Date</th>
                    <th>Out Time</th>
                    <th>In Time</th>
                    <th>Status</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    while ($r = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= e($r['regno']) ?></td>
                    <td><?= e($r['type']) ?></td>
                    <td><?= e($r['reason']) ?></td>
                    <td><?= e($r['start_date']) ?></td>
                    <td><?= e($r['return_date']) ?></td>
                    <td><?= e($r['out_time']) ?></td>
                    <td><?= e($r['in_time']) ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="8" class="empty-row">No gate pass or leave requests yet.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
