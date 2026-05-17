<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Combine first name and last name into full name
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $name = $first_name . ' ' . $last_name;
    
    $student_id = sanitize($_POST['student_id']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($student_id) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            // Check if student_id already exists
            $check_id_stmt = $conn->prepare("SELECT id FROM users WHERE student_id = ?");
            $check_id_stmt->bind_param("s", $student_id);
            $check_id_stmt->execute();
            $check_id_stmt->store_result();
            
            if ($check_id_stmt->num_rows > 0) {
                $error = 'Student ID already exists';
            } else {
                // Hash password and insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // FIXED: INSERT includes student_id column
                $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, role, student_id) VALUES (?, ?, ?, 'student', ?)");
                $insert_stmt->bind_param("ssss", $name, $email, $hashed_password, $student_id);
                
                if ($insert_stmt->execute()) {
                    $success = 'Registration successful! You can now login.';
                    // Clear any stored error
                    $error = '';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
                $insert_stmt->close();
            }
            $check_id_stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenfield Institute - Create Account</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<div class="register-box">
    <div class="logo-circle"><span>G</span></div>
    <h1>Greenfield Institute</h1>
    <p style="color:#777; font-size:13px; margin:4px 0 15px;">Student Registration System</p>

    <h2>Create Account</h2>

    <?php if ($error): ?>
        <div class="alert-message alert-error" id="form-message" style="display:block;">
            <?php echo $error; ?>
        </div>
    <?php elseif ($success): ?>
        <div class="alert-message alert-success" id="form-message" style="display:block;">
            <?php echo $success; ?>
        </div>
    <?php else: ?>
        <div class="alert-message alert-error" id="form-message" style="display:none;"></div>
    <?php endif; ?>

    <form method="POST" action="" id="registerForm">
        <div class="section-title">Personal Information</div>

        <div class="two-col">
            <div>
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="first_name" placeholder="e.g. Jane" required />
            </div>
            <div>
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="last_name" placeholder="e.g. Doe" required />
            </div>
        </div>

        <label for="studentId">Student ID:</label>
        <input type="text" id="studentId" name="student_id" placeholder="e.g. GF-2024-001" required />

        <div class="section-title">Account Details</div>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="e.g. jane@greenfield.ac" required />

        <label for="pw">Password:</label>
        <input type="password" id="pw" name="password" placeholder="Create a password" required />

        <label for="pw2">Confirm Password:</label>
        <input type="password" id="pw2" name="confirm_password" placeholder="Re-type your password" required />

        <button type="submit" class="btn-register">Create Account</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<script src="js/register.js"></script>
</body>
</html>