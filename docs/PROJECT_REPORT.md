# Reporte de proyecto — ST-Hogar

## 1) Resumen ejecutivo
ST-Hogar es una aplicación interna construida en PHP 8 y MariaDB para gestionar clientes, servicios tecnológicos, equipos instalados, bitácoras, pagos y evidencias. Su objetivo es centralizar el control operativo y administrativo del servicio técnico, con una interfaz web orientada a equipos internos. El proyecto se ejecuta sobre un servidor web (Apache/Nginx) y expone un front-end accesible desde el DocumentRoot configurado en `/public`.【F:README.md†L1-L31】

## 2) Objetivos del proyecto
- Centralizar la información de clientes y servicios tecnológicos en una sola plataforma interna.【F:README.md†L1-L4】
- Facilitar el seguimiento de equipos, pagos, bitácoras y evidencias relacionadas con cada servicio.【F:README.md†L1-L4】
- Proveer un flujo de trabajo web para perfiles administrativos, comerciales y técnicos con acceso controlado por roles.【F:README.md†L29-L31】【F:app/helpers/auth.php†L1-L64】

## 3) Alcance funcional
El alcance funcional se organiza en módulos, definidos por controladores, modelos y vistas principales:
- **Autenticación y control de acceso**: inicio/cierre de sesión y redirección por rol.【F:app/helpers/auth.php†L1-L64】
- **Clientes**: registro y consulta de clientes con búsqueda y sugerencias.【F:README.md†L6-L7】【F:docs/DELIVERABLES.md†L10-L47】
- **Servicios tecnológicos**: administración de servicios y su detalle (vista y formulario).【F:docs/DELIVERABLES.md†L10-L47】
- **Equipos instalados**: alta, edición y listado de equipos vinculados al servicio.【F:docs/DELIVERABLES.md†L10-L47】
- **Pagos**: registro y consulta de pagos asociados a los servicios.【F:docs/DELIVERABLES.md†L10-L47】
- **Adjuntos y evidencias**: carga y gestión de archivos relacionados a servicios y clientes.【F:docs/DELIVERABLES.md†L10-L47】
- **Bitácoras**: seguimiento de eventos del servicio (modelo de logs).【F:docs/DELIVERABLES.md†L33-L39】
- **Dashboard**: panel de visualización inicial para perfiles administrativos/comerciales.【F:docs/DELIVERABLES.md†L10-L20】
- **Exportación**: salida de datos para análisis externo.【F:docs/DELIVERABLES.md†L10-L20】

## 4) Arquitectura y estructura del sistema
El proyecto sigue una organización tipo MVC, con separación entre controladores, modelos, vistas y utilidades:
- **/app/controllers**: lógica de orquestación de cada módulo funcional.【F:docs/DELIVERABLES.md†L7-L20】
- **/app/models**: acceso a datos y entidades del dominio (clientes, servicios, pagos, etc.).【F:docs/DELIVERABLES.md†L30-L39】
- **/app/views**: plantillas de interfaz para cada sección de la aplicación.【F:docs/DELIVERABLES.md†L40-L66】
- **/app/helpers**: utilidades para autenticación, CSRF, validaciones y vistas.【F:docs/DELIVERABLES.md†L21-L29】
- **/app/core**: infraestructura base de routing y acceso a base de datos.【F:docs/DELIVERABLES.md†L21-L24】
- **/public**: punto de entrada web con `index.php` y assets estáticos.【F:docs/DELIVERABLES.md†L67-L73】

## 5) Seguridad y control de acceso
- **Sesiones y roles**: el sistema utiliza sesión para identificar usuarios y roles (`admin`, `ventas`, `técnico`) con reglas de acceso específicas.【F:README.md†L29-L31】【F:app/helpers/auth.php†L1-L64】
- **Protección CSRF**: generación y validación de tokens para prevenir solicitudes maliciosas.【F:app/helpers/csrf.php†L1-L32】
- **Middleware de autenticación**: verificación de acceso antes de cargar módulos sensibles.【F:docs/DELIVERABLES.md†L21-L22】

## 6) Modelo de datos
- **Base de datos**: script SQL listo para importación en phpMyAdmin (`database/sthogar.sql`).【F:docs/DELIVERABLES.md†L75-L77】
- **Entidades principales**: usuarios, clientes, servicios, equipos, pagos, adjuntos y logs de servicio, representadas como modelos en `/app/models`.【F:docs/DELIVERABLES.md†L30-L39】

## 7) Dependencias y requisitos
- PHP 8.
- MariaDB (phpMyAdmin).
- Servidor web (Apache/Nginx).【F:README.md†L20-L23】

## 8) Despliegue y ejecución
1. Importar el script SQL (`database/sthogar.sql`).【F:README.md†L25-L27】
2. Ajustar credenciales de base de datos en `app/config/database.php`.【F:README.md†L26-L27】
3. Configurar el DocumentRoot del servidor web hacia `/public`.【F:README.md†L27-L28】
4. Acceder a la aplicación desde el navegador y usar usuarios de prueba si aplica.【F:README.md†L29-L31】

## 9) Usuarios de prueba
- Admin: `admin@sthogar.test` / `Admin123*`.
- Ventas: `ventas@sthogar.test` / `Ventas123*`.
- Técnico: `tecnico@sthogar.test` / `Tecnico123*`.【F:README.md†L29-L31】

## 10) Entregables
- **Código fuente**: repositorio completo con estructura definida en `docs/DELIVERABLES.md`.【F:docs/DELIVERABLES.md†L1-L73】
- **Script SQL**: `database/sthogar.sql` listo para phpMyAdmin.【F:docs/DELIVERABLES.md†L75-L77】
- **Guía de instalación**: pasos resumidos en el README y en este reporte.【F:README.md†L24-L31】
