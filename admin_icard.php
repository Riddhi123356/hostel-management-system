<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* Fetch requests */
$requests = $conn->query("
    SELECT r.id, r.regno, r.status, i.name, i.year
    FROM icard_requests r
    LEFT JOIN icard i ON r.regno = i.regno
    ORDER BY r.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin - ICard</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="content">
<h2>I-Card Requests</h2><hr>

<table>
<tr>
<th>Reg No</th>
<th>Name</th>
<th>Year</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while ($row = $requests->fetch_assoc()) { ?>
<tr>
<td><?= $row['regno'] ?></td>

<td>
<form method="post" action="save_icard.php">
<input type="hidden" name="regno" value="<?= $row['regno'] ?>">
<input type="text" name="name" value="<?= $row['name'] ?>" required>
</td>

<td>
<input type="text" name="year" value="<?= $row['year'] ?>" required>
</td>

<td><?= $row['status'] ?></td>

<td>
<button name="save">Save</button>
<button formaction="update_icard_status.php" name="approve">Approve</button>
</td>
</form>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>
