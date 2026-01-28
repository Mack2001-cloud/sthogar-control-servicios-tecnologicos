<?php
ob_start();
$budgetAmount = (float) ($servicio['budget_amount'] ?? 0);
$extrasAmount = (float) ($servicio['extras_amount'] ?? 0);
$extrasDescription = $servicio['extras_description'] ?? '';
$equipmentMaterials = $servicio['equipment_materials'] ?? '';
$budgetExceeds = $budgetAmount > (float) ($servicio['estimated_amount'] ?? 0);
$serviceLabel = 'Instalación';
$documentacion = $documentacion ?? [];
$documentacionTotal = $documentacion['total'] ?? '';
$documentacionTotalAmount = (float) $documentacionTotal;
?>
<div class="row g-4">
    <div class="col-12">
        <div class="card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div>
                    <h5 class="mb-1"><?= e($serviceLabel) ?> #<?= e((string) $servicio['id']) ?></h5>
                    <p class="text-muted mb-0">
                        Cliente: <?= e($servicio['cliente_name']) ?> (<?= e($servicio['cliente_email'] ?? 'Sin correo') ?>)
                    </p>
                </div>
                <span class="badge bg-primary-subtle text-primary">
                    <?= e($statusOptions[$servicio['status']] ?? $servicio['status']) ?>
                </span>
            </div>

            <div class="row mt-4 g-3">
                <div class="col-md-6">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                        <h6 class="text-uppercase text-muted mb-0">Datos principales</h6>
                        <a class="btn btn-sm btn-outline-light" href="/servicios/documentacion/view?id=<?= e((string) $servicio['id']) ?>">
                            Hoja de instalación
                        </a>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-5">Tipo</dt>
                        <dd class="col-7"><?= e($servicio['type']) ?></dd>
                        <dt class="col-5">Técnico</dt>
                        <dd class="col-7"><?= e($servicio['tecnico_name'] ?? 'Sin asignar') ?></dd>
                        <dt class="col-5">Programado</dt>
                        <dd class="col-7"><?= e($servicio['scheduled_at'] ?? '-') ?></dd>
                    </dl>
                </div>
            </div>
            <?php if ($equipmentMaterials): ?>
                <div class="mt-3">
                    <strong>Equipos y material a utilizar</strong>
                    <p class="mb-0"><?= e($equipmentMaterials) ?></p>
                </div>
            <?php endif; ?>
            <div class="mt-3">
                <strong>Descripción</strong>
                <p class="mb-0"><?= e($servicio['description']) ?></p>
            </div>
            <?php if ($budgetExceeds && $extrasDescription): ?>
                <div class="mt-3">
                    <strong>Descripción de extras</strong>
                    <p class="mb-0"><?= e($extrasDescription) ?></p>
                </div>
            <?php endif; ?>
            <div class="mt-3">
                <h6 class="text-uppercase text-muted mb-2">Montos</h6>
                <dl class="row mb-0">
                    <dt class="col-6">Total</dt>
                    <dd class="col-6">$<?= e(number_format($documentacionTotalAmount, 2)) ?></dd>
                    <dt class="col-6">Monto pagado</dt>
                    <dd class="col-6">$<?= e(number_format((float) $servicio['amount'], 2)) ?></dd>
                    <dt class="col-6">Presupuesto</dt>
                    <dd class="col-6">$<?= e(number_format($budgetAmount, 2)) ?></dd>
                    <?php if ($budgetExceeds): ?>
                        <dt class="col-6">Extras</dt>
                        <dd class="col-6">$<?= e(number_format($extrasAmount, 2)) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <h6>Presupuesto de instalación</h6>
            <form method="POST" action="/servicios/presupuesto" class="row g-3">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="servicio_id" value="<?= e((string) $servicio['id']) ?>">
                <div class="col-md-6">
                    <label class="form-label">Presupuesto</label>
                    <input type="number" step="0.01" name="budget_amount" class="form-control" value="<?= e(number_format($budgetAmount, 2, '.', '')) ?>">
                </div>
                <?php if ($budgetExceeds): ?>
                    <div class="col-md-6">
                        <label class="form-label">Extras</label>
                        <input type="number" step="0.01" name="extras_amount" class="form-control" value="<?= e(number_format($extrasAmount, 2, '.', '')) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción de extras</label>
                        <textarea name="extras_description" class="form-control" rows="2"><?= e($extrasDescription) ?></textarea>
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-muted mb-0">El presupuesto no excede el monto base. Si se supera, aquí aparecerá el apartado de extras.</p>
                    </div>
                <?php endif; ?>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Guardar presupuesto</button>
                </div>
            </form>
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

</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
