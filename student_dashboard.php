<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
requireLogin();

// If admin tries to access, redirect to admin dashboard
if (isAdmin()) {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Get enrolled courses
$enrolled_sql = "SELECT c.*, r.registration_date 
                 FROM courses c
                 JOIN registrations r ON c.id = r.course_id
                 WHERE r.user_id = ? AND r.status = 'enrolled'
                 ORDER BY r.registration_date DESC";

$stmt = $conn->prepare($enrolled_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$enrolled_courses = $stmt->get_result();
$enrolled_count = $enrolled_courses->num_rows;

// Get available courses (not enrolled)
$available_sql = "SELECT c.*, 
                  (SELECT COUNT(*) FROM registrations WHERE course_id = c.id AND status = 'enrolled') as enrolled_count
                  FROM courses c
                  WHERE c.id NOT IN (
                      SELECT course_id FROM registrations 
                      WHERE user_id = ? AND status = 'enrolled'
                  )
                  ORDER BY c.course_code";

$stmt2 = $conn->prepare($available_sql);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$available_courses = $stmt2->get_result();
$available_count = $available_courses->num_rows;

// Get message
$message = '';
$message_type = '';

if (isset($_GET['message'])) {
    if ($_GET['message'] == 'registered') {
        $message = 'Successfully registered for the course!';
        $message_type = 'success';
    } elseif ($_GET['message'] == 'dropped') {
        $message = 'Successfully dropped the course.';
        $message_type = 'success';
    } elseif ($_GET['message'] == 'error') {
        $message = 'An error occurred. Please try again.';
        $message_type = 'error';
    } elseif ($_GET['message'] == 'duplicate') {
        $message = 'You are already registered for this course.';
        $message_type = 'error';
    } elseif ($_GET['message'] == 'full') {
        $message = 'This course is already full.';
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Greenfield Institute</title>
    <link rel="stylesheet" href="css/student_dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Greenfield Institute</h2>
    <a href="student_dashboard.php" class="active">📊 Dashboard</a>
    <a href="my_courses.php">📚 My Courses</a>
    <a href="student_dashboard.php#courses">🔍 Browse Courses</a>
    <a href="#">📅 Schedule</a>
    <a href="profile.php">👤 Profile</a>
    <a href="settings.php">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="top">
        <h1>Good <?php 
            $hour = date('H');
            if ($hour < 12) echo "Morning";
            elseif ($hour < 17) echo "Afternoon";
            else echo "Evening";
        ?>, <?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?> 👋</h1>
        <div class="semester">
            Semester 2, 2026
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="stats">
        <div class="box">
            <p>📚 Enrolled Courses</p>
            <h1><?php echo $enrolled_count; ?></h1>
        </div>
        <div class="box">
            <p>✅ Available Courses</p>
            <h1><?php echo $available_count; ?></h1>
        </div>
        <div class="box">
            <p>⏰ Credit Hours</p>
            <h1><?php echo $enrolled_count * 3; ?></h1>
        </div>
        <div class="box">
            <p>📝 Total Courses</p>
            <h1><?php echo $enrolled_count + $available_count; ?></h1>
        </div>
    </div>

    <div class="content">
        <!-- Enrolled Courses Section -->
        <div class="card">
            <h2>My Enrolled Courses</h2>
            <?php if ($enrolled_count > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Lecturer</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($course = $enrolled_courses->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($course['course_code']); ?></strong><br>
                                <small><?php echo htmlspecialchars($course['course_name']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                            <td><span class="status status-active">Active</span></td>
                            <td>
                                <a href="drop_course.php?course_id=<?php echo $course['id']; ?>" 
                                   class="drop-btn"
                                   onclick="return confirm('Are you sure you want to drop this course?')">Drop</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>You are not enrolled in any courses yet.</p>
                    <p>Browse available courses below to register!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Sidebar Content -->
        <div>
            <div class="card">
                <h2>Today's Schedule</h2>
                <div class="schedule-item">
                    <h4>📖 Study Time</h4>
                    <p>Review your enrolled courses</p>
                </div>
                <div class="schedule-item">
                    <h4>🎯 Registration</h4>
                    <p>Browse and register for new courses</p>
                </div>
                <div class="schedule-item">
                    <h4>📝 Check Updates</h4>
                    <p>View announcements and deadlines</p>
                </div>
            </div>

            <div class="card" style="margin-top: 20px;">
                <h2>Quick Announcements</h2>
                <div class="notice">
                    <h4>Course Registration</h4>
                    <p>Semester 2, 2026 registration is now open!</p>
                </div>
                <div class="notice">
                    <h4>Need Help?</h4>
                    <p>Contact admin at admin@greenfield.ac.ke</p>
                </div>
                <div class="notice">
                    <h4>Student Portal</h4>
                    <p>Check your registered courses regularly</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Courses Section -->
    <div class="card" style="margin-top: 25px;" id="courses">
        <h2>🔍 Browse Available Courses</h2>
        <?php if ($available_courses->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Lecturer</th>
                        <th>Capacity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $available_courses->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($course['course_code']); ?></strong></td>
                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                        <td><?php echo $course['enrolled_count']; ?>/<?php echo $course['capacity']; ?></td>
                        <td>
                            <a href="register_course.php?course_id=<?php echo $course['id']; ?>" 
                               class="register-btn">Register</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p>No available courses at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="js/student_dashboard.js"></script>
</body>
</html>