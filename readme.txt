=== Pedidos de Impresión ===
Contributors: DrCausa
Donate link: https://github.com/DrCausa
Tags: pedidos, impresion, CPT, REST API, metabox
Requires at least: 4.7
Tested up to: 6.5
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Registra un Custom Post Type para "Pedidos de Impresión" con metaboxes personalizados y soporte para API REST.

== Description ==

Pedidos de Impresión es un plugin de WordPress que permite registrar y gestionar pedidos de impresión
a través de un Custom Post Type (CPT). Cada pedido incluye campos personalizados (tamaño, papel,
acabado, cantidad y estado) y puede ser consultado mediante la API REST en el endpoint `/ai/v1/pedidos`.

Características principales:

* Registro de un CPT llamado "Pedidos de Impresión"
* Metaboxes personalizados para tamaño, papel, acabado, cantidad y estado
* Columnas personalizadas en la lista de pedidos para estado y tamaño
* Filtro por estado en la administración de pedidos
* Soporte completo para REST API con filtros de estado, paginación y límite
* Compatible con traducciones usando `Text Domain`

Más información en:

* [GitHub](https://github.com/DrCausa/ai-pedidos)
* [WordPress](https://wordpress.org/)

== Installation ==

1. Subir la carpeta `ai-pedidos` al directorio `/wp-content/plugins/`
2. Activar el plugin desde el menú "Plugins" en WordPress
3. Comenzar a añadir pedidos desde el menú "Pedidos" en el panel de administración
4. Consultar pedidos vía REST API en `https://tusitio.com/wp-json/ai/v1/pedidos`

== Frequently Asked Questions ==

= ¿Qué es el endpoint REST `/ai/v1/pedidos`? =

Es el endpoint donde se pueden consultar los pedidos de impresión de forma programática.
Permite filtrar por estado (`pending`, `in-progress`, `ready`), establecer la cantidad de resultados por página
y la página a consultar.

= ¿Cómo se agregan nuevos pedidos? =

Desde el panel de administración de WordPress, en el menú "Pedidos", selecciona "Añadir Pedido"
y completa los metaboxes con los detalles del pedido.

= ¿Qué estados están disponibles para un pedido? =

Los estados disponibles son:

* Pendiente (`pending`)
* En proceso (`in-progress`)
* Listo (`ready`)

== Contributors and Developers ==

== Screenshots ==

1. Pantalla principal de pedidos en el panel de administración
2. Metaboxes para editar los detalles del pedido
3. Filtro por estado en la lista de pedidos
4. Ejemplo de respuesta del endpoint REST `/ai/v1/pedidos`

== Changelog ==

= 1.0.0 =
* Primera versión estable
* Registro de CPT "Pedidos de Impresión"
* Metaboxes personalizados para tamaño, papel, acabado, cantidad y estado
* Columnas personalizadas en listado de pedidos
* Filtro por estado en administración
* API REST con soporte para filtros y paginación

== Upgrade Notice ==

= 1.0.0 =
Primera versión estable. Instala o actualiza para gestionar pedidos de impresión con soporte REST API.