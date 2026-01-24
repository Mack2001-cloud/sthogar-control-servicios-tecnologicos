<?php

namespace App\Controllers;

use App\Models\Cliente;
use App\Models\Tecnico;
use PDOException;

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
        $tecnicos = is_admin() ? Tecnico::findAll() : [];

        echo view('clientes/form', [
            'title' => 'Nuevo cliente',
            'cliente' => null,
            'action' => '/clientes/create',
            'tecnicos' => $tecnicos,
        ]);
    }

    public function create(): void
    {
        verify_csrf();
        $tecnicoId = is_admin() ? (int) ($_POST['tecnico_id'] ?? 0) : 0;
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
            'tecnico_id' => $tecnicoId > 0 ? $tecnicoId : null,
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

    public function view(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $cliente = Cliente::find($id);
        if (!$cliente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $counts = Cliente::serviceCounts($id);

        echo view('clientes/view', [
            'title' => 'Detalle de cliente',
            'cliente' => $cliente,
            'counts' => $counts,
        ]);
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

        $tecnicos = is_admin() ? Tecnico::findAll() : [];

        echo view('clientes/form', [
            'title' => 'Editar cliente',
            'cliente' => $cliente,
            'action' => '/clientes/edit?id=' . $id,
            'tecnicos' => $tecnicos,
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

        $tecnicoId = is_admin() ? (int) ($_POST['tecnico_id'] ?? 0) : (int) ($cliente['tecnico_id'] ?? 0);
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
            'tecnico_id' => $tecnicoId > 0 ? $tecnicoId : null,
        ];

        Cliente::update($id, $data);
        set_flash('success', 'Cliente actualizado.');
        header('Location: /clientes');
        exit;
    }

    public function delete(): void
    {
        verify_csrf();

        $id = (int) ($_POST['id'] ?? 0);
        $cliente = Cliente::find($id);
        if (!$cliente) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $counts = Cliente::dependencyCounts($id);
        $servicios = (int) ($counts['servicios'] ?? 0);
        $equipos = (int) ($counts['equipos'] ?? 0);

        if ($servicios > 0 || $equipos > 0) {
            $detalles = [];
            if ($servicios > 0) {
                $detalles[] = $servicios . ' servicio(s)';
            }
            if ($equipos > 0) {
                $detalles[] = $equipos . ' equipo(s)';
            }
            set_flash(
                'danger',
                'No se puede eliminar el cliente porque tiene ' . implode(' y ', $detalles) . ' asociados. Elimina esos registros primero.'
            );
            header('Location: /clientes');
            exit;
        }

        try {
            Cliente::delete($id);
            set_flash('success', 'Cliente eliminado.');
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                set_flash('danger', 'No se puede eliminar el cliente porque tiene registros asociados.');
            } else {
                throw $exception;
            }
        }
        header('Location: /clientes');
        exit;
    }
}
