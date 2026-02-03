<?php

namespace App\Models;

use App\Core\Database;

class Adjunto
{
    private static ?bool $hasEvidenceNumberColumn = null;
    private static ?bool $hasWindowColumn = null;

    private static function hasEvidenceNumberColumn(): bool
    {
        if (self::$hasEvidenceNumberColumn !== null) {
            return self::$hasEvidenceNumberColumn;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare("SHOW COLUMNS FROM adjuntos LIKE 'numero_evidencia'");
        $stmt->execute();
        self::$hasEvidenceNumberColumn = (bool) $stmt->fetch();

        return self::$hasEvidenceNumberColumn;
    }

    private static function hasWindowColumn(): bool
    {
        if (self::$hasWindowColumn !== null) {
            return self::$hasWindowColumn;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare("SHOW COLUMNS FROM adjuntos LIKE 'ventana'");
        $stmt->execute();
        self::$hasWindowColumn = (bool) $stmt->fetch();

        return self::$hasWindowColumn;
    }

    public static function create(array $data): void
    {
        $pdo = Database::connection();
        $columns = ['servicio_id'];
        $placeholders = [':servicio_id'];
        $params = [
            'servicio_id' => $data['servicio_id'],
        ];

        if (self::hasEvidenceNumberColumn()) {
            $columns[] = 'numero_evidencia';
            $placeholders[] = ':evidence_number';
            $params['evidence_number'] = $data['evidence_number'];
        }

        if (self::hasWindowColumn()) {
            $columns[] = 'ventana';
            $placeholders[] = ':window_name';
            $params['window_name'] = $data['window_name'];
        }

        $columns = array_merge($columns, [
            'descripcion',
            'nombre_original',
            'nombre_guardado',
            'ruta',
            'tipo_mime',
            'tamano',
        ]);

        $placeholders = array_merge($placeholders, [
            ':description',
            ':original_name',
            ':filename',
            ':path',
            ':mime_type',
            ':size',
        ]);

        $params = array_merge($params, [
            'description' => $data['description'],
            'filename' => $data['filename'],
            'original_name' => $data['original_name'],
            'path' => $data['path'] ?? $data['filename'],
            'mime_type' => $data['mime_type'],
            'size' => $data['size'],
        ]);

        $sql = sprintf(
            'INSERT INTO adjuntos (%s) VALUES (%s)',
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function byServicio(int $servicioId): array
    {
        $pdo = Database::connection();
        $evidenceSelect = self::hasEvidenceNumberColumn()
            ? 'numero_evidencia AS evidence_number'
            : 'NULL AS evidence_number';
        $windowSelect = self::hasWindowColumn()
            ? 'ventana AS window_name'
            : 'NULL AS window_name';

        $sql = sprintf(
            'SELECT id, servicio_id, %s, %s, descripcion AS description, nombre_original AS original_name, nombre_guardado AS filename, ruta AS path, tipo_mime AS mime_type, tamano AS size, creado_en AS created_at FROM adjuntos WHERE servicio_id = :servicio_id ORDER BY creado_en DESC',
            $evidenceSelect,
            $windowSelect
        );
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['servicio_id' => $servicioId]);
        return $stmt->fetchAll();
    }
}
