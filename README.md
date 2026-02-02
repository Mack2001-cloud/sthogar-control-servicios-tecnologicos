# Sistema de Gestión y Control de Servicios Tecnológicos – ST-Hogar

Aplicación interna en PHP 8 + MariaDB para controlar clientes, servicios tecnológicos, equipos instalados, bitácoras, pagos y evidencias.

## Funcionalidades
- Autenticación con roles (admin, ventas, técnico) y control de acceso por módulo.
- Gestión de clientes con búsqueda rápida y sugerencias.
- Registro de servicios e instalaciones con detalle técnico y documentación.
- Bitácoras, pagos y adjuntos asociados a cada servicio.
- Gestión de técnicos y agentes de ventas (solo admin).
- Exportación de clientes y servicios en CSV.

## Estructura
```
/app
  /config
    auth.php
    config.php
    database.php
  /controllers
    AdjuntosController.php
    AdminAgentesVentasController.php
    AdminTecnicosController.php
    AuthController.php
    ClientesController.php
    DashboardController.php
    ExportController.php
    PagosController.php
    ServiciosController.php
  /core
    Database.php
    Router.php
  /helpers
    auth.php
    csrf.php
    flash.php
    session.php
    validators.php
    view.php
  /middlewares
    AuthMiddleware.php
    RoleMiddleware.php
  /models
    Adjunto.php
    AgenteVenta.php
    Cliente.php
    Equipo.php
    Pago.php
    Servicio.php
    ServicioDocumentacion.php
    ServicioLog.php
    Tecnico.php
    User.php
  /views
    /admin
      /agentes_ventas
        create.php
        edit.php
        index.php
      /tecnicos
        create.php
        edit.php
        index.php
    /auth
      login.php
    /clientes
      form.php
      index.php
      view.php
    /dashboard
      index.php
    /layouts
      main.php
    /partials
      403.php
      404.php
      419.php
    /servicios
      documentacion.php
      form.php
      index.php
      instalacion_documentacion.php
      instalacion_form.php
      instalacion_view.php
      view.php
/database
  sthogar.sql
/docs
  DELIVERABLES.md
  PROJECT_REPORT.md
/public
  index.php
  .htaccess
  /assets
/storage
  /uploads
README.md
```

## Requisitos
- PHP 8
- MariaDB (phpMyAdmin)
- Servidor web (Apache/Nginx)

## Configuración rápida
1. Importa el script SQL: `database/sthogar.sql`.
2. Ajusta credenciales en `app/config/database.php`.
3. Configura el DocumentRoot apuntando a `/public`.

## Usuarios de prueba
- Admin: `admin@sthogar.test` / `Admin123*`
- Ventas: `ventas@sthogar.test` / `Ventas123*`
- Técnico: `tecnico@sthogar.test` / `Tecnico123*`

## Documentación
- Reporte de proyecto: `docs/PROJECT_REPORT.md`.
