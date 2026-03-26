<?php
function getConnection() {
    // Get MySQL connection URL from Railway environment
    $mysqlUrl = getenv('MYSQL_URL');
    
    if ($mysqlUrl) {
        // Parse Railway MySQL URL
        // Format: mysql://username:password@host:port/database
        $url = parse_url($mysqlUrl);
        
        $host = $url['host'];
        $port = isset($url['port']) ? $url['port'] : 3306;
        $user = $url['user'];
        $pass = $url['pass'];
        $db = ltrim($url['path'], '/');
        
        // Create connection
        $conn = new mysqli($host, $user, $pass, $db, $port);
    } else {
        // Local development
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_NAME', 'kitchen_app');
        
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
