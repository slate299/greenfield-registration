<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireLogin();

$message = '';
$message_type = '';

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current hash
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (password_verify($current_password, $user['password_hash'])) {
        if ($new_password === $confirm_password && strlen($new_password) >= 6) {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $update->bind_param("si", $new_hash, $_SESSION['user_id']);
            
            if ($update->execute()) {
                // Keep session active - user stays logged in
                $_SESSION['user_name'] = $_SESSION['user_name'];
                $message = "Password updated successfully!";
                $message_type = "success";
            }
        } else {
            $message = "Passwords don't match or too short (min 6 characters)";
            $message_type = "error";
        }
    } else {
        $message = "Current password is incorrect";
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Greenfield Institute</title>
    <link rel="stylesheet" href="css/my_courses.css">
    <style>
        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            color: #123524;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .form-group input:focus {
            border-color: #2ecc71;
            outline: none;
        }
        .btn-save {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn-save:hover {
            background: #27ae60;
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
    <a href="profile.php">👤 Profile</a>
    <a href="settings.php" class="active">⚙️ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="top">
        <h1>Settings</h1>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>" style="margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="settings-card">
        <h2 style="color: #123524; margin-bottom: 20px;">Change Password</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit" name="change_password" class="btn-save">Update Password</button>
        </form>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="student_dashboard.php" style="color: #2ecc71;">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>