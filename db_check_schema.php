<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

echo "INVOICES Columns:\n";
$stmt = $conn->query("SHOW COLUMNS FROM invoices");
foreach($stmt->fetchAll() as $col) {
    echo $col['Field'] . " - " . $col['Type'] . "\n";
}

echo "\nQUOTATIONS Columns:\n";
$stmt = $conn->query("SHOW COLUMNS FROM quotations");
foreach($stmt->fetchAll() as $col) {
    echo $col['Field'] . " - " . $col['Type'] . "\n";
}
