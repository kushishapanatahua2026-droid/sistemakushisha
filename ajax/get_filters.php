<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$fields = ['anio', 'mes', 'profesional', 'servicio', 'diagnostico', 'establecimiento', 'red', 'microred'];
$response = [];
foreach ($fields as $field) {
    $response[$field] = getFilterOptions($field);
}

echo json_encode(['ok' => true, 'data' => $response], JSON_UNESCAPED_UNICODE);
