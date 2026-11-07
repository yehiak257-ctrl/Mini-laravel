<?php

use App\Controllers\UserController;
use App\Controllers\PostController;

$router->get('/post',[PostController::class,'index']);

$router->get('/users/{id}/posts/{postId}', [UserController::class, 'posts']);


$router->get('/users', [UserController::class, 'index']);
$router->get('/users/{id}', [UserController::class, 'show']);


$router->get('/', function () {
    return 'Home page';
});

$router->get('/about', function () {
    return 'About page';
});