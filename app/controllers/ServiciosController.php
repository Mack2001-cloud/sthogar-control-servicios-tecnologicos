<?php

namespace App\Controllers;

use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\ServicioLog;
use App\Models\Pago;
use App\Models\Adjunto;
use App\Models\Tecnico;
use App\Models\ServicioDocumentacion;

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
        $view = $defaultServiceType === 'instalacion' ? 'servicios/instalacion_form' : 'servicios/form';
        $title = $defaultServiceType === 'instalacion' ? 'Nueva instalación' : 'Nuevo servicio';
        $cancelLink = $defaultServiceType === 'instalacion' ? '/instalaciones' : '/servicios';

        echo view($view, [
            'title' => $title,
            'servicio' => [
                'service_type' => $defaultServiceType,
            ],
            'clientes' => $clientes,
            'tecnicos' => $tecnicos,
            'statusOptions' => $this->statusOptions,
            'action' => '/servicios/create',
            'cancelLink' => $cancelLink,
        ]);
    }

    public function create(): void
    {
        verify_csrf();
        $tecnicoId = is_admin() ? (int) ($_POST['tecnico_id'] ?? 0) : 0;
        $serviceType = $_POST['service_type'] ?? 'soporte';
        $estimatedAmount = (float) ($_POST['amount'] ?? 0);
        $budgetAmount = (float) ($_POST['budget_amount'] ?? 0);

        if ($serviceType === 'instalacion') {
            $estimatedAmount = (float) ($_POST['installation_cost'] ?? $estimatedAmount);
            $budgetAmount = (float) ($_POST['equipment_cost'] ?? $budgetAmount);
        }

        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'type' => trim($_POST['type'] ?? ''),
            'service_type' => $serviceType,
            'description' => trim($_POST['description'] ?? ''),
            'equipment_materials' => trim($_POST['equipment_materials'] ?? ''),
            'status' => $_POST['status'] ?? 'pendiente',
            'scheduled_at' => $_POST['scheduled_at'] ?? null,
            'estimated_amount' => $estimatedAmount,
            'budget_amount' => $budgetAmount,
            'tecnico_id' => $tecnicoId > 0 ? $tecnicoId : null,
        ];

        if (!$data['cliente_id'] || !required($data['type'])) {
            set_flash('danger', 'Cliente y tipo son obligatorios.');
            $redirect = $serviceType === 'instalacion'
                ? '/servicios/create?service_type=instalacion'
                : '/servicios/create';
            header('Location: ' . $redirect);
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
        $documentacion = ServicioDocumentacion::findByServicio($id);

        $isInstalacion = ($servicio['service_type'] ?? '') === 'instalacion';
        $view = $isInstalacion ? 'servicios/instalacion_view' : 'servicios/view';
        $title = $isInstalacion ? 'Detalle de instalación' : 'Detalles de servicio';

        echo view($view, [
            'title' => $title,
            'servicio' => $servicio,
            'equipos' => $equipos,
            'logs' => $logs,
            'pagos' => $pagos,
            'adjuntos' => $adjuntos,
            'totalPagos' => $totalPagos,
            'statusOptions' => $this->statusOptions,
            'documentacion' => $documentacion,
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
        $view = ($servicio['service_type'] ?? '') === 'instalacion' ? 'servicios/instalacion_form' : 'servicios/form';
        $title = ($servicio['service_type'] ?? '') === 'instalacion' ? 'Editar instalación' : 'Editar servicio';
        $cancelLink = ($servicio['service_type'] ?? '') === 'instalacion' ? '/instalaciones' : '/servicios';

        echo view($view, [
            'title' => $title,
            'servicio' => $servicio,
            'clientes' => $clientes,
            'tecnicos' => $tecnicos,
            'statusOptions' => $this->statusOptions,
            'action' => '/servicios/edit?id=' . $id,
            'cancelLink' => $cancelLink,
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
        $serviceType = $_POST['service_type'] ?? ($servicio['service_type'] ?? 'soporte');
        $estimatedAmount = (float) ($_POST['amount'] ?? 0);
        $budgetAmount = (float) ($_POST['budget_amount'] ?? ($servicio['budget_amount'] ?? 0));

        if ($serviceType === 'instalacion') {
            $estimatedAmount = (float) ($_POST['installation_cost'] ?? $estimatedAmount);
            $budgetAmount = (float) ($_POST['equipment_cost'] ?? $budgetAmount);
        }

        $data = [
            'cliente_id' => (int) ($_POST['cliente_id'] ?? 0),
            'type' => trim($_POST['type'] ?? ''),
            'service_type' => $serviceType,
            'description' => trim($_POST['description'] ?? ''),
            'equipment_materials' => trim($_POST['equipment_materials'] ?? ''),
            'status' => $_POST['status'] ?? 'pendiente',
            'scheduled_at' => $_POST['scheduled_at'] ?? null,
            'estimated_amount' => $estimatedAmount,
            'budget_amount' => $budgetAmount,
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

    public function updateBudget(): void
    {
        verify_csrf();
        $id = (int) ($_POST['servicio_id'] ?? 0);
        $servicio = Servicio::find($id);
        if (!$servicio) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $budgetAmount = (float) ($_POST['budget_amount'] ?? 0);
        $extrasAmount = (float) ($_POST['extras_amount'] ?? 0);
        $extrasDescription = trim($_POST['extras_description'] ?? '');

        if ($budgetAmount <= (float) ($servicio['estimated_amount'] ?? 0)) {
            $extrasAmount = 0;
            $extrasDescription = '';
        }

        Servicio::updateBudget($id, [
            'budget_amount' => $budgetAmount,
            'extras_amount' => $extrasAmount,
            'extras_description' => $extrasDescription,
        ]);

        set_flash('success', 'Presupuesto actualizado.');
        header('Location: /servicios/view?id=' . $id);
        exit;
    }

    public function updateDocumentation(): void
    {
        verify_csrf();
        $id = (int) ($_POST['servicio_id'] ?? 0);
        $servicio = Servicio::find($id);
        if (!$servicio) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $concepts = $_POST['concept'] ?? [];
        $units = $_POST['unit'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $unitPrices = $_POST['unit_price'] ?? [];
        $amounts = $_POST['amount'] ?? [];
        $maxRows = max(count($concepts), count($units), count($quantities), count($unitPrices), count($amounts), 8);
        $items = [];

        for ($i = 0; $i < $maxRows; $i++) {
            $items[] = [
                'concept' => trim((string) ($concepts[$i] ?? '')),
                'unit' => trim((string) ($units[$i] ?? '')),
                'quantity' => trim((string) ($quantities[$i] ?? '')),
                'unit_price' => trim((string) ($unitPrices[$i] ?? '')),
                'amount' => trim((string) ($amounts[$i] ?? '')),
            ];
        }

        ServicioDocumentacion::upsertByServicio($id, [
            'fecha' => $_POST['document_date'] ?? null,
            'items' => $items,
            'responsable_venta' => trim((string) ($_POST['document_responsable'] ?? '')),
            'total' => (float) ($_POST['document_total'] ?? 0),
        ]);

        set_flash('success', 'Hoja general actualizada.');
        header('Location: /servicios/view?id=' . $id);
        exit;
    }

    public function addEquipo(): void
    {
        verify_csrf();
        $servicioId = (int) ($_POST['servicio_id'] ?? 0);
        $servicio = Servicio::find($servicioId);
        if (!$servicio) {
            http_response_code(404);
            echo view('partials/404');
            return;
        }

        $equipoName = trim((string) ($_POST['equipment_name'] ?? ''));
        if ($equipoName === '') {
            set_flash('danger', 'El nombre del equipo es obligatorio.');
            header('Location: /servicios/view?id=' . $servicioId);
            exit;
        }

        Equipo::create([
            'cliente_id' => (int) $servicio['cliente_id'],
            'servicio_id' => $servicioId,
            'name' => $equipoName,
            'brand' => 'Sin especificar',
            'model' => 'Sin especificar',
            'serial_number' => '',
            'location' => '',
            'notes' => '',
        ]);

        set_flash('success', 'Equipo agregado al servicio.');
        header('Location: /servicios/view?id=' . $servicioId);
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
