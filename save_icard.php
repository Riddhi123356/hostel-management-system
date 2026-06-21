<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();
csrf_verify();

$regno = trim($_POST['regno'] ?? '');
$name = trim($_POST['name'] ?? '');
$year = trim($_POST['year'] ?? '');

if ($regno !== '') {
    $stmt = $conn->prepare("SELECT id FROM icard WHERE regno = ?");
    $stmt->bind_param("s", $regno);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE icard SET name = ?, year = ? WHERE regno = ?");
        $stmt->bind_param("sss", $name, $year, $regno);
    } else {
        $stmt = $conn->prepare("INSERT INTO icard (regno, name, year) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $regno, $name, $year);
    }
    $stmt->execute();
}

header("Location: admin_icard.php");
exit();
