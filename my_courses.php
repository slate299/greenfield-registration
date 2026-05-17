<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Get enrolled courses with registration info
$sql = "SELECT c.*, r.registration_date, r.status as reg_status,
        (SELECT COUNT(*) FROM registrations WHERE course_id = c.id AND status = 'enrolled') as enrolled_count
        FROM courses c
        JOIN registrations r ON c.id = r.course_id
        WHERE r.user_id = ? AND r.status = 'enrolled'
        ORDER BY c.course_code";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_courses = $stmt->get_result();
$total_courses = $my_courses->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Greenfield Institute</title>
    <link rel="stylesheet" href="css/my_courses.css">
</head>
<body>

<div class="sidebar">
    <h2>Greenfield Institute</h2>
    <a href="student_dashboard.php">📊 Dashboard</a>
    <a href="my_courses.php" class="active">📚 My Courses</a>
    <a href="student_dashboard.php#courses">🔍 Browse Courses</a>
    <a href="#">📅 Schedule</a>
    <a href="#">👤 Profile</a>
    <a href="#">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <?php
    // Check for messages from URL
    $message = '';
    $message_type = '';
    
    if (isset($_GET['message'])) {
        if ($_GET['message'] == 'dropped') {
            $message = '✅ Course dropped successfully!';
            $message_type = 'success';
        } elseif ($_GET['message'] == 'error') {
            $message = '❌ An error occurred. Please try again.';
            $message_type = 'error';
        } elseif ($_GET['message'] == 'registered') {
            $message = '✅ Successfully registered for the course!';
            $message_type = 'success';
        }
    }
    ?>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>" style="margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <div class="top">
        <h1>My Courses</h1>
        <div class="search">
            <input type="text" placeholder="🔍 Search course..." onkeyup="searchCourse(this.value)">
        </div>
    </div>

    <div class="buttons">
        <button onclick="filterCourse('all')" class="active-filter">All</button>
        <button onclick="filterCourse('active')">Active</button>
        <button onclick="filterCourse('pending')">Pending</button>
    </div>

    <div class="courses" id="coursesContainer">
        <?php if ($total_courses > 0): ?>
            <?php while ($course = $my_courses->fetch_assoc()): ?>
                <div class="card" data-status="active" data-name="<?php echo strtolower(htmlspecialchars($course['course_name'])); ?>">
                    <span class="status status-active">Active</span>
                    <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                    <p>👨‍🏫 <strong><?php echo htmlspecialchars($course['instructor']); ?></strong></p>
                    <p>📖 <?php echo htmlspecialchars($course['course_code']); ?></p>
                    <p>📅 <?php echo htmlspecialchars($course['semester']); ?></p>
                    
                    <div class="progress">
                        <div style="width: <?php echo rand(30, 95); ?>%;"></div>
                    </div>
                    
                    <div class="actions">
                        <button class="view-btn" onclick="viewCourse('<?php echo htmlspecialchars($course['course_name']); ?>')">📖 View</button>
                        <button class="drop-btn" onclick="openModal(<?php echo $course['id']; ?>, '<?php echo htmlspecialchars($course['course_name']); ?>')">🗑️ Drop</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>📚 You are not enrolled in any courses yet.</p>
                <a href="student_dashboard.php#courses" class="btn">Browse Available Courses</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for drop confirmation -->
<div class="modal" id="modal">
    <div class="modal-box">
        <h3>⚠️ Drop Course?</h3>
        <p id="courseNameDisplay">Are you sure you want to drop this course?</p>
        <div class="modal-buttons">
            <button onclick="closeModal()">❌ Cancel</button>
            <button onclick="dropCourse()">✓ Confirm Drop</button>
        </div>
    </div>
</div>

<script src="js/my_courses.js"></script>
</body>
</html>