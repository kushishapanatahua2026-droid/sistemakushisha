<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/auth.php';
requireAuth();
renderHeader('Generar Reporte');
?>
<div class="card mb-4">
    <div class="card-body">
        <h5>Filtros dinámicos</h5>
        <form id="filtrosForm" class="row g-3">
            <div class="col-md-2"><label class="form-label">Año</label><input name="Anio" class="form-control"></div>
            <div class="col-md-2"><label class="form-label">Mes</label><input name="Mes" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Profesional</label><input name="Nombres_Personal" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Servicio</label><input name="Descripcion_Ups" class="form-control"></div>
            <div class="col-md-2"><label class="form-label">Diagnóstico</label><input name="Descripcion_Item" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Tipo Diagnóstico</label><input name="Tipo_Diagnostico" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Condición Servicio</label><input name="Id_Condicion_Servicio" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Establecimiento</label><input name="Nombre_Establecimiento" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Red</label><input name="Descripcion_Red" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">MicroRed</label><input name="Descripcion_MicroRed" class="form-control"></div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
                <button type="button" id="btnLimpiar" class="btn btn-outline-secondary">Limpiar filtros</button>
            </div>
        </form>
    </div>
</div>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card"><div class="card-body">
            <table id="tablaReporte" class="table table-sm table-striped w-100"></table>
        </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3"><div class="card-body chart-container"><canvas id="chartReportePie"></canvas></div></div>
        <div class="card"><div class="card-body chart-container"><canvas id="chartReporteBar"></canvas></div></div>
    </div>
</div>
<script src="assets/reportes.js"></script>
<?php renderFooter(); ?>
