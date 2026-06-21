<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];

$stmt = $conn->prepare("SELECT * FROM maintenance WHERE regno = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();

$activePage = 'maintenance.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Maintenance Issues</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Maintenance Issues</h2>
        <p class="page-subtitle">Report a problem in your room and track its progress.</p>

        <a href="issue.php" class="btn btn-amber" style="margin-bottom:18px;display:inline-block;">+ New Maintenance Request</a>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No.</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
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
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="6" class="empty-row">No maintenance issues reported yet.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
