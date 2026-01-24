<?php
ob_start();
?>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="card-title">Clientes</h5>
                <p class="display-6 mb-0"><?= e((string) $stats['clientes']) ?></p>
                <span class="text-muted">Total registrados</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="card-title">Servicios</h5>
                <p class="display-6 mb-0"><?= e((string) $stats['servicios']) ?></p>
                <span class="text-muted">Órdenes activas</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="card-title">Equipos</h5>
                <p class="display-6 mb-0"><?= e((string) $stats['equipos']) ?></p>
                <span class="text-muted">Inventario instalado</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Acciones rápidas</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-outline-light" href="/clientes/create">Nuevo cliente</a>
                    <a class="btn btn-outline-light" href="/servicios/create">Nuevo servicio</a>
                    <a class="btn btn-outline-light" href="/equipos/create">Registrar equipo</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Recomendaciones</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">• Mantén la bitácora actualizada para cada servicio.</li>
                    <li class="mb-2">• Adjunta evidencias antes de cerrar un servicio.</li>
                    <li>• Exporta reportes para respaldos periódicos.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
