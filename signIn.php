<?php
session_start();
include "../db.php";

$error = "";

if (isset($_POST['signin'])) {

    $name     = $_POST['name'];
    $regno    = $_POST['regno'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users 
            WHERE name='$name' 
            AND regno='$regno' 
            AND password='$password'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();

        $_SESSION['regno'] = $row['regno'];
        $_SESSION['name']  = $row['name'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Name, Registration Number or Password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Sign In</title>
<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Arial;
    background:linear-gradient(to right,#74ebd5,#9face6);
}
.container{
    background:#fff;
    padding:25px;
    width:330px;
    border-radius:8px;
    box-shadow:0 0 10px gray;
}
input{
    width:95%;
    padding:10px;
    margin:10px 0;
}
button{
    width:100%;
    padding:10px;
    background:#4CAF50;
    color:#fff;
    border:none;
    cursor:pointer;
}
.error{
    color:red;
    text-align:center;
}
</style>
</head>

<body>

<div class="container">
    <h2 align="center">Student Sign In</h2>

    <?php if($error!="") echo "<p class='error'>$error</p>"; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="regno" placeholder="Registration Number" required>
        <input type="password" name="password" placeholder="Hostel Password" required>
        <button type="submit" name="signin">Sign In</button>
    </form>
</div>

</body>
</html>
