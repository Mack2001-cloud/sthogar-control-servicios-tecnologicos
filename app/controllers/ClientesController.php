<?php

namespace App\Controllers;

use App\Models\Cliente;

class ClientesController
{
    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $clientes = Cliente::all($search ?: null);

        echo view('clientes/index', [
            'title' => 'Clientes',
            'clientes' => $clientes,
            'search' => $search,
        ]);
    }

    public function createForm(): void
    {
        echo view('clientes/form', [
            'title' => 'Nuevo cliente',
            'cliente' => null,
            'action' => '/clientes/create',
        ]);
    }

    public function create(): void
    {
        verify_csrf();
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        if (!required($data['name'])) {
            set_flash('danger', 'El nombre es obligatorio.');
            header('Location: /clientes/create');
            exit;
        }

        Cliente::create($data);
        set_flash('success', 'Cliente registrado correctamente.');
        header('Location: /clientes');
        exit;
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $cliente = Cliente::find($id);
        if (!$cliente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        echo view('clientes/form', [
            'title' => 'Editar cliente',
            'cliente' => $cliente,
            'action' => '/clientes/edit?id=' . $id,
        ]);
    }

    public function update(): void
    {
        verify_csrf();
        $id = (int) ($_GET['id'] ?? 0);
        $cliente = Cliente::find($id);
        if (!$cliente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        Cliente::update($id, $data);
        set_flash('success', 'Cliente actualizado.');
        header('Location: /clientes');
        exit;
    }

    public function delete(): void
    {
        verify_csrf();
        if (!is_admin()) {
            require_role('admin');
        }

        $id = (int) ($_POST['id'] ?? 0);
        Cliente::delete($id);
        set_flash('success', 'Cliente eliminado.');
        header('Location: /clientes');
        exit;
    }
}
