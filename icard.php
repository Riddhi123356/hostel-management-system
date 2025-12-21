<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

$regno = $_SESSION['regno'];

/* Submit request */
if (isset($_POST['request_icard'])) {

    $check = $conn->query("SELECT * FROM icard_requests WHERE regno='$regno'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO icard_requests (regno, status) VALUES ('$regno','Pending')");
    }

    header("Location: icard.php");
    exit();
}

/* Fetch icard data */
$icard = $conn->query("SELECT * FROM icard WHERE regno='$regno'")->fetch_assoc();

/* Fetch request */
$request = $conn->query("SELECT * FROM icard_requests WHERE regno='$regno'")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>I-Card</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="content">
<h2>I-Card</h2><hr>

<table>
<tr>
 <th>Registration No</th>
 <th>Name</th>
 <th>Year</th>
 <th>Status</th>
</tr>
<tr>
 <td><?= $regno ?></td>
 <td><?= $icard['name'] ?? '-' ?></td>
 <td><?= $icard['year'] ?? '-' ?></td>
 <td><button><?= $request['status'] ?? 'Not Requested' ?></button></td>
</tr>
</table>

<br>

<form method="post">
<?php
if (!$request) {
    echo "<button type='submit' name='request_icard'>Request For I-Card</button>";
} else {
    echo "<button disabled>Request Submitted</button>";
}
?>
</form>

</div>
</body>
</html>
