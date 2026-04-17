<?php

namespace App\Core;

use Dotenv\Dotenv;

class App
{
    public function bootstrap(): void
    {
        $root = dirname(__DIR__, 2);

        if (file_exists($root . '/.env')) {
            Dotenv::createImmutable($root)->safeLoad();
        }

        Config::load($root . '/config/app.php', 'app');
        Config::load($root . '/config/database.php', 'database');

        Container::set('db', fn () => Database::connection());
        Container::set('view', fn () => View::make());
    }

    public function run(Request $request): void
    {
        $routes = require dirname(__DIR__, 2) . '/routes/web.php';
        $router = new Router($routes);
        $router->dispatch($request);
    }
}
