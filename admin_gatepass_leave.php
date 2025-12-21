<?php
session_start();
include "../db.php";

/* Admin login check */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* Fetch all requests */
$result = $conn->query("
    SELECT * 
    FROM gatepass_leave
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Gate Pass & Leave</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="content">
<h2>Gate Pass & Leave Requests</h2>
<hr>

<table>
<tr>
    <th>Reg No</th>
    <th>Type</th>
    <th>Reason</th>
    <th>Start Date</th>
    <th>Return Date</th>
    <th>Out Time</th>
    <th>In Time</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?= htmlspecialchars($row['regno']); ?></td>
    <td><?= htmlspecialchars($row['type']); ?></td>
    <td><?= htmlspecialchars($row['reason']); ?></td>
    <td><?= $row['start_date']; ?></td>
    <td><?= $row['return_date']; ?></td>
    <td><?= $row['out_time']; ?></td>
    <td><?= $row['in_time']; ?></td>
    <td><?= $row['status']; ?></td>
    <td>
        <?php if ($row['status'] == 'Pending') { ?>
        <form method="post" action="update_gatepass_leave_status.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <button type="submit" name="approve">Approve</button>
            <button type="submit" name="reject">Reject</button>
        </form>
        <?php } else { ?>
            —
        <?php } ?>
    </td>
</tr>
<?php
    }
} else {
    echo "<tr><td colspan='9' style='text-align:center;'>No Requests Found</td></tr>";
}
?>

</table>
</div>
</body>
</html>
