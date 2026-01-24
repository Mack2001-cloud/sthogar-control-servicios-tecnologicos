<?php $home = post_login_route(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="auth-body">
<div class="container py-5 text-center">
    <h1 class="display-5">404</h1>
    <p class="text-muted">La página solicitada no existe.</p>
    <a class="btn btn-primary" href="<?= e($home) ?>">Volver al inicio</a>
</div>
</body>
</html>
