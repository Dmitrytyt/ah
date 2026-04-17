<?php

return [
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
    'database' => $_ENV['DB_NAME'] ?? 'blog',
    'username' => $_ENV['DB_USER'] ?? 'blog',
    'password' => $_ENV['DB_PASS'] ?? 'blog',
    'charset' => 'utf8mb4',
];
