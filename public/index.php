<?php
/**
 * Punto de entrada principal - Dashboard de inventario
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$db = \App\Database::getConnection();
$producto = new \App\Producto($db);
$productos = $producto->listar();
$resumen = $producto->resumen();

$busqueda = isset($_GET['q']) ? trim((string) $_GET['q']) : null;
if ($busqueda !== null && $busqueda !== '') {
    $productos = $producto->listar($busqueda);
}

require dirname(__DIR__) . '/views/layout.php';
