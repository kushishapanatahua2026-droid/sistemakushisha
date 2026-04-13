<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';

function jsonResponse(array $payload): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function buildWhere(array $allowedFields): array
{
    $where = [];
    $params = [];

    foreach ($allowedFields as $field) {
        $value = trim((string)($_GET[$field] ?? ''));
        if ($value !== '') {
            $where[] = "$field LIKE ?";
            $params[] = "%$value%";
        }
    }

    return [count($where) ? 'WHERE ' . implode(' AND ', $where) : '', $params];
}
