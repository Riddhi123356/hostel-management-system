<?php
/**
 * Small utility to generate a password_hash() for manually inserting a
 * student or admin row into the database (e.g. via phpMyAdmin).
 *
 * The original hash.php had no login check and hard-coded a real phone
 * number into the URL — anyone could load it and see a working hash.
 * This version is gated behind the admin session and takes input from
 * a form instead of being hard-coded.
 */
session_start();
include "includes/functions.php";
require_admin_login();

$hash = "";
$plain = "";

if (isset($_POST['generate'])) {
    $plain = $_POST['plain_password'] ?? '';
    if ($plain !== '') {
        $hash = password_hash($plain, PASSWORD_DEFAULT);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Password Hash Utility</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Password Hash Utility</h2>
        <p class="page-subtitle">Generate a password_hash() value to insert manually via phpMyAdmin, if ever needed.</p>

        <div class="panel">
            <form method="post">
                <label>Plain text password</label>
                <input type="text" name="plain_password" value="<?= e($plain) ?>" required>
                <button type="submit" name="generate" class="btn-primary">Generate Hash</button>
            </form>

            <?php if ($hash) { ?>
                <label style="margin-top:18px;">Generated hash (copy this into the database)</label>
                <textarea readonly rows="3"><?= e($hash) ?></textarea>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>
