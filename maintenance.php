<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

$regno = $_SESSION['regno'];

$result = $conn->query("
    SELECT * FROM maintenance 
    WHERE regno='$regno' 
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>About</title>
   <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<div class="content">

<button type="button" onclick="window.location.href='issue.php'">
Maintenance Request
</button><br><br>

<table>
<tr>
    <th>Registration<br>No.</th>
    <th>Type</th>
    <th>Description</th>
    <th>Status</th>
    <th>Created<br>At</th>
    <th>Updated<br>At</th>
</tr>

<?php
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?= $row['regno']; ?></td>
    <td><?= $row['category']; ?></td>
    <td><?= nl2br($row['description']); ?></td>
    <td><button><?= $row['status']; ?></button></td>
    <td><?= date("d-M-Y", strtotime($row['created_at'])); ?></td>
    <td><?= $row['updated_at'] ?? '-'; ?></td>
</tr>
<?php }} else { ?>
<tr>
    <td colspan="6" style="text-align:center;">No Issues Found</td>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>
