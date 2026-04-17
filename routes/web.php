<?php

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/category/{slug}', [CategoryController::class, 'show']],
    ['GET', '/post/{slug}', [PostController::class, 'show']],
];
