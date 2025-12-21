<?php
session_start();
include "../db.php";

$regno = $_POST['regno'];
$name = $_POST['name'];
$year = $_POST['year'];

$check = $conn->query("SELECT * FROM icard WHERE regno='$regno'");

if ($check->num_rows > 0) {
    $conn->query("UPDATE icard SET name='$name', year='$year' WHERE regno='$regno'");
} else {
    $conn->query("INSERT INTO icard (regno,name,year) VALUES ('$regno','$name','$year')");
}

header("Location: admin_icard.php");
exit();
