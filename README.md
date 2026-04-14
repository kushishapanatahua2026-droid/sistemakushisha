# Sistema Web Profesional: Importar Excel + Dashboard Dinámico

Sistema en PHP 8+ sin MVC, estructurado por carpetas, para importar Excel, almacenar en MySQL y generar dashboards interactivos con filtros múltiples.

## Tecnologías usadas
- PHP 8+
- MySQL
- Bootstrap 5
- DataTables + exportaciones (Excel/PDF/CSV)
- Chart.js
- PhpSpreadsheet

## Estructura
- `config/`
- `assets/`
- `uploads/`
- `ajax/`
- `includes/`
- `public/`
- `sql/`

## Configuración rápida
Editar `config/config.php`:
```php
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'dashboard_salud';
```

## Instalación (simple)
1. Copiar carpeta al servidor.
2. Crear base de datos (opcional, el sistema la crea automáticamente).
3. Ejecutar dependencias:
   ```bash
   composer install
   ```
4. Configurar `config/config.php`.
5. Abrir `public/index.php`.

## Flujo funcional
### Menú 1: Cargar Excel
- Permite `.xlsx`, `.xls`, `.xlsm`.
- Inserta 74 campos fijos por fila.
- Tabla dinámica con buscador, paginación, ordenamiento y exportaciones.

### Menú 2: Generar Reporte
- Selección múltiple de indicadores.
- Filtros por Año, Mes, Profesional, Servicio, Diagnóstico, Establecimiento, Red, Microred.
- Dashboard dinámico con tarjetas KPI, gráficos y tablas resumen.

## Escalabilidad
- Inserción en transacción.
- Índices en columnas críticas para reportes.
- Carga parcial en tabla de visualización para mantener rendimiento.

## Producción
- Activar OPcache.
- Ajustar `memory_limit` y `upload_max_filesize`.
- Configurar permisos de escritura para `uploads/`.
