/**
 * Lógica del dashboard - Sistema de Gestión de Inventario
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

(function () {
    'use strict';

    const baseUrl = document.querySelector('script[src*="app.js"]')?.src?.replace(/\/js\/app\.js.*$/, '') || '';
    const apiUrl = baseUrl + '/api/productos.php';

    const modal = document.getElementById('modalProducto');
    const form = document.getElementById('formProducto');
    const modalTitulo = document.getElementById('modalTitulo');
    const productoId = document.getElementById('productoId');

    function abrirModal(editar = false) {
        modal.setAttribute('aria-hidden', 'false');
        modalTitulo.textContent = editar ? 'Editar producto' : 'Nuevo producto';
        if (!editar) {
            form.reset();
            productoId.value = '';
        }
    }

    function cerrarModal() {
        modal.setAttribute('aria-hidden', 'true');
    }

    function obtenerProducto(id) {
        return fetch(apiUrl + '?id=' + encodeURIComponent(id))
            .then(r => {
                if (!r.ok) throw new Error('No se pudo cargar el producto');
                return r.json();
            });
    }

    function guardarProducto(datos) {
        const esEdicion = datos.id && parseInt(datos.id, 10) > 0;
        const url = apiUrl + (esEdicion ? '?id=' + datos.id : '');
        const options = {
            method: esEdicion ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        };
        return fetch(url, options).then(r => {
            const contentType = r.headers.get('Content-Type') || '';
            const body = contentType.includes('json') ? r.json() : r.text();
            if (!r.ok) return body.then(msg => { throw new Error(typeof msg === 'object' && msg.error ? msg.error : 'Error al guardar'); });
            return body;
        });
    }

    function eliminarProducto(id) {
        if (!confirm('¿Eliminar este producto? Se realizará una baja lógica.')) return;
        return fetch(apiUrl + '?id=' + encodeURIComponent(id), { method: 'DELETE' })
            .then(r => r.json())
            .then(() => { window.location.reload(); })
            .catch(err => alert('Error: ' + err.message));
    }

    document.getElementById('btnNuevo').addEventListener('click', () => abrirModal(false));

    document.getElementById('btnCerrarModal').addEventListener('click', cerrarModal);
    document.getElementById('btnCancelar').addEventListener('click', cerrarModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) cerrarModal();
    });

    document.querySelectorAll('.btnEditar').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = parseInt(this.getAttribute('data-id'), 10);
            abrirModal(true);
            obtenerProducto(id).then(p => {
                productoId.value = p.id;
                document.getElementById('codigo').value = p.codigo || '';
                document.getElementById('nombre').value = p.nombre || '';
                document.getElementById('descripcion').value = p.descripcion || '';
                document.getElementById('categoria').value = p.categoria || '';
                document.getElementById('precio').value = p.precio ?? '';
                document.getElementById('stock').value = p.stock ?? '';
                document.getElementById('stock_minimo').value = p.stock_minimo ?? '';
            }).catch(() => alert('No se pudo cargar el producto'));
        });
    });

    document.querySelectorAll('.btnEliminar').forEach(btn => {
        btn.addEventListener('click', function () {
            eliminarProducto(parseInt(this.getAttribute('data-id'), 10));
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const fd = new FormData(form);
        const datos = {
            id: fd.get('id') || undefined,
            codigo: fd.get('codigo'),
            nombre: fd.get('nombre'),
            descripcion: fd.get('descripcion'),
            categoria: fd.get('categoria'),
            precio: fd.get('precio'),
            stock: fd.get('stock'),
            stock_minimo: fd.get('stock_minimo')
        };
        if (datos.id) datos.id = parseInt(datos.id, 10);
        guardarProducto(datos)
            .then(() => { cerrarModal(); window.location.reload(); })
            .catch(err => alert(err.message));
    });
})();
