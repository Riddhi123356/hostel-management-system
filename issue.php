<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$error = "";

if (isset($_POST['submit'])) {
    csrf_verify();

    $regno = $_SESSION['regno'];
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($category === '' || $description === '') {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO maintenance (regno, category, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $regno, $category, $description);

        if ($stmt->execute()) {
            header("Location: maintenance.php");
            exit();
        } else {
            $error = "Something went wrong while submitting your request. Please try again.";
        }
    }
}

$activePage = 'maintenance.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>New Maintenance Issue</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Report a Maintenance Issue</h2>
        <p class="page-subtitle">Let us know what's wrong and we'll take care of it.</p>

        <div class="panel">
            <?php if ($error) { ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php } ?>

            <form method="post">
                <?= csrf_field() ?>

                <label for="category">Category*</label>
                <select name="category" id="category" required>
                    <option value="">Select</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Plumber">Plumber</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="Kitchen">Kitchen</option>
                    <option value="IT">IT</option>
                    <option value="Security">Security</option>
                </select>

                <label for="description">Description*</label>
                <textarea name="description" id="description" rows="5" required></textarea>

                <label>Photo Proof (optional)</label>
                <input type="file" name="photo">
                <p style="font-size:12px;color:var(--muted);margin-top:-10px;">
                    Note: photo upload is not yet wired to storage — this field is a placeholder for a future update.
                </p>

                <button type="submit" name="submit" class="btn-primary">Submit Request</button>
                <a href="maintenance.php" class="btn btn-outline">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
