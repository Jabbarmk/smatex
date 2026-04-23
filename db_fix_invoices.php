<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "Connected to database.\n";

    // 1. Add columns to 'invoices' table if they don't exist
    $columns = [
        'discount' => "DECIMAL(10,2) DEFAULT 0.00 AFTER subtotal",
        'tax_percentage' => "DECIMAL(5,2) DEFAULT 0.00 AFTER discount",
        'vat_total' => "DECIMAL(10,2) DEFAULT 0.00 AFTER tax_percentage" 
    ];

    echo "Checking 'invoices' table columns...\n";
    foreach ($columns as $col => $def) {
        // Use $conn instead of $db
        $stmt = $conn->query("SHOW COLUMNS FROM invoices LIKE '$col'");
        if ($stmt->rowCount() == 0) {
            echo "Adding column '$col'...\n";
            $conn->exec("ALTER TABLE invoices ADD COLUMN $col $def");
        } else {
            echo "Column '$col' already exists.\n";
        }
    }

    // 2. Add columns to 'quotations' table as well just in case (for future consistency)
    // Though currently controller might not use them yet, good to have schema ready.
    $q_columns = [
        'discount' => "DECIMAL(10,2) DEFAULT 0.00 AFTER subtotal",
        'tax_percentage' => "DECIMAL(5,2) DEFAULT 0.00 AFTER discount",
        'vat_total' => "DECIMAL(10,2) DEFAULT 0.00 AFTER tax_percentage"
    ];

    echo "\nChecking 'quotations' table columns...\n";
    foreach ($q_columns as $col => $def) {
        $stmt = $conn->query("SHOW COLUMNS FROM quotations LIKE '$col'");
        if ($stmt->rowCount() == 0) {
            echo "Adding column '$col'...\n";
            $conn->exec("ALTER TABLE quotations ADD COLUMN $col $def");
        } else {
            echo "Column '$col' already exists.\n";
        }
    }

    echo "\nDatabase schema update completed successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
