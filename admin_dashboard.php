<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in and is admin
requireAdmin();

$user_name = $_SESSION['user_name'];

// Get statistics
$stats = [];

// Total students
$student_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
$stats['students'] = $student_result->fetch_assoc()['count'];

// Total courses
$course_result = $conn->query("SELECT COUNT(*) as count FROM courses");
$stats['courses'] = $course_result->fetch_assoc()['count'];

// Total registrations
$reg_result = $conn->query("SELECT COUNT(*) as count FROM registrations WHERE status = 'enrolled'");
$stats['registrations'] = $reg_result->fetch_assoc()['count'];

// Get all courses
$courses_result = $conn->query("SELECT c.*, 
                                (SELECT COUNT(*) FROM registrations WHERE course_id = c.id AND status = 'enrolled') as enrolled_count 
                                FROM courses c 
                                ORDER BY c.course_code");

// Get success/error messages
$message = '';
$message_type = '';

if (isset($_GET['message'])) {
    if ($_GET['message'] == 'added') {
        $message = '✅ Course added successfully!';
        $message_type = 'success';
    } elseif ($_GET['message'] == 'updated') {
        $message = '✅ Course updated successfully!';
        $message_type = 'success';
    } elseif ($_GET['message'] == 'deleted') {
        $message = '✅ Course deleted successfully!';
        $message_type = 'success';
    } elseif ($_GET['message'] == 'error') {
        $message = '❌ An error occurred. Please try again.';
        $message_type = 'error';
    } elseif ($_GET['message'] == 'has_registrations') {
        $message = '⚠️ Cannot delete: Students are enrolled in this course.';
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Greenfield Institute</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>🌿 Greenfield Inst.</h2>
    <a href="admin_dashboard.php" class="active">📊 Dashboard</a>
    <a href="view_registrations.php">👥 Registrations</a>
    <a href="add_course.php">➕ Add Course</a>
    <a href="import_xml.php">📥 Import XML</a>
    <a href="export_xml.php">📤 Export XML</a>
    <a href="#">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="top">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?> 👨‍💼</h1>
        <div class="admin-badge">Administrator</div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="stats">
        <div class="stat-card">
            <h2 class="stat-students"><?php echo $stats['students']; ?></h2>
            <p>👥 Total Students</p>
        </div>
        <div class="stat-card">
            <h2 class="stat-courses"><?php echo $stats['courses']; ?></h2>
            <p>📚 Total Courses</p>
        </div>
        <div class="stat-card">
            <h2 class="stat-registrations"><?php echo $stats['registrations']; ?></h2>
            <p>📝 Total Registrations</p>
        </div>
    </div>

    <!-- Add Course Button -->
    <a href="add_course.php" class="add-btn">➕ Add New Course</a>

    <!-- Course Management Table -->
    <div class="card">
        <h2>📚 Course Management</h2>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course Name</th>
                        <th>Instructor</th>
                        <th>Semester</th>
                        <th>Capacity</th>
                        <th>Enrolled</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $courses_result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($course['course_code']); ?></strong></td>
                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                        <td><?php echo htmlspecialchars($course['semester']); ?></td>
                        <td><?php echo $course['capacity']; ?></td>
                        <td><?php echo $course['enrolled_count']; ?></td>
                        <td>
                            <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="edit-btn">✏️ Edit</a>
                            <a href="delete_course.php?id=<?php echo $course['id']; ?>" class="delete-btn" onclick="return confirmDelete(<?php echo $course['id']; ?>)">🗑️ Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="js/admin_dashboard.js"></script>
</body>
</html>