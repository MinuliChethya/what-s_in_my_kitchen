<?php
require_once 'include/functions.php';

// Get selected ingredients from session
if (isset($_SESSION['pantry_ingredients']) && !empty($_SESSION['pantry_ingredients'])) {
    $ingredients = $_SESSION['pantry_ingredients'];
} else {
    $ingredients = [];
}

// Get all recipes from database
$conn = getConnection();
$result = $conn->query("SELECT * FROM recipes ORDER BY id DESC");

// Check if query was successful
if (!$result) {
    die("Database query failed: " . $conn->error);
}

$allRecipes = [];
while ($row = $result->fetch_assoc()) {
    $allRecipes[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Matching Recipes</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
body{
margin:0;
font-family:Arial;
background-image: url("https://i.pinimg.com/736x/e1/8f/43/e18f43411827ea579deeafa6721554f2.jpg");
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
.hero {
    min-height: 500px;
    height: auto;
    padding: 40px 20px;
    text-align: center;
}
.title {
    background: transparent;
    padding: clamp(20px, 5vw, 40px) clamp(20px, 5vw, 80px);
    border-radius: 30px;
    margin: 0 auto 40px auto;
    max-width: 90%;
    width: fit-content;
    font-size: clamp(2rem, 6vw, 3rem);
    font-weight: bold;
    color: #7a0000;
}
.selected-ingredients {
    background: rgba(230, 168, 122, 0.9);
    padding: 15px 25px;
    border-radius: 50px;
    margin: 20px auto 30px auto;
    max-width: 800px;
    font-size: clamp(0.9rem, 2.2vw, 1rem);
    color: #670D0D;
    font-weight: bold;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}
.ingredient-tag {
    background: #7a0000;
    color: white;
    padding: 5px 15px;
    border-radius: 25px;
    font-size: 0.9rem;
    display: inline-block;
}
.recipes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin: 40px auto;
    max-width: 1100px;
    padding: 0 15px;
}
.card {
    background: rgba(255, 255, 255, 0.95);
    border: none;
    border-radius: 20px !important;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}
.card img {
    height: 200px;
    object-fit: cover;
    border-bottom: 3px solid #e6a87a;
}
.card-body {
    padding: 20px;
    text-align: left;
}
.card-title {
    color: #7a0000;
    font-weight: bold;
    font-size: clamp(1.2rem, 3vw, 1.4rem);
    margin-bottom: 12px;
}
.card-text {
    color: #670D0D;
    font-size: 0.95rem;
    margin-bottom: 15px;
}
.card-text i {
    color: #e6a87a;
    margin-right: 5px;
}
.progress {
    height: 25px;
    border-radius: 12px;
    margin: 15px 0 10px 0;
    background-color: #f0d5bf;
}
.progress-bar {
    background: linear-gradient(45deg, #7a0000, #e6a87a) !important;
    border-radius: 12px;
    font-weight: bold;
    font-size: 0.85rem;
    line-height: 25px;
}
.match-badge {
    display: inline-block;
    background: #e6a87a;
    color: #670D0D;
    padding: 5px 15px;
    border-radius: 25px;
    font-weight: bold;
    font-size: 0.9rem;
    margin-top: 5px;
}
.no-recipes {
    background: rgba(255,255,255,0.9);
    padding: 40px;
    border-radius: 30px;
    font-size: 1.2rem;
    color: #7a0000;
    text-align: center;
    margin: 40px auto;
    max-width: 600px;
    font-weight: bold;
}
.back-to-pantry {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 30px;
    background: #e6a87a;
    color: #670D0D;
    text-decoration: none;
    border-radius: 30px;
    font-weight: bold;
    transition: all 0.3s ease;
}
.back-to-pantry:hover {
    background: #7a0000;
    color: white;
}
footer{
background:black;
color:white;
text-align:center;
padding:15px;
margin-top: 40px;
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
    font-weight: bold;
}
.nav-link:hover {
    color: #ffb54d;
    text-decoration: underline;
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
    .recipes {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
}
@media screen and (max-width: 480px) {
    .title {
        font-size: clamp(1.5rem, 5vw, 2rem);
        padding: 15px;
    }
    .card img {
        height: 160px;
    }
    footer span {
        display: block;
        margin: 5px 0;
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
    <a class="nav-link active" aria-current="page" href="recipes.php">Recipes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="addrecipes.php">Add Recipe</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="profile.php">Profile</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="contact.php">Contact</a>
  </li>
  <?php if (isLoggedIn()): ?>
    <li class="nav-item">
        <a class="nav-link" href="auth/logout.php">Logout</a>
    </li>
  <?php else: ?>
    <li class="nav-item">
        <a class="nav-link" href="auth/login.php">Login</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="auth/register.php">Register</a>
    </li>
  <?php endif; ?>
</ul>
</nav>
<div class="hero">
<div class="title">📖 Matching Recipes</div>

<!-- Selected ingredients display -->
<div class="selected-ingredients" id="selectedIngredients">
    <?php if (empty($ingredients)): ?>
        No ingredients selected. 
        <a href="pantry.php" style="color: #7a0000; font-weight: bold;">Go to Pantry</a> to select ingredients.
    <?php else: ?>
        Selected: 
        <?php foreach ($ingredients as $item): ?>
            <span class="ingredient-tag"><?php echo htmlspecialchars($item); ?></span>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Recipes container -->
<div class="recipes" id="recipes">
    <?php
    $hasMatches = false;
    
    if (!empty($ingredients)) {
        foreach ($allRecipes as $recipe) {
            // Split ingredients string into array
            $recipeIngredients = array_map('trim', explode(',', $recipe['ingredients']));
            
            // Count matching ingredients
            $matchCount = 0;
            foreach ($recipeIngredients as $recipeIng) {
                if (in_array($recipeIng, $ingredients)) {
                    $matchCount++;
                }
            }
            
            $matchPercent = 0;
            if (count($recipeIngredients) > 0) {
                $matchPercent = floor(($matchCount / count($recipeIngredients)) * 100);
            }
            
            if ($matchCount > 0) {
                $hasMatches = true;
                ?>
                <div class="card" onclick='openRecipe(<?php echo json_encode($recipe); ?>)'>
                    <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($recipe['name']); ?>"
                         onerror="this.src='https://via.placeholder.com/300x200?text=Recipe'">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($recipe['name']); ?></h5>
                        <p class="card-text">
                            <i class="bi bi-clock"></i> <?php echo htmlspecialchars($recipe['time']); ?>  
                            &nbsp; | &nbsp;
                            <i class="bi bi-bar-chart"></i> <?php echo htmlspecialchars($recipe['difficulty']); ?>
                        </p>
                        <div class="progress">
                            <div class="progress-bar" style="width:<?php echo $matchPercent; ?>%">
                                <?php echo $matchPercent; ?>% Match
                            </div>
                        </div>
                        <span class="match-badge">
                            <i class="bi bi-check-circle-fill"></i> <?php echo $matchCount; ?>/<?php echo count($recipeIngredients); ?> ingredients
                        </span>
                    </div>
                </div>
                <?php
            }
        }
        
        if (!$hasMatches) {
            echo '<div class="no-recipes">
                    <i class="bi bi-emoji-frown" style="font-size: 3rem;"></i>
                    <p>No matching recipes found.</p>
                    <p>Try adding more ingredients to your pantry!</p>
                    <a href="pantry.php" class="back-to-pantry">
                        <i class="bi bi-arrow-left"></i> Back to Pantry
                    </a>
                  </div>';
        }
    } else {
        echo '<div class="no-recipes">
                <i class="bi bi-basket" style="font-size: 3rem;"></i>
                <p>No ingredients selected!</p>
                <p>Please go to the pantry and select ingredients you have.</p>
                <a href="pantry.php" class="back-to-pantry">
                    <i class="bi bi-cart4"></i> Go to Pantry
                </a>
              </div>';
    }
    ?>
</div>

</div>
<footer>
© 2026 What's in My Kitchen? | Privacy | Terms | Contact
</footer>
</div>

<script>
function openRecipe(recipe) {
    // Store recipe in localStorage for the details page
    localStorage.setItem("selectedRecipe", JSON.stringify(recipe));
    window.location.href = "recipe-details.php?id=" + recipe.id;
}
</script>
</body>
</html>