<?php
require_once 'includes/functions.php';

// Start session and check if user is logged in
requireLogin();

// Redirect based on role
if ($_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
} else {
    header("Location: student_dashboard.php");
}
exit();
?>