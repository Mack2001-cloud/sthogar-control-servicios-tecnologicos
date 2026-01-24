<?php

namespace App\Controllers;

use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\ServicioLog;
use App\Models\Pago;
use App\Models\Adjunto;
use App\Models\Tecnico;

class ServiciosController
{
    private array $statusOptions = [
        'pendiente' => 'Pendiente',
        'proceso' => 'En proceso',
        'finalizado' => 'Finalizado',
        'cancelado' => 'Cancelado',
    ];

    public function index(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'cliente_id' => $_GET['cliente_id'] ?? '',
            'service_type' => $_GET['service_type'] ?? '',
        ];

        $servicios = Servicio::all($filters);
        $clientes = Cliente::all();

        echo view('servicios/index', [
            'title' => 'Servicios',
            'servicios' => $servicios,
            'clientes' => $clientes,
            'filters' => $filters,
            'statusOptions' => $this->statusOptions,
        ]);
    }

    public function instalaciones(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'cliente_id' => $_GET['cliente_id'] ?? '',
            'service_type' => 'instalacion',
        ];

        $servicios = Servicio::all($filters);
        $clientes = Cliente::all();

        echo view('servicios/index', [
            'title' => 'Instalaciones',
            'servicios' => $servicios,
            'clientes' => $clientes,
            'filters' => $filters,
            'statusOptions' => $this->statusOptions,
            'filterAction' => '/instalaciones',
            'listHeading' => 'Listado de instalaciones',
            'createLabel' => 'Nueva instalación',
            'createLink' => '/servicios/create?service_type=instalacion',
        ]);
    }

    public function createForm(): void
    {
        $clientes = Cliente::all();
        $tecnicos = is_admin() ? Tecnico::findAll() : [];
        $defaultServiceType = $_GET['service_type'] ?? 'soporte';
        echo view('servicios/form', [
            'title' => 'Nuevo servicio',
            'servicio' => [
                'service_type' => $defaultServiceType,
            ],
            'clientes' => $clientes,
            'tecnicos' => $tecnicos,
            'statusOptions' => $this->statusOptions,
            'action' => '/servicios/create',
        ]);
    }

    public function create(): void
    {
        verify_csrf();
        $tecnicoId = is_admin() ? (int) ($_POST['tecnico_id'] ?? 0) : 0;
        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'type' => trim($_POST['type'] ?? ''),
            'service_type' => $_POST['service_type'] ?? 'soporte',
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'pendiente',
            'scheduled_at' => $_POST['scheduled_at'] ?? null,
            'estimated_amount' => (float) ($_POST['amount'] ?? 0),
            'tecnico_id' => $tecnicoId > 0 ? $tecnicoId : null,
        ];

        if (!$data['cliente_id'] || !required($data['type'])) {
            set_flash('danger', 'Cliente y tipo son obligatorios.');
            header('Location: /servicios/create');
            exit;
        }

        $servicioId = Servicio::create($data);
        ServicioLog::create([
            'servicio_id' => $servicioId,
            'user_id' => (int) $_SESSION['user_id'],
            'status' => $data['status'],
            'note' => 'Servicio creado.',
        ]);

        set_flash('success', 'Servicio registrado.');
        header('Location: /servicios/view?id=' . $servicioId);
        exit;
    }

    public function view(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $servicio = Servicio::find($id);
        if (!$servicio) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $equipos = Equipo::byCliente((int) $servicio['cliente_id']);
        $logs = ServicioLog::byServicio($id);
        $pagos = Pago::byServicio($id);
        $adjuntos = Adjunto::byServicio($id);
        $totalPagos = Pago::totalByServicio($id);

        echo view('servicios/view', [
            'title' => 'Detalle de servicio',
            'servicio' => $servicio,
            'equipos' => $equipos,
            'logs' => $logs,
            'pagos' => $pagos,
            'adjuntos' => $adjuntos,
            'totalPagos' => $totalPagos,
            'statusOptions' => $this->statusOptions,
        ]);
    }

    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $servicio = Servicio::find($id);
        if (!$servicio) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $clientes = Cliente::all();
        $tecnicos = is_admin() ? Tecnico::findAll() : [];
        echo view('servicios/form', [
            'title' => 'Editar servicio',
            'servicio' => $servicio,
            'clientes' => $clientes,
            'tecnicos' => $tecnicos,
            'statusOptions' => $this->statusOptions,
            'action' => '/servicios/edit?id=' . $id,
        ]);
    }

    public function update(): void
    {
        verify_csrf();
        $id = (int) ($_GET['id'] ?? 0);
        $servicio = Servicio::find($id);
        if (!$servicio) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $tecnicoId = is_admin() ? (int) ($_POST['tecnico_id'] ?? 0) : (int) ($servicio['tecnico_id'] ?? 0);
        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'type' => trim($_POST['type'] ?? ''),
            'service_type' => $_POST['service_type'] ?? 'soporte',
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'pendiente',
            'scheduled_at' => $_POST['scheduled_at'] ?? null,
            'estimated_amount' => (float) ($_POST['amount'] ?? 0),
            'tecnico_id' => $tecnicoId > 0 ? $tecnicoId : null,
        ];

        Servicio::update($id, $data);
        set_flash('success', 'Servicio actualizado.');
        header('Location: /servicios/view?id=' . $id);
        exit;
    }

    public function updateStatus(): void
    {
        verify_csrf();
        $id = (int) ($_POST['servicio_id'] ?? 0);
        $status = $_POST['status'] ?? 'pendiente';
        $note = trim($_POST['note'] ?? '');

        Servicio::updateStatus($id, $status);
        ServicioLog::create([
            'servicio_id' => $id,
            'user_id' => (int) $_SESSION['user_id'],
            'status' => $status,
            'note' => $note ?: 'Actualización de estado.',
        ]);

        set_flash('success', 'Estado actualizado.');
        header('Location: /servicios/view?id=' . $id);
        exit;
    }

    public function delete(): void
    {
        verify_csrf();
        if (!is_admin()) {
            require_role('admin');
        }

        $id = (int) ($_POST['id'] ?? 0);
        Servicio::delete($id);
        set_flash('success', 'Servicio eliminado.');
        header('Location: /servicios');
        exit;
    }
}
