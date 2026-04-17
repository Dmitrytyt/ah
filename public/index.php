<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Request;

require_once __DIR__ . '/../vendor/autoload.php';

set_exception_handler(static function (Throwable $exception): void {
    $logDir = dirname(__DIR__) . '/storage/logs';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }

    $logFile = $logDir . '/app.log';
    $message = sprintf(
        "[%s] %s in %s:%d
%s

",
        date('Y-m-d H:i:s'),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );

    error_log($message, 3, $logFile);

    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
    }

    echo 'Внутренняя ошибка сервера. Попробуйте позже.';
});

$app = new App();
$app->bootstrap();
$app->run(Request::capture());
