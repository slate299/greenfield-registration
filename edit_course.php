<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireAdmin();

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($course_id <= 0) {
    header("Location: admin_dashboard.php");
    exit();
}

// Get course data
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_code = sanitize($_POST['course_code']);
    $course_name = sanitize($_POST['course_name']);
    $description = sanitize($_POST['description']);
    $instructor = sanitize($_POST['instructor']);
    $capacity = (int)$_POST['capacity'];
    $semester = sanitize($_POST['semester']);
    
    $update_stmt = $conn->prepare("UPDATE courses SET course_code = ?, course_name = ?, description = ?, instructor = ?, capacity = ?, semester = ? WHERE id = ?");
    $update_stmt->bind_param("ssssisi", $course_code, $course_name, $description, $instructor, $capacity, $semester, $course_id);
    
    if ($update_stmt->execute()) {
        header("Location: admin_dashboard.php?message=updated");
        exit();
    } else {
        $error = 'Error updating course';
    }
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Admin</title>
    <link rel="stylesheet" href="css/admin_forms.css">
</head>
<body>

<div class="sidebar">
    <h2>🌿 Greenfield Inst.</h2>
    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="view_registrations.php">👥 Registrations</a>
    <a href="add_course.php">➕ Add Course</a>
    <a href="import_xml.php">📥 Import XML</a>
    <a href="export_xml.php">📤 Export XML</a>
    <a href="#">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="form-container">
        <h1>✏️ Edit Course</h1>
        <p>Update course information</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Course Code <span class="required">*</span></label>
                <input type="text" name="course_code" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Course Name <span class="required">*</span></label>
                <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Instructor <span class="required">*</span></label>
                <input type="text" name="instructor" value="<?php echo htmlspecialchars($course['instructor']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" value="<?php echo $course['capacity']; ?>">
            </div>
            
            <div class="form-group">
                <label>Semester</label>
                <select name="semester">
                    <option value="Semester 1, 2026" <?php echo $course['semester'] == 'Semester 1, 2026' ? 'selected' : ''; ?>>📖 Semester 1, 2026</option>
                    <option value="Semester 2, 2026" <?php echo $course['semester'] == 'Semester 2, 2026' ? 'selected' : ''; ?>>📖 Semester 2, 2026</option>
                    <option value="Semester 3, 2026" <?php echo $course['semester'] == 'Semester 3, 2026' ? 'selected' : ''; ?>>📖 Semester 3, 2026</option>
                </select>
           </div>
            
            <button type="submit" class="btn-submit">💾 Update Course</button>
            <a href="admin_dashboard.php" class="btn-cancel">← Cancel</a>
        </form>
    </div>
</div>

<script src="js/admin_dashboard.js"></script>
</body>
</html>