<?php
$conn = new mysqli("localhost", "root", "", "management");
if ($conn->connect_error) {
    die("Database connection failed");
}
?>
