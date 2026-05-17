<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireAdmin();

// Get all registrations with student and course info
$sql = "SELECT r.id, r.registration_date, r.status,
        u.name as student_name, u.email as student_email,
        c.course_code, c.course_name, c.instructor, c.semester
        FROM registrations r
        JOIN users u ON r.user_id = u.id
        JOIN courses c ON r.course_id = c.id
        WHERE r.status = 'enrolled'
        ORDER BY r.registration_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registrations - Admin</title>
    <link rel="stylesheet" href="css/green.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <div>
                <h1>Student Registrations</h1>
                <p>Monitor all course registrations</p>
            </div>
            <div class="nav-links">
                <a href="admin_dashboard.php">🏠 Dashboard</a>
                <a href="view_registrations.php">👥 Registrations</a>
                <a href="logout.php">🚪 Logout</a>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 10px; margin-top: 20px;">
            <h2>📋 All Registrations (<?php echo $result->num_rows; ?>)</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <table style="width: 100%; margin-top: 15px;">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Student Email</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Instructor</th>
                            <th>Semester</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['instructor']); ?></td>
                            <td><?php echo htmlspecialchars($row['semester']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['registration_date'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No registrations found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>