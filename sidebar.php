<?php
/**
 * Student sidebar navigation.
 * Include this from any student-facing page AFTER session_start().
 * Expects $activePage to be set to the current file name for highlighting.
 */
$activePage = $activePage ?? '';
?>
<div class="sidebar">
    <div class="brand">Hostel Portal</div>
    <div class="profile">
        <strong><?= e($_SESSION['regno'] ?? '') ?></strong>
        <div class="status-dot">Online</div>
    </div>
    <nav>
        <a href="dashboard.php" class="<?= $activePage === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="fees.php" class="<?= $activePage === 'fees.php' ? 'active' : '' ?>">Hostel Fees</a>
        <a href="maintenance.php" class="<?= $activePage === 'maintenance.php' ? 'active' : '' ?>">Maintenance Issue</a>
        <a href="icard.php" class="<?= $activePage === 'icard.php' ? 'active' : '' ?>">I-Card</a>
        <a href="promotion.php" class="<?= $activePage === 'promotion.php' ? 'active' : '' ?>">Promotion</a>
        <a href="leave.php" class="<?= $activePage === 'leave.php' ? 'active' : '' ?>">Gate Pass & Leave</a>
        <a href="refund.php" class="<?= $activePage === 'refund.php' ? 'active' : '' ?>">Refund Request</a>
        <a href="reporting.php" class="<?= $activePage === 'reporting.php' ? 'active' : '' ?>">Reporting History</a>
        <a href="changeInformation.php" class="<?= $activePage === 'changeInformation.php' ? 'active' : '' ?>">Change Information</a>
        <a href="changePassword.php" class="<?= $activePage === 'changePassword.php' ? 'active' : '' ?>">Change Password</a>
    </nav>
    <div class="logout-link">
        <a href="logout.php">Logout</a>
    </div>
</div>
