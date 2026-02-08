<?php
/**
 * Carga de configuración y autoload
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

declare(strict_types=1);

define('APP_ROOT', __DIR__);

require_once APP_ROOT . '/config/config.php';
$dbConfig = require APP_ROOT . '/config/database.php';

// Autoload simple para namespace App
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = APP_ROOT . '/src/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

\App\Database::setConfig($dbConfig);
