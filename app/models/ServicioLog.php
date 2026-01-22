<?php

namespace App\Models;

use App\Core\Database;

class ServicioLog
{
    public static function byServicio(int $servicioId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT servicio_logs.*, users.name AS user_name FROM servicio_logs LEFT JOIN users ON servicio_logs.user_id = users.id WHERE servicio_id = :servicio_id ORDER BY created_at DESC');
        $stmt->execute(['servicio_id' => $servicioId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO servicio_logs (servicio_id, user_id, status, note) VALUES (:servicio_id, :user_id, :status, :note)');
        $stmt->execute([
            'servicio_id' => $data['servicio_id'],
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'note' => $data['note'],
        ]);
    }
}
