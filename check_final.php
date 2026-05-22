<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=crm-app', 'root', '');
    $orphans = $pdo->query('SELECT count(*) as total FROM products WHERE account_id NOT IN (SELECT id FROM accounts)')->fetch(PDO::FETCH_ASSOC);
    echo "Orphaned Products: " . $orphans['total'] . "\n";
    
    $nulls = $pdo->query('SELECT count(*) as total FROM products WHERE account_id IS NULL')->fetch(PDO::FETCH_ASSOC);
    echo "Null Account Products: " . $nulls['total'] . "\n";
    
    $valid = $pdo->query('SELECT p.name, a.name as account_name FROM products p JOIN accounts a ON p.account_id = a.id LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
    echo "Sample Valid: " . json_encode($valid) . "\n";
} catch (Exception $e) {
    echo $e->getMessage();
}
