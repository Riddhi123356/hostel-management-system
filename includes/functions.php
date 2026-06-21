<?php
/**
 * Shared helper functions used across the app:
 *  - auth guards (student / admin)
 *  - CSRF token generation + verification
 *  - small output/escaping helpers
 *
 * Include this AFTER session_start() and AFTER db.php.
 */

/** Escape a value for safe HTML output. */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirect a guest (not logged in student) to the sign-in page. */
function require_student_login() {
    if (!isset($_SESSION['regno'])) {
        header("Location: signIn.php");
        exit();
    }
}

/** Redirect a guest (not logged in admin) to the admin login page. */
function require_admin_login() {
    if (!isset($_SESSION['admin'])) {
        header("Location: admin_login.php");
        exit();
    }
}

/** Generate (or reuse) a CSRF token for the current session. */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Render a hidden CSRF input field. */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** Verify a posted CSRF token, halting the request if it's missing/invalid. */
function csrf_verify() {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die("Security check failed (invalid or expired form). Please go back and try again.");
    }
}

/** Format a date string safely, returning a fallback when empty/null. */
function format_date($value, $fallback = '-') {
    if (empty($value)) return $fallback;
    $ts = strtotime($value);
    return $ts ? date("d-M-Y", $ts) : $fallback;
}

/** Small helper to render a colored status badge consistently. */
function status_badge($status) {
    $status = $status ?: 'Pending';
    $class = 'badge-pending';
    if (strtolower($status) === 'approved' || strtolower($status) === 'solved') {
        $class = 'badge-approved';
    } elseif (strtolower($status) === 'rejected') {
        $class = 'badge-rejected';
    }
    return '<span class="badge ' . $class . '">' . e($status) . '</span>';
}
