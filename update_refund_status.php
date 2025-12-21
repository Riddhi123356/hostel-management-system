<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_POST['id'];

$director = $_POST['director_status'];
$rector = $_POST['rector_status'];
$librarian = $_POST['librarian_status'];
$accountant = $_POST['accountant_status'];

$sql = "
UPDATE refund_requests SET
    director_status='$director',
    rector_status='$rector',
    librarian_status='$librarian',
    accountant_status='$accountant'
WHERE id='$id'
";

$conn->query($sql);

header("Location: admin_refund.php");
exit();
