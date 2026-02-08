<?php
/**
 * Vista principal del dashboard - Layout
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */
$titulo = 'Dashboard - Sistema de Inventario';
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/css/estilos.css">
</head>
<body>
    <div class="contenedor">
        <header class="cabecera">
            <h1>Sistema de Gestión de Inventario</h1>
            <p class="firma">Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro</p>
        </header>

        <section class="resumen">
            <div class="tarjeta">
                <span class="tarjeta-etiqueta">Total productos</span>
                <span class="tarjeta-valor"><?= (int) ($resumen['total_productos'] ?? 0) ?></span>
            </div>
            <div class="tarjeta tarjeta--alerta">
                <span class="tarjeta-etiqueta">Bajo stock</span>
                <span class="tarjeta-valor"><?= (int) ($resumen['bajo_stock'] ?? 0) ?></span>
            </div>
            <div class="tarjeta">
                <span class="tarjeta-etiqueta">Valor inventario</span>
                <span class="tarjeta-valor">$<?= number_format((float) ($resumen['valor_inventario'] ?? 0), 2) ?></span>
            </div>
        </section>

        <section class="acciones">
            <form class="busqueda" method="get" action="">
                <input type="search" name="q" placeholder="Buscar por código, nombre o categoría..."
                    value="<?= htmlspecialchars($busqueda ?? '') ?>">
                <button type="submit">Buscar</button>
            </form>
            <button type="button" class="btn btn--primario" id="btnNuevo">Nuevo producto</button>
        </section>

        <section class="tabla-seccion">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Stock mín.</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="7" class="tabla-vacio">No hay productos que mostrar.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                    <tr data-id="<?= (int) $p['id'] ?>" class="<?= ($p['stock'] <= $p['stock_minimo'] && $p['stock_minimo'] > 0) ? 'fila-bajo-stock' : '' ?>">
                        <td><?= htmlspecialchars($p['codigo']) ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                        <td>$<?= number_format((float) $p['precio'], 2) ?></td>
                        <td><?= (int) $p['stock'] ?></td>
                        <td><?= (int) $p['stock_minimo'] ?></td>
                        <td>
                            <button type="button" class="btn btn--small btnEditar" data-id="<?= (int) $p['id'] ?>">Editar</button>
                            <button type="button" class="btn btn--small btn--peligro btnEliminar" data-id="<?= (int) $p['id'] ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- Modal formulario producto -->
    <div id="modalProducto" class="modal" aria-hidden="true">
        <div class="modal-contenido">
            <div class="modal-cabecera">
                <h2 id="modalTitulo">Nuevo producto</h2>
                <button type="button" class="modal-cerrar" id="btnCerrarModal" aria-label="Cerrar">&times;</button>
            </div>
            <form id="formProducto">
                <input type="hidden" name="id" id="productoId">
                <div class="form-grupo">
                    <label for="codigo">Código *</label>
                    <input type="text" id="codigo" name="codigo" required maxlength="50">
                </div>
                <div class="form-grupo">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" required maxlength="255">
                </div>
                <div class="form-grupo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="2"></textarea>
                </div>
                <div class="form-grupo">
                    <label for="categoria">Categoría</label>
                    <input type="text" id="categoria" name="categoria" maxlength="100">
                </div>
                <div class="form-fila">
                    <div class="form-grupo">
                        <label for="precio">Precio *</label>
                        <input type="number" id="precio" name="precio" step="0.01" min="0" value="0" required>
                    </div>
                    <div class="form-grupo">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" value="0">
                    </div>
                    <div class="form-grupo">
                        <label for="stock_minimo">Stock mínimo</label>
                        <input type="number" id="stock_minimo" name="stock_minimo" min="0" value="0">
                    </div>
                </div>
                <div class="form-acciones">
                    <button type="button" class="btn" id="btnCancelar">Cancelar</button>
                    <button type="submit" class="btn btn--primario">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= htmlspecialchars($baseUrl) ?>/js/app.js"></script>
</body>
</html>
