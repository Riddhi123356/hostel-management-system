<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

$regno = $_SESSION['regno'];

$sql = "SELECT * FROM fees 
        WHERE regno='$regno' 
        AND fees_status='paid'";

$result = $conn->query($sql);

if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Hostel Fees</title>
<link rel="stylesheet" href="../css/style.css">
</head>

<body>
<div class="content">

<table>
<tr>
    <th>Name</th>
    <th>Registration No.</th>
    <th>Year</th>
    <th>Fees</th>
    <th>Status</th>
</tr>

<?php if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>
<tr>
    <td><?= $row['name']; ?></td>
    <td><?= $row['regno']; ?></td>
    <td><?= $row['year']; ?></td>
    <td>₹ <?= $row['fees_amount']; ?></td>
    <td>
        <button class="button" onclick="window.print()">Print Receipt</button><br>
        Fees Paid
    </td>
</tr>
<?php } else { ?>
<tr>
    <td colspan="5" style="text-align:center;">No Paid Fees Found</td>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>
