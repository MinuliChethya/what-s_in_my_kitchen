<?php
require_once 'include/functions.php';
requireLogin(); // User must be logged in to add recipes

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $ingredients = sanitize($_POST['ingredients']);
    $steps = sanitize($_POST['steps']);
    $time = sanitize($_POST['time']);
    $difficulty = sanitize($_POST['difficulty']);
    $image_url = sanitize($_POST['image']);
    
    // Validation
    if (empty($name) || empty($ingredients) || empty($steps) || empty($time) || empty($difficulty)) {
        $error = 'All fields are required!';
    } else {
        // Save to database
        if (addRecipe($_SESSION['user_id'], $name, $ingredients, $steps, $time, $difficulty, $image_url)) {
            $message = 'Recipe added successfully!';
            // Clear form (optional)
            $_POST = array();
        } else {
            $error = 'Failed to add recipe. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Recipe</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
body{
margin:0;
font-family:Arial;
background-image: url("https://i.pinimg.com/736x/e3/87/fd/e387fd40f1ff8c5e986f1b246be9337d.jpg");
background-attachment:fixed;
background-size:cover;
background-position:center;
}
.main-container{
max-width:1200px;
width:100%;
margin:auto;
}
nav{
background:#e6a87a;
padding:10px 0;
text-align:center;
color:#670D0D;
}
.section {
    min-height: 500px;
    height: auto;
    padding: 40px 20px;
    text-align: center;
}
.page-title {
    background: transparent;
    padding: clamp(15px, 4vw, 30px) clamp(30px, 8vw, 100px);
    border-radius: 30px;
    margin: 0 auto 40px auto;
    max-width: 90%;
    width: fit-content;
    font-size: clamp(2rem, 6vw, 3rem);
    font-weight: bold;
    color: #7a0000;
}
.form-box {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 40px;
    max-width: 700px;
    width: 95%;
    margin: 0 auto 40px auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: 2px solid #e6a87a;
}
.form-box h2 {
    text-align: center;
    color: #7a0000;
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: bold;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 3px solid #e6a87a;
}
label {
    color: #670D0D;
    font-weight: bold;
    margin-top: 15px;
    display: block;
    text-align: left;
    font-size: 1rem;
}
input, textarea, select {
    width: 100%;
    padding: 12px 15px;
    margin-top: 8px;
    margin-bottom: 15px;
    border-radius: 15px;
    border: 2px solid #e6a87a;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}
input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #7a0000;
    box-shadow: 0 0 0 3px rgba(122, 0, 0, 0.1);
}
textarea {
    min-height: 100px;
    resize: vertical;
}
.form-btn {
    background: #7a0000;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 40px;
    cursor: pointer;
    width: 100%;
    font-size: clamp(1rem, 2.5vw, 1.2rem);
    font-weight: bold;
    margin-top: 20px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.form-btn:hover {
    background: #5b390b;
    transform: scale(1.02);
}
.helper-text {
    text-align: left;
    color: #666;
    font-size: 0.85rem;
    margin-top: -10px;
    margin-bottom: 10px;
    font-style: italic;
}
.alert {
    border-radius: 15px;
    margin-bottom: 20px;
}
.nav-pills {
    justify-content: center;
}
.nav-pills .nav-link {
color: #8b0000; 
font-weight: bold;
margin: 0 10px;
}
.nav-pills .nav-link.active {
    background-color: rgb(182, 128, 58);
    color: #670D0D; 
}
@media screen and (max-width: 768px) {
    .nav-pills {
        flex-direction: column;
        align-items: center;
    }
    .nav-pills .nav-link {
        width: 100%;
        text-align: center;
        margin: 3px 0 !important;
    }
    .form-box {
        width: 92%;
        padding: 25px;
    }
}
</style>
</head>
<body>
<div class="main-container">
<nav>
<ul class="nav nav-pills justify-content-center">
  <li class="nav-item">
    <a class="nav-link" href="index.php">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="pantry.php">Pantry</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="recipes.php">Recipes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="addrecipes.php">Add Recipe</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="profile.php">Profile</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="contact.php">Contact</a>
  </li>
  <?php if (isLoggedIn()): ?>
    <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
    </li>
  <?php else: ?>
    <li class="nav-item">
        <a class="nav-link" href="login.php">Login</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="register.php">Register</a>
    </li>
  <?php endif; ?>
</ul>
</nav>
<div class="section">
<div class="page-title">
    <i class="bi bi-pencil-square"></i> Share Your Recipe
</div>
<div class="form-box">
<h2><i class="bi bi-plus-circle-fill"></i> Add New Recipe</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" action="">
<label><i class="bi bi-file-text"></i> Recipe Name</label>
<input type="text" name="name" placeholder="e.g. Spaghetti Carbonara" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>

<label><i class="bi bi-basket"></i> Ingredients (comma separated)</label>
<textarea name="ingredients" placeholder="e.g. Pasta, Eggs, Cheese, Bacon" required><?php echo isset($_POST['ingredients']) ? htmlspecialchars($_POST['ingredients']) : ''; ?></textarea>
<div class="helper-text">Separate each ingredient with a comma</div>

<label><i class="bi bi-list-check"></i> Cooking Steps (comma separated)</label>
<textarea name="steps" placeholder="e.g. Boil pasta, Fry bacon, Mix eggs, Combine all" required><?php echo isset($_POST['steps']) ? htmlspecialchars($_POST['steps']) : ''; ?></textarea>
<div class="helper-text">Separate each step with a comma</div>

<label><i class="bi bi-clock"></i> Cooking Time</label>
<input type="text" name="time" placeholder="e.g. 20 min" value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>" required>

<label><i class="bi bi-bar-chart"></i> Difficulty</label>
<select name="difficulty" required>
    <option value="" disabled <?php echo !isset($_POST['difficulty']) ? 'selected' : ''; ?>>Select difficulty</option>
    <option value="Easy" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'Easy') ? 'selected' : ''; ?>>Easy</option>
    <option value="Medium" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
    <option value="Hard" <?php echo (isset($_POST['difficulty']) && $_POST['difficulty'] == 'Hard') ? 'selected' : ''; ?>>Hard</option>
</select>

<label><i class="bi bi-image"></i> Recipe Image URL (optional)</label>
<input type="url" name="image" placeholder="https://example.com/image.jpg" value="<?php echo isset($_POST['image']) ? htmlspecialchars($_POST['image']) : ''; ?>">
<div class="helper-text">Leave empty for default image</div>

<button type="submit" class="form-btn">
    <i class="bi bi-save"></i> Save Recipe
</button>
</form>
</div>
</div>
<footer style="background:black; color:white; text-align:center; padding:15px;">
© 2026 What's in My Kitchen? | Privacy | Terms | Contact
</footer>
</div>
</body>
</html>