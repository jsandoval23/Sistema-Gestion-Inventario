<?php
/**
 * Modelo y operaciones CRUD de productos
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

declare(strict_types=1);

namespace App;

use PDO;

class Producto
{
    private PDO $db;
    private string $tabla = 'productos';

    public function __construct(PDO $connection)
    {
        $this->db = $connection;
    }

    /**
     * Lista todos los productos, con filtro opcional por búsqueda
     * @return array<int, array<string, mixed>>
     */
    public function listar(?string $busqueda = null): array
    {
        $sql = "SELECT id, codigo, nombre, descripcion, categoria, precio, stock, stock_minimo, activo, creado_en
                FROM {$this->tabla}
                WHERE activo = 1";

        $params = [];

        if ($busqueda !== null && $busqueda !== '') {
            $sql .= " AND (codigo LIKE :busqueda OR nombre LIKE :busqueda OR categoria LIKE :busqueda)";
            $params['busqueda'] = '%' . $busqueda . '%';
        }

        $sql .= " ORDER BY nombre ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un producto por ID
     */
    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Crea un nuevo producto
     * @param array<string, mixed> $datos
     */
    public function crear(array $datos): int
    {
        $campos = ['codigo', 'nombre', 'descripcion', 'categoria', 'precio', 'stock', 'stock_minimo'];
        $cols = implode(', ', $campos);
        $placeholders = ':' . implode(', :', $campos);

        $sql = "INSERT INTO {$this->tabla} ({$cols}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'codigo'       => $datos['codigo'],
            'nombre'       => $datos['nombre'],
            'descripcion'  => $datos['descripcion'] ?? null,
            'categoria'    => $datos['categoria'] ?? null,
            'precio'       => (float) ($datos['precio'] ?? 0),
            'stock'        => (int) ($datos['stock'] ?? 0),
            'stock_minimo' => (int) ($datos['stock_minimo'] ?? 0),
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Actualiza un producto existente
     * @param array<string, mixed> $datos
     */
    public function actualizar(int $id, array $datos): bool
    {
        $campos = ['codigo', 'nombre', 'descripcion', 'categoria', 'precio', 'stock', 'stock_minimo', 'activo'];
        $set = [];
        foreach ($campos as $c) {
            $set[] = "{$c} = :{$c}";
        }
        $setStr = implode(', ', $set);

        $sql = "UPDATE {$this->tabla} SET {$setStr} WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'           => $id,
            'codigo'       => $datos['codigo'],
            'nombre'       => $datos['nombre'],
            'descripcion'  => $datos['descripcion'] ?? null,
            'categoria'    => $datos['categoria'] ?? null,
            'precio'       => (float) ($datos['precio'] ?? 0),
            'stock'        => (int) ($datos['stock'] ?? 0),
            'stock_minimo' => (int) ($datos['stock_minimo'] ?? 0),
            'activo'       => isset($datos['activo']) ? (int) $datos['activo'] : 1,
        ]);
    }

    /**
     * Eliminación lógica (marca activo = 0)
     */
    public function eliminar(int $id): bool
    {
        $sql = "UPDATE {$this->tabla} SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Resumen para dashboard: total productos, bajo stock, etc.
     * @return array<string, mixed>
     */
    public function resumen(): array
    {
        $sql = "SELECT
                    COUNT(*) AS total_productos,
                    SUM(CASE WHEN stock <= stock_minimo AND stock_minimo > 0 THEN 1 ELSE 0 END) AS bajo_stock,
                    SUM(stock * precio) AS valor_inventario
                FROM {$this->tabla}
                WHERE activo = 1";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['total_productos' => 0, 'bajo_stock' => 0, 'valor_inventario' => 0];
    }
}
