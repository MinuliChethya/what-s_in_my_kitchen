<?php
require_once '../include/db.php';
require_once '../include/functions.php';

$error = '';

// If already logged in, redirect to home
if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password';
    } else {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: ../index.php');
                exit();
            } else {
                $error = 'Invalid password';
            }
        } else {
            $error = 'User not found';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login - What's in My Kitchen?</title>
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
    max-width: 450px;
    width: 95%;
    margin: 80px auto;
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
</style>
</head>
<body>
<div class="main-container">
    <h2><i class="bi bi-box-arrow-in-right"></i> Login</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <label><i class="bi bi-person"></i> Username or Email</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label><i class="bi bi-lock"></i> Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn-primary">Login</button>
        
        <div class="text-center mt-3">
            <a href="register.php">Don't have an account? Register here</a>
        </div>
        <div class="text-center mt-2">
            <a href="index.php">← Back to Home</a>
        </div>
    </form>
</div>
</body>
</html>
