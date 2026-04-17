<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = []): void
    {
        Container::get('view')->render($template, $data);
    }
}
