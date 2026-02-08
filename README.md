# Sistema de Gestión de Inventario

**Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro**

Sistema web para administrar inventario de productos: alta, edición, baja lógica, búsqueda y dashboard con resumen (total de productos, alertas de bajo stock y valor del inventario).

---

## Tecnologías utilizadas

| Área        | Tecnología        |
|------------|-------------------|
| Backend    | PHP 8.x           |
| Base de datos | MySQL 5.7+ / 8.x |
| Frontend   | HTML5, CSS3, JavaScript (vanilla) |
| Servidor   | Apache / Nginx o servidor integrado PHP |

---

## Estructura del proyecto

```
inventario-codexuspro/
├── config/
│   ├── config.php      # Configuración general
│   └── database.php    # Parámetros de conexión MySQL
├── public/
│   ├── api/
│   │   └── productos.php   # API REST (CRUD)
│   ├── css/
│   │   └── estilos.css
│   ├── js/
│   │   └── app.js
│   ├── index.php           # Entrada del dashboard
│   └── .htaccess
├── src/
│   ├── Database.php    # Conexión PDO
│   └── Producto.php    # Modelo y CRUD de productos
├── sql/
│   └── schema.sql      # Creación de BD y tabla
├── views/
│   └── layout.php      # Vista principal
├── bootstrap.php       # Carga de config y autoload
├── .env.example
└── README.md
```

---

## Requisitos

- PHP 8.0 o superior (extensiones: `pdo_mysql`, `json`, `mbstring`)
- MySQL 5.7+ o MariaDB equivalente
- Servidor web con soporte PHP (Apache, Nginx) o CLI de PHP para el servidor integrado

---

## Instalación y ejecución

### 1. Base de datos

Crear la base de datos y la tabla desde MySQL:

```bash
mysql -u root -p < sql/schema.sql
```

O ejecutar manualmente el contenido de `sql/schema.sql` en tu cliente MySQL (phpMyAdmin, DBeaver, etc.).

### 2. Configuración

Opcional: copiar `.env.example` a `.env` y definir las variables de entorno. Si no usas `.env`, se toman los valores por defecto de `config/database.php`:

- **DB_HOST**: `localhost`
- **DB_NAME**: `inventario_codexuspro`
- **DB_USER**: `root`
- **DB_PASS**: (vacío por defecto)

Ajusta `config/database.php` si no usas variables de entorno.

### 3. Servidor integrado de PHP (recomendado para desarrollo)

Desde la raíz del proyecto:

```bash
cd inventario-codexuspro
php -S localhost:8000 -t public
```

Abrir en el navegador: **http://localhost:8000**

### 4. Apache / Nginx

- **Document root** debe apuntar a la carpeta `public/`.
- En Apache, asegúrate de que `mod_rewrite` esté habilitado si usas el `.htaccess` incluido.

---

## Uso

- **Dashboard**: en la portada se muestran el resumen (total productos, bajo stock, valor inventario), la tabla de productos y la búsqueda.
- **Buscar**: usar el campo de búsqueda y pulsar “Buscar” (filtra por código, nombre o categoría).
- **Nuevo producto**: botón “Nuevo producto”, completar el formulario y guardar.
- **Editar**: botón “Editar” en cada fila; se abre el mismo formulario con los datos cargados.
- **Eliminar**: botón “Eliminar” (baja lógica; el producto deja de mostrarse en el listado).

La API en `public/api/productos.php` acepta:

- **GET** `?id=1` — un producto; sin `id` devuelve lista (opcional `q` para búsqueda).
- **POST** — cuerpo JSON con `codigo`, `nombre`, y opcionales `descripcion`, `categoria`, `precio`, `stock`, `stock_minimo`.
- **PUT** `?id=1` — actualizar producto (mismo cuerpo que POST).
- **DELETE** `?id=1` — baja lógica del producto.

---

## Buenas prácticas aplicadas

- Separación de configuración, lógica y presentación.
- Uso de PDO con consultas preparadas.
- Eliminación lógica en lugar de borrado físico.
- API REST con respuestas JSON y códigos HTTP coherentes.
- Código comentado en puntos clave y firma en archivos principales.
- Nombres de variables y métodos en español coherentes con el dominio.

---

*Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro*
<<<<<<< HEAD
=======

>>>>>>> 07fbddffd4988346a3d58c21cf1b8d97b885339f
