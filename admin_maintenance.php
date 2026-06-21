<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

$result = $conn->query("SELECT * FROM maintenance ORDER BY created_at DESC");

$activePage = 'admin_maintenance.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Maintenance Requests</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Maintenance Requests</h2>
        <p class="page-subtitle">Review and resolve issues reported by students.</p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    while ($r = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= e($r['regno']) ?></td>
                    <td><?= e($r['category']) ?></td>
                    <td><?= nl2br(e($r['description'])) ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                    <td><?= format_date($r['created_at']) ?></td>
                    <td><?= format_date($r['updated_at']) ?></td>
                    <td>
                        <?php if ($r['status'] == 'Pending') { ?>
                        <form method="post" action="update_maintenance_status.php" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" name="solve" class="btn-green"
                                onclick="return confirmAction('Mark this issue as solved?')">Solve</button>
                        </form>
                        <?php } else { ?>
                            &#10003;
                        <?php } ?>
                    </td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="7" class="empty-row">No maintenance requests found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script src="js/javascript.js"></script>
</body>
</html>
