<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

$regno = $_SESSION['regno'];

/* Submit request */
if (isset($_POST['submit_change'])) {
    $description = $_POST['description'];

    if (!empty($description)) {
        $stmt = $conn->prepare("
            INSERT INTO change_information_requests (regno, description)
            VALUES (?,?)
        ");
        $stmt->bind_param("ss", $regno, $description);
        $stmt->execute();

        header("Location: changeInformation.php");
        exit();
    }
}

/* Fetch requests */
$result = $conn->query("
    SELECT description, status
    FROM change_information_requests
    WHERE regno='$regno'
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Change Information</title>
  <link rel="stylesheet" href="../css/style.css">

</head>

<body>
<h2>Change Information Request</h2><hr>

<h3>Request To Change Information</h3>

<form method="post">
<label>Description</label><br>
<textarea name="description" cols="50" rows="5"
placeholder="If You Want To Change Basic Information Like name, mobile no..etc, Then Explain here..."
required></textarea>
<br><br>

<input type="submit" name="submit_change" value="Submit">
</form>

<div class="content">
<table>
<tr>
   <th>Description</th>
   <th>Status</th>
</tr>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['description']}</td>
                <td><button class='button'>{$row['status']}</button></td>
              </tr>";
    }
} else {
    echo "<tr>
            <td colspan='2'>No Requests Found</td>
          </tr>";
}
?>
</table>
</div>

<script src="js/common.js"></script>
</body>
</html>
