<?php
ob_start();
?>
<form method="POST" action="/admin/tecnicos/create" class="card p-4">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Especialidad</label>
            <select name="especialidad" class="form-select" required>
                <?php foreach ($especialidades as $especialidad): ?>
                    <option value="<?= e($especialidad) ?>">
                        <?= e($especialidad) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Fecha de ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Activo</label>
            <select name="activo" class="form-select">
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Notas</label>
            <textarea name="notas" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-12">
            <hr>
            <h6 class="text-uppercase text-muted mb-3">Acceso del técnico</h6>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" autocomplete="new-password" required>
            <small class="text-muted">Mínimo 8 caracteres.</small>
        </div>
        <div class="col-md-6">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirm" class="form-control" autocomplete="new-password" required>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success" type="submit">Crear técnico</button>
        <a class="btn btn-outline-light" href="/admin/tecnicos">Cancelar</a>
    </div>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>
