<?php

namespace App\Controllers;

use App\Models\Pago;

class PagosController
{
    public function create(): void
    {
        verify_csrf();
        $servicioId = (int) ($_POST['servicio_id'] ?? 0);
        $amount = (float) ($_POST['amount'] ?? 0);
        $method = trim($_POST['method'] ?? '');
        $paidAt = $_POST['paid_at'] ?? date('Y-m-d');

        if ($amount <= 0) {
            set_flash('danger', 'El monto debe ser mayor a 0.');
            header('Location: /servicios/view?id=' . $servicioId);
            exit;
        }

        Pago::create([
            'servicio_id' => $servicioId,
            'amount' => $amount,
            'method' => $method,
            'paid_at' => $paidAt,
        ]);

        set_flash('success', 'Pago registrado.');
        header('Location: /servicios/view?id=' . $servicioId);
        exit;
    }
}
