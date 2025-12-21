<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

$regno = $_SESSION['regno'];

$result = $conn->query("
    SELECT * FROM refund_requests
    WHERE registration_no='$regno'
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Refund Status</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="content">

<h3>Refund Requests</h3>

<table>
<tr>
    <th>Registration No.</th>
    <th>Request Type</th>
    <th>Amount</th>
    <th>Bank</th>
    <th>Account No</th>
    <th>IFSC</th>
    <th>Director</th>
    <th>Rector</th>
    <th>Librarian</th>
    <th>Accountant</th>
    <th>Created At</th>
</tr>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?= $row['registration_no']; ?></td>
    <td><?= $row['request_type']; ?></td>
    <td><?= $row['amount']; ?></td>
    <td><?= $row['bank_name']; ?></td>
    <td><?= $row['account_no']; ?></td>
    <td><?= $row['ifsc']; ?></td>
    <td><?= $row['director_status']; ?></td>
    <td><?= $row['rector_status']; ?></td>
    <td><?= $row['librarian_status']; ?></td>
    <td><?= $row['accountant_status']; ?></td>
    <td><?= date("d-M-Y", strtotime($row['created_at'])); ?></td>
</tr>
<?php
    }
} else {
?>
<tr>
    <td colspan="11" style="text-align:center;">No Refund Requests Found</td>
</tr>
<?php } ?>

</table>
</div>

</body>
</html>
