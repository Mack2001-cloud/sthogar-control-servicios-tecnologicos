<?php

namespace App\Controllers;

use App\Models\AgenteVenta;
use App\Models\User;
use DateTime;

class AdminAgentesVentasController
{
    public function index(): void
    {
        $agentes = AgenteVenta::findAll();

        echo view('admin/agentes_ventas/index', [
            'title' => 'Agentes de ventas',
            'agentes' => $agentes,
        ]);
    }

    public function createForm(): void
    {
        echo view('admin/agentes_ventas/create', [
            'title' => 'Nuevo agente de ventas',
        ]);
    }

    public function create(): void
    {
        verify_csrf();

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $fechaIngreso = trim($_POST['fecha_ingreso'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        $activo = (int) ($_POST['activo'] ?? 1);
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if (!required($nombre) || !required($email) || !required($telefono) || !required($direccion)) {
            set_flash('danger', 'Nombre, email, teléfono y dirección son obligatorios.');
            header('Location: /admin/agentes-ventas/create');
            exit;
        }

        if (!validate_email($email)) {
            set_flash('danger', 'Email inválido.');
            header('Location: /admin/agentes-ventas/create');
            exit;
        }

        if ($fechaIngreso !== '') {
            $date = DateTime::createFromFormat('Y-m-d', $fechaIngreso);
            if (!$date || $date->format('Y-m-d') !== $fechaIngreso) {
                set_flash('danger', 'Fecha de ingreso inválida.');
                header('Location: /admin/agentes-ventas/create');
                exit;
            }
        } else {
            $fechaIngreso = null;
        }

        if ($password === '') {
            set_flash('danger', 'La contraseña es obligatoria.');
            header('Location: /admin/agentes-ventas/create');
            exit;
        }

        if ($password !== $passwordConfirm) {
            set_flash('danger', 'Las contraseñas no coinciden.');
            header('Location: /admin/agentes-ventas/create');
            exit;
        }

        if (mb_strlen($password) < 8) {
            set_flash('danger', 'La contraseña debe tener al menos 8 caracteres.');
            header('Location: /admin/agentes-ventas/create');
            exit;
        }

        $existing = User::findByEmail($email);
        if ($existing) {
            set_flash('danger', 'El email ya está registrado.');
            header('Location: /admin/agentes-ventas/create');
            exit;
        }

        AgenteVenta::create([
            'usuario_nombre' => $nombre,
            'usuario_email' => $email,
            'usuario_pass_hash' => password_hash($password, PASSWORD_DEFAULT),
            'telefono' => $telefono,
            'direccion' => $direccion,
            'fecha_ingreso' => $fechaIngreso,
            'notas' => $notas !== '' ? $notas : null,
            'activo' => $activo,
        ]);

        set_flash('success', 'Agente de ventas creado correctamente.');
        header('Location: /admin/agentes-ventas');
        exit;
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $agente = AgenteVenta::findById($id);
        if (!$agente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        echo view('admin/agentes_ventas/edit', [
            'title' => 'Editar agente de ventas',
            'agente' => $agente,
        ]);
    }

    public function update(): void
    {
        verify_csrf();

        $id = (int) ($_POST['id'] ?? 0);
        $agente = AgenteVenta::findById($id);
        if (!$agente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $fechaIngreso = trim($_POST['fecha_ingreso'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        $activo = (int) ($_POST['activo'] ?? 0);
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if (!required($telefono) || !required($direccion)) {
            set_flash('danger', 'Teléfono y dirección son obligatorios.');
            header('Location: /admin/agentes-ventas/edit?id=' . $id);
            exit;
        }

        if ($fechaIngreso !== '') {
            $date = DateTime::createFromFormat('Y-m-d', $fechaIngreso);
            if (!$date || $date->format('Y-m-d') !== $fechaIngreso) {
                set_flash('danger', 'Fecha de ingreso inválida.');
                header('Location: /admin/agentes-ventas/edit?id=' . $id);
                exit;
            }
        } else {
            $fechaIngreso = null;
        }

        $usuarioNombre = $nombre !== '' ? $nombre : $agente['usuario_nombre'];
        $usuarioEmail = $email !== '' ? $email : $agente['usuario_email'];

        if (!validate_email($usuarioEmail)) {
            set_flash('danger', 'Email inválido.');
            header('Location: /admin/agentes-ventas/edit?id=' . $id);
            exit;
        }

        if ($usuarioEmail !== $agente['usuario_email']) {
            $existing = User::findByEmail($usuarioEmail);
            if ($existing && (int) $existing['id'] !== (int) $agente['usuario_id']) {
                set_flash('danger', 'El email ya está registrado.');
                header('Location: /admin/agentes-ventas/edit?id=' . $id);
                exit;
            }
        }

        $passwordHash = null;
        if ($password !== '') {
            if ($password !== $passwordConfirm) {
                set_flash('danger', 'Las contraseñas no coinciden.');
                header('Location: /admin/agentes-ventas/edit?id=' . $id);
                exit;
            }

            if (mb_strlen($password) < 8) {
                set_flash('danger', 'La contraseña debe tener al menos 8 caracteres.');
                header('Location: /admin/agentes-ventas/edit?id=' . $id);
                exit;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        }

        AgenteVenta::update($id, [
            'telefono' => $telefono,
            'direccion' => $direccion,
            'fecha_ingreso' => $fechaIngreso,
            'notas' => $notas !== '' ? $notas : null,
            'activo' => $activo,
            'usuario_id' => (int) $agente['usuario_id'],
            'usuario_nombre' => $usuarioNombre,
            'usuario_email' => $usuarioEmail,
            'usuario_pass_hash' => $passwordHash,
        ]);

        set_flash('success', 'Agente de ventas actualizado correctamente.');
        header('Location: /admin/agentes-ventas');
        exit;
    }

    public function toggle(): void
    {
        verify_csrf();

        $id = (int) ($_POST['id'] ?? 0);
        $agente = AgenteVenta::findById($id);
        if (!$agente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $nuevoEstado = (int) $agente['activo'] === 1 ? 0 : 1;
        AgenteVenta::toggleActive($id, $nuevoEstado);

        $mensaje = $nuevoEstado === 1 ? 'Agente de ventas activado.' : 'Agente de ventas desactivado.';
        set_flash('success', $mensaje);
        header('Location: /admin/agentes-ventas');
        exit;
    }

    public function delete(): void
    {
        verify_csrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            set_flash('danger', 'Agente de ventas inválido.');
            header('Location: /admin/agentes-ventas');
            exit;
        }

        $agente = AgenteVenta::findById($id);
        if (!$agente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        try {
            AgenteVenta::delete($id);
            set_flash('success', 'Agente de ventas eliminado correctamente.');
        } catch (\Throwable $e) {
            set_flash('danger', 'No se pudo eliminar el agente de ventas.');
        }

        header('Location: /admin/agentes-ventas');
        exit;
    }
}
