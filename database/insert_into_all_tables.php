<?php

require __DIR__ . '/../vendor/autoload.php';
use App\Database;
use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $pdo = (new Database())->getConnection();
    $file = __DIR__. '/seeders/insert_into_all_tables.sql';

    $sql = file_get_contents($file);
    $pdo->exec($sql);

    echo "Seeding succeeds!";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}