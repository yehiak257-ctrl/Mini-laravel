<?php

namespace App;

class Request
{
public function uri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
    public function path(): string
{
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}
public function query(string $key, $default = null)
{
    return $_GET[$key] ?? $default;
}
}