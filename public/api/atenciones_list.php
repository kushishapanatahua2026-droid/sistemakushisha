<?php

declare(strict_types=1);

require_once __DIR__ . '/_common.php';

$sql = "SELECT Id_Cita, Fecha_Atencion,
        CONCAT(Apellido_Paterno_Paciente,' ',Apellido_Materno_Paciente,' ',Nombres_Paciente) paciente,
        CONCAT(Apellido_Paterno_Personal,' ',Apellido_Materno_Personal,' ',Nombres_Personal) profesional,
        Descripcion_Ups, Descripcion_Item, Tipo_Diagnostico
        FROM atenciones ORDER BY id DESC LIMIT 1000";
$rows = db()->query($sql)->fetchAll();
jsonResponse(['data' => $rows]);
