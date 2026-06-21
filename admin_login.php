<?php
session_start();
include "db.php";
include "includes/functions.php";

$error = "";

if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin'] = $row['username'];
            $_SESSION['admin_id'] = $row['id'];

            header("Location: admin_dashboard.php");
            exit();
        }
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-box">
        <h2>Admin Login</h2>
        <p class="auth-subtitle">Hostel management control panel</p>

        <?php if ($error) { ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php } ?>

        <form method="post">
            <label for="username">Admin Username</label>
            <input type="text" id="username" name="username" required autofocus>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <div class="auth-switch">Are you a student? <a href="signIn.php">Student sign in</a></div>
    </div>
</div>

</body>
</html>
