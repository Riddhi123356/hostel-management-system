<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$result = $conn->query("
    SELECT * FROM refund_requests
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Refund Requests</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background-color:#F4F7FB;">

<div class="content">
<h2>Refund Request</h2>

<table>
<tr>
    <th>Reg. No</th>
    <th>Type</th>
    <th>Amount</th>
    <th>Bank</th>
    <th>Account No</th>
    <th>IFSC</th>
    <th>Director</th>
    <th>Rector</th>
    <th>Librarian</th>
    <th>Accountant</th>
    <th>Action</th>
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

    <form method="post" action="update_refund_status.php">
        <td>
            <select name="director_status">
                <option <?= $row['director_status']=="Pending"?"selected":"" ?>>Pending</option>
                <option <?= $row['director_status']=="Approved"?"selected":"" ?>>Approved</option>
                <option <?= $row['director_status']=="Rejected"?"selected":"" ?>>Rejected</option>
            </select>
        </td>

        <td>
            <select name="rector_status">
                <option <?= $row['rector_status']=="Pending"?"selected":"" ?>>Pending</option>
                <option <?= $row['rector_status']=="Approved"?"selected":"" ?>>Approved</option>
                <option <?= $row['rector_status']=="Rejected"?"selected":"" ?>>Rejected</option>
            </select>
        </td>

        <td>
            <select name="librarian_status">
                <option <?= $row['librarian_status']=="Pending"?"selected":"" ?>>Pending</option>
                <option <?= $row['librarian_status']=="Approved"?"selected":"" ?>>Approved</option>
                <option <?= $row['librarian_status']=="Rejected"?"selected":"" ?>>Rejected</option>
            </select>
        </td>

        <td>
            <select name="accountant_status">
                <option <?= $row['accountant_status']=="Pending"?"selected":"" ?>>Pending</option>
                <option <?= $row['accountant_status']=="Approved"?"selected":"" ?>>Approved</option>
                <option <?= $row['accountant_status']=="Rejected"?"selected":"" ?>>Rejected</option>
            </select>
        </td>

        <td>
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <button type="submit">Update</button>
        </td>
    </form>
</tr>
<?php
}
} else {
?>
<tr>
    <td colspan="11" style="text-align:center;">No Refund Requests</td>
</tr>
<?php } ?>

</table>
</div>

</body>
</html>
