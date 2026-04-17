<?php

namespace App\Core;

class Router
{
    public function __construct(private readonly array $routes) {}

    public function dispatch(Request $request): void
    {
        foreach ($this->routes as [$method, $uri, $action]) {
            $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $uri);
            $pattern = '#^' . $pattern . '$#';

            if ($method !== $request->method || !preg_match($pattern, $request->path, $matches)) {
                continue;
            }

            $params = array_filter($matches, static fn ($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
            [$controllerClass, $controllerMethod] = $action;
            $controller = new $controllerClass();
            $controller->$controllerMethod($request, ...array_values($params));
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
