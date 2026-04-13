<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/auth.php';

use App\ExcelImporter;

requireAuth();
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['excel']) || $_FILES['excel']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Debe seleccionar un archivo válido.';
    } else {
        $tmpName = $_FILES['excel']['tmp_name'];
        $originalName = $_FILES['excel']['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, ALLOWED_EXTENSIONS, true)) {
            $error = 'Formato no permitido.';
        } elseif ($_FILES['excel']['size'] > MAX_UPLOAD_SIZE) {
            $error = 'Archivo demasiado grande.';
        } else {
            $target = UPLOAD_DIR . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $originalName);
            if (move_uploaded_file($tmpName, $target)) {
                $importer = new ExcelImporter(db());
                $inserted = $importer->import($target);
                $message = "Importación completada. Filas insertadas: {$inserted}";
            } else {
                $error = 'No fue posible guardar el archivo.';
            }
        }
    }
}

renderHeader('Cargar Excel');
?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">Importar archivo Excel</h5>
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-8">
                <input type="file" name="excel" class="form-control" accept=".xlsx,.xls,.xlsm" required>
            </div>
            <div class="col-md-4 d-grid">
                <button class="btn btn-primary">Importar Excel</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h5>Vista de registros importados</h5>
        <div class="table-responsive">
            <table id="tablaAtenciones" class="table table-striped table-sm w-100">
                <thead><tr><th>ID Cita</th><th>Fecha Atención</th><th>Paciente</th><th>Profesional</th><th>Servicio</th><th>Diagnóstico</th><th>Tipo Diagnóstico</th></tr></thead>
            </table>
        </div>
    </div>
</div>
<script src="assets/upload.js"></script>
<?php renderFooter(); ?>
