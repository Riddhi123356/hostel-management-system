<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

if (isset($_POST['submit'])) {

    $regno = $_SESSION['regno'];
    $type = $_POST['type'];
    $reason = $_POST['reason'];
    $out_time = $_POST['out_time'];
    $in_time = $_POST['in_time'];
    $start_date = $_POST['start_date'] ?? null;

// If Gate Pass → same date as return date
if ($type == "Gate Pass") {
    $return_date = $start_date;
} else {
    $return_date = $_POST['return_date'] ?? null;
}

    

    $attachment = "";
    if (!empty($_FILES['attachment']['name'])) {
        $attachment = time() . "_" . $_FILES['attachment']['name'];
        move_uploaded_file($_FILES['attachment']['tmp_name'], "uploads/" . $attachment);
    }

    $sql = "INSERT INTO gatepass_leave 
    (regno, type, reason, out_time, in_time, start_date, return_date, attachment)
    VALUES
    ('$regno','$type','$reason','$out_time','$in_time','$start_date','$return_date','$attachment')";

    $conn->query($sql);

    header("Location: leave.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Promotion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h3>Apply For Gate Pass</h3><hr>

<form method="post" enctype="multipart/form-data">

<label>Select Type*<br></label>
<select id="typeSelect" name="type" onchange="showFields()" required>
    <option value="">Select Type</option>
    <option value="Gate Pass">Gate Pass</option>
    <option value="Leave">Leave</option>
</select><br><br>

<label>Reason*<br></label>
<select name="reason" required>
    <option value="">Select</option>
    <option value="Shopping">Shopping</option>
    <option value="college/coaching">college/coaching</option>
    <option value="End of Admission">End of Admission</option>
    <option value="go to home">go to home</option>
    <option value="leave extend">leave extend</option>
    <option value="Medical">Medical</option>
</select><br><br>

<label>Approx Out time*<br></label>
<input type="text" name="out_time" required><br><br>

<label>Approx In time*<br></label>
<input type="text" name="in_time" required><br><br>

<div id="date1" style="display:none;">
    <label>Date*</label>
    <input type="date" name="start_date">
</div><br>

<div id="date2" style="display:none;">
    <label>Return Date*</label>
    <input type="date" name="return_date">
</div><br>

<div id="attachmentField" style="display:none;">
    <label>Attachment(MAX:2MB)*</label><br>
    <button class="attachment">
        <input type="file" name="attachment">
    </button>
</div><br>

<input type="submit" name="submit">

</form>

<script>
function showFields() {
    let type = document.getElementById("typeSelect").value;
    document.getElementById("date1").style.display = "none";
    document.getElementById("date2").style.display = "none";
    document.getElementById("attachmentField").style.display = "none";

    if (type === "Gate Pass") {
        document.getElementById("date1").style.display = "block";
        document.getElementById("attachmentField").style.display = "block";
    }

    if (type === "Leave") {
        document.getElementById("date1").style.display = "block";
        document.getElementById("date2").style.display = "block";
        document.getElementById("attachmentField").style.display = "block";
    }
}
</script>

</body>
</html>
