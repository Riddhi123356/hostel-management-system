<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

$error = "";
$success = "";

/* ---- Add a new fee record ---- */
if (isset($_POST['add_fee'])) {
    csrf_verify();

    $regno = trim($_POST['regno'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $fees_amount = $_POST['fees_amount'] ?? '';
    $fees_status = ($_POST['fees_status'] ?? 'unpaid') === 'paid' ? 'paid' : 'unpaid';

    if ($regno === '' || $fees_amount === '') {
        $error = "Registration number and fee amount are required.";
    } else {
        // Confirm the student actually exists, so fee records don't get
        // created for typo'd / non-existent registration numbers.
        $check = $conn->prepare("SELECT regno FROM users WHERE regno = ?");
        $check->bind_param("s", $regno);
        $check->execute();

        if ($check->get_result()->num_rows === 0) {
            $error = "No student found with that registration number. Add the student first.";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO fees (regno, name, year, fees_amount, fees_status)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssds", $regno, $name, $year, $fees_amount, $fees_status);

            if ($stmt->execute()) {
                $success = "Fee record added for $regno.";
            } else {
                $error = "Something went wrong while adding the fee record.";
            }
        }
    }
}

/* ---- Toggle a fee record's paid/unpaid status (e.g. for cash payments) ---- */
if (isset($_POST['toggle_status'])) {
    csrf_verify();

    $id = (int)($_POST['id'] ?? 0);
    $newStatus = ($_POST['new_status'] ?? '') === 'paid' ? 'paid' : 'unpaid';

    $stmt = $conn->prepare("UPDATE fees SET fees_status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();

    $success = "Fee status updated.";
}

/* ---- Delete a fee record ---- */
if (isset($_POST['delete_fee'])) {
    csrf_verify();

    $id = (int)($_POST['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM fees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $success = "Fee record deleted.";
}

$records = $conn->query("SELECT * FROM fees ORDER BY id DESC");

$activePage = 'admin_fees.php';
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
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Hostel Fees</h2>
        <p class="page-subtitle">Add fee records for students and manage payment status.</p>

        <div class="panel">
            <?php if ($error) { ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php } ?>
            <?php if ($success) { ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php } ?>

            <form method="post">
                <?= csrf_field() ?>

                <label>Registration Number*</label>
                <input type="text" name="regno" required>

                <label>Student Name</label>
                <input type="text" name="name">

                <label>Year</label>
                <input type="text" name="year" placeholder="e.g. 2nd Year">

                <label>Fee Amount (&#8377;)*</label>
                <input type="number" step="0.01" name="fees_amount" required>

                <label>Status</label>
                <select name="fees_status">
                    <option value="unpaid">Unpaid</option>
                    <option value="paid">Paid</option>
                </select>

                <button type="submit" name="add_fee" class="btn-primary">Add Fee Record</button>
            </form>
        </div>

        <h3>All Fee Records</h3>
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>

                <?php if ($records->num_rows > 0) {
                    while ($r = $records->fetch_assoc()) { ?>
                <tr>
                    <td><?= e($r['regno']) ?></td>
                    <td><?= e($r['name'] ?: '-') ?></td>
                    <td><?= e($r['year'] ?: '-') ?></td>
                    <td>&#8377; <?= e(number_format((float)$r['fees_amount'], 2)) ?></td>
                    <td><?= status_badge($r['fees_status'] === 'paid' ? 'Approved' : 'Pending') ?></td>
                    <td><?= format_date($r['created_at']) ?></td>
                    <td class="action-row">
                        <form method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <input type="hidden" name="new_status" value="<?= $r['fees_status'] === 'paid' ? 'unpaid' : 'paid' ?>">
                            <button type="submit" name="toggle_status" class="btn-outline">
                                Mark as <?= $r['fees_status'] === 'paid' ? 'Unpaid' : 'Paid' ?>
                            </button>
                        </form>
                        <form method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" name="delete_fee" class="btn-red"
                                onclick="return confirmAction('Delete this fee record?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="7" class="empty-row">No fee records yet.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script src="js/javascript.js"></script>
</body>
</html>