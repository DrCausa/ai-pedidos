# Pedidos de Impresión

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![WordPress](https://img.shields.io/badge/WordPress-6.6+-blue)
![PHP](https://img.shields.io/badge/PHP-8.0+-violet)
![License](https://img.shields.io/badge/license-MIT-green)
![Last Commit](https://img.shields.io/github/last-commit/DrCausa/ai-pedidos)

**Pedidos de Impresión** es un plugin de WordPress que registra un **Custom Post Type (CPT)** para gestionar pedidos de impresión, incluyendo:

- Campos personalizados (metaboxes) como tamaño, papel, acabado, cantidad y estado.
- Filtro por estado en la administración de pedidos.
- API REST disponible en `/wp-json/ai/v1/pedidos` con soporte para filtros y paginación.

---

## Características

- CPT `ai_pedido` para gestionar pedidos de impresión.
- Metaboxes para ingresar y editar información de los pedidos.
- Filtro en el listado de pedidos por estado: `Pendiente`, `En proceso`, `Listo`.
- API REST con parámetros opcionales: `estado`, `limit` y `page`.

---

## Instalación

1. Clona o descarga este repositorio en la carpeta de plugins de WordPress:

```bash
git clone https://github.com/DrCausa/ai-pedidos.git wp-content/plugins/ai-pedidos
```

2. Instala las dependencias y genera el autoload de Composer:

```bash
composer install
```

> 💡 **NOTA:** La carpeta `vendor/` y `composer.lock` **no se suben** al repositorio, están en `.gitignore`. Composer es necesario para que el plugin cargue sus clases correctamente.

3. Activa el plugin desde el panel de administración de WordPress.
4. El CPT **Pedidos de Impresión** aparecerá en el menú de administración como `Pedidos`.

---

## Uso

### Administración de pedidos

1. Añade un nuevo pedido desde **Pedidos → Añadir Nuevo Pedido**.
2. Completa los campos:

- Tamaño
- Papel
- Acabado
- Cantidad
- Estado (`Pendiente` / `En proceso` / `Listo`)

3. Filtra los pedidos por estado usando el selector en la lista de pedidos.

### API REST

- Endpoint: `/wp-json/ai/v1/pedidos`
- Método: `GET`
- Parámetros opcionales:
  - `estado` → Filtra por estado (`pending`, `in-progress`, `ready`)
  - `limit` → Cantidad de resultados por página (default: 10)
  - `page` → Número de página (default: 1)

**Ejemplo de uso:**

```bash
curl https://tusitio.com/wp-json/ai/v1/pedidos?estado=pending&limit=5&page=1
```

**Respuesta esperada:**

```json
{
  "total": 1,
  "per_page": 5,
  "current_page": 1,
  "total_pages": 1,
  "pedidos": [
    {
      "id": 123,
      "title": "Mi Primer Pedido",
      "author": "DrCausa",
      "size": "A4",
      "paper": "Couche",
      "finish": "Brillante",
      "quantity": 50,
      "status": "pending"
    }
  ]
}
```

---

## Desarrollo

Este plugin sigue **PSR-4 autoloading** y está organizado en namespaces:

- `AI\Pedidos\PostTypes` → Registro del CPT.
- `AI\Pedidos\Metaboxes` → Gestión de metaboxes y columnas personalizadas.
- `AI\Pedidos\REST` → Endpoints de API REST.

Para desarrollo, instala las dependencias de Composer:

```bash
composer install
```

> 📌 **IMPORTANTE:** La carpeta `vendor/` y `composer.lock` **no se suben** al repositorio, están en `.gitignore`. Composer es necesario para que el plugin funcione correctamente.

---

## Contribuciones

¡Todas las contribuciones son bienvenidas!

1. Haz un fork del repositorio.
2. Crea tu feature branch: `git checkout -b feature/nueva-funcionalidad`
3. Haz commit de tus cambios: `git commit -m "Agrega nueva funcionalidad"`
4. Push a tu branch: `git push origin feature/nueva-funcionalidad`
5. Abre un Pull Request.

---

## Licencia

Este proyecto está bajo la **Licencia MIT**.  
Consulta el archivo [LICENSE](./LICENSE) para más detalles.
