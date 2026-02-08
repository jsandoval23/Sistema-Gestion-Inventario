<?php
/**
 * Configuración general de la aplicación
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

declare(strict_types=1);

// Evitar acceso directo
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

return [
    'app_name'    => 'Sistema de Gestión de Inventario',
    'app_version' => '1.0.0',
    'environment' => getenv('APP_ENV') ?: 'development',
    'debug'       => filter_var(getenv('APP_DEBUG') ?: true, FILTER_VALIDATE_BOOLEAN),
    'timezone'    => 'America/Mexico_City',
];
