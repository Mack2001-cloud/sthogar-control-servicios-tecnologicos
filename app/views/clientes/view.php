<?php
ob_start();
$counts = $counts ?? [];
$totalServicios = (int) ($counts['total_servicios'] ?? 0);
$totalInstalaciones = (int) ($counts['instalaciones'] ?? 0);
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Detalle del cliente</h4>
        <p class="text-muted mb-0">Información general y actividad registrada.</p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-light" href="/clientes">Volver</a>
        <a class="btn btn-primary" href="/clientes/edit?id=<?= e((string) $cliente['id']) ?>">Editar</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-4">
            <h5 class="mb-3"><?= e($cliente['name']) ?></h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Email:</strong>
                    <div><?= e($cliente['email'] ?: '-') ?></div>
                </div>
                <div class="col-md-6">
                    <strong>Teléfono:</strong>
                    <div><?= e($cliente['phone'] ?: '-') ?></div>
                </div>
                <div class="col-12">
                    <strong>Dirección:</strong>
                    <div><?= e($cliente['address'] ?: '-') ?></div>
                </div>
                <div class="col-12">
                    <strong>Referencia:</strong>
                    <div><?= e($cliente['notes'] ?: '-') ?></div>
                </div>
                <div class="col-md-6">
                    <strong>Técnico asignado:</strong>
                    <div><?= e($cliente['tecnico_name'] ?? 'Sin asignar') ?></div>
                </div>
                <div class="col-md-6">
                    <strong>Fecha de registro:</strong>
                    <div><?= e($cliente['created_at'] ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-4 mb-4">
            <h6 class="mb-3">Resumen de actividad</h6>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Servicios registrados</span>
                <span class="fw-semibold"><?= e((string) $totalServicios) ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">Instalaciones</span>
                <span class="fw-semibold"><?= e((string) $totalInstalaciones) ?></span>
            </div>
        </div>
        <div class="card p-4">
            <h6 class="mb-3">Ingresos generados</h6>
            <p class="display-6 mb-0">$<?= e(number_format((float) ($cliente['total_income'] ?? 0), 2)) ?></p>
            <p class="text-muted mb-0">Total recibido por servicios del cliente.</p>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
