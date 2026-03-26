<?php
require_once 'include/functions.php';
requireLogin(); // User must be logged in to view profile

$user = getUserData($_SESSION['user_id']);
$savedRecipes = getSavedRecipes($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
<title>Profile - What's in My Kitchen?</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
body{
margin:0;
font-family:Arial;
background-image: url("https://i.pinimg.com/1200x/2d/e6/a3/2de6a374fa68b10533d0785e2dcbcfb4.jpg");
background-attachment:fixed;
background-size:cover;
background-position:center;
}
.main-container{
max-width:1200px;
width:100%;
margin:auto;
}
nav {
    background: #e6a87a;
    padding: 10px 0;
    text-align: center;
    color: #670D0D;
}
.main-content {
    padding: 40px 20px;
    min-height: 500px;
}
.title {
    background: transparent;
    padding: clamp(15px, 4vw, 30px) clamp(30px, 8vw, 100px);
    border-radius: 30px;
    margin: 0 auto 40px auto;
    max-width: 90%;
    width: fit-content;
    font-size: clamp(2rem, 6vw, 3rem);
    font-weight: bold;
    color: #7a0000;
    text-align: center;
}
.profile-row {
    display: flex;
    gap: 30px;
    align-items: flex-start;
    max-width: 1100px;
    margin: 0 auto;
}
.profile-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 30px;
    width: 320px;
    flex-shrink: 0;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: 2px solid #e6a87a;
}
.profile-icon {
    width: 120px;
    height: 120px;
    background: #7a0000;
    border-radius: 60px;
    margin: 0 auto 25px;
    text-align: center;
    line-height: 120px;
    color: white;
    font-size: 50px;
    border: 4px solid #e6a87a;
}
.info-item {
    margin: 20px 0;
}
.info-label {
    color: #7a0000;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.info-value {
    color: #333;
    font-size: 16px;
    margin-top: 5px;
}
.edit-btn {
    background: #7a0000;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 30px;
    width: 100%;
    cursor: pointer;
    font-weight: bold;
    margin: 10px 0;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 1rem;
}
.edit-btn:hover {
    background: #5b390b;
    transform: scale(1.02);
}
.recipes-section {
    flex: 1;
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: 2px solid #e6a87a;
}
.recipes-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 3px solid #e6a87a;
}
.recipes-header h2 {
    color: #7a0000;
    margin: 0;
    font-size: clamp(1.3rem, 3vw, 1.8rem);
    display: flex;
    align-items: center;
    gap: 10px;
}
.recipe-count {
    background: #e6a87a;
    color: #670D0D;
    padding: 5px 15px;
    border-radius: 30px;
    font-weight: bold;
    font-size: 0.9rem;
}
.recipe-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
    min-height: 200px;
}
.recipe-card {
    background: rgba(230, 168, 122, 0.2);
    padding: 20px 15px;
    border-radius: 20px;
    text-align: center;
    position: relative;
    color: #670D0D;
    font-weight: bold;
    border: 2px solid #e6a87a;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.recipe-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.recipe-card i {
    font-size: 2rem;
    color: #7a0000;
}
.recipe-name {
    font-size: 1rem;
    margin-bottom: 10px;
    line-height: 1.4;
}
.remove-recipe {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.remove-recipe:hover {
    background: #c82333;
}
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: #666;
    background: rgba(230, 168, 122, 0.1);
    border-radius: 20px;
    border: 2px dashed #e6a87a;
}
.empty-state i {
    font-size: 3rem;
    color: #e6a87a;
    margin-bottom: 15px;
}
.empty-state .browse-btn {
    background: #7a0000;
    color: white;
    padding: 10px 25px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    transition: all 0.3s ease;
}
.empty-state .browse-btn:hover {
    background: #5b390b;
    transform: scale(1.05);
}
footer{
background:black;
color:white;
text-align:center;
padding:15px;
margin-top: 30px;
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
    .profile-row {
        flex-direction: column;
    }
    .profile-card {
        width: 100%;
    }
    .nav-pills {
        flex-direction: column;
        align-items: center;
    }
    .nav-pills .nav-link {
        width: 100%;
        text-align: center;
        margin: 3px 0 !important;
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
    <a class="nav-link" href="addrecipes.php">Add Recipe</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="profile.php">Profile</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="contact.php">Contact</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="auth/logout.php">Logout</a>
  </li>
</ul>
</nav>
<div class="main-content">
    <div class="title">
        <i class="bi bi-person-circle"></i> My Profile
    </div>
    <div class="profile-row">
        <div class="profile-card">
            <div class="profile-icon">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="info-item">
                <div class="info-label"><i class="bi bi-person"></i> Username:</div>
                <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label"><i class="bi bi-envelope"></i> Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label"><i class="bi bi-calendar"></i> Member Since:</div>
                <div class="info-value"><?php echo date('F Y', strtotime($user['created_at'])); ?></div>
            </div>
            <a href="auth/logout.php" class="edit-btn" style="background: #dc3545; text-decoration: none;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
        
        <div class="recipes-section">
            <div class="recipes-header">
                <h2>
                    <i class="bi bi-bookmark-heart"></i> 
                    Saved Recipes
                </h2>
                <span class="recipe-count"><?php echo count($savedRecipes); ?> recipe(s)</span>
            </div>
            
            <div class="recipe-grid" id="savedRecipesGrid">
                <?php if (empty($savedRecipes)): ?>
                    <div class="empty-state">
                        <i class="bi bi-bookmark-plus"></i>
                        <p>No saved recipes yet</p>
                        <p style="font-size: 0.9rem;">Browse recipes and save them to your collection!</p>
                        <a href="recipes.php" class="browse-btn">
                            <i class="bi bi-search"></i> Browse Recipes
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($savedRecipes as $index => $recipe): ?>
                        <div class="recipe-card" onclick="viewRecipe(<?php echo $recipe['id']; ?>)">
                            <i class="bi bi-bookmark-check-fill"></i>
                            <span class="recipe-name"><?php echo htmlspecialchars($recipe['name']); ?></span>
                            <button class="remove-recipe" onclick="removeRecipe(<?php echo $recipe['id']; ?>, event)">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<footer>
  © 2026 What's in My Kitchen? | Privacy | Terms | Contact
</footer>
</div>

<script>
function viewRecipe(recipeId) {
    window.location.href = "recipe-details.php?id=" + recipeId;
}

function removeRecipe(recipeId, event) {
    event.stopPropagation();
    
    if (confirm("Remove this recipe from your saved collection?")) {
        // Send AJAX request to remove recipe
        fetch('remove_saved_recipe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'recipe_id=' + recipeId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to remove recipe');
            }
        });
    }
}
</script>
</body>
</html>