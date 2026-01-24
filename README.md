# Sistema de Gestión y Control de Servicios Tecnológicos – ST-Hogar

Aplicación interna en PHP 8 + MariaDB para controlar clientes, servicios tecnológicos, equipos instalados, bitácoras, pagos y evidencias.

## Estructura
```
/app
  /config
  /controllers
  /models
  /views
  /helpers
  /middlewares
  /core
/database
  sthogar.sql
/docs
  DELIVERABLES.md
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
