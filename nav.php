<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Navigation</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="sidebar">
    <a href="dashboard.php" target="contentFrame">Dashboard</a>
    <a href="fees.php" target="contentFrame">Hostel Fees</a>
    <a href="maintenance.php" target="contentFrame">Maintenance</a>
    <a href="icard.php" target="contentFrame">I-card</a>
    <a href="promotion.php" target="contentFrame">Promotion</a>
    <a href="leave.php" target="contentFrame"background-color: #FAFAFA;>Gate Pass</a>
    <a href="changePassword.php" target="contentFrame" tabindex="0">Change Password</a>
    <a href="logout.php" target="_top" tabindex="0">Logout</a>
</nav>
</body>
</html>
