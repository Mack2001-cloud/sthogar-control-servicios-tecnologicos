# Entregables ST-Hogar

## 1) Árbol del proyecto
```
.
├── app
│   ├── config
│   │   ├── auth.php
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers
│   │   ├── AdjuntosController.php
│   │   ├── AdminAgentesVentasController.php
│   │   ├── AdminTecnicosController.php
│   │   ├── AuthController.php
│   │   ├── ClientesController.php
│   │   ├── DashboardController.php
│   │   ├── ExportController.php
│   │   ├── PagosController.php
│   │   └── ServiciosController.php
│   ├── core
│   │   ├── Database.php
│   │   └── Router.php
│   ├── helpers
│   │   ├── auth.php
│   │   ├── csrf.php
│   │   ├── flash.php
│   │   ├── session.php
│   │   ├── validators.php
│   │   └── view.php
│   ├── middlewares
│   │   ├── AuthMiddleware.php
│   │   └── RoleMiddleware.php
│   ├── models
│   │   ├── Adjunto.php
│   │   ├── AgenteVenta.php
│   │   ├── Cliente.php
│   │   ├── Equipo.php
│   │   ├── Pago.php
│   │   ├── Servicio.php
│   │   ├── ServicioDocumentacion.php
│   │   ├── ServicioLog.php
│   │   ├── Tecnico.php
│   │   └── User.php
│   └── views
│       ├── admin
│       │   ├── agentes_ventas
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   └── index.php
│       │   └── tecnicos
│       │       ├── create.php
│       │       ├── edit.php
│       │       └── index.php
│       ├── auth
│       │   └── login.php
│       ├── clientes
│       │   ├── form.php
│       │   ├── index.php
│       │   └── view.php
│       ├── dashboard
│       │   └── index.php
│       ├── layouts
│       │   └── main.php
│       ├── partials
│       │   ├── 403.php
│       │   ├── 404.php
│       │   └── 419.php
│       └── servicios
│           ├── documentacion.php
│           ├── form.php
│           ├── index.php
│           ├── instalacion_documentacion.php
│           ├── instalacion_form.php
│           ├── instalacion_view.php
│           └── view.php
├── database
│   └── sthogar.sql
├── docs
│   ├── DELIVERABLES.md
│   └── PROJECT_REPORT.md
├── public
│   ├── .htaccess
│   ├── index.php
│   └── assets
│       └── css
│           └── styles.css
├── storage
│   └── uploads
│       ├── .gitignore
│       └── .htaccess
└── README.md
```

## 2) Código completo
El código completo de cada archivo está disponible en el repositorio, siguiendo la estructura anterior.

## 3) Script SQL
- `database/sthogar.sql` (listo para phpMyAdmin).

## 4) Pasos para correrlo en XAMPP
1. Copia el proyecto dentro de `htdocs`.
2. Configura Apache para apuntar el DocumentRoot a `/public` (o usa un VirtualHost).
3. Crea la base de datos importando `database/sthogar.sql` en phpMyAdmin.
4. Ajusta credenciales en `app/config/database.php` si es necesario.
5. Accede a `http://localhost` (o al VirtualHost configurado) y usa los usuarios de prueba del README.
