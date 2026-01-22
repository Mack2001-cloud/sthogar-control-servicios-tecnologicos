<?php

use App\Models\User;

function auth_user(): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return User::find((int) $_SESSION['user_id']);
}

function login_user(array $user): void
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
}

function logout_user(): void
{
    unset($_SESSION['user_id'], $_SESSION['role']);
}

function is_admin(): bool
{
    return ($_SESSION['role'] ?? '') === 'admin';
}

function require_role(string $role): void
{
    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        echo view('partials/403');
        exit;
    }
}
