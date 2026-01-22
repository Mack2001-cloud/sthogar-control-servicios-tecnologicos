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

    public function login(): void
    {
        verify_csrf();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            set_flash('danger', 'Credenciales inválidas.');
            header('Location: /auth/login');
            exit;
        }

        login_user($user);
        set_flash('success', 'Bienvenido/a de nuevo.');
        header('Location: /dashboard');
        exit;
    }

    public function logout(): void
    {
        logout_user();
        set_flash('success', 'Sesión cerrada correctamente.');
        header('Location: /auth/login');
        exit;
    }
}
