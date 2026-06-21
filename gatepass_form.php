<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$error = "";

if (isset($_POST['submit'])) {
    csrf_verify();

    $regno = $_SESSION['regno'];
    $type = $_POST['type'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $out_time = trim($_POST['out_time'] ?? '');
    $in_time = trim($_POST['in_time'] ?? '');
    $start_date = $_POST['start_date'] ?? null;

    if ($type === "Gate Pass") {
        $return_date = $start_date;
    } else {
        $return_date = $_POST['return_date'] ?? null;
    }

    if ($type === '' || $reason === '' || $out_time === '' || $in_time === '') {
        $error = "Please fill in all required fields.";
    } else {
        $attachment = null;

        if (!empty($_FILES['attachment']['name'])) {
            $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
            $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                $error = "Attachment must be a JPG, PNG, or PDF file.";
            } elseif ($_FILES['attachment']['size'] > 2 * 1024 * 1024) {
                $error = "Attachment must be 2MB or smaller.";
            } else {
                $safeName = time() . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['attachment']['name']);
                $destination = __DIR__ . "/uploads/" . $safeName;

                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $destination)) {
                    $attachment = $safeName;
                } else {
                    $error = "Could not upload the attachment. Please try again.";
                }
            }
        }

        if (!$error) {
            $stmt = $conn->prepare("
                INSERT INTO gatepass_leave (regno, type, reason, out_time, in_time, start_date, return_date, attachment)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssssssss", $regno, $type, $reason, $out_time, $in_time, $start_date, $return_date, $attachment);

            if ($stmt->execute()) {
                header("Location: leave.php");
                exit();
            } else {
                $error = "Something went wrong while submitting your request.";
            }
        }
    }
}

$activePage = 'leave.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Apply for Gate Pass / Leave</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Apply for Gate Pass / Leave</h2>
        <p class="page-subtitle">Submit a request and track its approval status.</p>

        <div class="panel">
            <?php if ($error) { ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <label>Select Type*</label>
                <select id="typeSelect" name="type" onchange="showFields()" required>
                    <option value="">Select Type</option>
                    <option value="Gate Pass">Gate Pass</option>
                    <option value="Leave">Leave</option>
                </select>

                <label>Reason*</label>
                <select name="reason" required>
                    <option value="">Select</option>
                    <option value="Shopping">Shopping</option>
                    <option value="college/coaching">College / Coaching</option>
                    <option value="End of Admission">End of Admission</option>
                    <option value="go to home">Go to Home</option>
                    <option value="leave extend">Leave Extend</option>
                    <option value="Medical">Medical</option>
                </select>

                <label>Approx Out Time*</label>
                <input type="text" name="out_time" placeholder="e.g. 10:00 AM" required>

                <label>Approx In Time*</label>
                <input type="text" name="in_time" placeholder="e.g. 6:00 PM" required>

                <div id="date1" style="display:none;">
                    <label>Date*</label>
                    <input type="date" name="start_date">
                </div>

                <div id="date2" style="display:none;">
                    <label>Return Date*</label>
                    <input type="date" name="return_date">
                </div>

                <div id="attachmentField" style="display:none;">
                    <label>Attachment (Max 2MB, JPG/PNG/PDF)</label>
                    <input type="file" name="attachment">
                </div>

                <button type="submit" name="submit" class="btn-primary">Submit</button>
                <a href="leave.php" class="btn btn-outline">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="js/javascript.js"></script>
</body>
</html>
