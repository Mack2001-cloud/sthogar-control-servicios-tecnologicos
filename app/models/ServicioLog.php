<?php

namespace App\Models;

use App\Core\Database;

class ServicioLog
{
    public static function byServicio(int $servicioId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT bitacora_servicio.id, bitacora_servicio.servicio_id, bitacora_servicio.usuario_id AS user_id, bitacora_servicio.estatus_nuevo AS status, bitacora_servicio.comentario AS note, bitacora_servicio.creado_en AS created_at, usuarios.nombre AS user_name FROM bitacora_servicio LEFT JOIN usuarios ON bitacora_servicio.usuario_id = usuarios.id WHERE servicio_id = :servicio_id ORDER BY bitacora_servicio.creado_en DESC');
        $stmt->execute(['servicio_id' => $servicioId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO bitacora_servicio (servicio_id, usuario_id, estatus_nuevo, comentario) VALUES (:servicio_id, :user_id, :status, :note)');
        $stmt->execute([
            'servicio_id' => $data['servicio_id'],
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'note' => $data['note'],
        ]);
    }
}
