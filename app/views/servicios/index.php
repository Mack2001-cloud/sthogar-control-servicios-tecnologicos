<?php
ob_start();
$statusColors = [
    'pendiente' => 'bg-warning text-dark',
    'proceso' => 'bg-primary',
    'finalizado' => 'bg-success',
    'cancelado' => 'bg-danger',
];
$filterAction = $filterAction ?? '/servicios';
$listHeading = $listHeading ?? 'Listado de servicios';
$createLabel = $createLabel ?? 'Nuevo servicio';
$createLink = $createLink ?? '/servicios/create';
?>
<div class="card p-3 mb-3">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="fw-semibold">Significado de colores:</span>
        <?php foreach ($statusOptions as $value => $label): ?>
            <span class="badge <?= e($statusColors[$value] ?? 'bg-secondary') ?>">
                <?= e($label) ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>
<div class="card p-3">
    <form class="row g-3" method="GET" action="<?= e($filterAction) ?>">
        <div class="col-md-4">
            <label class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= e((string) $cliente['id']) ?>" <?= (string) $filters['cliente_id'] === (string) $cliente['id'] ? 'selected' : '' ?>>
                        <?= e($cliente['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= (string) $filters['status'] === (string) $value ? 'selected' : '' ?>>
                        <?= e($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (!empty($filters['service_type'])): ?>
            <input type="hidden" name="service_type" value="<?= e((string) $filters['service_type']) ?>">
        <?php endif; ?>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <button class="btn btn-primary" type="submit">Filtrar</button>
            <a class="btn btn-outline-light" href="<?= e($filterAction) ?>">Limpiar</a>
        </div>
    </form>
</div>

<div class="d-flex justify-content-between align-items-center mt-4">
    <h2 class="h5 mb-0"><?= e($listHeading) ?></h2>
    <a class="btn btn-success" href="<?= e($createLink) ?>"><?= e($createLabel) ?></a>
</div>

<div class="card mt-3">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
            <tr>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Técnico</th>
                <th>Estado</th>
                <th>Programado</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($servicios as $servicio): ?>
                <tr>
                    <td><?= e($servicio['cliente_name']) ?></td>
                    <td><?= e($servicio['type']) ?></td>
                    <td><?= e($servicio['tecnico_name'] ?? 'Sin asignar') ?></td>
                    <td>
                        <span class="badge <?= e($statusColors[$servicio['status']] ?? 'bg-secondary') ?>">
                            <?= e($statusOptions[$servicio['status']] ?? $servicio['status']) ?>
                        </span>
                    </td>
                    <td><?= e($servicio['scheduled_at'] ?? '-') ?></td>
                    <td>$<?= e(number_format((float) $servicio['amount'], 2)) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-light" href="/servicios/view?id=<?= e((string) $servicio['id']) ?>">Ver</a>
                            <a class="btn btn-sm btn-outline-light" href="/servicios/edit?id=<?= e((string) $servicio['id']) ?>">Editar</a>
                            <?php if (is_admin()): ?>
                                <form method="POST" action="/servicios/delete" onsubmit="return confirm('¿Eliminar servicio?');">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id" value="<?= e((string) $servicio['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$servicios): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Sin servicios.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
