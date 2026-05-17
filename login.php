<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

startSession();

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $role_from_form = isset($_POST['role']) ? $_POST['role'] : 'student';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password_hash'])) {
                if ($user['role'] !== $role_from_form) {
                    $error = 'This account is not registered as a ' . $role_from_form . '.';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    if ($user['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: student_dashboard.php");
                    }
                    exit();
                }
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
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
    <title>Greenfield Institute - Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="login-box">
    <div class="logo-circle"><span>G</span></div>
    <h1>Greenfield Institute</h1>
    <p style="color:#777; font-size:13px; margin:4px 0 15px;">Student Registration System</p>

    <div class="toggle-row">
        <button type="button" class="toggle-btn active" id="btnStudent" onclick="switchRole('student')">Student</button>
        <button type="button" class="toggle-btn" id="btnAdmin" onclick="switchRole('admin')">Admin</button>
    </div>

    <h2 id="formTitle">Student Login</h2>

    <div class="admin-note" id="adminNote" style="display:none;">
        Admin access only. Authorised personnel only.
    </div>

    <?php if ($error): ?>
        <div class="error-msg" id="error-msg"><?php echo $error; ?></div>
    <?php else: ?>
        <div class="error-msg" id="error-msg" style="display:none;">Wrong email or password.</div>
    <?php endif; ?>

    <form method="POST" action="" id="loginForm">
        <input type="hidden" name="role" id="roleInput" value="student">
        
        <label for="email" id="emailLabel">Student Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required />

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />

        <button type="submit" class="btn-login" id="loginBtn">Login as Student</button>
    </form>

    <p id="registerLink">Don't have an account? <a href="register.php">Register here</a></p>
</div>

<script src="js/login.js"></script>
</body>
</html>