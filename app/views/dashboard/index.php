<?php
ob_start();
$incomeByTechnician = $incomeByTechnician ?? [];
$incomeLabels = array_map(
    static fn(array $row): string => (string) ($row['tecnico_name'] ?? ''),
    $incomeByTechnician
);
$incomeTotals = array_map(
    static fn(array $row): float => (float) ($row['total_income'] ?? 0),
    $incomeByTechnician
);
$incomeLabelsJson = json_encode($incomeLabels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
$incomeTotalsJson = json_encode($incomeTotals, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
?>
<div class="row g-4">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Acciones rápidas</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-outline-light" href="/clientes/create">Nuevo cliente</a>
                    <a class="btn btn-outline-light" href="/servicios/create">Nuevo servicio</a>
                    <a class="btn btn-outline-light" href="/servicios/create?service_type=instalacion">Nueva instalación</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-4">
        <div class="card stat-card stat-card--clientes">
            <div class="card-body">
                <h5 class="card-title">Clientes</h5>
                <p class="display-6 mb-0"><?= e((string) $stats['clientes']) ?></p>
                <span class="text-muted">Total registrados</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card stat-card--servicios">
            <div class="card-body">
                <h5 class="card-title">Servicios</h5>
                <p class="display-6 mb-0"><?= e((string) $stats['servicios']) ?></p>
                <span class="text-muted">Total en proceso</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card stat-card--instalaciones">
            <div class="card-body">
                <h5 class="card-title">Instalaciones</h5>
                <p class="display-6 mb-0"><?= e((string) $stats['instalaciones']) ?></p>
                <span class="text-muted">Total en proceso</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Ingresos por técnico</h6>
                        <p class="mb-0 text-muted small">Totales acumulados según pagos registrados.</p>
                    </div>
                </div>
                <?php if ($incomeByTechnician): ?>
                    <div class="chart-container mt-3">
                        <canvas id="incomeByTechnicianChart" height="120"></canvas>
                    </div>
                <?php else: ?>
                    <p class="text-muted mt-3 mb-0">Aún no hay ingresos registrados para técnicos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if ($incomeByTechnician): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            const initIncomeChart = () => {
                const labels = <?= $incomeLabelsJson ?: '[]' ?>;
                const values = <?= $incomeTotalsJson ?: '[]' ?>;
                const canvas = document.getElementById('incomeByTechnicianChart');

                if (!canvas) {
                    return;
                }

                const chartContext = canvas.getContext('2d');
                const formatter = new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN',
                    maximumFractionDigits: 0,
                });

                new Chart(chartContext, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Ingresos',
                            data: values,
                            backgroundColor: '#3a86ff',
                            borderRadius: 8,
                            borderSkipped: false,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => formatter.format(context.parsed.y || 0),
                                },
                            },
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted'),
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.06)',
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted'),
                                    callback: (value) => formatter.format(value || 0),
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.06)',
                                },
                            },
                        },
                    },
                });
            };
            initIncomeChart();
        })();
    </script>
<?php endif; ?>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
