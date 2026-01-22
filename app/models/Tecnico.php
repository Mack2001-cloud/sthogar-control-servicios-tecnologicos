<?php

namespace App\Models;

use App\Core\Database;

class Tecnico
{
    public static function findAll(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query('SELECT tecnicos.id, tecnicos.telefono, tecnicos.especialidad, tecnicos.activo, usuarios.nombre AS usuario_nombre, usuarios.email AS usuario_email FROM tecnicos INNER JOIN usuarios ON tecnicos.usuario_id = usuarios.id ORDER BY usuarios.nombre');
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT tecnicos.*, usuarios.nombre AS usuario_nombre, usuarios.email AS usuario_email, usuarios.activo AS usuario_activo FROM tecnicos INNER JOIN usuarios ON tecnicos.usuario_id = usuarios.id WHERE tecnicos.id = :id');
        $stmt->execute(['id' => $id]);
        $tecnico = $stmt->fetch();

        return $tecnico ?: null;
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('UPDATE tecnicos SET telefono = :telefono, direccion = :direccion, especialidad = :especialidad, fecha_ingreso = :fecha_ingreso, notas = :notas, activo = :activo WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'especialidad' => $data['especialidad'],
            'fecha_ingreso' => $data['fecha_ingreso'],
            'notas' => $data['notas'],
            'activo' => $data['activo'],
        ]);

        $userStmt = $pdo->prepare('UPDATE usuarios SET nombre = :nombre, email = :email, activo = :activo WHERE id = :id');
        $userStmt->execute([
            'id' => $data['usuario_id'],
            'nombre' => $data['usuario_nombre'],
            'email' => $data['usuario_email'],
            'activo' => $data['activo'],
        ]);

        $pdo->commit();
    }

    public static function toggleActive(int $id, int $active): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('SELECT usuario_id FROM tecnicos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $usuarioId = $stmt->fetchColumn();

        if (!$usuarioId) {
            $pdo->rollBack();
            return;
        }

        $updateTecnico = $pdo->prepare('UPDATE tecnicos SET activo = :activo WHERE id = :id');
        $updateTecnico->execute([
            'id' => $id,
            'activo' => $active,
        ]);

        $updateUsuario = $pdo->prepare('UPDATE usuarios SET activo = :activo WHERE id = :id');
        $updateUsuario->execute([
            'id' => (int) $usuarioId,
            'activo' => $active,
        ]);

        $pdo->commit();
    }
}
