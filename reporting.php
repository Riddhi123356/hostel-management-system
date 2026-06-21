<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];

$stmt = $conn->prepare("SELECT remark, created_at FROM reporting_history WHERE registration_no = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();

$activePage = 'reporting.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reporting History</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Reporting History</h2>
        <p class="page-subtitle">Remarks and notes recorded about your hostel stay.</p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Remark</th>
                    <th>Created At</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    while ($r = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= nl2br(e($r['remark'])) ?></td>
                    <td><?= format_date($r['created_at']) ?></td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="2" class="empty-row">No reporting history found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
