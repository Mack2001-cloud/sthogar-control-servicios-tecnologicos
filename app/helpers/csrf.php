<?php

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_validate(?string $token = null): bool
{
    $token = $token ?? ($_POST['csrf_token'] ?? '');

    if (!$token || empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function verify_csrf(): void
{
    if (!csrf_validate()) {
        http_response_code(419);
        echo view('partials/419');
        exit;
    }
}
