<?php
require_once 'db.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: auth/login.php');
        exit();
    }
}

// Sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Get user data
function getUserData($user_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get all recipes
function getAllRecipes() {
    $conn = getConnection();
    $result = $conn->query("SELECT * FROM recipes ORDER BY id DESC");
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    return $recipes;
}

// Get user's saved recipes
function getSavedRecipes($user_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT r.* FROM recipes r 
                            INNER JOIN saved_recipes s ON r.id = s.recipe_id 
                            WHERE s.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    return $recipes;
}

// Add recipe to saved
function saveRecipe($user_id, $recipe_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO saved_recipes (user_id, recipe_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $recipe_id);
    return $stmt->execute();
}

// Remove saved recipe
function removeSavedRecipe($user_id, $recipe_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
    $stmt->bind_param("ii", $user_id, $recipe_id);
    return $stmt->execute();
}

// Check if recipe is saved
function isRecipeSaved($user_id, $recipe_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id FROM saved_recipes WHERE user_id = ? AND recipe_id = ?");
    $stmt->bind_param("ii", $user_id, $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Add new recipe
function addRecipe($user_id, $name, $ingredients, $steps, $time, $difficulty, $image_url) {
    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO recipes (user_id, name, ingredients, steps, time, difficulty, image_url) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $name, $ingredients, $steps, $time, $difficulty, $image_url);
    return $stmt->execute();
}

// Get single recipe
function getRecipe($recipe_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>