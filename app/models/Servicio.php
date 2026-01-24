<?php

namespace App\Models;

use App\Core\Database;

class Servicio
{
    private static array $columnCache = [];

    private static function hasColumn(string $column): bool
    {
        if (array_key_exists($column, self::$columnCache)) {
            return self::$columnCache[$column];
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :table AND column_name = :column');
        $stmt->execute([
            'table' => 'servicios',
            'column' => $column,
        ]);
        self::$columnCache[$column] = ((int) $stmt->fetchColumn()) > 0;
        return self::$columnCache[$column];
    }

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
        $estimatedAmountSql = self::hasColumn('monto_estimado')
            ? 'servicios.monto_estimado AS estimated_amount'
            : '0 AS estimated_amount';
        $budgetAmountSql = self::hasColumn('presupuesto')
            ? 'servicios.presupuesto AS budget_amount'
            : '0 AS budget_amount';
        $extrasAmountSql = self::hasColumn('extras_monto')
            ? 'servicios.extras_monto AS extras_amount'
            : '0 AS extras_amount';
        $extrasDescriptionSql = self::hasColumn('extras_descripcion')
            ? 'servicios.extras_descripcion AS extras_description'
            : "'' AS extras_description";
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
            ' . $estimatedAmountSql . ',
            ' . $budgetAmountSql . ',
            ' . $extrasAmountSql . ',
            ' . $extrasDescriptionSql . ',
            servicios.creado_en AS created_at,
            servicios.actualizado_en AS updated_at,
            COALESCE(SUM(pagos.monto), 0) AS amount,
            clientes.nombre AS cliente_name,
            usuarios.nombre AS tecnico_name
            FROM servicios
            LEFT JOIN clientes ON servicios.cliente_id = clientes.id
            LEFT JOIN tecnicos ON servicios.tecnico_id = tecnicos.id
            LEFT JOIN usuarios ON tecnicos.usuario_id = usuarios.id
            LEFT JOIN pagos ON pagos.servicio_id = servicios.id
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

        if (!empty($filters['service_type'])) {
            $sql .= ' AND servicios.tipo = :service_type';
            $params['service_type'] = $filters['service_type'];
        }

        $sql .= ' GROUP BY servicios.id ORDER BY servicios.creado_en DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $estimatedAmountSql = self::hasColumn('monto_estimado')
            ? 'servicios.monto_estimado AS estimated_amount'
            : '0 AS estimated_amount';
        $budgetAmountSql = self::hasColumn('presupuesto')
            ? 'servicios.presupuesto AS budget_amount'
            : '0 AS budget_amount';
        $extrasAmountSql = self::hasColumn('extras_monto')
            ? 'servicios.extras_monto AS extras_amount'
            : '0 AS extras_amount';
        $extrasDescriptionSql = self::hasColumn('extras_descripcion')
            ? 'servicios.extras_descripcion AS extras_description'
            : "'' AS extras_description";
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
            ' . $estimatedAmountSql . ',
            ' . $budgetAmountSql . ',
            ' . $extrasAmountSql . ',
            ' . $extrasDescriptionSql . ',
            servicios.creado_en AS created_at,
            servicios.actualizado_en AS updated_at,
            COALESCE(SUM(pagos.monto), 0) AS amount,
            clientes.nombre AS cliente_name,
            clientes.email AS cliente_email,
            usuarios.nombre AS tecnico_name
            FROM servicios
            LEFT JOIN clientes ON servicios.cliente_id = clientes.id
            LEFT JOIN tecnicos ON servicios.tecnico_id = tecnicos.id
            LEFT JOIN usuarios ON tecnicos.usuario_id = usuarios.id
            LEFT JOIN pagos ON pagos.servicio_id = servicios.id
            WHERE servicios.id = :id
            GROUP BY servicios.id');
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
        $columns = [
            'folio',
            'cliente_id',
            'categoria',
            'tipo',
            'descripcion',
            'prioridad',
            'estatus',
            'fecha_programada',
            'tecnico_id',
        ];
        $values = [
            'folio' => $folio,
            'cliente_id' => $data['cliente_id'],
            'categoria' => $categoria,
            'type' => $tipo,
            'description' => $data['description'],
            'priority' => $data['priority'] ?? 'media',
            'status' => $data['status'],
            'scheduled_at' => $data['scheduled_at'],
            'tecnico_id' => $data['tecnico_id'] ?? null,
        ];

        if (self::hasColumn('monto_estimado')) {
            $columns[] = 'monto_estimado';
            $values['estimated_amount'] = $data['estimated_amount'] ?? 0;
        }

        if (self::hasColumn('presupuesto')) {
            $columns[] = 'presupuesto';
            $values['budget_amount'] = $data['budget_amount'] ?? 0;
        }

        if (self::hasColumn('extras_monto')) {
            $columns[] = 'extras_monto';
            $values['extras_amount'] = $data['extras_amount'] ?? 0;
        }

        if (self::hasColumn('extras_descripcion')) {
            $columns[] = 'extras_descripcion';
            $values['extras_description'] = $data['extras_description'] ?? '';
        }

        $placeholders = array_map(static fn (string $column): string => ':' . self::columnPlaceholder($column), $columns);
        $stmt = $pdo->prepare('INSERT INTO servicios (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')');
        $stmt->execute(self::mapColumnValues($columns, $values));
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connection();
        $categoria = self::normalizeCategoria($data['categoria'] ?? $data['type'] ?? '');
        $tipo = self::normalizeTipo($data['tipo'] ?? $data['service_type'] ?? null);
        $fields = [
            'cliente_id' => $data['cliente_id'],
            'categoria' => $categoria,
            'tipo' => $tipo,
            'descripcion' => $data['description'],
            'prioridad' => $data['priority'] ?? 'media',
            'estatus' => $data['status'],
            'fecha_programada' => $data['scheduled_at'],
            'tecnico_id' => $data['tecnico_id'] ?? null,
        ];

        if (self::hasColumn('monto_estimado')) {
            $fields['monto_estimado'] = $data['estimated_amount'] ?? 0;
        }

        if (self::hasColumn('presupuesto')) {
            $fields['presupuesto'] = $data['budget_amount'] ?? 0;
        }

        if (self::hasColumn('extras_monto')) {
            $fields['extras_monto'] = $data['extras_amount'] ?? 0;
        }

        if (self::hasColumn('extras_descripcion')) {
            $fields['extras_descripcion'] = $data['extras_description'] ?? '';
        }

        $setParts = [];
        foreach ($fields as $column => $value) {
            $setParts[] = $column . ' = :' . self::columnPlaceholder($column);
        }

        $stmt = $pdo->prepare('UPDATE servicios SET ' . implode(', ', $setParts) . ' WHERE id = :id');
        $values = self::mapColumnValues(array_keys($fields), $fields);
        $values['id'] = $id;
        $stmt->execute($values);
    }

    public static function updateStatus(int $id, string $status): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE servicios SET estatus = :status WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public static function updateBudget(int $id, array $data): void
    {
        $pdo = Database::connection();
        $fields = [];

        if (self::hasColumn('presupuesto')) {
            $fields['presupuesto'] = $data['budget_amount'] ?? 0;
        }

        if (self::hasColumn('extras_monto')) {
            $fields['extras_monto'] = $data['extras_amount'] ?? 0;
        }

        if (self::hasColumn('extras_descripcion')) {
            $fields['extras_descripcion'] = $data['extras_description'] ?? '';
        }

        if (!$fields) {
            return;
        }

        $setParts = [];
        foreach ($fields as $column => $value) {
            $setParts[] = $column . ' = :' . self::columnPlaceholder($column);
        }

        $stmt = $pdo->prepare('UPDATE servicios SET ' . implode(', ', $setParts) . ' WHERE id = :id');
        $values = self::mapColumnValues(array_keys($fields), $fields);
        $values['id'] = $id;
        $stmt->execute($values);
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

    private static function columnPlaceholder(string $column): string
    {
        return match ($column) {
            'tipo' => 'type',
            'descripcion' => 'description',
            'prioridad' => 'priority',
            'estatus' => 'status',
            'fecha_programada' => 'scheduled_at',
            'monto_estimado' => 'estimated_amount',
            'presupuesto' => 'budget_amount',
            'extras_monto' => 'extras_amount',
            'extras_descripcion' => 'extras_description',
            default => $column,
        };
    }

    private static function mapColumnValues(array $columns, array $values): array
    {
        $mapped = [];
        foreach ($columns as $column) {
            $mapped[self::columnPlaceholder($column)] = $values[self::columnPlaceholder($column)] ?? $values[$column] ?? null;
        }
        return $mapped;
    }
}
