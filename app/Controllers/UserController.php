<?php

namespace App\Controllers;

use App\Database;
use App\Models\User;
use App\Request;
use App\View;

class UserController
{
public function index(Request $request, User $users)
{
    return View::make('users', [
        'page' => $request->query('page', 1),
        'users' => $users->all(),
    ]);
}

public function show($id, User $users)
{
    $user = $users->find($id);

    if ($user === null) {
        return 'User not found';
    }

    return 'User: ' . htmlspecialchars($user['name']);
}
}