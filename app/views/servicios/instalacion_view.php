<?php
ob_start();
$budgetAmount = (float) ($servicio['budget_amount'] ?? 0);
$extrasAmount = (float) ($servicio['extras_amount'] ?? 0);
$extrasDescription = $servicio['extras_description'] ?? '';
$equipmentMaterials = $servicio['equipment_materials'] ?? '';
$budgetExceeds = $budgetAmount > (float) ($servicio['estimated_amount'] ?? 0);
$serviceLabel = 'Instalación';
$documentacion = $documentacion ?? [];
$documentacionItems = $documentacion['items'] ?? [];
$documentacionItems = array_pad($documentacionItems, 8, [
    'concept' => '',
    'unit' => '',
    'quantity' => '',
    'unit_price' => '',
    'amount' => '',
]);
$documentacionFecha = $documentacion['fecha'] ?? ($servicio['scheduled_at'] ?? '');
$documentacionResponsable = $documentacion['responsable_venta'] ?? ($servicio['tecnico_name'] ?? '');
$documentacionTotal = $documentacion['total'] ?? '';
?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card p-4 mb-4">
            <h5><?= e($serviceLabel) ?> #<?= e((string) $servicio['id']) ?></h5>
            <p class="text-muted">Cliente: <?= e($servicio['cliente_name']) ?> (<?= e($servicio['cliente_email'] ?? '') ?>)</p>
            <div class="row">
                <div class="col-md-6"><strong>Tipo:</strong> <?= e($servicio['type']) ?></div>
                <div class="col-md-6"><strong>Técnico:</strong> <?= e($servicio['tecnico_name'] ?? 'Sin asignar') ?></div>
                <div class="col-md-6"><strong>Estado:</strong> <?= e($statusOptions[$servicio['status']] ?? $servicio['status']) ?></div>
                <div class="col-md-6"><strong>Programado:</strong> <?= e($servicio['scheduled_at'] ?? '-') ?></div>
                <div class="col-md-6"><strong>Monto estimado:</strong> $<?= e(number_format((float) $servicio['estimated_amount'], 2)) ?></div>
                <div class="col-md-6"><strong>Monto pagado:</strong> $<?= e(number_format((float) $servicio['amount'], 2)) ?></div>
            <div class="col-md-6"><strong>Presupuesto:</strong> $<?= e(number_format($budgetAmount, 2)) ?></div>
            <?php if ($budgetExceeds): ?>
                <div class="col-md-6"><strong>Extras:</strong> $<?= e(number_format($extrasAmount, 2)) ?></div>
            <?php endif; ?>
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
                        <p class="text-muted mb-0">El presupuesto no excede el monto estimado. Si se supera, aquí aparecerá el apartado de extras.</p>
                    </div>
                <?php endif; ?>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Guardar presupuesto</button>
                </div>
            </form>
        </div>

        <div class="card p-4 mb-4">
            <h6>Hoja de instalación</h6>
            <form method="POST" action="/servicios/documentacion" class="mt-3" data-documentation-form>
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="servicio_id" value="<?= e((string) $servicio['id']) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="document_date" class="form-control" value="<?= e($documentacionFecha) ?>">
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-dark table-sm align-middle">
                        <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Concepto</th>
                            <th style="width: 120px;">Unidad</th>
                            <th style="width: 120px;">Cantidad</th>
                            <th style="width: 140px;">P. unitario</th>
                            <th style="width: 140px;">Importe</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($documentacionItems as $index => $item): ?>
                            <tr data-doc-row>
                                <td class="text-muted"><?= e((string) ($index + 1)) ?></td>
                                <td>
                                    <input type="text" name="concept[]" class="form-control form-control-sm" value="<?= e($item['concept'] ?? '') ?>">
                                </td>
                                <td>
                                    <input type="text" name="unit[]" class="form-control form-control-sm" value="<?= e($item['unit'] ?? '') ?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="quantity[]" class="form-control form-control-sm" value="<?= e($item['quantity'] ?? '') ?>" data-doc-quantity>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="unit_price[]" class="form-control form-control-sm" value="<?= e($item['unit_price'] ?? '') ?>" data-doc-unit-price>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="amount[]" class="form-control form-control-sm" value="<?= e($item['amount'] ?? '') ?>" data-doc-amount>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mb-2">Precios sujetos a cambio sin previo aviso.</p>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Total</label>
                        <input type="number" step="0.01" name="document_total" class="form-control" value="<?= e((string) $documentacionTotal) ?>" data-doc-total>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Responsable venta</label>
                        <input type="text" name="document_responsable" class="form-control" value="<?= e($documentacionResponsable) ?>">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary" type="submit">Guardar hoja general</button>
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
<script>
    (() => {
        const form = document.querySelector('[data-documentation-form]');
        if (!form) {
            return;
        }

        const rows = Array.from(form.querySelectorAll('[data-doc-row]'));
        const totalInput = form.querySelector('[data-doc-total]');

        const parseValue = (value) => {
            if (typeof value !== 'string') {
                return 0;
            }
            const normalized = value.replace(',', '.');
            const number = parseFloat(normalized);
            return Number.isFinite(number) ? number : 0;
        };

        const formatValue = (value) => (Number.isFinite(value) ? value.toFixed(2) : '');

        const updateTotal = () => {
            if (!totalInput) {
                return;
            }
            const total = rows.reduce((sum, row) => {
                const amountInput = row.querySelector('[data-doc-amount]');
                return sum + parseValue(amountInput?.value ?? '');
            }, 0);
            totalInput.value = total ? formatValue(total) : '';
        };

        rows.forEach((row) => {
            const quantityInput = row.querySelector('[data-doc-quantity]');
            const unitPriceInput = row.querySelector('[data-doc-unit-price]');
            const amountInput = row.querySelector('[data-doc-amount]');

            const updateRowAmount = () => {
                if (!amountInput) {
                    return;
                }
                const quantity = parseValue(quantityInput?.value ?? '');
                const unitPrice = parseValue(unitPriceInput?.value ?? '');
                if ((quantityInput?.value ?? '') !== '' || (unitPriceInput?.value ?? '') !== '') {
                    amountInput.value = formatValue(quantity * unitPrice);
                }
                updateTotal();
            };

            quantityInput?.addEventListener('input', updateRowAmount);
            unitPriceInput?.addEventListener('input', updateRowAmount);
            amountInput?.addEventListener('input', updateTotal);
        });

        updateTotal();
    })();
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
