<?php

namespace App\Models;

use App\Core\Database;

class Equipo
{
    public static function all(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query('SELECT equipos.id,
            equipos.cliente_id,
            equipos.servicio_id,
            equipos.categoria_equipo AS name,
            equipos.serie AS serial_number,
            equipos.ubicacion AS location,
            equipos.notas AS notes,
            equipos.creado_en AS created_at,
            clientes.nombre AS cliente_name
            FROM equipos
            LEFT JOIN clientes ON equipos.cliente_id = clientes.id
            ORDER BY equipos.creado_en DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, cliente_id, servicio_id, categoria_equipo AS name, serie AS serial_number, ubicacion AS location, notas AS notes, creado_en AS created_at FROM equipos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $equipo = $stmt->fetch();
        return $equipo ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO equipos (cliente_id, servicio_id, categoria_equipo, marca, modelo, serie, ubicacion, notas) VALUES (:cliente_id, :servicio_id, :name, :brand, :model, :serial_number, :location, :notes)');
        $stmt->execute([
            'cliente_id' => $data['cliente_id'],
            'servicio_id' => $data['servicio_id'] ?? null,
            'name' => $data['name'],
            'brand' => $data['brand'] ?? 'Sin especificar',
            'model' => $data['model'] ?? 'Sin especificar',
            'serial_number' => $data['serial_number'],
            'location' => $data['location'],
            'notes' => $data['notes'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE equipos SET cliente_id = :cliente_id, servicio_id = :servicio_id, categoria_equipo = :name, marca = :brand, modelo = :model, serie = :serial_number, ubicacion = :location, notas = :notes WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'cliente_id' => $data['cliente_id'],
            'servicio_id' => $data['servicio_id'] ?? null,
            'name' => $data['name'],
            'brand' => $data['brand'] ?? 'Sin especificar',
            'model' => $data['model'] ?? 'Sin especificar',
            'serial_number' => $data['serial_number'],
            'location' => $data['location'],
            'notes' => $data['notes'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('DELETE FROM equipos WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function countAll(): int
    {
        $pdo = Database::connection();
        return (int) $pdo->query('SELECT COUNT(*) FROM equipos')->fetchColumn();
    }

    public static function byCliente(int $clienteId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, cliente_id, servicio_id, categoria_equipo AS name, serie AS serial_number, ubicacion AS location, notas AS notes, creado_en AS created_at FROM equipos WHERE cliente_id = :cliente_id ORDER BY creado_en DESC');
        $stmt->execute(['cliente_id' => $clienteId]);
        return $stmt->fetchAll();
    }
}
