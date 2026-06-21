<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];

// Show the most recent fee record regardless of status, so unpaid
// students can see what they owe (and pay it).
$stmt = $conn->prepare("SELECT * FROM fees WHERE regno = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $regno);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$activePage = 'fees.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hostel Fees</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Hostel Fees</h2>
        <p class="page-subtitle">Your fee payment status for this academic year.</p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Registration No.</th>
                    <th>Year</th>
                    <th>Fees</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php if ($row) { ?>
                <tr>
                    <td><?= e($row['name']) ?></td>
                    <td><?= e($row['regno']) ?></td>
                    <td><?= e($row['year']) ?></td>
                    <td>&#8377; <?= e(number_format((float)$row['fees_amount'], 2)) ?></td>
                    <td><?= status_badge($row['fees_status'] === 'paid' ? 'Approved' : 'Pending') ?></td>
                    <td>
                        <?php if ($row['fees_status'] === 'paid') { ?>
                            <button class="btn btn-outline no-print" onclick="window.print()">Print Receipt</button>
                        <?php } else { ?>
                            <a href="pay_fees.php?id=<?= (int)$row['id'] ?>" class="btn btn-amber">Pay Now</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } else { ?>
                <tr>
                    <td colspan="6" class="empty-row">No fee record found yet. Please contact the hostel office.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>