<?php

namespace App\Models;

use App\Core\Database;

class Servicio
{
    private static function normalizeCategoria(?string $value): string
    {
        $normalized = strtoupper(trim((string) $value));
        $normalized = str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'], ['A', 'E', 'I', 'O', 'U', 'N'], $normalized);

        $map = [
            'CCTV' => 'CCTV',
            'AUTOMATIZACION' => 'AUTOMATIZACION',
            'RED' => 'RED',
            'SOPORTE' => 'SOPORTE',
            'POS' => 'POS',
            'VENTAS' => 'VENTA',
            'VENTA' => 'VENTA',
        ];

        return $map[$normalized] ?? 'SOPORTE';
    }

    private static function normalizeTipo(?string $value): string
    {
        $normalized = strtolower(trim((string) $value));
        $map = [
            'instalacion' => 'instalacion',
            'mantenimiento' => 'mantenimiento',
            'soporte' => 'soporte',
            'venta' => 'venta',
        ];

        return $map[$normalized] ?? 'soporte';
    }

    public static function all(array $filters = []): array
    {
        $pdo = Database::connection();
        $sql = 'SELECT servicios.id,
            servicios.folio,
            servicios.cliente_id,
            servicios.tecnico_id,
            servicios.categoria AS type,
            servicios.tipo AS service_type,
            servicios.descripcion AS description,
            servicios.prioridad,
            servicios.estatus AS status,
            servicios.fecha_programada AS scheduled_at,
            servicios.creado_en AS created_at,
            servicios.actualizado_en AS updated_at,
            0 AS amount,
            clientes.nombre AS cliente_name
            FROM servicios
            LEFT JOIN clientes ON servicios.cliente_id = clientes.id
            WHERE 1=1';
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= ' AND servicios.estatus = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['cliente_id'])) {
            $sql .= ' AND servicios.cliente_id = :cliente_id';
            $params['cliente_id'] = $filters['cliente_id'];
        }

        $sql .= ' ORDER BY servicios.creado_en DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT servicios.id,
            servicios.folio,
            servicios.cliente_id,
            servicios.tecnico_id,
            servicios.categoria AS type,
            servicios.tipo AS service_type,
            servicios.descripcion AS description,
            servicios.prioridad,
            servicios.estatus AS status,
            servicios.fecha_programada AS scheduled_at,
            servicios.creado_en AS created_at,
            servicios.actualizado_en AS updated_at,
            0 AS amount,
            clientes.nombre AS cliente_name,
            clientes.email AS cliente_email
            FROM servicios
            LEFT JOIN clientes ON servicios.cliente_id = clientes.id
            WHERE servicios.id = :id');
        $stmt->execute(['id' => $id]);
        $servicio = $stmt->fetch();
        return $servicio ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $folio = $data['folio'] ?? ('SRV-' . date('YmdHis') . '-' . random_int(100, 999));
        $categoria = self::normalizeCategoria($data['categoria'] ?? $data['type'] ?? '');
        $tipo = self::normalizeTipo($data['tipo'] ?? $data['service_type'] ?? null);
        $stmt = $pdo->prepare('INSERT INTO servicios (folio, cliente_id, categoria, tipo, descripcion, prioridad, estatus, fecha_programada, tecnico_id) VALUES (:folio, :cliente_id, :categoria, :type, :description, :priority, :status, :scheduled_at, :tecnico_id)');
        $stmt->execute([
            'folio' => $folio,
            'cliente_id' => $data['cliente_id'],
            'categoria' => $categoria,
            'type' => $tipo,
            'description' => $data['description'],
            'priority' => $data['priority'] ?? 'media',
            'status' => $data['status'],
            'scheduled_at' => $data['scheduled_at'],
            'tecnico_id' => $data['tecnico_id'] ?? null,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $categoria = self::normalizeCategoria($data['categoria'] ?? $data['type'] ?? '');
        $tipo = self::normalizeTipo($data['tipo'] ?? $data['service_type'] ?? null);
        $stmt = $pdo->prepare('UPDATE servicios SET cliente_id = :cliente_id, categoria = :categoria, tipo = :type, descripcion = :description, prioridad = :priority, estatus = :status, fecha_programada = :scheduled_at, tecnico_id = :tecnico_id WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'cliente_id' => $data['cliente_id'],
            'categoria' => $categoria,
            'type' => $tipo,
            'description' => $data['description'],
            'priority' => $data['priority'] ?? 'media',
            'status' => $data['status'],
            'scheduled_at' => $data['scheduled_at'],
            'tecnico_id' => $data['tecnico_id'] ?? null,
        ]);
    }

    public static function updateStatus(int $id, string $status): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE servicios SET estatus = :status WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('DELETE FROM servicios WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function countAll(): int
    {
        $pdo = Database::connection();
        return (int) $pdo->query('SELECT COUNT(*) FROM servicios')->fetchColumn();
    }
}
