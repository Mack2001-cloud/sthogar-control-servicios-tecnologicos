<?php
ob_start();
$cliente = $cliente ?? [
    'name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'notes' => '',
    'tecnico_id' => null,
    'tecnico_name' => '',
];
?>
<form method="POST" action="<?= e($action) ?>" class="card p-4">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="<?= e($cliente['name']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input type="email" name="email" class="form-control" value="<?= e($cliente['email']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="<?= e($cliente['phone']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input type="text" name="address" class="form-control" value="<?= e($cliente['address']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Técnico asignado</label>
            <?php if (is_admin()): ?>
                <select name="tecnico_id" class="form-select">
                    <option value="">Sin asignar</option>
                    <?php foreach (($tecnicos ?? []) as $tecnico): ?>
                        <option value="<?= e((string) $tecnico['id']) ?>" <?= (string) $tecnico['id'] === (string) ($cliente['tecnico_id'] ?? '') ? 'selected' : '' ?>>
                            <?= e($tecnico['usuario_nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="text" class="form-control" value="<?= e($cliente['tecnico_name'] ?: 'Sin asignar') ?>" readonly>
                <small class="text-muted">Solo el administrador puede editar el técnico asignado.</small>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <label class="form-label">Notas</label>
            <textarea name="notes" class="form-control" rows="3"><?= e($cliente['notes']) ?></textarea>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success" type="submit">Guardar</button>
        <a class="btn btn-outline-light" href="/clientes">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
