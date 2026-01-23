<?php

namespace App\Models;

use App\Core\Database;

class Cliente
{
    public static function all(?string $search = null): array
    {
        $pdo = Database::connection();
        $sql = 'SELECT clientes.id, clientes.nombre AS name, clientes.email, clientes.telefono AS phone, clientes.direccion AS address, clientes.referencia AS notes, clientes.tecnico_id, usuarios.nombre AS tecnico_name, clientes.creado_en AS created_at FROM clientes LEFT JOIN tecnicos ON clientes.tecnico_id = tecnicos.id LEFT JOIN usuarios ON tecnicos.usuario_id = usuarios.id';
        $params = [];

        if ($search) {
            $sql .= ' WHERE clientes.nombre LIKE :term OR clientes.email LIKE :term OR clientes.telefono LIKE :term OR clientes.direccion LIKE :term OR clientes.referencia LIKE :term OR usuarios.nombre LIKE :term';
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
        $stmt = $pdo->prepare('SELECT clientes.id, clientes.nombre AS name, clientes.email, clientes.telefono AS phone, clientes.direccion AS address, clientes.referencia AS notes, clientes.tecnico_id, usuarios.nombre AS tecnico_name, clientes.creado_en AS created_at FROM clientes LEFT JOIN tecnicos ON clientes.tecnico_id = tecnicos.id LEFT JOIN usuarios ON tecnicos.usuario_id = usuarios.id WHERE clientes.id = :id');
        $stmt->execute(['id' => $id]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO clientes (nombre, email, telefono, direccion, referencia, tecnico_id) VALUES (:name, :email, :phone, :address, :notes, :tecnico_id)');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'notes' => $data['notes'],
            'tecnico_id' => $data['tecnico_id'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE clientes SET nombre = :name, email = :email, telefono = :phone, direccion = :address, referencia = :notes, tecnico_id = :tecnico_id WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'notes' => $data['notes'],
            'tecnico_id' => $data['tecnico_id'],
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
