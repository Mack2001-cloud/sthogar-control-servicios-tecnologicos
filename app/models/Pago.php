<?php

namespace App\Models;

use App\Core\Database;

class Pago
{
    public static function create(array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO pagos (servicio_id, monto, metodo, referencia_pago, fecha_pago) VALUES (:servicio_id, :amount, :method, :reference, :paid_at)');
        $stmt->execute([
            'servicio_id' => $data['servicio_id'],
            'amount' => $data['amount'],
            'method' => $data['method'],
            'reference' => $data['reference'] ?? null,
            'paid_at' => $data['paid_at'],
        ]);
    }

    public static function byServicio(int $servicioId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, servicio_id, monto AS amount, metodo AS method, referencia_pago AS reference, fecha_pago AS paid_at, creado_en AS created_at FROM pagos WHERE servicio_id = :servicio_id ORDER BY fecha_pago DESC');
        $stmt->execute(['servicio_id' => $servicioId]);
        return $stmt->fetchAll();
    }

    public static function totalByServicio(int $servicioId): float
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT SUM(monto) FROM pagos WHERE servicio_id = :servicio_id');
        $stmt->execute(['servicio_id' => $servicioId]);
        return (float) $stmt->fetchColumn();
    }
}
