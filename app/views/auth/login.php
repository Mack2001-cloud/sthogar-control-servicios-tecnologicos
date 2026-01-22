<?php
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Login') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="auth-body">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h1 class="h4">ST-Hogar</h1>
                        <p class="text-muted mb-0">Ingreso al panel interno</p>
                    </div>

                    <?php if ($flash): ?>
                        <div class="alert alert-<?= e($flash['type']) ?>">
                            <?= e($flash['message']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/auth/login">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Ingresar</button>
                    </form>
                </div>
            </div>
            <p class="text-center text-muted small mt-3">Admin: admin@sthogar.test / clave: Admin123*</p>
        </div>
    </div>
</div>
</body>
</html>
