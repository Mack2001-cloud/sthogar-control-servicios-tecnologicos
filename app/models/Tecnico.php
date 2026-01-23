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

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $userStmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, pass_hash, rol, activo) VALUES (:nombre, :email, :pass_hash, :rol, :activo)');
            $userStmt->execute([
                'nombre' => $data['usuario_nombre'],
                'email' => $data['usuario_email'],
                'pass_hash' => $data['usuario_pass_hash'],
                'rol' => 'tecnico',
                'activo' => $data['activo'],
            ]);

            $usuarioId = (int) $pdo->lastInsertId();

            $stmt = $pdo->prepare('INSERT INTO tecnicos (usuario_id, telefono, direccion, especialidad, fecha_ingreso, notas, activo) VALUES (:usuario_id, :telefono, :direccion, :especialidad, :fecha_ingreso, :notas, :activo)');
            $stmt->execute([
                'usuario_id' => $usuarioId,
                'telefono' => $data['telefono'],
                'direccion' => $data['direccion'],
                'especialidad' => $data['especialidad'],
                'fecha_ingreso' => $data['fecha_ingreso'],
                'notas' => $data['notas'],
                'activo' => $data['activo'],
            ]);

            $tecnicoId = (int) $pdo->lastInsertId();
            $pdo->commit();

            return $tecnicoId;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
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

        if (!empty($data['usuario_pass_hash'])) {
            $passwordStmt = $pdo->prepare('UPDATE usuarios SET pass_hash = :pass_hash WHERE id = :id');
            $passwordStmt->execute([
                'id' => $data['usuario_id'],
                'pass_hash' => $data['usuario_pass_hash'],
            ]);
        }

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
