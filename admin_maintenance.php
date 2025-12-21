<?php
session_start();
include "../db.php";

/* ADMIN LOGIN CHECK */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* FETCH ALL MAINTENANCE REQUESTS */
$result = $conn->query("
    SELECT * FROM maintenance 
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Maintenance Requests</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body style="background-color:#F4F7FB;">

<div class="content">
    <h2>Maintenance Requests</h2>

    <table>
        <tr>
            <th>Reg No</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['regno']; ?></td>
            <td><?= $row['category']; ?></td>
            <td><?= nl2br($row['description']); ?></td>

            <td>
                <?php if ($row['status'] == 'Pending') { ?>
                    <span style="color:red;font-weight:bold;">Pending</span>
                <?php } else { ?>
                    <span style="color:green;font-weight:bold;">Solved</span>
                <?php } ?>
            </td>

            <td><?= date("d-M-Y", strtotime($row['created_at'])); ?></td>
            <td><?= $row['updated_at'] ? date("d-M-Y", strtotime($row['updated_at'])) : '-'; ?></td>

            <td>
                <?php if ($row['status'] == 'Pending') { ?>
                    <a href="update_maintenance_status.php?id=<?= $row['id']; ?>" 
                       onclick="return confirm('Mark this issue as solved?')">
                        <button class="button">Solve</button>
                    </a>
                <?php } else { ?>
                    ✔
                <?php } ?>
            </td>
        </tr>
        <?php } } else { ?>
        <tr>
            <td colspan="7" style="text-align:center;">No Requests Found</td>
        </tr>
        <?php } ?>

    </table>
</div>

</body>
</html>
