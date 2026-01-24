<?php
ob_start();
?>
<div class="d-flex justify-content-between align-items-center">
    <h2 class="h5 mb-0">Listado de equipos</h2>
    <a class="btn btn-success" href="/equipos/create">Nuevo equipo</a>
</div>

<div class="card mt-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
            <tr>
                <th>Producto</th>
                <th>Modelo</th>
                <th>Características técnicas</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($equipos as $equipo): ?>
                <tr>
                    <td><?= e($equipo['name']) ?></td>
                    <td><?= e($equipo['model'] ?? 'Sin especificar') ?></td>
                    <td><?= e($equipo['notes'] ?: 'Sin especificar') ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-light" href="/equipos/edit?id=<?= e((string) $equipo['id']) ?>">Editar</a>
                            <?php if (is_admin()): ?>
                                <form method="POST" action="/equipos/delete" onsubmit="return confirm('¿Eliminar equipo?');">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id" value="<?= e((string) $equipo['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$equipos): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">Sin equipos registrados.</td>
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
