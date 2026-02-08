<?php
/**
 * Configuración de conexión a base de datos
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

declare(strict_types=1);

return [
    'driver'   => 'mysql',
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'port'     => (int) (getenv('DB_PORT') ?: 3307),
    'database' => getenv('DB_NAME') ?: 'inventario_codexuspro',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset'  => 'utf8mb4',
];
