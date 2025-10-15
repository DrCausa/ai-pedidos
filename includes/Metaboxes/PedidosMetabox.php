<?php

namespace AI\Pedidos\Metaboxes;

defined('ABSPATH') || exit;

class PedidosMetabox
{
  private $meta_prefix;
  private $text_domain;
  private $post_type;

  public function __construct()
  {
    $this->meta_prefix = AI_PEDIDOS_META_PREFIX;
    $this->text_domain = AI_PEDIDOS_TEXT_DOMAIN;
    $this->post_type   = AI_PEDIDOS_CPT;

    add_action('add_meta_boxes', [$this, 'add_metabox']);
    add_action('save_post_' . $this->post_type, [$this, 'save_metabox']);
  }

  public function add_metabox()
  {
    add_meta_box(
      'ai_pedidos_details',
      __('Detalles del Pedido', $this->text_domain),
      [$this, 'render_metabox'],
      $this->post_type,
      'normal',
      'high'
    );
  }

  public function render_metabox($post)
  {
    wp_nonce_field('ai_pedidos_nonce_action', 'ai_pedidos_nonce');

    $size_id     = $this->meta_prefix . 'size';
    $paper_id    = $this->meta_prefix . 'paper';
    $finish_id   = $this->meta_prefix . 'finish';
    $quantity_id = $this->meta_prefix . 'quantity';
    $status_id   = $this->meta_prefix . 'status';

    $size     = get_post_meta($post->ID, $size_id,     true);
    $paper    = get_post_meta($post->ID, $paper_id,    true);
    $finish   = get_post_meta($post->ID, $finish_id,   true);
    $quantity = get_post_meta($post->ID, $quantity_id, true);
    $status   = get_post_meta($post->ID, $status_id,   true);
?>
    <p>
      <label for="<?= $size_id ?>"><?php _e("Tamaño", $this->text_domain) ?></label>
      <input type="text" name="<?= $size_id ?>" value="<?php esc_attr($size) ?>" class="widefat">
    </p>

    <p>
      <label for="<?= $paper_id ?>"><?php _e("Papel", $this->text_domain) ?></label>
      <input type="text" name="<?= $paper_id ?>" value="<?php esc_attr($paper) ?>" class="widefat">
    </p>

    <p>
      <label for="<?= $finish_id ?>"><?php _e("Acabado", $this->text_domain) ?></label>
      <input type="text" name="<?= $finish_id ?>" value="<?php esc_attr($finish) ?>" class="widefat">
    </p>

    <p>
      <label for="<?= $quantity_id ?>"><?php _e("Cantidad", $this->text_domain) ?></label>
      <input type="text" name="<?= $quantity_id ?>" value="<?php esc_attr($quantity) ?>" class="widefat">
    </p>

    <p>
      <label for="<?= $status_id ?>"><?php _e("Estado", $this->text_domain) ?></label>
      <select name="<?= $status_id ?>" class="widefat">
        <option value="pending" <?php selected($status, 'pending') ?>><?php _e('Pendiente', $this->text_domain) ?></option>
        <option value="in-progress" <?php selected($status, 'in-progress') ?>><?php _e('En proceso', $this->text_domain) ?></option>
        <option value="ready" <?php selected($status, 'ready') ?>><?php _e('Listo', $this->text_domain) ?></option>
      </select>
    </p>

<?php
  }

  public function save_metabox($post_id)
  {
    if (
      !isset($_POST['ai_pedidos_nonce']) ||
      !wp_verify_nonce($_POST['ai_pedidos_nonce'], 'ai_pedidos_nonce_action')
    ) {
      return;
    }

    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    $size_id     = $this->meta_prefix . 'size';
    $paper_id    = $this->meta_prefix . 'paper';
    $finish_id   = $this->meta_prefix . 'finish';
    $quantity_id = $this->meta_prefix . 'quantity';
    $status_id   = $this->meta_prefix . 'status';

    if (isset($_POST[$size_id])) {
      update_post_meta($post_id, $size_id, sanitize_text_field($_POST[$size_id]));
    }

    if (isset($_POST[$paper_id])) {
      update_post_meta($post_id, $paper_id, sanitize_text_field($_POST[$paper_id]));
    }

    if (isset($_POST[$finish_id])) {
      update_post_meta($post_id, $finish_id, sanitize_text_field($_POST[$finish_id]));
    }

    if (isset($_POST[$quantity_id])) {
      $quantity = intval($_POST[$quantity_id]);
      update_post_meta($post_id, $quantity_id, $quantity);
    }

    if (isset($_POST[$status_id])) {
      $status = sanitize_key($_POST[$status_id]);
      $valid_statuses = ['pending', 'in-progress', 'ready'];
      if (in_array($status, $valid_statuses, true)) {
        update_post_meta($post_id, $status_id, $status);
      }
    }
  }
}
