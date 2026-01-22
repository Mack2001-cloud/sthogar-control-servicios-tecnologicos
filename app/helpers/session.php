<?php

function start_secure_session(array $config): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    if (!empty($config['session_name'])) {
        session_name($config['session_name']);
    }

    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Strict',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'path' => '/',
    ]);

    session_start();
}
