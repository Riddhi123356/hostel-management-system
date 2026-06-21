<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

$result = $conn->query("SELECT * FROM refund_requests ORDER BY created_at DESC");

$statusOptions = ['Pending', 'Approved', 'Rejected'];

$activePage = 'admin_refund.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Refund Requests</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Refund Requests</h2>
        <p class="page-subtitle">Each refund needs sign-off from four departments before it's complete.</p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg. No</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Bank</th>
                    <th>Account No</th>
                    <th>IFSC</th>
                    <th>Director</th>
                    <th>Rector</th>
                    <th>Librarian</th>
                    <th>Accountant</th>
                    <th>Action</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    $rowNum = 0;
                    while ($r = $result->fetch_assoc()) {
                        $rowNum++;
                        $formId = 'refundRow' . $rowNum;
                ?>
                <tr>
                    <td><?= e($r['registration_no']) ?></td>
                    <td><?= e($r['request_type']) ?></td>
                    <td>&#8377; <?= e(number_format((float)$r['amount'], 2)) ?></td>
                    <td><?= e($r['bank_name']) ?></td>
                    <td><?= e($r['account_no']) ?></td>
                    <td><?= e($r['ifsc']) ?></td>

                    <td>
                        <select form="<?= $formId ?>" name="director_status">
                            <?php foreach ($statusOptions as $opt) { ?>
                            <option <?= $r['director_status'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select form="<?= $formId ?>" name="rector_status">
                            <?php foreach ($statusOptions as $opt) { ?>
                            <option <?= $r['rector_status'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select form="<?= $formId ?>" name="librarian_status">
                            <?php foreach ($statusOptions as $opt) { ?>
                            <option <?= $r['librarian_status'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select form="<?= $formId ?>" name="accountant_status">
                            <?php foreach ($statusOptions as $opt) { ?>
                            <option <?= $r['accountant_status'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <form id="<?= $formId ?>" method="post" action="update_refund_status.php" style="display:contents;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" class="btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="11" class="empty-row">No refund requests found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
