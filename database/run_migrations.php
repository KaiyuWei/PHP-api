<?php

require __DIR__ . '/../vendor/autoload.php';
use App\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$pdo = (new Database())->getConnection();

function runMigrationFiles($pdo, $files)
{
    foreach ($files as $file) {
        $sql = file_get_contents($file);
        $pdo->exec($sql);

        echo "Executed: " . basename($file) . "\n";
    }
}

try {
    $path = __DIR__ . '/migrations';
    $files = glob($path . '/*.sql');

    $pdo->beginTransaction();

    runMigrationFiles($pdo, $files);

    $pdo->commit();

} catch (\Exception $e) {
    echo 'Connection failed: ' . $e->getMessage();

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
}