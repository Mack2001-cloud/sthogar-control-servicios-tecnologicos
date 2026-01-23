# Entregables ST-Hogar

## 1) Árbol del proyecto
```
.
├── app
│   ├── config
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers
│   │   ├── AdjuntosController.php
│   │   ├── AuthController.php
│   │   ├── ClientesController.php
│   │   ├── DashboardController.php
│   │   ├── EquiposController.php
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
│   │   ├── validators.php
│   │   └── view.php
│   ├── middlewares
│   │   └── auth.php
│   ├── models
│   │   ├── Adjunto.php
│   │   ├── Cliente.php
│   │   ├── Equipo.php
│   │   ├── Pago.php
│   │   ├── Servicio.php
│   │   ├── ServicioLog.php
│   │   └── User.php
│   └── views
│       ├── auth
│       │   └── login.php
│       ├── clientes
│       │   ├── form.php
│       │   └── index.php
│       ├── dashboard
│       │   └── index.php
│       ├── equipos
│       │   ├── form.php
│       │   └── index.php
│       ├── layouts
│       │   └── main.php
│       ├── partials
│       │   ├── 403.php
│       │   ├── 404.php
│       │   └── 419.php
│       └── servicios
│           ├── form.php
│           ├── index.php
│           └── view.php
├── database
│   └── sthogar.sql
├── docs
│   └── DELIVERABLES.md
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
