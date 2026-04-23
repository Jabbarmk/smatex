<?php
require_once 'config/config.php';
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Settings Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(50) UNIQUE NOT NULL,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Insert Default Values
    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :val) ON DUPLICATE KEY UPDATE setting_value=:val");
    $stmt->execute(['key' => 'tax_percentage', 'val' => '5']);
    $stmt->execute(['key' => 'currency_symbol', 'val' => 'AED']);
    $stmt->execute(['key' => 'currency_position', 'val' => 'before']);
    $stmt->execute(['key' => 'decimal_separator', 'val' => '.']);
    $stmt->execute(['key' => 'thousands_separator', 'val' => ',']);
    $stmt->execute(['key' => 'decimal_places', 'val' => '2']);
    $stmt->execute(['key' => 'company_name', 'val' => 'Smatflix Technologies FZCO']);

    // Add Discount Column to Invoices if not exists
    $columns = $pdo->query("SHOW COLUMNS FROM invoices LIKE 'discount'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE invoices ADD COLUMN discount DECIMAL(10, 2) DEFAULT 0.00 AFTER subtotal");
        echo "Added discount column.\n";
    }

    // Add Tax Percentage Column to Invoices if not exists (to store applied rate)
    $columns = $pdo->query("SHOW COLUMNS FROM invoices LIKE 'tax_percentage'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE invoices ADD COLUMN tax_percentage DECIMAL(5, 2) DEFAULT 0.00 AFTER discount");
        echo "Added tax_percentage column.\n";
    }

    // Add Terms & Conditions Column to Quotations if not exists
    $columns = $pdo->query("SHOW COLUMNS FROM quotations LIKE 'terms_conditions'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE quotations ADD COLUMN terms_conditions TEXT DEFAULT NULL AFTER status");
        echo "Added terms_conditions column to quotations.\n";
    }

    echo "Database updated successfully.\n";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
