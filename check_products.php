<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=crm-app', 'root', '');
    $res = $pdo->query('SELECT count(*) as total FROM products')->fetch(PDO::FETCH_ASSOC);
    echo "Total Products: " . $res['total'] . "\n";
    
    $accounts = $pdo->query('SELECT id, name FROM accounts')->fetchAll(PDO::FETCH_ASSOC);
    echo "Accounts: " . json_encode($accounts) . "\n";
} catch (Exception $e) {
    echo $e->getMessage();
}
