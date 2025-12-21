<?php
session_start();
include "../db.php";

$regno = $_SESSION['regno'];

$result = $conn->query("SELECT * FROM gatepass_leave WHERE regno='$regno' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Leave</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="content">

<button type="button" onclick="window.location.href='gatepass_form.php'">
GATE PASS REQUEST
</button><br><br>

<table>
<tr>
    <th>Registration<br>No.</th>
    <th>Type</th>
    <th>Reason</th>
    <th>Date</th>
    <th>Return date</th>
    <th>Out Time</th>
    <th>In time</th>
    <th>Status</th>
</tr>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>{$row['regno']}</td>
        <td>{$row['type']}</td>
        <td>{$row['reason']}</td>
        <td>{$row['start_date']}</td>
        <td>{$row['return_date']}</td>
        <td>{$row['out_time']}</td>
        <td>{$row['in_time']}</td>
        <td><button class='button'>{$row['status']}</button></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No Requests Found</td></tr>";
}
?>
</table>
</div>
</body>
</html>
