<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireAdmin();

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($course_id <= 0) {
    header("Location: admin_dashboard.php?message=error");
    exit();
}

// Check if any students are enrolled
$check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM registrations WHERE course_id = ? AND status = 'enrolled'");
$check_stmt->bind_param("i", $course_id);
$check_stmt->execute();
$result = $check_stmt->get_result();
$data = $result->fetch_assoc();

if ($data['count'] > 0) {
    header("Location: admin_dashboard.php?message=has_registrations");
    exit();
}
$check_stmt->close();

// Delete the course
$delete_stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
$delete_stmt->bind_param("i", $course_id);

if ($delete_stmt->execute()) {
    header("Location: admin_dashboard.php?message=deleted");
} else {
    header("Location: admin_dashboard.php?message=error");
}
$delete_stmt->close();
$conn->close();
?>