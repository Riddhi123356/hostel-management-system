<?php
session_start();
include "db.php";
include "includes/functions.php";

$error = "";

// Already logged in? Skip straight to the dashboard.
if (isset($_SESSION['regno'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['signin'])) {
    $regno    = trim($_POST['regno'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($regno === '' || $password === '') {
        $error = "Please enter both registration number and password.";
    } else {
        $stmt = $conn->prepare("SELECT regno, password FROM users WHERE regno = ?");
        $stmt->bind_param("s", $regno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['regno'] = $row['regno'];

                header("Location: dashboard.php");
                exit();
            }
        }

        $error = "Invalid registration number or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Sign In</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-box">
        <h2>Student Sign In</h2>
        <p class="auth-subtitle">Welcome back to the hostel portal</p>

        <?php if ($error) { ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php } ?>

        <form method="post">
            <label for="regno">Registration Number</label>
            <input type="text" id="regno" name="regno" placeholder="e.g. STU001" required autofocus>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Hostel password" required>

            <div class="show-password-row">
                <input type="checkbox" id="showPass" onclick="togglePassword(this, 'password')">
                <label for="showPass" style="margin:0;font-weight:400;">Show password</label>
            </div>

            <button type="submit" name="signin">Sign In</button>
        </form>

        <div class="auth-switch">Are you an admin? <a href="admin_login.php">Admin sign in</a></div>
    </div>
</div>

<script src="js/javascript.js"></script>
</body>
</html>
