<?php

namespace App\Models;

use App\Core\Database;

class Adjunto
{
    public static function create(array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO adjuntos (servicio_id, filename, original_name, mime_type, size) VALUES (:servicio_id, :filename, :original_name, :mime_type, :size)');
        $stmt->execute([
            'servicio_id' => $data['servicio_id'],
            'filename' => $data['filename'],
            'original_name' => $data['original_name'],
            'mime_type' => $data['mime_type'],
            'size' => $data['size'],
        ]);
    }

    public static function byServicio(int $servicioId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM adjuntos WHERE servicio_id = :servicio_id ORDER BY uploaded_at DESC');
        $stmt->execute(['servicio_id' => $servicioId]);
        return $stmt->fetchAll();
    }
}
