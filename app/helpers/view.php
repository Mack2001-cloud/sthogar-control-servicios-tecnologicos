<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function view(string $view, array $data = []): string
{
    $basePath = __DIR__ . '/../views/';
    $viewPath = $basePath . $view . '.php';

    if (!file_exists($viewPath)) {
        return 'Vista no encontrada';
    }

    extract($data, EXTR_SKIP);

    ob_start();
    include $viewPath;
    return ob_get_clean();
}
