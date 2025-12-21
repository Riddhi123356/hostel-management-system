<?php
session_start();
include "../db.php";

/* Admin login check */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* Fetch all change information requests */
$result = $conn->query("
    SELECT *
    FROM change_information_requests
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Change Information Requests</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="content">
    <h2>Change Information Requests</h2>
    <hr>

    <table>
        <tr>
            <th>Registration No.</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?= htmlspecialchars($row['regno']); ?></td>
            <td><?= nl2br(htmlspecialchars($row['description'])); ?></td>
            <td><?= $row['status']; ?></td>
            <td>
                <?php if ($row['status'] == 'Pending') { ?>
                <form method="post" action="update_change_info_status.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                    <input type="hidden" name="regno" value="<?= $row['regno']; ?>">
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
        ?>
        <tr>
            <td colspan="4" style="text-align:center;">No Requests Found</td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
