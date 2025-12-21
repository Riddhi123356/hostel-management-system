<?php
include "../db.php";

if (isset($_POST['approve'])) {

    $id   = $_POST['id'];
    $name = $_POST['name'];
    $year = $_POST['year'];

    $conn->query("
        UPDATE icard_requests 
        SET name='$name', year='$year', status='Approved'
        WHERE id='$id'
    ");

    header("Location: admin_icard.php");
}
