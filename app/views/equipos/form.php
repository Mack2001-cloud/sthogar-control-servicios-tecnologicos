<?php
ob_start();
$equipo = $equipo ?? [
    'cliente_id' => '',
    'name' => '',
    'serial_number' => '',
    'location' => '',
    'notes' => '',
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
                    <option value="<?= e((string) $cliente['id']) ?>" <?= (string) $cliente['id'] === (string) $equipo['cliente_id'] ? 'selected' : '' ?>>
                        <?= e($cliente['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nombre del equipo</label>
            <input type="text" name="name" class="form-control" value="<?= e($equipo['name']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Serial</label>
            <input type="text" name="serial_number" class="form-control" value="<?= e($equipo['serial_number']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Ubicación</label>
            <input type="text" name="location" class="form-control" value="<?= e($equipo['location']) ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Notas</label>
            <textarea name="notes" class="form-control" rows="3"><?= e($equipo['notes']) ?></textarea>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success" type="submit">Guardar</button>
        <a class="btn btn-outline-light" href="/equipos">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
