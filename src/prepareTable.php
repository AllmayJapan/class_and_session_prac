<?php
$host = 'db';
$db_name = 'mydatabase';
$user = 'myuser';
$password = 'mypassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";

    $pdo -> exec($sql);

    echo "テーブル 'users' を作成しました。";
} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e -> getMessage();
}