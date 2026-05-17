<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireAdmin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_code = sanitize($_POST['course_code']);
    $course_name = sanitize($_POST['course_name']);
    $description = sanitize($_POST['description']);
    $instructor = sanitize($_POST['instructor']);
    $capacity = (int)$_POST['capacity'];
    $semester = sanitize($_POST['semester']);
    
    if (empty($course_code) || empty($course_name) || empty($instructor)) {
        $error = 'Please fill in all required fields';
    } else {
        $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, description, instructor, capacity, semester) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $course_code, $course_name, $description, $instructor, $capacity, $semester);
        
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=added");
            exit();
        } else {
            $error = 'Error adding course: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course - Admin</title>
    <link rel="stylesheet" href="css/admin_forms.css">
</head>
<body>

<div class="sidebar">
    <h2>🌿 Greenfield Inst.</h2>
    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="view_registrations.php">👥 Registrations</a>
    <a href="add_course.php" class="active">➕ Add Course</a>
    <a href="import_xml.php">📥 Import XML</a>
    <a href="export_xml.php">📤 Export XML</a>
    <a href="#">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="form-container">
        <h1>➕ Add New Course</h1>
        <p>Create a new course in the system</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Course Code <span class="required">*</span></label>
                <input type="text" name="course_code" placeholder="e.g., CS401" required>
            </div>
            
            <div class="form-group">
                <label>Course Name <span class="required">*</span></label>
                <input type="text" name="course_name" placeholder="e.g., Advanced Web Development" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Course description..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Instructor <span class="required">*</span></label>
                <input type="text" name="instructor" placeholder="e.g., Prof. John Doe" required>
            </div>
            
            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" value="30">
            </div>
            
            <div class="form-group">
                <label>Semester</label>
                <select name="semester">
                    <option value="Semester 1, 2026">📖 Semester 1, 2026</option>
                    <option value="Semester 2, 2026">📖 Semester 2, 2026</option>
                    <option value="Semester 3, 2026">📖 Semester 3, 2026</option>
                </select>
            </div>
            
            <button type="submit" class="btn-submit">➕ Add Course</button>
            <a href="admin_dashboard.php" class="btn-cancel">← Cancel</a>
        </form>
    </div>
</div>

<script src="js/admin_dashboard.js"></script>
</body>
</html>