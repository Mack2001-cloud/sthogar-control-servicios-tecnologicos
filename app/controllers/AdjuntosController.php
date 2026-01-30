<?php

namespace App\Controllers;

use App\Models\Adjunto;

class AdjuntosController
{
    public function upload(): void
    {
        verify_csrf();
        $config = require __DIR__ . '/../config/config.php';
        $servicioId = (int) ($_POST['servicio_id'] ?? 0);
        $evidenceNumber = (int) ($_POST['evidence_number'] ?? 0);
        $windowName = trim((string) ($_POST['window_name'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));

        if ($evidenceNumber <= 0 || $windowName === '' || $description === '') {
            set_flash('danger', 'Completa el número de evidencia, la ventana y la descripción.');
            header('Location: /servicios/view?id=' . $servicioId);
            exit;
        }

        if (!isset($_FILES['adjunto']) || $_FILES['adjunto']['error'] !== UPLOAD_ERR_OK) {
            set_flash('danger', 'Error al subir el archivo.');
            header('Location: /servicios/view?id=' . $servicioId);
            exit;
        }

        $file = $_FILES['adjunto'];
        if ($file['size'] > $config['max_upload_size']) {
            set_flash('danger', 'El archivo supera el tamaño permitido.');
            header('Location: /servicios/view?id=' . $servicioId);
            exit;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, $config['allowed_upload_types'], true)) {
            set_flash('danger', 'Tipo de archivo no permitido.');
            header('Location: /servicios/view?id=' . $servicioId);
            exit;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('adjunto_', true) . '.' . $extension;
        $destination = rtrim($config['uploads_dir'], '/') . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            set_flash('danger', 'No se pudo guardar el archivo.');
            header('Location: /servicios/view?id=' . $servicioId);
            exit;
        }

        Adjunto::create([
            'servicio_id' => $servicioId,
            'evidence_number' => $evidenceNumber,
            'window_name' => $windowName,
            'description' => $description,
            'filename' => $filename,
            'original_name' => $file['name'],
            'mime_type' => $mimeType,
            'size' => $file['size'],
        ]);

        set_flash('success', 'Adjunto subido correctamente.');
        header('Location: /servicios/view?id=' . $servicioId);
        exit;
    }
}
