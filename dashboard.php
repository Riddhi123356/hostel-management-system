<?php
session_start();
if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link rel="stylesheet" href="../css/style.css">

<style>
.layout {
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 260px;
    background-color: #243a52; 
    color: white;
}

a {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 18px;
}
    

a:hover {
    background-color: #2c4f6b;
}

a.active {
    background-color: #1f3e56;
    border-left: 4px solid #2ecc71;
    font-weight: bold;
}

.content {
    flex: 1;
}

iframe {
    width: 100%;
    height: 100%;
    border: none;
}

</style>
</head>

<body>

<div class="layout">

    <!-- LEFT MENU -->
    <div class="sidebar">
        <?php include "sidebar.php"; ?>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="content">
        <iframe name="contentFrame" src=""></iframe>
    </div>

</div>
</body>
</html>
