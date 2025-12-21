<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

if (isset($_POST['submit'])) {

    $regno = $_SESSION['regno'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $sql = "INSERT INTO maintenance (regno, category, description)
            VALUES ('$regno', '$category', '$description')";

    if ($conn->query($sql)) {
        header("Location: maintenance.php");
        exit();
    } else {
        echo "Error";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Services</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h2>Add Maintenance Issue</h2><hr>

<form method="post">
<label>Category*<br></label>
<select name="category" required>
    <option value="">Select</option>
    <option value="Electrical">Electrical</option>
    <option value="Furniture">Furniture</option>
    <option value="Plumber">Plumber</option>
    <option value="Cleaning">Cleaning</option>
    <option value="Kitchen">Kitchen</option>
    <option value="IT">IT</option>
    <option value="Security">Security</option>
</select><br><br>

<label>Description*</label><br>
<textarea name="description" rows="5" cols="50" required></textarea><br><br>

<label>Photo Proof*<br></label>
<input type="file"><br><br>

<input type="submit" name="submit" value="Submit">
</form>

</body>
</html>
