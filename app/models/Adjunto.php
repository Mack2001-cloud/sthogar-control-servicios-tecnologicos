<?php

namespace App\Models;

use App\Core\Database;

class Adjunto
{
    public static function create(array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO adjuntos (servicio_id, nombre_original, nombre_guardado, ruta, tipo_mime, tamano) VALUES (:servicio_id, :original_name, :filename, :path, :mime_type, :size)');
        $stmt->execute([
            'servicio_id' => $data['servicio_id'],
            'filename' => $data['filename'],
            'original_name' => $data['original_name'],
            'path' => $data['path'] ?? $data['filename'],
            'mime_type' => $data['mime_type'],
            'size' => $data['size'],
        ]);
    }

    public static function byServicio(int $servicioId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, servicio_id, nombre_original AS original_name, nombre_guardado AS filename, ruta AS path, tipo_mime AS mime_type, tamano AS size, creado_en AS created_at FROM adjuntos WHERE servicio_id = :servicio_id ORDER BY creado_en DESC');
        $stmt->execute(['servicio_id' => $servicioId]);
        return $stmt->fetchAll();
    }
}
