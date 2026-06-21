<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

$requests = $conn->query("
    SELECT r.id, r.regno, r.status, i.name, i.year
    FROM icard_requests r
    LEFT JOIN icard i ON r.regno = i.regno
    ORDER BY r.id DESC
");

$activePage = 'admin_icard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>I-Card Requests</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>I-Card Requests</h2>
        <p class="page-subtitle">Fill in details and approve student I-Card requests.</p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php if ($requests->num_rows > 0) {
                    $rowNum = 0;
                    while ($r = $requests->fetch_assoc()) {
                        $rowNum++;
                        $formId = 'icardRow' . $rowNum;
                ?>
                <tr>
                    <td>
                        <?= e($r['regno']) ?>
                        <input type="hidden" form="<?= $formId ?>" name="regno" value="<?= e($r['regno']) ?>">
                    </td>
                    <td><input type="text" form="<?= $formId ?>" name="name" value="<?= e($r['name']) ?>" required></td>
                    <td><input type="text" form="<?= $formId ?>" name="year" value="<?= e($r['year']) ?>" required></td>
                    <td><?= status_badge($r['status']) ?></td>
                    <td class="action-row">
                        <form id="<?= $formId ?>" method="post" action="save_icard.php" style="display:contents;">
                            <?= csrf_field() ?>
                            <button type="submit" name="save" class="btn-outline">Save</button>
                            <?php if ($r['status'] !== 'Approved') { ?>
                                <button type="submit" formaction="update_icard_status.php" name="approve" class="btn-green">Approve</button>
                            <?php } ?>
                        </form>
                    </td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="5" class="empty-row">No I-Card requests found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
