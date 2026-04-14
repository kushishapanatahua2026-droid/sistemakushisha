# Sistema Web - Importación Excel + Dashboard

Sistema web en **PHP 8 + MySQL + Bootstrap 5** para importar Excel (74 campos) y generar reportes dinámicos con tablas y gráficos.

## Requisitos
- PHP 8.1+
- MySQL 8+
- Composer
- Extensiones PHP: pdo_mysql, zip, gd, mbstring

## Instalación
1. Clonar proyecto y entrar al directorio.
2. Instalar dependencias:
   ```bash
   composer install
   ```
3. Crear la base de datos y tablas:
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
4. Configurar credenciales de base de datos en `config/config.php`.
5. Levantar servidor local:
   ```bash
   php -S localhost:8000 -t public
   ```
6. Acceder a `http://localhost:8000`.

## Estructura
- `public/`: rutas web y assets.
- `public/api/`: endpoints JSON para tablas/gráficos.
- `src/`: clases de negocio (`ExcelImporter`).
- `sql/schema.sql`: script de creación de base de datos y tabla principal.
- `uploads/`: almacenamiento de archivos importados.

## Funcionalidades
- Dashboard con acceso directo (sin login).
- Carga de Excel (.xlsx, .xls, .xlsm).
- Importación a MySQL con los 74 campos.
- Tabla dinámica DataTables con exportación Excel/PDF/CSV.
- Dashboard con indicadores y gráficos (barras, pastel, líneas).
- Reportes dinámicos con filtros y actualización de gráficos.
