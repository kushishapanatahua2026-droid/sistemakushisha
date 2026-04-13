<?php

declare(strict_types=1);

namespace App;

use PDO;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImporter
{
    public const COLUMNS = [
        'Id_Cita','Anio','Mes','Dia','Fecha_Atencion','Lote','Num_Pag','Num_Reg','Id_Ups','Descripcion_Ups',
        'Descripcion_Sector','Descripcion_Disa','Descripcion_Red','Descripcion_MicroRed','Codigo_Unico','Nombre_Establecimiento',
        'Abrev_Tipo_Doc_Paciente','Numero_Documento_Paciente','Apellido_Paterno_Paciente','Apellido_Materno_Paciente','Nombres_Paciente',
        'Fecha_Nacimiento_Paciente','Genero','Id_Etnia','Descripcion_Etnia','Historia_Clinica','Ficha_Familiar','Id_Financiador',
        'Descripcion_Financiador','Descripcion_Pais','Abrev_Tipo_Doc_Personal','Numero_Documento_Personal','Apellido_Paterno_Personal',
        'Apellido_Materno_Personal','Nombres_Personal','Fecha_Nacimiento_Personal','Id_Condicion','Descripcion_Condicion','Id_Profesion',
        'Descripcion_Profesion','Id_Colegio','Descripcion_Colegio','Numero_Colegiatura','Abrev_Tipo_Doc_Registrador',
        'Numero_Documento_Registrador','Apellido_Paterno_Registrador','Apellido_Materno_Registrador','Nombres_Registrador',
        'Fecha_Nacimiento_Registrador','Id_Condicion_Establecimiento','Id_Condicion_Servicio','Edad_Reg','Tipo_Edad','Anio_Actual_Paciente',
        'Mes_Actual_Paciente','Dia_Actual_Paciente','Id_Turno','Codigo_Item','Descripcion_Item','Tipo_Diagnostico','Valor_Lab',
        'Id_Correlativo','Peso','Talla','Hemoglobina','Perimetro_Abdominal','Perimetro_Cefalico','Descripcion_Otra_Condicion',
        'Fecha_Ultima_Regla','Fecha_Solicitud_Hb','Fecha_Resultado_Hb','Fecha_Registro','Fecha_Modificacion','Intervalo'
    ];

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function import(string $filePath): int
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (count($rows) <= 1) {
            return 0;
        }

        $placeholders = implode(',', array_fill(0, count(self::COLUMNS), '?'));
        $columnsSql = implode(',', self::COLUMNS);
        $sql = "INSERT INTO atenciones ($columnsSql) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        $inserted = 0;
        $this->pdo->beginTransaction();
        try {
            foreach (array_slice($rows, 1) as $row) {
                if (count(array_filter($row, fn($value) => $value !== null && $value !== '')) === 0) {
                    continue;
                }

                $normalized = array_pad(array_slice($row, 0, count(self::COLUMNS)), count(self::COLUMNS), null);
                $stmt->execute($normalized);
                $inserted++;
            }
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return $inserted;
    }
}
