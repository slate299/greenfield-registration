<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Check where to redirect after drop
$redirect_from = isset($_GET['from']) && $_GET['from'] == 'my_courses' ? 'my_courses.php' : 'student_dashboard.php';

if ($course_id <= 0) {
    header("Location: $redirect_from?message=error");
    exit();
}

// Delete registration
$stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ? AND course_id = ? AND status = 'enrolled'");
$stmt->bind_param("ii", $user_id, $course_id);

if ($stmt->execute()) {
    // Success - redirect with success message
    header("Location: $redirect_from?message=dropped");
} else {
    // Error - redirect with error message
    header("Location: $redirect_from?message=error");
}

$stmt->close();
$conn->close();
?>