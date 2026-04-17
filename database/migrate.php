<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Database;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);
if (file_exists($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}
Config::load($root . '/config/database.php', 'database');
$db = Database::connection();
$sql = file_get_contents(__DIR__ . '/migrations/001_create_tables.sql');
$db->exec($sql);

echo "Migrations completed.\n";
