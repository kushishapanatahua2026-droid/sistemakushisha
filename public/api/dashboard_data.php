<?php

declare(strict_types=1);

require_once __DIR__ . '/_common.php';

$pdo = db();
$kpis = [
    'total_registros' => (int)$pdo->query('SELECT COUNT(*) FROM atenciones')->fetchColumn(),
    'total_servicios' => (int)$pdo->query('SELECT COUNT(DISTINCT Descripcion_Ups) FROM atenciones')->fetchColumn(),
    'total_profesionales' => (int)$pdo->query('SELECT COUNT(DISTINCT Numero_Documento_Personal) FROM atenciones')->fetchColumn(),
    'total_diagnosticos' => (int)$pdo->query('SELECT COUNT(DISTINCT Codigo_Item) FROM atenciones')->fetchColumn(),
];

$top = $pdo->query("SELECT Descripcion_Item label, COUNT(*) value FROM atenciones GROUP BY Descripcion_Item ORDER BY value DESC LIMIT 10")->fetchAll();
$tipo = $pdo->query("SELECT Tipo_Diagnostico label, COUNT(*) value FROM atenciones GROUP BY Tipo_Diagnostico ORDER BY value DESC")->fetchAll();
$fecha = $pdo->query("SELECT Fecha_Atencion label, COUNT(*) value FROM atenciones GROUP BY Fecha_Atencion ORDER BY Fecha_Atencion ASC LIMIT 30")->fetchAll();

jsonResponse([
    'kpis' => $kpis,
    'top_diagnosticos' => ['labels' => array_column($top, 'label'), 'values' => array_map('intval', array_column($top, 'value'))],
    'tipo_diagnostico' => ['labels' => array_column($tipo, 'label'), 'values' => array_map('intval', array_column($tipo, 'value'))],
    'por_fecha' => ['labels' => array_column($fecha, 'label'), 'values' => array_map('intval', array_column($fecha, 'value'))],
]);
