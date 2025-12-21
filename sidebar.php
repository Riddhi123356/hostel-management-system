<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="profile">
    <br>
    <center><strong><?php echo $_SESSION['name']; ?></strong></center><br>
    <span style="color: #2ecc71;font-size: 14px;">● Online</span><br><br>
</div>
<nav class="sidebar" id="sidebar">
<a href="fees.php" target="contentFrame">Hostel Fees</a><br><br>
<a href="maintenance.php" target="contentFrame">Maintenance Issue</a><br><br>
<a href="icard.php" target="contentFrame"> Applied for I-card</a><br><br>
<a href="promotion.php" target="contentFrame">Applied for Promotion</a><br><br>
<a href="leave.php" target="contentFrame"> Gate Pass & Leave</a><br><br>
<a href="refund.php" target="contentFrame">Refund Request</a><br><br>
<a href="reporting.php" target="contentFrame">Reporting History</a><br><br>
<a href="changeInformation.php" target="contentFrame">Change Information</a><br><br>
<a href="changePassword.php" target="contentFrame">Change Password</a><br><br>
</nav>
</body>
</html>