<?php

function auth_middleware(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /auth/login');
        exit;
    }
}
