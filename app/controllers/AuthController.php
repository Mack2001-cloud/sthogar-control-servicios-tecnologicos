<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function loginForm(): void
    {
        echo view('auth/login', [
            'title' => 'Iniciar sesión',
        ]);
    }

    public function loginPost(): void
    {
        if (!csrf_validate()) {
            set_flash('danger', 'Token CSRF inválido.');
            header('Location: ' . AUTH_LOGIN_ROUTE);
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $email !== '' ? User::findByEmail($email) : null;
        if (!$user || !password_verify($password, $user['pass_hash'])) {
            set_flash('danger', 'Credenciales inválidas.');
            header('Location: ' . AUTH_LOGIN_ROUTE);
            exit;
        }

        if ((int) $user['activo'] === 0) {
            set_flash('danger', 'Tu usuario está inactivo.');
            header('Location: ' . AUTH_LOGIN_ROUTE);
            exit;
        }

        session_regenerate_id(true);
        login_user($user);

        set_flash('success', 'Bienvenido/a de nuevo.');
        header('Location: ' . AUTH_POST_LOGIN_ROUTE);
        exit;
    }

    public function logout(): void
    {
        $config = require __DIR__ . '/../config/config.php';

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        start_secure_session($config);
        set_flash('success', 'Sesión cerrada correctamente.');
        header('Location: ' . AUTH_LOGIN_ROUTE);
        exit;
    }
}
