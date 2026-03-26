<?php
require_once 'include/functions.php';

$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id <= 0) {
    header('Location: recipes.php');
    exit();
}

$recipe = getRecipe($recipe_id);

if (!$recipe) {
    header('Location: recipes.php');
    exit();
}

// Parse ingredients and steps (they are stored as comma-separated strings)
$ingredients = explode(',', $recipe['ingredients']);
$steps = explode(',', $recipe['steps']);

// Trim whitespace from each ingredient and step
$ingredients = array_map('trim', $ingredients);
$steps = array_map('trim', $steps);

// Check if recipe is saved by current user
$isSaved = false;
if (isLoggedIn()) {
    $isSaved = isRecipeSaved($_SESSION['user_id'], $recipe_id);
}

// Handle save/unsave actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    if (isset($_POST['save'])) {
        if (!$isSaved) {
            saveRecipe($_SESSION['user_id'], $recipe_id);
            $isSaved = true;
        }
        // Redirect to avoid form resubmission
        header("Location: recipe-details.php?id=$recipe_id&saved=1");
        exit();
    } elseif (isset($_POST['unsave'])) {
        if ($isSaved) {
            removeSavedRecipe($_SESSION['user_id'], $recipe_id);
            $isSaved = false;
        }
        header("Location: recipe-details.php?id=$recipe_id&unsaved=1");
        exit();
    }
}

// Show success message if redirected from save/unsave
$showMessage = false;
$messageType = '';
$messageText = '';
if (isset($_GET['saved'])) {
    $showMessage = true;
    $messageType = 'success';
    $messageText = 'Recipe saved to your profile!';
} elseif (isset($_GET['unsaved'])) {
    $showMessage = true;
    $messageType = 'info';
    $messageText = 'Recipe removed from your profile.';
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo htmlspecialchars($recipe['name']); ?> - Recipe Details</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
body{
margin:0;
font-family:Arial;
background-image: url("https://i.pinimg.com/736x/20/14/bd/2014bd35882ea558f5a181002f92e233.jpg");
background-attachment:fixed;
background-size:cover;
background-position:center;
}
.main-container {
    max-width: 1000px;
    width: 95%;
    margin: 30px auto;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 40px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.back-btn {
    display: inline-block;
    margin-bottom: 25px;
    padding: 12px 25px;
    background: #e6a87a;
    color: #670D0D;
    text-decoration: none;
    border-radius: 30px;
    font-weight: bold;
    transition: all 0.3s ease;
    border: none;
    font-size: 1rem;
}
.back-btn:hover {
    background: #7a0000;
    color: white;
    transform: translateX(-5px);
    text-decoration: none;
}
.back-btn i {
    margin-right: 8px;
}
.recipe-header {
    text-align: center;
    margin-bottom: 30px;
}
h1 {
    color: #7a0000;
    font-size: clamp(1.8rem, 5vw, 2.5rem);
    font-weight: bold;
    margin-bottom: 15px;
}
.recipe-image {
    width: 100%;
    max-width: 400px;
    height: 250px;
    object-fit: cover;
    border-radius: 30px;
    margin: 0 auto 20px auto;
    display: block;
    border: 4px solid #e6a87a;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.recipe-info {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}
.info-badge {
    background: #e6a87a;
    color: #670D0D;
    padding: 10px 25px;
    border-radius: 30px;
    font-weight: bold;
    font-size: clamp(0.9rem, 2.5vw, 1rem);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.info-badge i {
    font-size: 1.2rem;
    color: #7a0000;
}
h3 {
    color: #7a0000;
    font-size: clamp(1.3rem, 4vw, 1.8rem);
    font-weight: bold;
    margin: 30px 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 3px solid #e6a87a;
    display: inline-block;
}
.ingredients-list {
    list-style: none;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
}
.ingredients-list li {
    background: rgba(230, 168, 122, 0.2);
    padding: 12px 18px;
    border-radius: 50px;
    font-size: 1rem;
    color: #670D0D;
    border: 2px solid #e6a87a;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}
.ingredients-list li:hover {
    background: rgba(230, 168, 122, 0.4);
    transform: translateX(5px);
}
.ingredients-list li:before {
    content: "🧂";
    font-size: 1.1rem;
}
.procedure-list {
    list-style: none;
    padding: 0;
    counter-reset: step-counter;
}
.procedure-list li {
    background: rgba(255, 255, 255, 0.8);
    padding: 15px 20px 15px 50px;
    margin-bottom: 12px;
    border-radius: 15px;
    font-size: 1rem;
    color: #333;
    position: relative;
    border-left: 4px solid #e6a87a;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.procedure-list li:hover {
    background: rgba(255, 255, 255, 1);
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.procedure-list li:before {
    content: counter(step-counter);
    counter-increment: step-counter;
    background: #7a0000;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: bold;
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
}
.button-container {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 30px;
    flex-wrap: wrap;
}
.save-btn, .unsave-btn {
    padding: 12px 30px;
    border: none;
    border-radius: 30px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
    text-decoration: none;
}
.save-btn {
    background: #7a0000;
    color: white;
}
.save-btn:hover {
    background: #5b390b;
    transform: scale(1.05);
}
.unsave-btn {
    background: #28a745;
    color: white;
}
.unsave-btn:hover {
    background: #218838;
    transform: scale(1.05);
}
.login-prompt {
    background: #e6a87a;
    padding: 20px;
    border-radius: 30px;
    text-align: center;
    margin-top: 20px;
}
.login-prompt p {
    margin: 0 0 10px 0;
    color: #670D0D;
    font-weight: bold;
}
.login-prompt a {
    color: #7a0000;
    font-weight: bold;
    text-decoration: none;
    padding: 8px 20px;
    background: white;
    border-radius: 30px;
    display: inline-block;
    margin-top: 10px;
}
.login-prompt a:hover {
    background: #7a0000;
    color: white;
    text-decoration: none;
}
.message {
    padding: 12px 20px;
    border-radius: 30px;
    margin-bottom: 20px;
    text-align: center;
    animation: slideDown 0.3s ease;
}
.message.success {
    background: #28a745;
    color: white;
}
.message.info {
    background: #17a2b8;
    color: white;
}
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
footer {
    text-align: center;
    margin-top: 30px;
    color: white;
    padding: 15px;
    background: rgba(0,0,0,0.7);
    border-radius: 15px;
}
/* Responsive Design */
@media screen and (max-width: 768px) {
    .main-container {
        width: 92%;
        padding: 20px;
    }
    .ingredients-list {
        grid-template-columns: 1fr;
    }
    .recipe-image {
        height: 200px;
    }
}
@media screen and (max-width: 480px) {
    .main-container {
        padding: 15px;
    }
    .back-btn {
        width: 100%;
        text-align: center;
    }
    .recipe-info {
        flex-direction: column;
        align-items: stretch;
    }
    .info-badge {
        justify-content: center;
    }
    .save-btn, .unsave-btn {
        width: 100%;
        justify-content: center;
    }
    .procedure-list li {
        padding: 12px 12px 12px 45px;
        font-size: 0.95rem;
    }
    .procedure-list li:before {
        width: 24px;
        height: 24px;
        font-size: 0.8rem;
    }
}
</style>
</head>
<body>
<div class="main-container">

<a href="recipes.php" class="back-btn">
    <i class="bi bi-arrow-left"></i> Back to Recipes
</a>

<?php if ($showMessage): ?>
    <div class="message <?php echo $messageType; ?>">
        <i class="bi <?php echo $messageType == 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill'; ?>"></i>
        <?php echo $messageText; ?>
    </div>
<?php endif; ?>

<div class="recipe-header">
    <h1><i class="bi bi-book"></i> <?php echo htmlspecialchars($recipe['name']); ?></h1>
</div>

<img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" 
     class="recipe-image" 
     alt="<?php echo htmlspecialchars($recipe['name']); ?>" 
     onerror="this.src='https://via.placeholder.com/400x250?text=Recipe+Image'">

<div class="recipe-info">
    <span class="info-badge">
        <i class="bi bi-clock"></i> <?php echo htmlspecialchars($recipe['time']); ?>
    </span>
    <span class="info-badge">
        <i class="bi bi-bar-chart"></i> <?php echo htmlspecialchars($recipe['difficulty']); ?>
    </span>
    <?php if ($recipe['user_id'] == 1): ?>
        <span class="info-badge">
            <i class="bi bi-star-fill"></i> Featured Recipe
        </span>
    <?php endif; ?>
</div>

<h3><i class="bi bi-basket"></i> Ingredients</h3>
<ul class="ingredients-list">
    <?php foreach ($ingredients as $ingredient): ?>
        <?php if (!empty($ingredient)): ?>
            <li><?php echo htmlspecialchars($ingredient); ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<h3><i class="bi bi-list-check"></i> Procedure</h3>
<ol class="procedure-list">
    <?php foreach ($steps as $step): ?>
        <?php if (!empty($step)): ?>
            <li><?php echo htmlspecialchars($step); ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ol>

<div class="button-container">
    <?php if (isLoggedIn()): ?>
        <?php if ($isSaved): ?>
            <form method="POST" action="" style="display: inline;">
                <button type="submit" name="unsave" class="unsave-btn">
                    <i class="bi bi-bookmark-check-fill"></i> Saved to Profile
                </button>
            </form>
        <?php else: ?>
            <form method="POST" action="" style="display: inline;">
                <button type="submit" name="save" class="save-btn">
                    <i class="bi bi-bookmark-plus"></i> Save to My Profile
                </button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <div class="login-prompt">
            <p><i class="bi bi-info-circle"></i> Login to save this recipe to your profile!</p>
            <a href="auth/login.php" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Login / Register
            </a>
        </div>
    <?php endif; ?>
</div>

</div>

<footer>
    <i class="bi bi-c-circle"></i> 2026 What's in My Kitchen? | 
    <i class="bi bi-heart-fill"></i> Cook with Love
</footer>

<script>
// Add any additional JavaScript if needed
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide message after 3 seconds
    const message = document.querySelector('.message');
    if (message) {
        setTimeout(function() {
            message.style.opacity = '0';
            setTimeout(function() {
                if (message.parentNode) {
                    message.style.display = 'none';
                }
            }, 300);
        }, 3000);
    }
});
</script>

</body>
</html>