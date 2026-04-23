<?php
/**
 * Database Initialization Script (Improved)
 * Creates only missing tables from database.sql
 */

require_once 'config/config.php';

try {
    echo "<h2>Database Setup</h2>";
    
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if needed
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✓ Connected to database '" . DB_NAME . "'</p>";
    
    // Switch to the application database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read the database schema
    $sqlContent = file_get_contents('database.sql');
    
    // Extract individual CREATE TABLE statements
    preg_match_all('/CREATE TABLE `(\w+)`[\s\S]*?(?=CREATE TABLE|INSERT INTO|COMMIT|$)/i', $sqlContent, $matches, PREG_OFFSET_CAPTURE);
    
    $created = 0;
    $existing = 0;
    
    // Process each match
    if (!empty($matches[0])) {
        foreach ($matches[0] as $idx => $match) {
            $statement = $match[0];
            $tableName = $matches[1][$idx][0];
            
            // Check if table already exists
            $result = $pdo->query("SHOW TABLES LIKE '$tableName'");
            
            if ($result->rowCount() == 0) {
                try {
                    // Add "IF NOT EXISTS" to the statement
                    $statement = preg_replace('/CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $statement);
                    $pdo->exec($statement);
                    echo "<p style='color: green;'>✓ Created table: $tableName</p>";
                    $created++;
                } catch (Exception $e) {
                    echo "<p style='color: orange;'>⚠ Could not create $tableName: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p>• Table already exists: $tableName</p>";
                $existing++;
            }
        }
    }
    
    // Try to insert default data only if users table is empty
    try {
        $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetch(PDO::FETCH_NUM)[0];
        if ($userCount == 0) {
            $pdo->exec("INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`) VALUES
            ('Super Admin', 'admin@smatex.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'Active')");
            echo "<p style='color: green;'>✓ Default admin user created</p>";
        }
    } catch (Exception $e) {
        // Silent
    }
    
    echo "<hr>";
    echo "<h3>Table Status:</h3>";
    echo "<ul>";
    
    $tables = ['users', 'leads', 'lead_status_history', 'quotations', 'quotation_items', 'invoices', 'invoice_items', 'receipts', 'expenses', 'statements', 'settings'];
    
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'")->rowCount();
        $status = $result > 0 ? '✓ Exists' : '✗ Missing';
        $color = $result > 0 ? 'green' : 'red';
        echo "<li style='color: $color;'><strong>$table</strong>: $status</li>";
    }
    
    echo "</ul>";
    
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✓ Setup complete! You can now use the application.</p>";
    echo "<p><a href='index.php'>Go to Application</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Database credentials needed in config/config.php:</p>";
    echo "<pre>";
    echo "DB_HOST: " . DB_HOST . "\n";
    echo "DB_NAME: " . DB_NAME . "\n";
    echo "DB_USER: " . DB_USER . "\n";
    echo "</pre>";
}
?>
