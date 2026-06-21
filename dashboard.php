<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];

/* Pull a few quick counts so the dashboard isn't just a static menu */
$stmt = $conn->prepare("SELECT COUNT(*) AS c FROM maintenance WHERE regno = ? AND status = 'Pending'");
$stmt->bind_param("s", $regno);
$stmt->execute();
$pendingMaintenance = $stmt->get_result()->fetch_assoc()['c'];

$stmt = $conn->prepare("SELECT COUNT(*) AS c FROM gatepass_leave WHERE regno = ? AND status = 'Pending'");
$stmt->bind_param("s", $regno);
$stmt->execute();
$pendingGatepass = $stmt->get_result()->fetch_assoc()['c'];

$stmt = $conn->prepare("SELECT fees_status FROM fees WHERE regno = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $regno);
$stmt->execute();
$feesRow = $stmt->get_result()->fetch_assoc();
$feesStatus = $feesRow['fees_status'] ?? 'unpaid';

$stmt = $conn->prepare("SELECT status FROM icard_requests WHERE regno = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $regno);
$stmt->execute();
$icardRow = $stmt->get_result()->fetch_assoc();
$icardStatus = $icardRow['status'] ?? 'Not Requested';

$activePage = 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Welcome back, <?= e($regno) ?></h2>
        <p class="page-subtitle">Here's a quick look at your hostel account.</p>

        <div class="cards">
            <div class="stat-card <?= $feesStatus === 'paid' ? 'accent-green' : 'accent-red' ?>">
                <div class="stat-number"><?= $feesStatus === 'paid' ? 'Paid' : 'Unpaid' ?></div>
                <div class="stat-label">Hostel Fees Status</div>
            </div>

            <div class="stat-card accent-amber">
                <div class="stat-number"><?= (int)$pendingMaintenance ?></div>
                <div class="stat-label">Pending Maintenance Issues</div>
            </div>

            <div class="stat-card accent-amber">
                <div class="stat-number"><?= (int)$pendingGatepass ?></div>
                <div class="stat-label">Pending Gate Pass / Leave</div>
            </div>

            <div class="stat-card">
                <div class="stat-number" style="font-size:20px;"><?= e($icardStatus) ?></div>
                <div class="stat-label">I-Card Request Status</div>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Hostel Fees</h3>
                <p>View your fee status and print a receipt.</p>
                <a href="fees.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>Maintenance</h3>
                <p>Report and track maintenance issues in your room.</p>
                <a href="maintenance.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>Gate Pass & Leave</h3>
                <p>Apply for a gate pass or leave, and track approvals.</p>
                <a href="leave.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>I-Card</h3>
                <p>Request or check the status of your hostel I-Card.</p>
                <a href="icard.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>Promotion</h3>
                <p>Submit your academic progress documents for the new term.</p>
                <a href="promotion.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>Refund Request</h3>
                <p>Submit a refund request and track approval across departments.</p>
                <a href="refund.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>Reporting History</h3>
                <p>View remarks and notes recorded about your hostel stay.</p>
                <a href="reporting.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>Change Information</h3>
                <p>Request an update to your name, mobile number, or other details.</p>
                <a href="changeInformation.php" class="btn btn-primary">Open</a>
            </div>
            <div class="card">
                <h3>Change Password</h3>
                <p>Update the password you use to sign in.</p>
                <a href="changePassword.php" class="btn btn-primary">Open</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
