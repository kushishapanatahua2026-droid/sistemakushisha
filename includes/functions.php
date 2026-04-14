<?php

declare(strict_types=1);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

require_once __DIR__ . '/db.php';

function getPdoForData(): mysqli
{
    initializeDatabase();
    return getConnection();
}

function isAllowedExcel(string $fileName): bool
{
    $allowed = ['xlsx', 'xls', 'xlsm'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    return in_array($ext, $allowed, true);
}

function normalizeDateValue(mixed $value): ?string
{
    if ($value === null || $value === '') {
        return null;
    }

    if (is_numeric($value)) {
        try {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }

    $ts = strtotime((string) $value);
    return $ts ? date('Y-m-d', $ts) : null;
}

function importExcelToDatabase(string $fullPath): array
{
    $conn = getPdoForData();

    require_once __DIR__ . '/../vendor/autoload.php';

    $reader = IOFactory::createReaderForFile($fullPath);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($fullPath);
    $sheet = $spreadsheet->getActiveSheet();

    $rows = $sheet->toArray(null, true, true, false);
    if (count($rows) < 2) {
        return ['inserted' => 0, 'message' => 'El archivo no contiene filas de datos'];
    }

    $placeholders = rtrim(str_repeat('?,', 74), ',');
    $sql = "INSERT INTO registros_salud (
        anio, mes, fecha, profesional, servicio, diagnostico, establecimiento, red, microred, categoria,
        estado, condicion_servicio, tipo_diagnostico,
        campo15, campo16, campo17, campo18, campo19, campo20, campo21, campo22, campo23, campo24, campo25,
        campo26, campo27, campo28, campo29, campo30, campo31, campo32, campo33, campo34, campo35, campo36,
        campo37, campo38, campo39, campo40, campo41, campo42, campo43, campo44, campo45, campo46, campo47,
        campo48, campo49, campo50, campo51, campo52, campo53, campo54, campo55, campo56, campo57, campo58,
        campo59, campo60, campo61, campo62, campo63, campo64, campo65, campo66, campo67, campo68, campo69,
        campo70, campo71, campo72, campo73, campo74
    ) VALUES ({$placeholders})";

    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', 74);

    $conn->begin_transaction();
    $inserted = 0;

    try {
        foreach (array_slice($rows, 1) as $row) {
            $data = array_pad($row, 74, null);
            $data = array_slice($data, 0, 74);

            $data[2] = normalizeDateValue($data[2]);
            $data[0] = ($data[0] !== null && $data[0] !== '') ? (string) (int) $data[0] : null;
            $data[1] = ($data[1] !== null && $data[1] !== '') ? (string) (int) $data[1] : null;

            $stmt->bind_param($types, ...$data);
            $stmt->execute();
            $inserted++;
        }

        $conn->commit();
        $stmt->close();
        $conn->close();

        return ['inserted' => $inserted, 'message' => 'Importación completada'];
    } catch (Throwable $e) {
        $conn->rollback();
        return ['inserted' => 0, 'message' => 'Error al importar: ' . $e->getMessage()];
    }
}

function getLatestRows(int $limit = 2000): array
{
    $conn = getPdoForData();
    $limit = max(1, min(5000, $limit));

    $sql = "SELECT id, anio, mes, fecha, profesional, servicio, diagnostico, establecimiento, red, microred, categoria, estado
            FROM registros_salud ORDER BY id DESC LIMIT {$limit}";
    $result = $conn->query($sql);
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $conn->close();

    return $rows;
}

function getFilterOptions(string $field): array
{
    $allowed = ['anio', 'mes', 'profesional', 'servicio', 'diagnostico', 'establecimiento', 'red', 'microred'];
    if (!in_array($field, $allowed, true)) {
        return [];
    }

    $conn = getPdoForData();
    $sql = "SELECT DISTINCT {$field} AS valor FROM registros_salud WHERE {$field} IS NOT NULL AND {$field} <> '' ORDER BY {$field}";
    $result = $conn->query($sql);
    $data = $result ? array_column($result->fetch_all(MYSQLI_ASSOC), 'valor') : [];
    $conn->close();

    return $data;
}

function buildWhereClause(array $filters, array &$params, string &$types): string
{
    $map = [
        'anio' => 'anio',
        'mes' => 'mes',
        'profesional' => 'profesional',
        'servicio' => 'servicio',
        'diagnostico' => 'diagnostico',
        'establecimiento' => 'establecimiento',
        'red' => 'red',
        'microred' => 'microred'
    ];

    $clauses = [];

    foreach ($map as $key => $column) {
        if (!empty($filters[$key])) {
            $values = is_array($filters[$key]) ? $filters[$key] : [$filters[$key]];
            $values = array_values(array_filter($values, static fn($v) => $v !== null && $v !== ''));
            if (!$values) {
                continue;
            }
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $clauses[] = "{$column} IN ({$placeholders})";
            foreach ($values as $v) {
                $params[] = (string) $v;
                $types .= 's';
            }
        }
    }

    return $clauses ? ' WHERE ' . implode(' AND ', $clauses) : '';
}
