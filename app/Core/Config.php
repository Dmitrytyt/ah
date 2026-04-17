<?php

namespace App\Core;

class Config
{
    private static array $items = [];

    public static function load(string $path, string $key): void
    {
        self::$items[$key] = require $path;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}
