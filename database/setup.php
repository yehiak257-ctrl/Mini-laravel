<?php

$pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec('
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL
    )
');

$pdo->exec("INSERT INTO users (name) VALUES ('Yahya'), ('Ahmed'), ('Sara')");

$rows = $pdo->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);

print_r($rows);