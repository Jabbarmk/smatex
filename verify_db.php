<?php
/**
 * Database Verification Script
 */

$dbs = [
    'smatex' => ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
    'smatex2' => ['host' => 'localhost', 'user' => 'root', 'pass' => '']
];

foreach ($dbs as $dbname => $creds) {
    try {
        echo "<h3>Database: $dbname</h3>";
        $pdo = new PDO("mysql:host={$creds['host']};dbname=$dbname;charset=utf8mb4", $creds['user'], $creds['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if leads table exists
        $result = $pdo->query("SHOW TABLES LIKE 'leads'")->rowCount();
        
        if ($result > 0) {
            echo "<p style='color: green;'>✓ Leads table EXISTS</p>";
            
            // Get table structure
            $columns = $pdo->query("DESCRIBE leads")->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>Structure:</p>";
            echo "<pre>";
            foreach ($columns as $col) {
                echo $col['Field'] . " - " . $col['Type'] . "\n";
            }
            echo "</pre>";
            
            // Count rows
            $count = $pdo->query("SELECT COUNT(*) FROM leads")->fetch(PDO::FETCH_NUM)[0];
            echo "<p>Rows: $count</p>";
        } else {
            echo "<p style='color: red;'>✗ Leads table MISSING</p>";
        }
        
        echo "<hr>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error connecting to $dbname: " . $e->getMessage() . "</p>";
    }
}
?>
