<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $payload = json_decode(file_get_contents('php://input') ?: '{}', true, 512, JSON_THROW_ON_ERROR);
    $indicators = $payload['indicators'] ?? [];
    $filters = $payload['filters'] ?? [];

    $allowed = [
        'total_registros',
        'totales_categoria',
        'totales_estado',
        'totales_fecha',
        'atenciones_profesionales',
        'top10_diagnosticos',
        'cantidad_servicio',
        'condicion_servicio',
        'tipo_diagnostico'
    ];
    $indicators = array_values(array_intersect($allowed, is_array($indicators) ? $indicators : []));

    $conn = getPdoForData();

    $params = [];
    $types = '';
    $where = buildWhereClause($filters, $params, $types);

    $out = ['kpis' => [], 'charts' => []];

    $runGrouped = static function (mysqli $conn, string $sql, array $params, string $types): array {
        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    };

    if (in_array('total_registros', $indicators, true)) {
        $sql = "SELECT COUNT(*) AS total FROM registros_salud {$where}";
        $rows = $runGrouped($conn, $sql, $params, $types);
        $out['kpis']['total_registros'] = (int) ($rows[0]['total'] ?? 0);
    }

    $groupDefs = [
        'totales_categoria' => ['categoria', 'Totales por categoría', 'bar'],
        'totales_estado' => ['estado', 'Totales por estado', 'pie'],
        'totales_fecha' => ['fecha', 'Totales por fecha', 'line'],
        'atenciones_profesionales' => ['profesional', 'Atenciones por profesionales', 'bar'],
        'top10_diagnosticos' => ['diagnostico', 'Top 10 diagnósticos', 'bar', 10],
        'cantidad_servicio' => ['servicio', 'Cantidad de servicio', 'bar'],
        'condicion_servicio' => ['condicion_servicio', 'Atención por condición de servicio', 'doughnut'],
        'tipo_diagnostico' => ['tipo_diagnostico', 'Atención por tipo de diagnóstico', 'polarArea']
    ];

    foreach ($groupDefs as $key => $def) {
        if (!in_array($key, $indicators, true)) {
            continue;
        }

        [$column, $title, $chartType] = $def;
        $limit = $def[3] ?? 100;

        $sql = "SELECT {$column} AS etiqueta, COUNT(*) AS total
                FROM registros_salud {$where}
                AND {$column} IS NOT NULL AND {$column} <> ''
                GROUP BY {$column}
                ORDER BY total DESC
                LIMIT {$limit}";

        if ($where === '') {
            $sql = str_replace('FROM registros_salud  AND', 'FROM registros_salud WHERE', $sql);
        }

        $rows = $runGrouped($conn, $sql, $params, $types);
        $out['charts'][$key] = [
            'title' => $title,
            'type' => $chartType,
            'labels' => array_column($rows, 'etiqueta'),
            'values' => array_map('intval', array_column($rows, 'total')),
            'table' => $rows
        ];
    }

    $conn->close();

    echo json_encode(['ok' => true, 'data' => $out], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
