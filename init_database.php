<?php
/**
 * Database Initialization Script
 * This script creates all required tables from database.sql
 */

require_once 'config/config.php';

try {
    echo "<h2>Database Initialization</h2>";
    echo "<p>Connecting to database: " . DB_NAME . "</p>";
    
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color: green;'>✓ Database '" . DB_NAME . "' created or already exists.</p>";
    
    // Connect to the newly created database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute database.sql
    $sql = file_get_contents('database.sql');
    
    // Split SQL statements and execute them
    $statements = array_filter(array_map('trim', preg_split('/;[\s\n]+/', $sql)));
    
    $tableCount = 0;
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
            // Count created tables
            if (stripos($statement, 'CREATE TABLE') !== false) {
                $tableCount++;
            }
        }
    }
    
    echo "<p style='color: green;'>✓ Database schema loaded successfully.</p>";
    
    // Verify critical tables exist
    $tables = ['users', 'leads', 'invoices', 'quotations', 'expenses', 'receipts', 'statements'];
    echo "<h3>Table Verification:</h3>";
    echo "<ul>";
    
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'")->rowCount();
        $status = $result > 0 ? '✓' : '✗';
        $color = $result > 0 ? 'green' : 'red';
        echo "<li style='color: $color;'>$status Table '$table'</li>";
    }
    
    echo "</ul>";
    
    echo "<p style='color: green; font-weight: bold;'>✓ Database initialization completed successfully!</p>";
    echo "<p><a href='index.php'>Go to Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Make sure your database credentials are correct in config/config.php</p>";
    exit;
}
?>
