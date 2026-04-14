<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';

initializeDatabase();
?><!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="wrapper d-flex">
    <aside class="sidebar bg-dark text-white p-3">
        <h5 class="mb-4">Dashboard Salud</h5>
        <ul class="nav flex-column gap-2">
            <li><a class="nav-link text-white active" data-page="upload" href="#">Cargar Excel</a></li>
            <li><a class="nav-link text-white" data-page="report" href="#">Generar Reporte</a></li>
        </ul>
    </aside>
    <main class="content flex-grow-1">
        <nav class="navbar navbar-expand-lg bg-white border-bottom px-3">
            <span class="navbar-brand mb-0 h1">Sistema Web Profesional</span>
        </nav>

        <div class="container-fluid p-4">
            <section id="page-upload" class="page-section">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Cargar Excel</h5>
                        <form id="uploadForm" class="row g-3">
                            <div class="col-md-8">
                                <input type="file" class="form-control" name="excel_file" id="excelFile" accept=".xlsx,.xls,.xlsm" required>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button type="submit" class="btn btn-primary">Importar Excel</button>
                            </div>
                        </form>
                        <small class="text-muted">Formatos permitidos: .xlsx, .xls, .xlsm</small>
                        <div id="uploadAlert" class="mt-3"></div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Últimos registros importados</h6>
                        <div class="table-responsive">
                            <table id="tablaRegistros" class="table table-striped table-bordered w-100">
                                <thead>
                                <tr>
                                    <th>ID</th><th>Año</th><th>Mes</th><th>Fecha</th><th>Profesional</th><th>Servicio</th>
                                    <th>Diagnóstico</th><th>Establecimiento</th><th>Red</th><th>Microred</th><th>Categoría</th><th>Estado</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section id="page-report" class="page-section d-none">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Generar Reporte</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Indicadores (múltiple)</label>
                                <select id="indicadores" class="form-select" multiple size="9">
                                    <option value="total_registros">Total de registros</option>
                                    <option value="totales_categoria">Totales por categoría</option>
                                    <option value="totales_estado">Totales por estado</option>
                                    <option value="totales_fecha">Totales por fecha</option>
                                    <option value="atenciones_profesionales">Atenciones por profesionales</option>
                                    <option value="top10_diagnosticos">10 diagnósticos más recurrentes</option>
                                    <option value="cantidad_servicio">Cantidad de servicio</option>
                                    <option value="condicion_servicio">Atención por condición de servicio</option>
                                    <option value="tipo_diagnostico">Atención por tipo de diagnóstico</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-2" id="filtersContainer"></div>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button id="btnGenerar" class="btn btn-success" type="button">Generar Dashboard</button>
                                <button id="btnLimpiar" class="btn btn-outline-secondary" type="button">Limpiar filtros</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="kpiRow" class="row g-3 mb-3"></div>
                <div id="chartsRow" class="row g-3"></div>
            </section>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/app.js"></script>
</body>
</html>
