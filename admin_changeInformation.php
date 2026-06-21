<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

$result = $conn->query("SELECT * FROM change_information_requests ORDER BY id DESC");

$activePage = 'admin_changeInformation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Information Requests</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Change Information Requests</h2>
        <p class="page-subtitle">Review and act on student requests to update their personal details.</p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg. No.</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    while ($r = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= e($r['regno']) ?></td>
                    <td><?= nl2br(e($r['description'])) ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                    <td>
                        <?php if ($r['status'] == 'Pending') { ?>
                        <form method="post" action="update_change_info_status.php" class="action-row">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <input type="hidden" name="regno" value="<?= e($r['regno']) ?>">
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
                    <td colspan="4" class="empty-row">No requests found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
