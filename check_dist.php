<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=crm-app', 'root', '');
    $res = $pdo->query('SELECT account_id, count(*) as count FROM products GROUP BY account_id')->fetchAll(PDO::FETCH_ASSOC);
    echo "Product Distribution: " . json_encode($res) . "\n";
    
    $accounts = $pdo->query('SELECT id, name FROM accounts')->fetchAll(PDO::FETCH_ASSOC);
    echo "Existing Accounts: " . json_encode($accounts) . "\n";
} catch (Exception $e) {
    echo $e->getMessage();
}
