<?php
ob_start();
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
    <form class="d-flex gap-2" method="GET" action="/clientes">
        <input type="text" name="q" class="form-control" placeholder="Buscar cliente" value="<?= e($search) ?>">
        <button class="btn btn-primary" type="submit">Buscar</button>
    </form>
    <div class="d-flex gap-2">
        <?php if (is_admin()): ?>
            <a class="btn btn-outline-light" href="/export/clientes.csv">Exportar CSV</a>
        <?php endif; ?>
        <a class="btn btn-success" href="/clientes/create">Nuevo cliente</a>
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
                <th>Dirección</th>
                <th>Ingresos</th>
                <th>Técnico asignado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= e($cliente['name']) ?></td>
                    <td><?= e($cliente['email']) ?></td>
                    <td><?= e($cliente['phone']) ?></td>
                    <td><?= e($cliente['address']) ?></td>
                    <td>$<?= e(number_format((float) ($cliente['total_income'] ?? 0), 2)) ?></td>
                    <td><?= e($cliente['tecnico_name'] ?? 'Sin asignar') ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-light" href="/clientes/view?id=<?= e((string) $cliente['id']) ?>">Ver</a>
                            <a class="btn btn-sm btn-outline-light" href="/clientes/edit?id=<?= e((string) $cliente['id']) ?>">Editar</a>
                            <?php if (is_admin()): ?>
                                <form method="POST" action="/clientes/delete" onsubmit="return confirm('¿Eliminar cliente?');">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id" value="<?= e((string) $cliente['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$clientes): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Sin clientes registrados.</td>
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
