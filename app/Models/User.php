<?php

namespace App\Models;

use App\Database;

class User
{
    public function __construct(protected Database $db)
    {
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM users');
    }

    public function find($id): ?array
    {
        $rows = $this->db->query('SELECT * FROM users WHERE id = ?', [$id]);

        return $rows[0] ?? null;
    }
}