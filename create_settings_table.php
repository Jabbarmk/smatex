<?php
// Explicitly define constants if config fails to load for standalone script, 
// but try loading config first.
$configFile = __DIR__ . '/config/config.php';
if (file_exists($configFile)) {
    require_once $configFile;
} else {
    // Fallback or die
    die("Config file not found at: " . $configFile);
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "Settings table created successfully.";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
