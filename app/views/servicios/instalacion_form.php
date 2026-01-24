<?php
ob_start();
$servicio = array_merge([
    'cliente_id' => '',
    'type' => '',
    'service_type' => 'instalacion',
    'description' => '',
    'equipment_materials' => '',
    'status' => 'pendiente',
    'scheduled_at' => '',
    'estimated_amount' => 0,
    'budget_amount' => 0,
    'tecnico_id' => null,
    'tecnico_name' => '',
], $servicio ?? []);
$cancelLink = $cancelLink ?? '/instalaciones';
$equipmentCost = (float) ($servicio['budget_amount'] ?? 0);
$installationCost = (float) ($servicio['estimated_amount'] ?? 0);
?>
<form method="POST" action="<?= e($action) ?>" class="card p-4">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="service_type" value="instalacion">

    <div class="row g-4">
        <div class="col-12">
            <h6 class="text-uppercase text-muted mb-2">Datos de la instalación</h6>
        </div>
        <div class="col-md-6">
            <label class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= e((string) $cliente['id']) ?>" <?= (string) $cliente['id'] === (string) $servicio['cliente_id'] ? 'selected' : '' ?>>
                        <?= e($cliente['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tipo de instalación</label>
            <select name="type" class="form-select" required>
                <?php foreach (['CCTV', 'Automatización', 'Red', 'Soporte', 'POS', 'Ventas'] as $type): ?>
                    <option value="<?= e($type) ?>" <?= $servicio['type'] === $type ? 'selected' : '' ?>><?= e($type) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Técnico asignado</label>
            <?php if (is_admin()): ?>
                <select name="tecnico_id" class="form-select">
                    <option value="">Sin asignar</option>
                    <?php foreach (($tecnicos ?? []) as $tecnico): ?>
                        <option value="<?= e((string) $tecnico['id']) ?>" <?= (string) $tecnico['id'] === (string) ($servicio['tecnico_id'] ?? '') ? 'selected' : '' ?>>
                            <?= e($tecnico['usuario_nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="text" class="form-control" value="<?= e($servicio['tecnico_name'] ?: 'Sin asignar') ?>" readonly>
                <small class="text-muted">Solo el administrador puede editar el técnico asignado.</small>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= (string) $servicio['status'] === (string) $value ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Fecha programada</label>
            <input type="date" name="scheduled_at" class="form-control" value="<?= e($servicio['scheduled_at']) ?>">
        </div>

        <div class="col-12">
            <h6 class="text-uppercase text-muted mb-2 mt-2">Equipo y costos</h6>
        </div>
        <div class="col-md-6">
            <label class="form-label">Equipo a instalar</label>
            <input type="text" name="equipment_materials" class="form-control" placeholder="Ej. Kit CCTV 8 canales" value="<?= e($servicio['equipment_materials']) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Costo del equipo</label>
            <input type="number" step="0.01" name="equipment_cost" class="form-control" value="<?= e(number_format($equipmentCost, 2, '.', '')) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Costo de instalación</label>
            <input type="number" step="0.01" name="installation_cost" class="form-control" value="<?= e(number_format($installationCost, 2, '.', '')) ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-control" rows="3"><?= e($servicio['description']) ?></textarea>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success" type="submit">Guardar instalación</button>
        <a class="btn btn-outline-light" href="<?= e($cancelLink) ?>">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
