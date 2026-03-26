<?php
require_once '../include/db.php';
require_once '../include/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $conn = getConnection();
        
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username or email already exists';
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! You can now login.';
                // Clear form
                $username = $email = '';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register - What's in My Kitchen?</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
body {
    margin: 0;
    font-family: Arial;
    background-image: url("https://i.pinimg.com/1200x/00/9b/17/009b17ecce180e2d5113aa95f0cc6836.jpg");
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
}
.main-container {
    max-width: 500px;
    width: 95%;
    margin: 50px auto;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 30px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
h2 {
    color: #7a0000;
    text-align: center;
    margin-bottom: 30px;
}
.form-control {
    border-radius: 20px;
    border: 2px solid #e6a87a;
    padding: 12px;
}
.form-control:focus {
    border-color: #7a0000;
    box-shadow: 0 0 0 3px rgba(122,0,0,0.1);
}
.btn-primary {
    background: #7a0000;
    border: none;
    border-radius: 40px;
    padding: 12px;
    font-weight: bold;
    width: 100%;
}
.btn-primary:hover {
    background: #5b390b;
}
.alert {
    border-radius: 15px;
}
</style>
</head>
<body>
<div class="main-container">
    <h2><i class="bi bi-person-plus"></i> Create Account</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <label><i class="bi bi-person"></i> Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo isset($username) ? $username : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label><i class="bi bi-envelope"></i> Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label><i class="bi bi-lock"></i> Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label><i class="bi bi-lock"></i> Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn-primary">Register</button>
        
        <div class="text-center mt-3">
            <a href="login.php">Already have an account? Login here</a>
        </div>
        <div class="text-center mt-2">
            <a href="../index.php">← Back to Home</a>
        </div>
    </form>
</div>
</body>
</html>