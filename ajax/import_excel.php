<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_FILES['excel_file'])) {
        throw new RuntimeException('No se recibió archivo.');
    }

    $file = $_FILES['excel_file'];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Error en la carga del archivo.');
    }

    if (($file['size'] ?? 0) > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('El archivo excede el tamaño permitido (30MB).');
    }

    $originalName = (string) ($file['name'] ?? '');
    if (!isAllowedExcel($originalName)) {
        throw new RuntimeException('Formato inválido. Solo se permite .xlsx, .xls, .xlsm');
    }

    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0775, true);
    }

    $safeName = date('Ymd_His') . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);
    $target = UPLOAD_PATH . '/' . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('No fue posible guardar el archivo.');
    }

    $result = importExcelToDatabase($target);
    echo json_encode(['ok' => true, 'data' => $result], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
