<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$message = "";
$messageType = "error";

if (isset($_POST['change_password'])) {
    csrf_verify();

    $regno = $_SESSION['regno'];
    $oldPass = $_POST['old_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if ($newPass !== $confirmPass) {
        $message = "New password and confirm password do not match.";
    } elseif (strlen($newPass) < 6) {
        $message = "New password must be at least 6 characters long.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE regno = ?");
        $stmt->bind_param("s", $regno);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows === 1) {
            $row = $check->fetch_assoc();

            if (password_verify($oldPass, $row['password'])) {
                $newHash = password_hash($newPass, PASSWORD_DEFAULT);

                $update = $conn->prepare("UPDATE users SET password = ? WHERE regno = ?");
                $update->bind_param("ss", $newHash, $regno);

                if ($update->execute()) {
                    $message = "Password changed successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error updating password. Please try again.";
                }
            } else {
                $message = "Old password is incorrect.";
            }
        }
    }
}

$activePage = 'changePassword.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Password</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Change Password</h2>
        <p class="page-subtitle">Update the password you use to sign in.</p>

        <div class="panel">
            <?php if ($message) { ?>
                <div class="alert <?= $messageType === 'success' ? 'alert-success' : 'alert-error' ?>"><?= e($message) ?></div>
            <?php } ?>

            <form method="post" onsubmit="return validatePassword();" id="changePasswordForm">
                <?= csrf_field() ?>

                <label>Old Password</label>
                <input type="password" name="old_password" id="oldPass" required>

                <label>New Password</label>
                <input type="password" name="new_password" id="newPass" required minlength="6">

                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirmPass" required minlength="6">

                <div id="passwordError" class="alert alert-error" style="display:none;"></div>

                <button type="submit" name="change_password" class="btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</div>

<script src="js/javascript.js"></script>
</body>
</html>
