<?php
session_start();
include "../db.php";

$regno = $_SESSION['regno'] ?? '';

$result = $conn->query("
    SELECT remark, created_at 
    FROM reporting_history 
    WHERE registration_no='$regno'
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reporting History</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h2>Reporting History</h2><hr>

<table>
    <tr>
        <th>Remark</th>
        <th>Created At</th>
    </tr>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['remark']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
} else {
    echo "<tr>
            <td colspan='2'>No Reporting History Found</td>
          </tr>";
}
?>

</table>

</body>
</html>
