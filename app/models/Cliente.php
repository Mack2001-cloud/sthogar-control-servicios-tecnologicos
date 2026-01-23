<?php

namespace App\Models;

use App\Core\Database;

class Cliente
{
    public static function all(?string $search = null): array
    {
        $pdo = Database::connection();
        $sql = 'SELECT id, nombre AS name, email, telefono AS phone, direccion AS address, referencia AS notes, creado_en AS created_at FROM clientes';
        $params = [];

        if ($search) {
            $sql .= ' WHERE nombre LIKE :term OR email LIKE :term OR telefono LIKE :term OR direccion LIKE :term OR referencia LIKE :term';
            $params['term'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY creado_en DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, nombre AS name, email, telefono AS phone, direccion AS address, referencia AS notes, creado_en AS created_at FROM clientes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO clientes (nombre, email, telefono, direccion, referencia) VALUES (:name, :email, :phone, :address, :notes)');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'notes' => $data['notes'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE clientes SET nombre = :name, email = :email, telefono = :phone, direccion = :address, referencia = :notes WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'notes' => $data['notes'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function countAll(): int
    {
        $pdo = Database::connection();
        return (int) $pdo->query('SELECT COUNT(*) FROM clientes')->fetchColumn();
    }
}
