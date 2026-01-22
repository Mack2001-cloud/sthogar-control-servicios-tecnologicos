<?php

namespace App\Models;

use App\Core\Database;

class Pago
{
    public static function create(array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO pagos (servicio_id, amount, method, paid_at) VALUES (:servicio_id, :amount, :method, :paid_at)');
        $stmt->execute([
            'servicio_id' => $data['servicio_id'],
            'amount' => $data['amount'],
            'method' => $data['method'],
            'paid_at' => $data['paid_at'],
        ]);
    }

    public static function byServicio(int $servicioId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM pagos WHERE servicio_id = :servicio_id ORDER BY paid_at DESC');
        $stmt->execute(['servicio_id' => $servicioId]);
        return $stmt->fetchAll();
    }

    public static function totalByServicio(int $servicioId): float
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT SUM(amount) FROM pagos WHERE servicio_id = :servicio_id');
        $stmt->execute(['servicio_id' => $servicioId]);
        return (float) $stmt->fetchColumn();
    }
}
