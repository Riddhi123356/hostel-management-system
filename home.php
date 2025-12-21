<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
}
?>
<!DOCTYPE html>
<html>
<frameset cols="20%,80%">
    <frame src="nav.php">
    <frame src="dashboard.php" name="contentFrame">
</frameset>
</html>
