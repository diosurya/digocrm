<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=crm-app', 'root', '');
    $res = $pdo->query('SELECT id, name, email, role, parent_id FROM users')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($res);
} catch (Exception $e) {
    echo $e->getMessage();
}
