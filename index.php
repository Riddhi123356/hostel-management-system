<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $regNo = $_POST['regNo'];   // phone / registration number
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE phone='$regNo' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        header("Location: home.php"); // frameset page
        exit;
    } else {
        $error = "Invalid Registration Number or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign In</title>

<style>
body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.container {
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    width: 300px;
}
input[type="text"], input[type="password"] {
    width: 95%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
button {
    width: 100%;
    padding: 10px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}
button:hover {
    
    background: #45a049; 
}

.error {
     color:pink; 
     text-align:center; 
}
</style>
</head>

<body>

<div class="container">
    <h4 align="center">Sign in to start your session</h4>

    <?php if (!empty($error)) { ?>
        <p class="error"><?= $error ?></p>
    <?php } ?>

    <form method="POST">
        <label>Registration Number</label>
        <input type="text" name="regNo" required placeholder="Enter Registration No">

        <label>Password</label>
        <input type="password" name="password" id="password" required placeholder="Enter Password">

        <div>
            <input type="checkbox" onclick="togglePassword()"> Show Password
        </div><br>

        <button type="submit" name="login">Sign In</button>
    </form>
</div>

<script>
function togglePassword() {
    var pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
