<?php
/**
 * API REST de productos (CRUD)
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once dirname(__DIR__, 2) . '/bootstrap.php';

$db = \App\Database::getConnection();
$producto = new \App\Producto($db);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode((string) file_get_contents('php://input'), true) ?? [];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

function enviarJson(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

try {
    switch ($method) {
        case 'GET':
            if ($id > 0) {
                $item = $producto->obtenerPorId($id);
                if (!$item) {
                    enviarJson(['error' => 'Producto no encontrado'], 404);
                    exit;
                }
                enviarJson($item);
            } else {
                $q = isset($_GET['q']) ? trim((string) $_GET['q']) : null;
                $lista = $producto->listar($q);
                enviarJson(['productos' => $lista]);
            }
            break;

        case 'POST':
            $codigo = trim((string) ($input['codigo'] ?? ''));
            $nombre = trim((string) ($input['nombre'] ?? ''));
            if ($codigo === '' || $nombre === '') {
                enviarJson(['error' => 'Código y nombre son obligatorios'], 400);
                exit;
            }
            $nuevoId = $producto->crear($input);
            enviarJson(['id' => $nuevoId, 'mensaje' => 'Producto creado'], 201);
            break;

        case 'PUT':
            if ($id <= 0) {
                enviarJson(['error' => 'ID requerido'], 400);
                exit;
            }
            $existe = $producto->obtenerPorId($id);
            if (!$existe) {
                enviarJson(['error' => 'Producto no encontrado'], 404);
                exit;
            }
            $producto->actualizar($id, $input);
            enviarJson(['mensaje' => 'Producto actualizado']);
            break;

        case 'DELETE':
            if ($id <= 0) {
                enviarJson(['error' => 'ID requerido'], 400);
                exit;
            }
            $existe = $producto->obtenerPorId($id);
            if (!$existe) {
                enviarJson(['error' => 'Producto no encontrado'], 404);
                exit;
            }
            $producto->eliminar($id);
            enviarJson(['mensaje' => 'Producto eliminado']);
            break;

        default:
            enviarJson(['error' => 'Método no permitido'], 405);
    }
} catch (Throwable $e) {
    enviarJson(['error' => 'Error del servidor: ' . $e->getMessage()], 500);
}
