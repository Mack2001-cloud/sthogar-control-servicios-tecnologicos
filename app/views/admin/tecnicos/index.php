<?php
ob_start();
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <p class="text-muted mb-0">Gestión de técnicos registrados.</p>
    </div>
</div>

<div class="card mt-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Especialidad</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tecnicos as $tecnico): ?>
                <tr>
                    <td><?= e($tecnico['usuario_nombre']) ?></td>
                    <td><?= e($tecnico['usuario_email']) ?></td>
                    <td><?= e($tecnico['telefono']) ?></td>
                    <td><?= e($tecnico['especialidad']) ?></td>
                    <td>
                        <span class="badge <?= (int) $tecnico['activo'] === 1 ? 'text-bg-success' : 'text-bg-secondary' ?>">
                            <?= (int) $tecnico['activo'] === 1 ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-light" href="/admin/tecnicos/edit?id=<?= e((string) $tecnico['id']) ?>">Editar</a>
                            <form method="POST" action="/admin/tecnicos/toggle" onsubmit="return confirm('¿Cambiar estado del técnico?');">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= e((string) $tecnico['id']) ?>">
                                <button class="btn btn-sm btn-outline-warning" type="submit">
                                    <?= (int) $tecnico['activo'] === 1 ? 'Desactivar' : 'Activar' ?>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$tecnicos): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Sin técnicos registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>
