<?php
require_once 'include/db.php';
require_once 'include/functions.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $msg = sanitize($_POST['message']);
    
    if (empty($name) || empty($email) || empty($msg)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $msg);
        
        if ($stmt->execute()) {
            $message = 'Message sent successfully! We will get back to you soon.';
            // Clear form
            $name = $email = $msg = '';
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Contact Us</title>
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
    max-width: 700px;
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
    border-radius: 15px;
    border: 2px solid #e6a87a;
    padding: 12px;
}
textarea.form-control {
    resize: vertical;
    min-height: 120px;
}
.btn-primary {
    background: #7a0000;
    border: none;
    border-radius: 40px;
    padding: 12px 30px;
    font-weight: bold;
}
.btn-primary:hover {
    background: #5b390b;
}
nav {
    background: #e6a87a;
    padding: 10px 0;
}
.nav-pills {
    justify-content: center;
}
.nav-pills .nav-link {
    color: #8b0000;
    font-weight: bold;
}
.nav-pills .nav-link.active {
    background-color: rgb(182, 128, 58);
}
</style>
</head>
<body>
<nav>
<ul class="nav nav-pills justify-content-center">
    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
    <li class="nav-item"><a class="nav-link" href="pantry.php">Pantry</a></li>
    <li class="nav-item"><a class="nav-link" href="recipes.php">Recipes</a></li>
    <li class="nav-item"><a class="nav-link" href="addrecipes.php">Add Recipe</a></li>
    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
</ul>
    <?php if (isLoggedIn()): ?>
        <li class="nav-item"><a class="nav-link" href="auth/logout.php">Logout</a></li>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="auth/register.php">Register</a></li>
    <?php endif; ?>
</ul>
</nav>

<div class="main-container">
    <h2><i class="bi bi-chat-dots"></i> Contact Us</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <label><i class="bi bi-person"></i> Your Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo isset($name) ? $name : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label><i class="bi bi-envelope"></i> Email Address</label>
            <input type="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label><i class="bi bi-chat"></i> Message</label>
            <textarea name="message" class="form-control" required><?php echo isset($msg) ? $msg : ''; ?></textarea>
        </div>
        
        <div class="text-center">
            <button type="submit" class="btn-primary">Send Message</button>
        </div>
    </form>
    
    <div class="text-center mt-4">
        <p class="text-muted">We'll get back to you within 24-48 hours!</p>
    </div>
</div>

<footer style="background:black; color:white; text-align:center; padding:15px; margin-top:20px;">
    © 2026 What's in My Kitchen? | Privacy | Terms | Contact
</footer>
</body>
</html>