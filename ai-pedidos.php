<?php

/**
 * Plugin Name: Pedidos de Impresión
 * Description: Registra un CPT para "Pedidos de Impresión" con metaboxes y soporte para API REST en /ai/v1/pedidos
 * Version: 1.0.0
 * Author: DrCausa
 * Author URI: https://github.com/DrCausa
 * Text Domain: ai-pedidos
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

if (! defined('AI_PEDIDOS_VERSION')) {
  define('AI_PEDIDOS_VERSION', '1.0.0');
}

if (! defined('AI_PEDIDOS_META_PREFIX')) {
  define('AI_PEDIDOS_META_PREFIX', 'ai_pedidos_');
}

if (! defined('AI_PEDIDOS_TEXT_DOMAIN')) {
  define('AI_PEDIDOS_TEXT_DOMAIN', 'ai-pedidos');
}

if (! defined('AI_PEDIDOS_CPT')) {
  define('AI_PEDIDOS_CPT', 'ai_pedido');
}

require_once __DIR__ . '/vendor/autoload.php';

add_action('plugins_loaded', function () {
  new \AI\Pedidos\PostTypes\Pedidos();
  new \AI\Pedidos\Metaboxes\PedidosMetabox();
  new \AI\Pedidos\REST\PedidosREST();
});
