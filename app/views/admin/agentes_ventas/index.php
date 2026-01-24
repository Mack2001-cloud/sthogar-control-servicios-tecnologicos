<?php
ob_start();
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <p class="text-muted mb-0">Gestión de agentes de ventas registrados.</p>
    </div>
    <a class="btn btn-primary" href="/admin/agentes-ventas/create">Nuevo agente de ventas</a>
</div>

<div class="card mt-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($agentes as $agente): ?>
                <tr>
                    <td><?= e($agente['usuario_nombre']) ?></td>
                    <td><?= e($agente['usuario_email']) ?></td>
                    <td><?= e($agente['telefono']) ?></td>
                    <td>
                        <span class="badge <?= (int) $agente['activo'] === 1 ? 'text-bg-success' : 'text-bg-secondary' ?>">
                            <?= (int) $agente['activo'] === 1 ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-light" href="/admin/agentes-ventas/edit?id=<?= e((string) $agente['id']) ?>">Editar</a>
                            <a class="btn btn-sm btn-outline-info" href="/admin/agentes-ventas/edit?id=<?= e((string) $agente['id']) ?>#password-section">Contraseña</a>
                            <form method="POST" action="/admin/agentes-ventas/toggle" onsubmit="return confirm('¿Cambiar estado del agente de ventas?');">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= e((string) $agente['id']) ?>">
                                <button class="btn btn-sm btn-outline-warning" type="submit">
                                    <?= (int) $agente['activo'] === 1 ? 'Desactivar' : 'Activar' ?>
                                </button>
                            </form>
                            <form method="POST" action="/admin/agentes-ventas/delete" onsubmit="return confirm('¿Eliminar este agente de ventas? Esta acción no se puede deshacer.');">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= e((string) $agente['id']) ?>">
                                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$agentes): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Sin agentes de ventas registrados.</td>
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
