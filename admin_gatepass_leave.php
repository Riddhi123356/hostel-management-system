<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

$result = $conn->query("SELECT * FROM gatepass_leave ORDER BY id DESC");

$activePage = 'admin_gatepass_leave.php';
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
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Gate Pass & Leave Requests</h2>
        <p class="page-subtitle">Approve or reject student gate pass and leave applications.</p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No</th>
                    <th>Type</th>
                    <th>Reason</th>
                    <th>Start Date</th>
                    <th>Return Date</th>
                    <th>Out Time</th>
                    <th>In Time</th>
                    <th>Status</th>
                    <th>Action</th>
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
                    <td>
                        <?php if ($r['status'] == 'Pending') { ?>
                        <form method="post" action="update_gatepass_leave_status.php" class="action-row">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" name="approve" class="btn-green">Approve</button>
                            <button type="submit" name="reject" class="btn-red">Reject</button>
                        </form>
                        <?php } else { ?>
                            &mdash;
                        <?php } ?>
                    </td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="9" class="empty-row">No requests found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
