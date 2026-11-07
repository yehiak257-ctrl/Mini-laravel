<?php

namespace App;

class View
{
    public static function make(string $name, array $data = []): string
    {
        $path = __DIR__ . '/../views/' . $name . '.php';

        extract($data);

        ob_start();
        require $path;

        return ob_get_clean();
    }
}