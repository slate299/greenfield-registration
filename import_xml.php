<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Only admin can access this page
requireAdmin();

$message = '';
$message_type = '';
$imported_count = 0;
$errors = [];

if (isset($_POST['import'])) {
    $xml_file = 'xml/courses.xml';
    
    if (file_exists($xml_file)) {
        $xml = simplexml_load_file($xml_file);
        
        if ($xml !== false) {
            foreach ($xml->course as $course) {
                $course_code = (string)$course->course_code;
                $course_name = (string)$course->course_name;
                $description = (string)$course->description;
                $instructor = (string)$course->instructor;
                $capacity = (int)$course->capacity;
                $semester = (string)$course->semester;
                
                // Check if course already exists
                $check_stmt = $conn->prepare("SELECT id FROM courses WHERE course_code = ?");
                $check_stmt->bind_param("s", $course_code);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                if ($check_stmt->num_rows == 0) {
                    // Insert new course
                    $insert_stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, description, instructor, capacity, semester) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert_stmt->bind_param("ssssis", $course_code, $course_name, $description, $instructor, $capacity, $semester);
                    
                    if ($insert_stmt->execute()) {
                        $imported_count++;
                    } else {
                        $errors[] = "Failed to import: $course_code";
                    }
                    $insert_stmt->close();
                } else {
                    $errors[] = "Skipped (already exists): $course_code";
                }
                $check_stmt->close();
            }
            
            $message = "Successfully imported $imported_count courses!";
            $message_type = 'success';
            if (count($errors) > 0) {
                $message .= " Errors: " . implode(", ", $errors);
            }
        } else {
            $message = "Failed to parse XML file.";
            $message_type = 'error';
        }
    } else {
        $message = "XML file not found at: $xml_file";
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import XML - Admin</title>
    <link rel="stylesheet" href="css/green.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <div>
                <h1>XML Data Integration</h1>
                <p>Import courses from XML file</p>
            </div>
            <div class="nav-links">
                <a href="admin_dashboard.php">🏠 Dashboard</a>
                <a href="export_xml.php">📤 Export to XML</a>
                <a href="import_xml.php">📥 Import from XML</a>
                <a href="logout.php">🚪 Logout</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <div style="background: white; padding: 20px; border-radius: 10px; margin-top: 20px;">
            <h2>📥 Import Courses from XML</h2>
            <p>This will import courses from <strong>xml/courses.xml</strong> into the database.</p>
            <p>Courses that already exist (by course code) will be skipped.</p>
            
            <form method="POST" action="">
                <button type="submit" name="import" class="btn btn-primary" style="background: #48bb78;">Import XML Data</button>
            </form>
            
            <div style="margin-top: 20px; padding: 15px; background: #f7fafc; border-radius: 5px;">
                <h3>XML File Content Preview:</h3>
                <pre style="overflow-x: auto; font-size: 12px;"><?php 
                if (file_exists('xml/courses.xml')) {
                    echo htmlspecialchars(file_get_contents('xml/courses.xml'));
                } else {
                    echo "XML file not found.";
                }
                ?></pre>
            </div>
        </div>
    </div>
</body>
</html>