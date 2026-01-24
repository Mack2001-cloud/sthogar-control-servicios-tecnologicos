<?php

namespace App\Controllers;

use App\Models\Equipo;
use App\Models\Cliente;

class EquiposController
{
    public function index(): void
    {
        $equipos = Equipo::all();

        echo view('equipos/index', [
            'title' => 'Equipos',
            'equipos' => $equipos,
        ]);
    }

    public function createForm(): void
    {
        $clientes = Cliente::all();
        echo view('equipos/form', [
            'title' => 'Nuevo equipo',
            'equipo' => null,
            'clientes' => $clientes,
            'action' => '/equipos/create',
        ]);
    }

    public function create(): void
    {
        verify_csrf();
        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'serial_number' => trim($_POST['serial_number'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        if (!$data['cliente_id'] || !required($data['name'])) {
            set_flash('danger', 'Cliente y nombre del equipo son obligatorios.');
            header('Location: /equipos/create');
            exit;
        }

        Equipo::create($data);
        set_flash('success', 'Equipo registrado.');
        header('Location: /equipos');
        exit;
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $equipo = Equipo::find($id);
        if (!$equipo) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $clientes = Cliente::all();
        echo view('equipos/form', [
            'title' => 'Editar equipo',
            'equipo' => $equipo,
            'clientes' => $clientes,
            'action' => '/equipos/edit?id=' . $id,
        ]);
    }

    public function update(): void
    {
        verify_csrf();
        $id = (int) ($_GET['id'] ?? 0);
        $equipo = Equipo::find($id);
        if (!$equipo) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'serial_number' => trim($_POST['serial_number'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        Equipo::update($id, $data);
        set_flash('success', 'Equipo actualizado.');
        header('Location: /equipos');
        exit;
    }

    public function delete(): void
    {
        verify_csrf();
        if (!is_admin_only()) {
            require_role('admin');
        }

        $id = (int) ($_POST['id'] ?? 0);
        Equipo::delete($id);
        set_flash('success', 'Equipo eliminado.');
        header('Location: /equipos');
        exit;
    }
}
