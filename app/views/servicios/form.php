<?php
ob_start();
$servicio = $servicio ?? [
    'cliente_id' => '',
    'type' => '',
    'description' => '',
    'status' => 'nuevo',
    'scheduled_at' => '',
    'amount' => 0,
];
?>
<form method="POST" action="<?= e($action) ?>" class="card p-4">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
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
            <label class="form-label">Tipo de servicio</label>
            <select name="type" class="form-select" required>
                <?php foreach (['CCTV', 'Automatización', 'Red', 'Soporte', 'POS', 'Ventas'] as $type): ?>
                    <option value="<?= e($type) ?>" <?= $servicio['type'] === $type ? 'selected' : '' ?>><?= e($type) ?></option>
                <?php endforeach; ?>
            </select>
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
        <div class="col-md-6">
            <label class="form-label">Monto estimado</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="<?= e((string) $servicio['amount']) ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-control" rows="3"><?= e($servicio['description']) ?></textarea>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success" type="submit">Guardar</button>
        <a class="btn btn-outline-light" href="/servicios">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
