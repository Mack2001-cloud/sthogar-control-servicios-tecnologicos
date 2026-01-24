<?php

namespace App\Models;

use App\Core\Database;

class ServicioDocumentacion
{
    private static ?bool $tableExists = null;

    private static function hasTable(): bool
    {
        if (self::$tableExists !== null) {
            return self::$tableExists;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = :table');
        $stmt->execute(['table' => 'servicios_documentacion']);
        self::$tableExists = ((int) $stmt->fetchColumn()) > 0;
        return self::$tableExists;
    }

    public static function findByServicio(int $servicioId): ?array
    {
        if (!self::hasTable()) {
            return null;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, servicio_id, cliente, direccion, fecha, items, observaciones, responsable_venta, cliente_firma, total FROM servicios_documentacion WHERE servicio_id = :servicio_id');
        $stmt->execute(['servicio_id' => $servicioId]);
        $documentacion = $stmt->fetch();

        if (!$documentacion) {
            return null;
        }

        $decodedItems = json_decode($documentacion['items'] ?? '[]', true);
        $documentacion['items'] = is_array($decodedItems) ? $decodedItems : [];

        return $documentacion;
    }

    public static function upsertByServicio(int $servicioId, array $data): void
    {
        if (!self::hasTable()) {
            return;
        }

        $pdo = Database::connection();
        $payload = [
            'servicio_id' => $servicioId,
            'cliente' => $data['cliente'] ?? '',
            'direccion' => $data['direccion'] ?? '',
            'fecha' => $data['fecha'] ?: null,
            'items' => json_encode($data['items'] ?? [], JSON_UNESCAPED_UNICODE),
            'observaciones' => $data['observaciones'] ?? '',
            'responsable_venta' => $data['responsable_venta'] ?? '',
            'cliente_firma' => $data['cliente_firma'] ?? '',
            'total' => $data['total'] ?? 0,
        ];

        $stmt = $pdo->prepare('INSERT INTO servicios_documentacion (servicio_id, cliente, direccion, fecha, items, observaciones, responsable_venta, cliente_firma, total)
            VALUES (:servicio_id, :cliente, :direccion, :fecha, :items, :observaciones, :responsable_venta, :cliente_firma, :total)
            ON DUPLICATE KEY UPDATE
                cliente = VALUES(cliente),
                direccion = VALUES(direccion),
                fecha = VALUES(fecha),
                items = VALUES(items),
                observaciones = VALUES(observaciones),
                responsable_venta = VALUES(responsable_venta),
                cliente_firma = VALUES(cliente_firma),
                total = VALUES(total)');
        $stmt->execute($payload);
    }
}
