<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

$message = "";

if (isset($_POST['change_password'])) {

    $regno = $_SESSION['regno'];
    $oldPass = $_POST['old_password'];
    $newPass = $_POST['new_password'];

    // Check old password
    $check = $conn->query("SELECT password FROM users WHERE regno='$regno'");

    if ($check && $check->num_rows == 1) {
        $row = $check->fetch_assoc();

        if ($row['password'] == $oldPass) {

            // Update password
            $update = $conn->query("
                UPDATE users 
                SET password='$newPass' 
                WHERE regno='$regno'
            ");

            if ($update) {
                $message = "Password changed successfully";
            } else {
                $message = "Error updating password";
            }

        } else {
            $message = "Old password is incorrect";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="js/javascript.js"></script>

    <style>
    
        .box {
            width: 350px;
            padding: 20px;
            border: 1px solid #ccc;
            background: #f9f9f9;
            border-radius: 6px;
        }
        input[type=password], input[type=submit] {
            width: 92%;
            padding: 10px;
            margin: 8px 0;
        }
    
        .msg {
            color: red;
            margin-bottom: 10px;
        }

        button {
            width:98%;
            padding: 10px;
            margin: 8px 0;
        }
    </style>
</head>

<body>

<h2>Change Password</h2><hr>

<div class="box">

<?php if ($message != "") { ?>
    <div class="msg"><?= $message ?></div>
<?php } ?>

<form method="post" onsubmit="return validatePassword();" id="changePasswordForm">

    <label>Old Password</label>
    <input type="password" name="old_password" id="oldPass" required>

    <label>New Password</label>
    <input type="password" name="new_password" id="newPass" required>

    <label>Confirm Password</label>
    <input type="password" id="confirmPass" required>

    <button name="change_password" value="Change Password">Change Password</button>

</form>
</div>

</body>
</html>
