<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
requireLogin();

$user_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if ($course_id <= 0) {
    header("Location: student_dashboard.php?message=error");
    exit();
}

// Check if already registered
$check_stmt = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND course_id = ? AND status = 'enrolled'");
$check_stmt->bind_param("ii", $user_id, $course_id);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    header("Location: student_dashboard.php?message=duplicate");
    exit();
}
$check_stmt->close();

// Check course capacity (FIXED VERSION with GROUP BY)
$capacity_stmt = $conn->prepare("SELECT c.capacity, COUNT(r.id) as enrolled 
                                 FROM courses c 
                                 LEFT JOIN registrations r ON c.id = r.course_id AND r.status = 'enrolled'
                                 WHERE c.id = ?
                                 GROUP BY c.id, c.capacity");
$capacity_stmt->bind_param("i", $course_id);
$capacity_stmt->execute();
$capacity_result = $capacity_stmt->get_result();
$course_data = $capacity_result->fetch_assoc();

if ($course_data && $course_data['enrolled'] >= $course_data['capacity']) {
    header("Location: student_dashboard.php?message=full");
    exit();
}
$capacity_stmt->close();

// Register for course
$insert_stmt = $conn->prepare("INSERT INTO registrations (user_id, course_id, status) VALUES (?, ?, 'enrolled')");
$insert_stmt->bind_param("ii", $user_id, $course_id);

if ($insert_stmt->execute()) {
    header("Location: student_dashboard.php?message=registered");
} else {
    header("Location: student_dashboard.php?message=error");
}

$insert_stmt->close();
$conn->close();
?>