<?php
// Fix script to create settings table
define('APP_ROOT', __DIR__);
// Adjust paths if necessary
if (file_exists(APP_ROOT . '/config/config.php')) {
    require_once APP_ROOT . '/config/config.php';
} else {
    die("Config file missing.");
}

require_once APP_ROOT . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $sql = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $conn->exec($sql);
    echo "Settings table created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
