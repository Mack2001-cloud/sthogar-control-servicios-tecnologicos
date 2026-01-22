<?php

namespace App\Models;

use App\Core\Database;

class Equipo
{
    public static function all(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query('SELECT equipos.*, clientes.name AS cliente_name FROM equipos LEFT JOIN clientes ON equipos.cliente_id = clientes.id ORDER BY equipos.created_at DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM equipos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $equipo = $stmt->fetch();
        return $equipo ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO equipos (cliente_id, name, serial_number, location, notes) VALUES (:cliente_id, :name, :serial_number, :location, :notes)');
        $stmt->execute([
            'cliente_id' => $data['cliente_id'],
            'name' => $data['name'],
            'serial_number' => $data['serial_number'],
            'location' => $data['location'],
            'notes' => $data['notes'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE equipos SET cliente_id = :cliente_id, name = :name, serial_number = :serial_number, location = :location, notes = :notes WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'cliente_id' => $data['cliente_id'],
            'name' => $data['name'],
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
        $stmt = $pdo->prepare('SELECT * FROM equipos WHERE cliente_id = :cliente_id ORDER BY created_at DESC');
        $stmt->execute(['cliente_id' => $clienteId]);
        return $stmt->fetchAll();
    }
}
