<?php

return [
    'name' => 'Blogy',
    'base_url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'views_path' => __DIR__ . '/../templates',
    'templates_c' => __DIR__ . '/../storage/templates_c',
    'cache_dir' => __DIR__ . '/../storage/cache',
];
