<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/auth.php';
requireAuth();
renderHeader('Dashboard');
?>
<div class="row g-3 mb-4" id="kpiCards">
    <div class="col-md-3"><div class="card card-indicator"><div class="card-body"><h6>Total Registros</h6><h3 id="kpiTotal">0</h3></div></div></div>
    <div class="col-md-3"><div class="card card-indicator"><div class="card-body"><h6>Servicios</h6><h3 id="kpiServicios">0</h3></div></div></div>
    <div class="col-md-3"><div class="card card-indicator"><div class="card-body"><h6>Profesionales</h6><h3 id="kpiProfesionales">0</h3></div></div></div>
    <div class="col-md-3"><div class="card card-indicator"><div class="card-body"><h6>Diagnósticos</h6><h3 id="kpiDiagnosticos">0</h3></div></div></div>
</div>
<div class="row g-3">
    <div class="col-lg-6"><div class="card"><div class="card-body chart-container"><canvas id="chartTopDiagnosticos"></canvas></div></div></div>
    <div class="col-lg-6"><div class="card"><div class="card-body chart-container"><canvas id="chartTipoDiag"></canvas></div></div></div>
    <div class="col-lg-12"><div class="card"><div class="card-body chart-container"><canvas id="chartPorFecha"></canvas></div></div></div>
</div>
<script src="assets/dashboard.js"></script>
<?php renderFooter(); ?>
