<?php
$systemName = 'Sistema de Gestión y Control de Servicios Tecnológicos – ST-Hogar';
$shortName = 'ST-Hogar | Control de Servicios';
$year = date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($shortName, ENT_QUOTES, 'UTF-8'); ?></title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
  >
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light">
  <header class="bg-primary text-white py-4 shadow-sm">
    <div class="container">
      <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
        <div>
          <p class="text-uppercase small mb-1"><?php echo htmlspecialchars($shortName, ENT_QUOTES, 'UTF-8'); ?></p>
          <h1 class="display-6 fw-bold mb-0">
            <?php echo htmlspecialchars($systemName, ENT_QUOTES, 'UTF-8'); ?>
          </h1>
        </div>
        <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
          Stack: PHP 8 + MariaDB + Bootstrap 5
        </span>
      </div>
    </div>
  </header>

  <main class="container my-5">
    <section class="row g-4">
      <div class="col-12 col-lg-7">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h2 class="h4 fw-semibold">Panel de Bienvenida</h2>
            <p class="text-muted">
              Este portal concentra la gestión de órdenes, asignaciones y el seguimiento de los servicios
              tecnológicos ofrecidos por ST-Hogar.
            </p>
            <ul class="list-unstyled">
              <li class="d-flex gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary">1</span>
                <span>Registrar solicitudes y priorizar intervenciones.</span>
              </li>
              <li class="d-flex gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary">2</span>
                <span>Controlar inventario y equipos disponibles.</span>
              </li>
              <li class="d-flex gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary">3</span>
                <span>Medir SLA y satisfacción de clientes.</span>
              </li>
            </ul>
            <button class="btn btn-primary" type="button" disabled>Acceder al panel</button>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-5">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h3 class="h5 fw-semibold">Módulos iniciales</h3>
            <div class="d-grid gap-3">
              <div class="module-card p-3 rounded-3 border">
                <h4 class="h6 mb-1">Mesa de ayuda</h4>
                <p class="small text-muted mb-0">Tickets, estados y escalamientos.</p>
              </div>
              <div class="module-card p-3 rounded-3 border">
                <h4 class="h6 mb-1">Planificación</h4>
                <p class="small text-muted mb-0">Agenda y asignaciones de técnicos.</p>
              </div>
              <div class="module-card p-3 rounded-3 border">
                <h4 class="h6 mb-1">Indicadores</h4>
                <p class="small text-muted mb-0">KPIs y reportes en tiempo real.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="row g-4 mt-1">
      <div class="col-12">
        <div class="card border-0 bg-white shadow-sm">
          <div class="card-body">
            <h3 class="h5 fw-semibold">Conexiones a datos</h3>
            <p class="text-muted mb-0">
              La base de datos MariaDB será administrada mediante phpMyAdmin. Aquí se almacenarán clientes,
              activos tecnológicos, contratos y métricas de servicio.
            </p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="border-top py-4 bg-white">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <span class="text-muted">&copy; <?php echo $year; ?> ST-Hogar. Todos los derechos reservados.</span>
      <span class="text-muted">Versión inicial del sistema de control.</span>
    </div>
  </footer>
</body>
</html>
