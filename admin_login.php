<?php
session_start();
include "../db.php";

$error = "";

// If already logged in
if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepared statement for security
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {

            $_SESSION['admin'] = $row['username'];
            $_SESSION['admin_id'] = $row['id'];

            header("Location: admin_dashboard.php");
            exit();
        }
    }

    $error = "Invalid username or password";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Arial, sans-serif;
    background:#F4F7FB;
}
.box{
    background:white;
    padding:30px;
    width:320px;
    border-radius:8px;
    box-shadow:0 0 10px #ccc;
}
input{
    width:93%;
    padding:10px;
    margin:10px 0;
}
button{
    width:100%;
    padding:10px;
    background:#2563EB;
    color:white;
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

<div class="box">
<h2 align="center">Admin Login</h2>

<?php if ($error) echo "<p class='error'>$error</p>"; ?>

<form method="post">
    <input type="text" name="username" placeholder="Admin Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>
</div>

</body>
</html>
