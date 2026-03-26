<?php
require_once 'include/functions.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize selected ingredients in session if not exists
if (!isset($_SESSION['pantry_ingredients'])) {
    $_SESSION['pantry_ingredients'] = [];
}

// Handle AJAX requests for adding/removing ingredients
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'add' && isset($_POST['ingredient'])) {
        $ingredient = sanitize($_POST['ingredient']);
        if (!in_array($ingredient, $_SESSION['pantry_ingredients'])) {
            $_SESSION['pantry_ingredients'][] = $ingredient;
        }
        echo json_encode(['success' => true, 'ingredients' => $_SESSION['pantry_ingredients']]);
        exit();
    }
    
    if ($_POST['action'] === 'remove' && isset($_POST['ingredient'])) {
        $ingredient = sanitize($_POST['ingredient']);
        $key = array_search($ingredient, $_SESSION['pantry_ingredients']);
        if ($key !== false) {
            unset($_SESSION['pantry_ingredients'][$key]);
            $_SESSION['pantry_ingredients'] = array_values($_SESSION['pantry_ingredients']);
        }
        echo json_encode(['success' => true, 'ingredients' => $_SESSION['pantry_ingredients']]);
        exit();
    }
    
    if ($_POST['action'] === 'clear') {
        $_SESSION['pantry_ingredients'] = [];
        echo json_encode(['success' => true]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>My Pantry - What's in My Kitchen?</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
body{
margin:0;
font-family:Arial;
background-image: url("https://i.pinimg.com/736x/ce/b1/35/ceb1358ba63a422fe05792220243ad1b.jpg");
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
    margin: 0 auto 20px auto;
    max-width: 90%;
    width: fit-content;
    font-size: clamp(2rem, 6vw, 3rem);
    font-weight: bold;
    color: #7a0000;
}
.search {
    margin: 20px auto 20px auto;
    max-width: 600px;
}
.search input {
    width: 100%;
    padding: clamp(10px, 2vw, 15px) clamp(15px, 3vw, 25px);
    border-radius: 40px;
    border: 2px solid #e6a87a;
    font-size: clamp(0.9rem, 2.5vw, 1rem);
    background: rgba(255,255,255,0.9);
}
.search input:focus {
    outline: none;
    border-color: #7a0000;
}
.selected-section {
    background: rgba(122, 0, 0, 0.85);
    padding: 20px;
    border-radius: 20px;
    margin: 20px auto;
    max-width: 1000px;
    color: white;
}
.selected-section h3 {
    margin: 0 0 15px 0;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.selected-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 15px;
}
.selected-tag {
    background: #e6a87a;
    color: #670D0D;
    padding: 8px 15px;
    border-radius: 30px;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.selected-tag:hover {
    background: #ffb54d;
    transform: scale(1.05);
}
.clear-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 30px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}
.clear-btn:hover {
    background: #c82333;
    transform: scale(1.05);
}
.categories {
    display: flex; 
    flex-wrap: wrap; 
    gap: 10px;
    justify-content: center;
    margin: 25px auto;
    max-width: 800px;
}
.categories button {
    margin: 3px;
    padding: clamp(8px, 1.5vw, 12px) clamp(15px, 3vw, 25px);
    border: none;
    background: #e6a87a;
    border-radius: 30px;
    cursor: pointer;
    font-size: clamp(0.9rem, 2.2vw, 1rem);
    font-weight: bold;
    color: #670D0D;
    transition: all 0.3s ease;
}
.categories button:hover {
    background: #7a0000;
    color: white;
}
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: clamp(15px, 2vw, 25px);
    margin: 40px auto;
    max-width: 1000px;
    padding: 0 10px;
}
.item {
    background: rgba(230, 168, 122, 0.85);
    padding: clamp(12px, 2vw, 18px);
    border-radius: 15px;
    font-size: clamp(1rem, 2.2vw, 1.1rem);
    display: flex;
    align-items: center;
    font-weight: bold;
    color: #670D0D;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
}
.item:hover {
    background: rgba(230, 168, 122, 1);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.item.selected {
    background: #7a0000;
    color: white;
    border-color: #e6a87a;
}
.item .check-icon {
    display: none;
    margin-left: auto;
    font-size: 1.2rem;
}
.item.selected .check-icon {
    display: inline-block;
}
.item input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    cursor: pointer;
    accent-color: #7a0000;
    pointer-events: none;
}
.find {
    text-align: center;
    margin: 40px 0 20px 0;
}
.find button {
    background: #7a0000;
    color: white;
    padding: clamp(12px, 2vw, 16px) clamp(30px, 6vw, 50px);
    border: none;
    border-radius: 40px;
    cursor: pointer;
    font-size: clamp(1rem, 2.5vw, 1.2rem);
    font-weight: bold;
    transition: all 0.3s ease;
    min-width: 250px;
}
.find button:hover {
    background: #5b390b;
    transform: scale(1.05);
}
.find button:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
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
    font-weight: bold;
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
    .grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
}
@media screen and (max-width: 480px) {
    .title {
        font-size: clamp(1.5rem, 5vw, 2rem);
        padding: 15px;
    }
    .grid {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
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
    <a class="nav-link active" aria-current="page" href="pantry.php">Pantry</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="recipes.php">Recipes</a>
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
<div class="title">🧺 My Pantry</div>

<!-- Selected Ingredients Section -->
<div class="selected-section" id="selectedSection" style="<?php echo empty($_SESSION['pantry_ingredients']) ? 'display: none;' : ''; ?>">
    <h3><i class="bi bi-basket"></i> Your Selected Ingredients</h3>
    <div class="selected-tags" id="selectedTags">
        <?php foreach ($_SESSION['pantry_ingredients'] as $ingredient): ?>
            <span class="selected-tag" onclick="removeIngredient('<?php echo htmlspecialchars($ingredient); ?>')">
                <?php echo htmlspecialchars($ingredient); ?> <i class="bi bi-x-circle"></i>
            </span>
        <?php endforeach; ?>
    </div>
    <button class="clear-btn" onclick="clearAllIngredients()">
        <i class="bi bi-trash"></i> Clear All
    </button>
</div>

<div class="search">
<input type="text" id="searchInput" placeholder="Search ingredients..." onkeyup="searchItems()">
</div>

<div class="categories">
<button onclick="filterItems('all')">All</button>
<button onclick="filterItems('protein')">🍗 Protein</button>
<button onclick="filterItems('veggies')">🥬 Veggies</button>
<button onclick="filterItems('spices')">🌿 Spices</button>
<button onclick="filterItems('dairy')">🥛 Dairy</button>
<button onclick="filterItems('grains')">🌾 Grains</button>
</div>

<div class="grid" id="ingredientsGrid">
<label class="item" data-cat="protein" data-name="Chicken">
<input type="checkbox" value="Chicken"> 🍗 Chicken
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="veggies" data-name="Potato">
<input type="checkbox" value="Potato"> 🥔 Potato
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="veggies" data-name="Onion">
<input type="checkbox" value="Onion"> 🧅 Onion
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="veggies" data-name="Tomato">
<input type="checkbox" value="Tomato"> 🍅 Tomato
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="grains" data-name="Oats">
<input type="checkbox" value="Oats"> 🌾 Oats
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="veggies" data-name="Carrot">
<input type="checkbox" value="Carrot"> 🥕 Carrot
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="veggies" data-name="Banana">
<input type="checkbox" value="Banana"> 🍌 Banana
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="dairy" data-name="Milk">
<input type="checkbox" value="Milk"> 🥛 Milk
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="veggies" data-name="Garlic">
<input type="checkbox" value="Garlic"> 🧄 Garlic
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="spices" data-name="Basil">
<input type="checkbox" value="Basil"> 🌿 Basil
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="dairy" data-name="Cheddar">
<input type="checkbox" value="Cheddar"> 🧀 Cheddar
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="spices" data-name="Curry powder">
<input type="checkbox" value="Curry powder"> 🍛 Curry powder
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="veggies" data-name="Lemon">
<input type="checkbox" value="Lemon"> 🍋 Lemon
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="protein" data-name="Fish">
<input type="checkbox" value="Fish"> 🐟 Fish
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="grains" data-name="Rice">
<input type="checkbox" value="Rice"> 🍚 Rice
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
<label class="item" data-cat="spices" data-name="Chillies">
<input type="checkbox" value="Chillies"> 🌶️ Chillies
<span class="check-icon"><i class="bi bi-check-circle-fill"></i></span>
</label>
</div>

<div class="find">
<button id="findBtn" onclick="findRecipes()" <?php echo empty($_SESSION['pantry_ingredients']) ? 'disabled' : ''; ?>>
    <i class="bi bi-search"></i> Find Matching Recipes
</button>
</div>

</div>
<footer>
© 2026 What's in My Kitchen? | Privacy | Terms | Contact
</footer>
</div>

<script>
// Get selected ingredients from PHP session
let selectedIngredients = <?php echo json_encode($_SESSION['pantry_ingredients']); ?>;

// Initialize page - mark checkboxes that are selected
function initializeSelected() {
    document.querySelectorAll('.item').forEach(item => {
        let ingredientName = item.querySelector('input').value;
        if (selectedIngredients.includes(ingredientName)) {
            item.classList.add('selected');
            item.querySelector('input').checked = true;
        }
    });
}

// Toggle ingredient selection
async function toggleIngredient(ingredientName, element) {
    let isSelected = selectedIngredients.includes(ingredientName);
    
    if (isSelected) {
        // Remove ingredient
        let response = await fetch('pantry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=remove&ingredient=' + encodeURIComponent(ingredientName)
        });
        let data = await response.json();
        if (data.success) {
            selectedIngredients = data.ingredients;
            element.classList.remove('selected');
            element.querySelector('input').checked = false;
            updateSelectedSection();
        }
    } else {
        // Add ingredient
        let response = await fetch('pantry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=add&ingredient=' + encodeURIComponent(ingredientName)
        });
        let data = await response.json();
        if (data.success) {
            selectedIngredients = data.ingredients;
            element.classList.add('selected');
            element.querySelector('input').checked = true;
            updateSelectedSection();
        }
    }
}

// Update selected ingredients section
function updateSelectedSection() {
    let section = document.getElementById('selectedSection');
    let tagsContainer = document.getElementById('selectedTags');
    let findBtn = document.getElementById('findBtn');
    
    if (selectedIngredients.length > 0) {
        section.style.display = 'block';
        tagsContainer.innerHTML = selectedIngredients.map(ing => 
            `<span class="selected-tag" onclick="removeIngredient('${ing.replace(/'/g, "\\'")}')">
                ${ing} <i class="bi bi-x-circle"></i>
            </span>`
        ).join('');
        findBtn.disabled = false;
    } else {
        section.style.display = 'none';
        findBtn.disabled = true;
    }
}

// Remove single ingredient
async function removeIngredient(ingredientName) {
    let response = await fetch('pantry.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=remove&ingredient=' + encodeURIComponent(ingredientName)
    });
    let data = await response.json();
    if (data.success) {
        selectedIngredients = data.ingredients;
        
        // Update checkbox
        document.querySelectorAll('.item').forEach(item => {
            if (item.querySelector('input').value === ingredientName) {
                item.classList.remove('selected');
                item.querySelector('input').checked = false;
            }
        });
        
        updateSelectedSection();
    }
}

// Clear all ingredients
async function clearAllIngredients() {
    if (confirm('Remove all ingredients from your pantry?')) {
        let response = await fetch('pantry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=clear'
        });
        let data = await response.json();
        if (data.success) {
            selectedIngredients = [];
            
            // Uncheck all checkboxes
            document.querySelectorAll('.item').forEach(item => {
                item.classList.remove('selected');
                item.querySelector('input').checked = false;
            });
            
            updateSelectedSection();
        }
    }
}

// Filter items by category
function filterItems(category) {
    let items = document.querySelectorAll(".item");
    items.forEach(item => {
        if (category === "all") {
            item.style.display = "flex";
        } else if (item.dataset.cat === category) {
            item.style.display = "flex";
        } else {
            item.style.display = "none";
        }
    });
}

// Search items
function searchItems() {
    let input = document.querySelector(".search input").value.toLowerCase();
    let items = document.querySelectorAll(".item");
    items.forEach(item => {
        let text = item.textContent.toLowerCase();
        item.style.display = text.includes(input) ? "flex" : "none";
    });
}

// Find recipes - THIS IS THE IMPORTANT FUNCTION
function findRecipes() {
    if (selectedIngredients.length > 0) {
        // The selected ingredients are already in session via AJAX
        // Just redirect to recipes page
        window.location.href = "recipes.php";
    } else {
        alert("Please select at least one ingredient from your pantry!");
    }
}

// Add click event listeners to items
document.querySelectorAll('.item').forEach(item => {
    let checkbox = item.querySelector('input');
    let ingredientName = checkbox.value;
    
    item.addEventListener('click', function(e) {
        // Don't trigger if clicking on the checkbox directly
        if (e.target !== checkbox) {
            toggleIngredient(ingredientName, item);
        }
    });
    
    checkbox.addEventListener('change', function(e) {
        e.stopPropagation();
        if (checkbox.checked) {
            if (!selectedIngredients.includes(ingredientName)) {
                toggleIngredient(ingredientName, item);
            }
        } else {
            if (selectedIngredients.includes(ingredientName)) {
                toggleIngredient(ingredientName, item);
            }
        }
    });
});

// Initialize page
initializeSelected();
updateSelectedSection();
</script>
</body>
</html>