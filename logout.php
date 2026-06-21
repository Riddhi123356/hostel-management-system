<?php
/**
 * Logs out either a student or an admin and returns them to the
 * appropriate sign-in screen.
 */
session_start();

$wasAdmin = isset($_SESSION['admin']);

$_SESSION = [];
session_unset();
session_destroy();

if ($wasAdmin) {
    header("Location: admin_login.php");
} else {
    header("Location: signIn.php");
}
exit();
