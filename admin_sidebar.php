<?php
/**
 * Admin sidebar navigation.
 * Include this from any admin-facing page AFTER session_start().
 * Expects $activePage to be set to the current file name for highlighting.
 */
$activePage = $activePage ?? '';
?>
<div class="sidebar">
    <div class="brand">Admin Panel</div>
    <div class="profile">
        <strong><?= e($_SESSION['admin'] ?? '') ?></strong>
        <div class="status-dot">Online</div>
    </div>
    <nav>
        <a href="admin_dashboard.php" class="<?= $activePage === 'admin_dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="admin_maintenance.php" class="<?= $activePage === 'admin_maintenance.php' ? 'active' : '' ?>">Maintenance Issues</a>
        <a href="admin_gatepass_leave.php" class="<?= $activePage === 'admin_gatepass_leave.php' ? 'active' : '' ?>">Gate Pass & Leave</a>
        <a href="admin_refund.php" class="<?= $activePage === 'admin_refund.php' ? 'active' : '' ?>">Refund Requests</a>
        <a href="admin_changeInformation.php" class="<?= $activePage === 'admin_changeInformation.php' ? 'active' : '' ?>">Change Information</a>
        <a href="admin_icard.php" class="<?= $activePage === 'admin_icard.php' ? 'active' : '' ?>">I-Card Requests</a>
        <a href="admin_fees.php" class="<?= $activePage === 'admin_fees.php' ? 'active' : '' ?>">Hostel Fees</a>
        <a href="admin_add_student.php" class="<?= $activePage === 'admin_add_student.php' ? 'active' : '' ?>">Add / Remove Student</a>
    </nav>
    <div class="logout-link">
        <a href="logout.php">Logout</a>
    </div>
</div>