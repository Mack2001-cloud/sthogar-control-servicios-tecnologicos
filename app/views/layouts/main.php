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
    <script>
        (() => {
            const storageKey = 'sthogar-theme';
            const root = document.documentElement;
            const storedTheme = localStorage.getItem(storageKey);
            if (storedTheme === 'light' || storedTheme === 'dark') {
                root.setAttribute('data-theme', storedTheme);
            } else {
                root.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>
<body class="app-body">
<div class="d-flex">
    <nav class="sidebar">
        <div class="sidebar-brand">
            <span class="badge text-bg-primary">ST-Hogar</span>
            <p class="small text-uppercase text-muted mb-0">Control de servicios</p>
        </div>
        <ul class="nav flex-column">
            <?php if (is_admin()): ?>
                <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="/clientes">Clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="/servicios">Servicios</a></li>
            <li class="nav-item"><a class="nav-link" href="/instalaciones">Instalaciones</a></li>
            <li class="nav-item"><a class="nav-link" href="/equipos">Equipos</a></li>
            <?php if (is_admin()): ?>
                <li class="nav-item"><a class="nav-link" href="/admin/tecnicos">Técnicos</a></li>
            <?php endif; ?>
            <?php if (is_admin()): ?>
                <li class="nav-item"><a class="nav-link" href="/export/clientes.csv">Exportar clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="/export/servicios.csv">Exportar servicios</a></li>
            <?php endif; ?>
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
                <button class="btn btn-outline-light btn-sm" id="theme-toggle" type="button">
                    Cambiar tema
                </button>
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
<script>
    (() => {
        const storageKey = 'sthogar-theme';
        const root = document.documentElement;
        const toggleButton = document.getElementById('theme-toggle');

        if (!toggleButton) {
            return;
        }

        const getCurrentTheme = () => root.getAttribute('data-theme') || 'dark';
        const setButtonLabel = (theme) => {
            const nextLabel = theme === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro';
            toggleButton.textContent = nextLabel;
            toggleButton.setAttribute('aria-pressed', theme === 'light' ? 'true' : 'false');
        };

        setButtonLabel(getCurrentTheme());

        toggleButton.addEventListener('click', () => {
            const currentTheme = getCurrentTheme();
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', nextTheme);
            localStorage.setItem(storageKey, nextTheme);
            setButtonLabel(nextTheme);
        });
    })();
</script>
</body>
</html>
