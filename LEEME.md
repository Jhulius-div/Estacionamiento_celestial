# Sistema Web de Estacionamiento (Laravel 13 + Celestial Admin)

## Requisitos
PHP 8.3+, Composer, XAMPP (MySQL/phpMyAdmin).

## Instalación (PowerShell, dentro de la carpeta del proyecto)
1. `composer install`
2. `Copy-Item .env.example .env`
3. `php artisan key:generate`
4. Base de datos (elige UNA opción):
   - **Opción A (recomendada):** abre phpMyAdmin → Importar → `database/estacionamiento_db.sql`
     (el script crea la base `estacionamiento_db` con datos de ejemplo).
   - **Opción B:** crea la base vacía `estacionamiento_db` y ejecuta `php artisan migrate --seed`.
5. `php artisan serve` y abre http://127.0.0.1:8000

## Usuarios de prueba
| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | admin@estacionamiento.com | admin123 |
| Operador | operador@estacionamiento.com | operador123 |
| Supervisor | supervisor@estacionamiento.com | supervisor123 |

## Permisos por rol (middleware `rol`)
- Administrador: todo.
- Operador: dashboard, espacios (solo ver), clientes, vehículos, entradas/salidas, caja.
- Supervisor: dashboard, espacios (solo ver), reportes.

## Estructura
- `app/Models`, `app/Http/Controllers`, `app/Http/Middleware/RolMiddleware.php`, `app/Support/Tarifa.php`
- `resources/views` (layout con navbar/sidebar de Celestial, un módulo por carpeta)
- `public/js/sistema.js` (CRUD con modales, jQuery, SweetAlert)
- `public/celestial` (plantilla, MIT) y `public/librerias` (DataTables, SweetAlert): todo local, sin CDN.
- El logo se sube en **Configuración** y se guarda en `public/uploads`.

## Tarifa
Se cobra por hora o fracción (mínimo 1 hora) según la tarifa del tipo de vehículo (editable en Tablas → Tarifas).
