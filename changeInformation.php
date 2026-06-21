<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];
$error = "";

if (isset($_POST['submit_change'])) {
    csrf_verify();

    $description = trim($_POST['description'] ?? '');

    if ($description === '') {
        $error = "Please describe what you'd like to change.";
    } else {
        $stmt = $conn->prepare("INSERT INTO change_information_requests (regno, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $regno, $description);
        $stmt->execute();

        header("Location: changeInformation.php");
        exit();
    }
}

$stmt = $conn->prepare("SELECT description, status FROM change_information_requests WHERE regno = ? ORDER BY id DESC");
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();

$activePage = 'changeInformation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Information</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Change Information Request</h2>
        <p class="page-subtitle">Request an update to your basic information (name, mobile number, etc.).</p>

        <div class="panel">
            <?php if ($error) { ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php } ?>

            <form method="post">
                <?= csrf_field() ?>
                <label>Description</label>
                <textarea name="description" rows="5"
                    placeholder="If you want to change basic information like name, mobile no., etc., explain here..."
                    required></textarea>
                <button type="submit" name="submit_change" class="btn-primary">Submit</button>
            </form>
        </div>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Description</th>
                    <th>Status</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    while ($r = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= nl2br(e($r['description'])) ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="2" class="empty-row">No requests found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
