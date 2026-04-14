<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode(['data' => getLatestRows()], JSON_UNESCAPED_UNICODE);
