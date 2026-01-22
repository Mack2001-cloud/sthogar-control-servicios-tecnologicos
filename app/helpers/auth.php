<?php

use App\Models\User;

function auth_user(): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return User::findById((int) $_SESSION['user_id']);
}

function login_user(array $user): void
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
}

function logout_user(): void
{
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_role']);
}

function is_admin(): bool
{
    return ($_SESSION['user_role'] ?? '') === 'admin';
}

function require_role(string $role): void
{
    if (($_SESSION['user_role'] ?? '') !== $role) {
        http_response_code(403);
        echo view('partials/403');
        exit;
    }
}
