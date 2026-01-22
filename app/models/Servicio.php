<?php

namespace App\Models;

use App\Core\Database;

class Servicio
{
    public static function all(array $filters = []): array
    {
        $pdo = Database::connection();
        $sql = 'SELECT servicios.*, clientes.name AS cliente_name FROM servicios LEFT JOIN clientes ON servicios.cliente_id = clientes.id WHERE 1=1';
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= ' AND servicios.status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['cliente_id'])) {
            $sql .= ' AND servicios.cliente_id = :cliente_id';
            $params['cliente_id'] = $filters['cliente_id'];
        }

        $sql .= ' ORDER BY servicios.created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT servicios.*, clientes.name AS cliente_name, clientes.email AS cliente_email FROM servicios LEFT JOIN clientes ON servicios.cliente_id = clientes.id WHERE servicios.id = :id');
        $stmt->execute(['id' => $id]);
        $servicio = $stmt->fetch();
        return $servicio ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO servicios (cliente_id, type, description, status, scheduled_at, amount) VALUES (:cliente_id, :type, :description, :status, :scheduled_at, :amount)');
        $stmt->execute([
            'cliente_id' => $data['cliente_id'],
            'type' => $data['type'],
            'description' => $data['description'],
            'status' => $data['status'],
            'scheduled_at' => $data['scheduled_at'],
            'amount' => $data['amount'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE servicios SET cliente_id = :cliente_id, type = :type, description = :description, status = :status, scheduled_at = :scheduled_at, amount = :amount WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'cliente_id' => $data['cliente_id'],
            'type' => $data['type'],
            'description' => $data['description'],
            'status' => $data['status'],
            'scheduled_at' => $data['scheduled_at'],
            'amount' => $data['amount'],
        ]);
    }

    public static function updateStatus(int $id, string $status): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE servicios SET status = :status WHERE id = :id');
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
