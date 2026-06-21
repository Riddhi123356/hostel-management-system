<?php
/**
 * ONE-TIME SETUP SCRIPT — creates your first admin login.
 *
 * Why this exists: hash.php (the password hash generator) requires you
 * to already be logged in as an admin, which is impossible the very
 * first time you set this project up. This script has no such
 * requirement, but only works ONCE: as soon as an admin row exists in
 * the database, it locks itself.
 *
 * HOW TO USE:
 *   1. Visit this file in your browser:
 *      http://localhost/hostel-management-system/setup_first_admin.php
 *   2. Fill in a username and password for your admin account.
 *   3. Submit. You'll get a confirmation, then you can log in normally
 *      at admin_login.php.
 *   4. (Optional but recommended) Delete this file afterwards, or leave
 *      it -- it's harmless once an admin already exists, since it
 *      refuses to run again.
 */
include "db.php";
include "includes/functions.php";

$existing = $conn->query("SELECT COUNT(*) AS c FROM admin")->fetch_assoc();
$alreadySetUp = $existing['c'] > 0;

$message = "";
$messageType = "error";

if (!$alreadySetUp && isset($_POST['create_admin'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '') {
        $message = "Username and password are both required.";
    } elseif ($password !== $confirm) {
        $message = "Password and confirm password do not match.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);

        if ($stmt->execute()) {
            $message = "Admin account created! You can now sign in at admin_login.php.";
            $messageType = "success";
            $alreadySetUp = true;
        } else {
            $message = "Could not create the admin account. That username may already be taken.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>First-Time Admin Setup</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-box" style="max-width:400px;">
        <h2>First-Time Admin Setup</h2>
        <p class="auth-subtitle">Create the first admin login for this installation.</p>

        <?php if ($message) { ?>
            <div class="alert <?= $messageType === 'success' ? 'alert-success' : 'alert-error' ?>"><?= e($message) ?></div>
        <?php } ?>

        <?php if ($alreadySetUp) { ?>
            <p style="text-align:center;color:var(--muted);font-size:13.5px;">
                An admin account already exists for this installation.
                This setup page is now locked for safety.
            </p>
            <a href="admin_login.php" class="btn btn-primary" style="display:block;text-align:center;margin-top:10px;">
                Go to Admin Login
            </a>
        <?php } else { ?>
            <form method="post">
                <label for="username">Admin Username</label>
                <input type="text" id="username" name="username" required autofocus>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="6">

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">

                <button type="submit" name="create_admin">Create Admin Account</button>
            </form>
        <?php } ?>
    </div>
</div>

</body>
</html>
