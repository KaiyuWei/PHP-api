<?php

require __DIR__ . '/../vendor/autoload.php';
use App\Database;
use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $pdo = (new Database())->getConnection();
    $path = __DIR__ . '/migrations';
    $files = glob($path . '/*.sql');

    foreach ($files as $file) {
        $sql = file_get_contents($file);
        $pdo->exec($sql);

        echo "Executed: " . basename($file) . "\n";
    }

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}