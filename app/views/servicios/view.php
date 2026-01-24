<?php
ob_start();
?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card p-4 mb-4">
            <h5>Servicio #<?= e((string) $servicio['id']) ?></h5>
            <p class="text-muted">Cliente: <?= e($servicio['cliente_name']) ?> (<?= e($servicio['cliente_email'] ?? '') ?>)</p>
            <div class="row">
                <div class="col-md-6"><strong>Tipo:</strong> <?= e($servicio['type']) ?></div>
                <div class="col-md-6"><strong>Técnico:</strong> <?= e($servicio['tecnico_name'] ?? 'Sin asignar') ?></div>
                <div class="col-md-6"><strong>Estado:</strong> <?= e($statusOptions[$servicio['status']] ?? $servicio['status']) ?></div>
                <div class="col-md-6"><strong>Programado:</strong> <?= e($servicio['scheduled_at'] ?? '-') ?></div>
                <div class="col-md-6"><strong>Monto estimado:</strong> $<?= e(number_format((float) $servicio['estimated_amount'], 2)) ?></div>
                <div class="col-md-6"><strong>Monto pagado:</strong> $<?= e(number_format((float) $servicio['amount'], 2)) ?></div>
            </div>
            <div class="mt-3">
                <strong>Descripción</strong>
                <p class="mb-0"><?= e($servicio['description']) ?></p>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <h6>Bitácora de servicio</h6>
            <ul class="list-group list-group-flush">
                <?php foreach ($logs as $log): ?>
                    <li class="list-group-item bg-transparent text-light">
                        <div class="d-flex justify-content-between">
                            <span><strong><?= e($log['status']) ?></strong> - <?= e($log['note']) ?></span>
                            <span class="text-muted small"><?= e($log['created_at']) ?></span>
                        </div>
                        <small class="text-muted"><?= e($log['user_name'] ?? 'Sistema') ?></small>
                    </li>
                <?php endforeach; ?>
                <?php if (!$logs): ?>
                    <li class="list-group-item bg-transparent text-muted">Sin registros en bitácora.</li>
                <?php endif; ?>
            </ul>
            <form method="POST" action="/servicios/status" class="mt-3">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="servicio_id" value="<?= e((string) $servicio['id']) ?>">
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <?php foreach ($statusOptions as $value => $label): ?>
                                <option value="<?= e($value) ?>" <?= (string) $servicio['status'] === (string) $value ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="note" class="form-control" placeholder="Nota de bitácora">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" type="submit">Actualizar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card p-4 mb-4">
            <h6>Pagos registrados</h6>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Monto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pagos as $pago): ?>
                        <tr>
                            <td><?= e($pago['paid_at']) ?></td>
                            <td><?= e($pago['method']) ?></td>
                            <td>$<?= e(number_format((float) $pago['amount'], 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$pagos): ?>
                        <tr>
                            <td colspan="3" class="text-muted text-center">Sin pagos registrados.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-muted">Total pagado: $<?= e(number_format($totalPagos, 2)) ?></p>
            <form method="POST" action="/pagos/create" class="row g-2">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="servicio_id" value="<?= e((string) $servicio['id']) ?>">
                <div class="col-md-4">
                    <input type="date" name="paid_at" class="form-control" value="<?= e(date('Y-m-d')) ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="method" class="form-control" placeholder="Método">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <input type="number" step="0.01" name="amount" class="form-control" placeholder="Monto">
                    <button class="btn btn-success" type="submit">Agregar</button>
                </div>
            </form>
        </div>

        <div class="card p-4">
            <h6>Adjuntos y evidencias</h6>
            <ul class="list-group list-group-flush mb-3">
                <?php foreach ($adjuntos as $adjunto): ?>
                    <li class="list-group-item bg-transparent text-light">
                        <?= e($adjunto['original_name']) ?>
                        <small class="text-muted">(<?= e($adjunto['mime_type']) ?>, <?= e((string) $adjunto['size']) ?> bytes)</small>
                    </li>
                <?php endforeach; ?>
                <?php if (!$adjuntos): ?>
                    <li class="list-group-item bg-transparent text-muted">Sin adjuntos.</li>
                <?php endif; ?>
            </ul>
            <form method="POST" action="/adjuntos/upload" enctype="multipart/form-data" class="row g-2">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="servicio_id" value="<?= e((string) $servicio['id']) ?>">
                <div class="col-md-8">
                    <input type="file" name="adjunto" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-light w-100" type="submit">Subir evidencia</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-4">
            <h6>Equipos instalados</h6>
            <ul class="list-group list-group-flush">
                <?php foreach ($equipos as $equipo): ?>
                    <li class="list-group-item bg-transparent text-light">
                        <strong><?= e($equipo['name']) ?></strong><br>
                        <small class="text-muted">Serial: <?= e($equipo['serial_number']) ?></small>
                    </li>
                <?php endforeach; ?>
                <?php if (!$equipos): ?>
                    <li class="list-group-item bg-transparent text-muted">Sin equipos registrados para el cliente.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
