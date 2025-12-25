<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<style>
/* ===== GLOBAL ===== */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #F4F7FB;
}

/* ===== LAYOUT ===== */
.wrapper {
    display: flex;
    height: 100vh;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 250px;
    background: #243a52;
    color: white;
    padding: 20px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 5px;
}

.sidebar .role {
    text-align: center;
    font-size: 13px;
    color: #2ecc71;
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    padding: 10px;
    margin: 6px 0;
    color: #ecf0f1;
    text-decoration: none;
    border-radius: 4px;
}

.sidebar a:hover {
    background: #345b7a;
}

.sidebar a.active {
    background: #1abc9c;
    font-weight: bold;
}

/* ===== CONTENT ===== */
.content {
    flex: 1;
    padding: 25px;
}

.header {
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 20px;
}

/* ===== CARDS ===== */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.card h3 {
    margin: 0;
    color: #34495e;
}

.card p {
    margin-top: 10px;
    color: #777;
}

/* ===== BUTTON ===== */
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-view {
    background: #3498db;
    color: white;
}

.btn-logout {
    background: #e74c3c;
    color: white;
    margin-top: 20px;
}
</style>
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>ADMIN PANEL</h2>
        <div class="role">Online</div>

        <a href="admin_dashboard.php" class="active">Dashboard</a>
        <a href="admin_maintenance.php">Maintenance Issues</a>
        <a href="admin_gatepass_leave.php">Gate Pass & Leave</a>
        <a href="admin_refund.php">Refund Requests</a>
        <a href="admin_changeInformation.php">Change Information</a>
        <a href="admin_icard.php">Request for Icard</a>
        <a href="admin_add_student.php">Add student</a>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="header">Dashboard Overview</div>

        <div class="cards">

            <div class="card">
                <h3>Maintenance</h3>
                <p>View & resolve student maintenance issues</p>
                <br>
                <a href="admin_maintenance.php">
                    <button class="btn btn-view">View</button>
                </a>
            </div>

            <div class="card">
                <h3>Gate Pass</h3>
                <p>Approve or reject gate pass & leave requests</p>
                <br>
                <a href="admin_gatepass_leave.php">
                    <button class="btn btn-view">View</button>
                </a>
            </div>

            <div class="card">
                <h3>Refund</h3>
                <p>Handle hostel fee refund requests</p>
                <br>
                <a href="admin_refund.php">
                    <button class="btn btn-view">View</button>
                </a>
            </div>

            <div class="card">
                <h3>Change Info</h3>
                <p>Approve student information change requests</p>
                <br>
                <a href="admin_changeInformation.php">
                    <button class="btn btn-view">View</button>
                </a>
            </div>
            <div class="card">
                <h3>I-card</h3>
                <p>View & approve student I-Card requests</p>
                <br>
                <a href="admin_icard.php">
                    <button class="btn btn-view">View</button>
                </a>
            </div>
             <div class="card">
                <h3>Students</h3>
                <p>Add new students and remove existing student accounts.
                 Manage registration numbers and login access.</p>
                <br>
                <a href="admin_icard.php">
                    <button class="btn btn-view">View</button>
                </a>
            </div>


        </div>
    </div>

</div>

</body>
</html>
