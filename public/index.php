<?php

require __DIR__ . "/../autoload.php";

use App\Request;
use App\Router;

$router = new Router();

require __DIR__ . "/../routes/web.php";

$request = new Request();

echo $router->dispatch($request->path());