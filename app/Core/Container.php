<?php

namespace App\Core;

class Container
{
    private static array $bindings = [];
    private static array $instances = [];

    public static function set(string $key, callable $resolver): void
    {
        self::$bindings[$key] = $resolver;
    }

    public static function get(string $key): mixed
    {
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = self::$bindings[$key]();
        }

        return self::$instances[$key];
    }
}
