<?php
ob_start();
$tecnico = $tecnico ?? [];
?>
<form method="POST" action="/admin/tecnicos/edit" class="card p-4">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="id" value="<?= e((string) $tecnico['id']) ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= e($tecnico['usuario_nombre']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($tecnico['usuario_email']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= e($tecnico['telefono']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Especialidad</label>
            <select name="especialidad" class="form-select" required>
                <?php foreach ($especialidades as $especialidad): ?>
                    <option value="<?= e($especialidad) ?>" <?= $especialidad === $tecnico['especialidad'] ? 'selected' : '' ?>>
                        <?= e($especialidad) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?= e($tecnico['direccion']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Fecha de ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" value="<?= e($tecnico['fecha_ingreso'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Activo</label>
            <select name="activo" class="form-select">
                <option value="1" <?= (int) $tecnico['activo'] === 1 ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (int) $tecnico['activo'] === 0 ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Notas</label>
            <textarea name="notas" class="form-control" rows="3"><?= e($tecnico['notas'] ?? '') ?></textarea>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success" type="submit">Guardar cambios</button>
        <a class="btn btn-outline-light" href="/admin/tecnicos">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>
