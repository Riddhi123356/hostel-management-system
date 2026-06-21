<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

$msg = "";
$msgType = "success";

if (isset($_POST['add'])) {
    csrf_verify();

    $regno = trim($_POST['regno'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($regno === '' || $password === '') {
        $msg = "Registration number and password are both required to add a student.";
        $msgType = "error";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE regno = ?");
        $stmt->bind_param("s", $regno);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows > 0) {
            $msg = "A student with that registration number already exists.";
            $msgType = "error";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (regno, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $regno, $hash);
            $stmt->execute();
            $msg = "Student added successfully.";
        }
    }
}

if (isset($_POST['remove'])) {
    csrf_verify();

    $regno = trim($_POST['regno'] ?? '');

    $stmt = $conn->prepare("SELECT id FROM users WHERE regno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows === 1) {
        $stmt = $conn->prepare("DELETE FROM users WHERE regno = ?");
        $stmt->bind_param("s", $regno);
        $stmt->execute();
        $msg = "Student removed successfully.";
    } else {
        $msg = "No student found with that registration number.";
        $msgType = "error";
    }
}

/* Recent students for quick reference */
$recent = $conn->query("SELECT regno, name, year, created_at FROM users ORDER BY id DESC LIMIT 10");

$activePage = 'admin_add_student.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add / Remove Student</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Add / Remove Student</h2>
        <p class="page-subtitle">Manage student login access by registration number.</p>

        <div class="panel">
            <?php if ($msg) { ?>
                <div class="alert <?= $msgType === 'success' ? 'alert-success' : 'alert-error' ?>"><?= e($msg) ?></div>
            <?php } ?>

            <form method="post">
                <?= csrf_field() ?>
                <label>Registration Number</label>
                <input type="text" name="regno" required>

                <label>Password (required for Add)</label>
                <input type="password" name="password">

                <div class="action-row">
                    <button type="submit" name="add" class="btn-green">Add Student</button>
                    <button type="submit" name="remove" class="btn-red"
                        onclick="return confirmAction('Remove this student account? This cannot be undone.')">Remove Student</button>
                </div>
            </form>
        </div>

        <h3>Recently Added Students</h3>
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Added On</th>
                </tr>
                <?php if ($recent->num_rows > 0) {
                    while ($r = $recent->fetch_assoc()) { ?>
                <tr>
                    <td><?= e($r['regno']) ?></td>
                    <td><?= e($r['name'] ?? '-') ?></td>
                    <td><?= e($r['year'] ?? '-') ?></td>
                    <td><?= format_date($r['created_at']) ?></td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="4" class="empty-row">No students added yet.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script src="js/javascript.js"></script>
</body>
</html>
