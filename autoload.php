<?php

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    $relative = str_replace($prefix, '', $class);
    $path = $baseDir . str_replace('\\', '/', $relative) . '.php';

    require $path;
});

