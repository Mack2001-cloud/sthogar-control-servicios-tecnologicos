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

## 11) Anexos
### Anexo A — Actividades realizadas durante el desarrollo del proyecto
Durante el desarrollo del proyecto se llevaron a cabo las siguientes actividades, aplicando técnicas de análisis, diseño de software, modelado de datos, programación y validación técnica, de acuerdo con la metodología incremental y las necesidades operativas de la empresa ST-Hogar.

1. **Identificación de procesos en la empresa ST-Hogar.**
   El proyecto inició con la identificación y análisis de los procesos que se realizan en ST-Hogar relacionados con la atención a clientes, registro de servicios tecnológicos, instalación de equipos, seguimiento de mantenimientos y control de pagos. Durante esta etapa se observó el flujo de trabajo operativo, la forma en que se registraba la información y los medios utilizados para su almacenamiento. Esta actividad permitió detectar áreas de oportunidad asociadas al manejo manual de la información, la duplicidad de registros y la dificultad para consultar historiales completos de servicio.
2. **Requerimientos funcionales y no funcionales del sistema.**
   Con base en el análisis previo, se procedió a la definición de los requerimientos funcionales y no funcionales del sistema digital. Los requerimientos funcionales incluyeron el registro, consulta, actualización y control de clientes, servicios, equipos, pagos y evidencias técnicas. Los requerimientos no funcionales consideraron aspectos como seguridad de la información, control de accesos, facilidad de uso, confiabilidad y organización del sistema.
3. **Diseño, selección de tecnologías y herramientas de desarrollo.**
   Posteriormente, se realizó la selección de las tecnologías y herramientas de desarrollo más adecuadas para la creación del sistema digital. Se definió el uso de PHP como lenguaje de programación, MariaDB como gestor de base de datos y un servidor web Apache. Esta selección permitió asegurar que el sistema contara con una estructura tecnológica compatible con la infraestructura disponible en ST-Hogar y con posibilidades de crecimiento futuro.
4. **Análisis de los procesos de servicio técnico.**
   En esta etapa se realizó un análisis detallado de los procesos técnicos y administrativos relacionados con la gestión de servicios tecnológicos. Se documentaron los procedimientos de atención, asignación de técnicos, instalación de equipos y cierre de servicios, lo cual permitió modelar de manera clara las operaciones del sistema y sentar las bases para el diseño de la base de datos y los módulos funcionales.
5. **Diseño y creación de la base de datos.**
   Con la información obtenida, se diseñó y creó la base de datos del sistema. Se estructuraron tablas, relaciones y campos necesarios para almacenar la información de usuarios, clientes, servicios, equipos, pagos, adjuntos y bitácoras de servicio. Esta actividad fue fundamental para garantizar la integridad de los datos, evitar inconsistencias y facilitar la consulta y actualización de la información.
6. **Validación técnica, revisión de riesgos y seguridad de la información.**
   Una vez diseñada la base de datos, se realizó una validación técnica del sistema, considerando posibles riesgos relacionados con el manejo de la información. Se revisaron aspectos de seguridad, control de accesos, manejo de sesiones y protección de datos, con el objetivo de asegurar la confidencialidad e integridad de la información operativa.
7. **Creación del proyecto y estructura de carpetas.**
   Posteriormente, se llevó a cabo la creación del proyecto en el entorno de desarrollo, organizando de manera adecuada la estructura de carpetas, archivos y módulos del sistema. Se definió una arquitectura Modelo–Vista–Controlador (MVC), lo que permitió mantener un orden lógico del código, facilitar el mantenimiento y asegurar una correcta administración del proyecto de software.
8. **Desarrollo del módulo de gestión de clientes.**
   En esta fase se desarrolló el módulo de gestión de clientes, encargado del registro, consulta y actualización de la información de los clientes atendidos por ST-Hogar. Se implementaron funciones que permiten centralizar los datos y mantener un historial actualizado de cada cliente.
9. **Desarrollo del módulo de gestión de servicios tecnológicos.**
   Posteriormente, se desarrolló el módulo de servicios tecnológicos, el cual permite registrar los servicios realizados, asignar técnicos, consultar el estado de cada servicio y visualizar el historial completo de atención, fortaleciendo el control operativo del sistema.
10. **Desarrollo del módulo de equipos instalados.**
    Se desarrolló el módulo encargado de la gestión de equipos instalados, permitiendo asociar los equipos a cada servicio realizado. Este módulo facilita el control técnico y el seguimiento de los equipos instalados en cada cliente.
11. **Integración con la base de datos.**
    Una vez desarrollados los módulos principales, se realizó la integración del sistema con la base de datos. Esta actividad permitió habilitar la comunicación entre la interfaz del sistema y el almacenamiento de la información, asegurando que los datos se guardaran y recuperaran correctamente.
12. **Desarrollo del módulo de pagos.**
    En esta etapa se desarrolló el módulo de pagos, el cual permite registrar y consultar los pagos asociados a los servicios tecnológicos realizados por ST-Hogar, contribuyendo al control administrativo del sistema.
13. **Desarrollo del módulo de evidencias y bitácoras.**
    Se implementaron módulos para la carga de evidencias técnicas y el registro de bitácoras de servicio, permitiendo documentar actividades, eventos y observaciones relacionadas con cada servicio.
14. **Pruebas funcionales, ajustes en formularios y seguridad.**
    Se llevaron a cabo pruebas funcionales del sistema, verificando el correcto funcionamiento de los formularios, módulos y procesos implementados. Se realizaron ajustes necesarios para corregir errores detectados y mejorar la seguridad y estabilidad del sistema.
15. **Entrega del sistema y elaboración del reporte final.**
    Finalmente, se realizó la entrega formal del sistema desarrollado, junto con la elaboración del reporte técnico del proyecto, documentando las actividades realizadas, los resultados obtenidos y las conclusiones del trabajo, concluyendo satisfactoriamente el proceso de estadía profesional.
