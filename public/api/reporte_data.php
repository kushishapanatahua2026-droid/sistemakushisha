<?php

declare(strict_types=1);

require_once __DIR__ . '/_common.php';

$allowed = ['Anio','Mes','Nombres_Personal','Descripcion_Ups','Descripcion_Item','Tipo_Diagnostico','Id_Condicion_Servicio','Nombre_Establecimiento','Descripcion_Red','Descripcion_MicroRed'];
[$where, $params] = buildWhere($allowed);
$pdo = db();

$tableSql = "SELECT Fecha_Atencion,
        CONCAT(Apellido_Paterno_Paciente,' ',Apellido_Materno_Paciente,' ',Nombres_Paciente) paciente,
        CONCAT(Apellido_Paterno_Personal,' ',Apellido_Materno_Personal,' ',Nombres_Personal) profesional,
        Descripcion_Ups,Descripcion_Item,Tipo_Diagnostico
        FROM atenciones $where ORDER BY id DESC LIMIT 2000";
$stmt = $pdo->prepare($tableSql);
$stmt->execute($params);
$table = $stmt->fetchAll();

$tipoSql = "SELECT Tipo_Diagnostico label, COUNT(*) value FROM atenciones $where GROUP BY Tipo_Diagnostico";
$stmtTipo = $pdo->prepare($tipoSql);
$stmtTipo->execute($params);
$tipo = $stmtTipo->fetchAll();

$servSql = "SELECT Descripcion_Ups label, COUNT(*) value FROM atenciones $where GROUP BY Descripcion_Ups ORDER BY value DESC LIMIT 10";
$stmtServ = $pdo->prepare($servSql);
$stmtServ->execute($params);
$serv = $stmtServ->fetchAll();

jsonResponse([
    'table' => $table,
    'tipo_diagnostico' => ['labels' => array_column($tipo, 'label'), 'values' => array_map('intval', array_column($tipo, 'value'))],
    'servicios' => ['labels' => array_column($serv, 'label'), 'values' => array_map('intval', array_column($serv, 'value'))],
]);
