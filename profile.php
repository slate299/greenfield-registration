<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_role = $_SESSION['role'];

// Get student_id if exists
$stmt = $conn->prepare("SELECT student_id, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Greenfield Institute</title>
    <link rel="stylesheet" href="css/my_courses.css">
    <style>
        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
        .profile-field {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .profile-field label {
            font-weight: bold;
            color: #123524;
            display: inline-block;
            width: 130px;
        }
        .profile-field span {
            color: #555;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Greenfield Institute</h2>
    <a href="student_dashboard.php">📊 Dashboard</a>
    <a href="my_courses.php">📚 My Courses</a>
    <a href="student_dashboard.php#courses">🔍 Browse Courses</a>
    <a href="#">📅 Schedule</a>
    <a href="profile.php" class="active">👤 Profile</a>
    <a href="settings.php">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="top">
        <h1>My Profile</h1>
    </div>

    <div class="profile-card">
        <h2 style="color: #123524; margin-bottom: 20px;">Personal Information</h2>
        
        <div class="profile-field">
            <label>Full Name:</label>
            <span><?php echo htmlspecialchars($user_name); ?></span>
        </div>
        
        <div class="profile-field">
            <label>Email Address:</label>
            <span><?php echo htmlspecialchars($user_email); ?></span>
        </div>
        
        <div class="profile-field">
            <label>Student ID:</label>
            <span><?php echo htmlspecialchars($user_data['student_id'] ?? 'Not assigned'); ?></span>
        </div>
        
        <div class="profile-field">
            <label>Role:</label>
            <span><?php echo ucfirst($user_role); ?></span>
        </div>
        
        <div class="profile-field">
            <label>Member Since:</label>
            <span><?php echo date('F j, Y', strtotime($user_data['created_at'] ?? 'now')); ?></span>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="student_dashboard.php" class="btn" style="background: #2ecc71; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>