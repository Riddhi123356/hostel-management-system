<?php
/**
 * Entry point. The original project used index.php as the student sign-in
 * page (with a frameset-based home.php after login). That's been replaced
 * by signIn.php + dashboard.php, so this file just routes visitors to the
 * right place based on their session.
 */
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
} elseif (isset($_SESSION['regno'])) {
    header("Location: dashboard.php");
} else {
    header("Location: signIn.php");
}
exit();
