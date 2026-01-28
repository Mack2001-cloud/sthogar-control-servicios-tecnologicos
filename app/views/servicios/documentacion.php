<?php
ob_start();
$serviceLabel = 'Servicio';
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
$documentacionTotal = $documentacion['total'] ?? '';
?>
<div class="row g-4">
    <div class="col-12">
        <div class="card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div>
                    <h5 class="mb-1">Hoja de servicio</h5>
                    <p class="text-muted mb-0">
                        <?= e($serviceLabel) ?> #<?= e((string) $servicio['id']) ?> · Cliente: <?= e($servicio['cliente_name']) ?>
                    </p>
                </div>
                <a class="btn btn-outline-light" href="/servicios/view?id=<?= e((string) $servicio['id']) ?>">
                    Volver a detalles
                </a>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <form method="POST" action="/servicios/documentacion" data-documentation-form>
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
            </form>
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
