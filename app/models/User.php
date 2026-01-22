<?php

namespace App\Models;

use App\Core\Database;

class User
{
    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, name, email, role FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function countAll(): int
    {
        $pdo = Database::connection();
        return (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
