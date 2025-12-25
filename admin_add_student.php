<?php
session_start();
include "../db.php";

$msg = "";

/* ADD STUDENT */
if (isset($_POST['add'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $check = $conn->query("SELECT * FROM users WHERE regno='$regno'");
    if ($check->num_rows > 0) {
        $msg = "Student already exists";
    } else {
        $conn->query("INSERT INTO users (regno, password) VALUES ('$regno', '$password')");
        $msg = "Student added successfully";
    }
}

/* REMOVE STUDENT */
if (isset($_POST['remove'])) {
    $regno = $_POST['regno'];

    $check = $conn->query("SELECT * FROM users WHERE regno='$regno'");
    if ($check->num_rows == 1) {
        $conn->query("DELETE FROM users WHERE regno='$regno'");
        $msg = "Student removed successfully";
    } else {
        $msg = "Student not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add / Remove Student</title>
<style>
body{
    font-family:Arial;
    background:linear-gradient(to right,#ffecd2,#fcb69f);
}
.box{
    width:380px;
    margin:80px auto;
    background:white;
    padding:25px;
    border-radius:8px;
    box-shadow:0 0 10px gray;
}
input,button{
    width:95%;
    padding:10px;
    margin:10px 0;
}
.add{
    background:#2ecc71;
    color:white;
    border:none;
}
.remove{
    background:#e74c3c;
    color:white;
    border:none;
}
.msg{
    text-align:center;
    font-weight:bold;
    color:green;
}
</style>
</head>

<body>

<div class="box">
    <h3 align="center">Add / Remove Student</h3>

    <?php if($msg!="") echo "<p class='msg'>$msg</p>"; ?>

    <form method="post">
        <input type="text" name="regno" placeholder="Registration Number" required>
        <input type="password" name="password" placeholder="Password (for Add only)">

        <button type="submit" name="add" class="add">Add Student</button>
        <button type="submit" name="remove" class="remove">Remove Student</button>
    </form>
</div>

</body>
</html>
