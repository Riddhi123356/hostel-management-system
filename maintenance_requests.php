<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Maintenance Requests</title>
<style>
body {
    font-family: Arial;
    background: #F4F7FB;
}

table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background: #2c3e50;
    color: white;
}

.pending { color: orange; font-weight: bold; }
.solved { color: green; font-weight: bold; }
</style>
</head>
<body>

<h2 align="center">Maintenance Requests (Admin)</h2>

<table>
<tr>
    <th>Registration No</th>
    <th>Type</th>
    <th>Description</th>
    <th>Status</th>
    <th>Created At</th>
    <th>Updated At</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM maintenance ORDER BY created_at DESC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?= $row['regno'] ?></td>
    <td><?= $row['type'] ?></td>
    <td><?= $row['description'] ?></td>
    <td class="<?= strtolower($row['status']) ?>">
        <?= $row['status'] ?>
    </td>
    <td><?= date("d-M-Y", strtotime($row['created_at'])) ?></td>
    <td>
        <?= $row['updated_at'] ? date("d-M-Y", strtotime($row['updated_at'])) : "-" ?>
    </td>
    <td>
        <?php if ($row['status'] != 'Solved') { ?>
        <form method="post" action="update_maintenance_status.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" name="solve">Mark as Solved</button>
        </form>
        <?php } else { echo "✔"; } ?>
    </td>
</tr>
<?php
    }
} else {
    echo "<tr><td colspan='7'>No Requests Found</td></tr>";
}
?>
</table>

</body>
</html>
