<?php
$flash = get_flash();
$user = auth_user();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ST-Hogar') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="app-body">
<div class="d-flex">
    <nav class="sidebar">
        <div class="sidebar-brand">
            <span class="badge text-bg-primary">ST-Hogar</span>
            <p class="small text-uppercase text-muted mb-0">Control de servicios</p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/clientes">Clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="/servicios">Servicios</a></li>
            <li class="nav-item"><a class="nav-link" href="/equipos">Equipos</a></li>
            <li class="nav-item"><a class="nav-link" href="/export/clientes.csv">Exportar clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="/export/servicios.csv">Exportar servicios</a></li>
        </ul>
    </nav>

    <main class="content flex-grow-1">
        <div class="topbar d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h5 mb-0"><?= e($title ?? '') ?></h1>
                <span class="text-muted small">Panel interno</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">Hola, <?= e($user['name'] ?? 'Usuario') ?></span>
                <a class="btn btn-outline-light btn-sm" href="/auth/logout">Salir</a>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> mt-3">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <?= $content ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
