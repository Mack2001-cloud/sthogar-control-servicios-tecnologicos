<?php

function role_required(string $role): callable
{
    return static function () use ($role): void {
        if (!is_logged_in()) {
            require_login();
        }

        if (current_user_role() !== $role) {
            http_response_code(403);
            echo view('partials/403');
            exit;
        }
    };
}

function role_required_any(array $roles): callable
{
    return static function () use ($roles): void {
        if (!is_logged_in()) {
            require_login();
        }

        if (!in_array(current_user_role(), $roles, true)) {
            set_flash('danger', 'No autorizado');
            http_response_code(403);
            echo view('partials/403');
            exit;
        }
    };
}
