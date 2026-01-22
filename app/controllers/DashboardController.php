<?php

namespace App\Controllers;

use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Equipo;

class DashboardController
{
    public function index(): void
    {
        $stats = [
            'clientes' => Cliente::countAll(),
            'servicios' => Servicio::countAll(),
            'equipos' => Equipo::countAll(),
        ];

        echo view('dashboard/index', [
            'title' => 'Dashboard',
            'stats' => $stats,
        ]);
    }
}
