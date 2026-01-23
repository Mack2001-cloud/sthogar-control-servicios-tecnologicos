<?php

namespace App\Controllers;

use App\Models\Tecnico;
use App\Models\User;
use DateTime;

class AdminTecnicosController
{
    private array $especialidades = ['CCTV', 'AUTOMATIZACION', 'RED', 'SOPORTE', 'POS', 'GENERAL'];

    public function index(): void
    {
        $tecnicos = Tecnico::findAll();

        echo view('admin/tecnicos/index', [
            'title' => 'Técnicos',
            'tecnicos' => $tecnicos,
        ]);
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $tecnico = Tecnico::findById($id);
        if (!$tecnico) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        echo view('admin/tecnicos/edit', [
            'title' => 'Editar técnico',
            'tecnico' => $tecnico,
            'especialidades' => $this->especialidades,
        ]);
    }

    public function update(): void
    {
        verify_csrf();

        $id = (int) ($_POST['id'] ?? 0);
        $tecnico = Tecnico::findById($id);
        if (!$tecnico) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $especialidad = trim($_POST['especialidad'] ?? '');
        $fechaIngreso = trim($_POST['fecha_ingreso'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        $activo = (int) ($_POST['activo'] ?? 0);
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if (!required($telefono) || !required($direccion)) {
            set_flash('danger', 'Teléfono y dirección son obligatorios.');
            header('Location: /admin/tecnicos/edit?id=' . $id);
            exit;
        }

        if (!in_array($especialidad, $this->especialidades, true)) {
            set_flash('danger', 'Especialidad inválida.');
            header('Location: /admin/tecnicos/edit?id=' . $id);
            exit;
        }

        if ($fechaIngreso !== '') {
            $date = DateTime::createFromFormat('Y-m-d', $fechaIngreso);
            if (!$date || $date->format('Y-m-d') !== $fechaIngreso) {
                set_flash('danger', 'Fecha de ingreso inválida.');
                header('Location: /admin/tecnicos/edit?id=' . $id);
                exit;
            }
        } else {
            $fechaIngreso = null;
        }

        $usuarioNombre = $nombre !== '' ? $nombre : $tecnico['usuario_nombre'];
        $usuarioEmail = $email !== '' ? $email : $tecnico['usuario_email'];

        if (!validate_email($usuarioEmail)) {
            set_flash('danger', 'Email inválido.');
            header('Location: /admin/tecnicos/edit?id=' . $id);
            exit;
        }

        if ($usuarioEmail !== $tecnico['usuario_email']) {
            $existing = User::findByEmail($usuarioEmail);
            if ($existing && (int) $existing['id'] !== (int) $tecnico['usuario_id']) {
                set_flash('danger', 'El email ya está registrado.');
                header('Location: /admin/tecnicos/edit?id=' . $id);
                exit;
            }
        }

        $passwordHash = null;
        if ($password !== '') {
            if ($password !== $passwordConfirm) {
                set_flash('danger', 'Las contraseñas no coinciden.');
                header('Location: /admin/tecnicos/edit?id=' . $id);
                exit;
            }

            if (mb_strlen($password) < 8) {
                set_flash('danger', 'La contraseña debe tener al menos 8 caracteres.');
                header('Location: /admin/tecnicos/edit?id=' . $id);
                exit;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        }

        Tecnico::update($id, [
            'telefono' => $telefono,
            'direccion' => $direccion,
            'especialidad' => $especialidad,
            'fecha_ingreso' => $fechaIngreso,
            'notas' => $notas !== '' ? $notas : null,
            'activo' => $activo,
            'usuario_id' => (int) $tecnico['usuario_id'],
            'usuario_nombre' => $usuarioNombre,
            'usuario_email' => $usuarioEmail,
            'usuario_pass_hash' => $passwordHash,
        ]);

        set_flash('success', 'Técnico actualizado correctamente.');
        header('Location: /admin/tecnicos');
        exit;
    }

    public function toggle(): void
    {
        verify_csrf();

        $id = (int) ($_POST['id'] ?? 0);
        $tecnico = Tecnico::findById($id);
        if (!$tecnico) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $nuevoEstado = (int) $tecnico['activo'] === 1 ? 0 : 1;
        Tecnico::toggleActive($id, $nuevoEstado);

        $mensaje = $nuevoEstado === 1 ? 'Técnico activado.' : 'Técnico desactivado.';
        set_flash('success', $mensaje);
        header('Location: /admin/tecnicos');
        exit;
    }
}
