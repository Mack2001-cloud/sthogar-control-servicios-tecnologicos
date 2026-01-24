<?php
ob_start();
$agente = $agente ?? [];
?>
<form method="POST" action="/admin/agentes-ventas/edit" class="card p-4">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="id" value="<?= e((string) $agente['id']) ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= e($agente['usuario_nombre']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= e($agente['usuario_email']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= e($agente['telefono']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?= e($agente['direccion']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Fecha de ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" value="<?= e($agente['fecha_ingreso'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Activo</label>
            <select name="activo" class="form-select">
                <option value="1" <?= (int) $agente['activo'] === 1 ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= (int) $agente['activo'] === 0 ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Notas</label>
            <textarea name="notas" class="form-control" rows="3"><?= e($agente['notas'] ?? '') ?></textarea>
        </div>
        <div class="col-12" id="password-section">
            <hr>
            <h6 class="text-uppercase text-muted mb-3">Contraseña del agente de ventas</h6>
            <p class="text-muted mb-0">Actualiza la contraseña si el agente de ventas necesita nuevos accesos.</p>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="password" class="form-control" autocomplete="new-password">
            <small class="text-muted">Déjalo en blanco para mantener la contraseña actual.</small>
        </div>
        <div class="col-md-6">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirm" class="form-control" autocomplete="new-password">
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success" type="submit">Guardar cambios</button>
        <a class="btn btn-outline-light" href="/admin/agentes-ventas">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>
