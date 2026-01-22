<?php

namespace App\Controllers;

use App\Models\Cliente;
use App\Models\Servicio;

class ExportController
{
    public function clientes(): void
    {
        $clientes = Cliente::all();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="clientes.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nombre', 'Email', 'Teléfono', 'Dirección', 'Notas']);

        foreach ($clientes as $cliente) {
            fputcsv($output, [
                $cliente['id'],
                $cliente['name'],
                $cliente['email'],
                $cliente['phone'],
                $cliente['address'],
                $cliente['notes'],
            ]);
        }

        fclose($output);
    }

    public function servicios(): void
    {
        $servicios = Servicio::all();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="servicios.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Cliente', 'Tipo', 'Estado', 'Fecha programada', 'Monto']);

        foreach ($servicios as $servicio) {
            fputcsv($output, [
                $servicio['id'],
                $servicio['cliente_name'],
                $servicio['type'],
                $servicio['status'],
                $servicio['scheduled_at'],
                $servicio['amount'],
            ]);
        }

        fclose($output);
    }
}
