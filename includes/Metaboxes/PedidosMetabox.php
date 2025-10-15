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

    add_filter('manage_' . $this->post_type . '_posts_columns', [$this, 'add_custom_columns']);
    add_action('manage_' . $this->post_type . '_posts_custom_column', [$this, 'render_custom_columns'], 10, 2);

    add_action('restrict_manage_posts', [$this, 'add_status_filter']);
    add_filter('pre_get_posts', [$this, 'apply_status_filter']);
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

    echo '
      <p>
        <label for="' . esc_attr($size_id) . '">' . __("Tamaño", $this->text_domain) . '</label>
        <input type="text" name="' . esc_attr($size_id) . '" value="' . esc_attr($size) . '" class="widefat">
      </p>

      <p>
        <label for="' . esc_attr($paper_id) . '">' . __("Papel", $this->text_domain) . '</label>
        <input type="text" name="' . esc_attr($paper_id) . '" value="' . esc_attr($paper) . '" class="widefat">
      </p>

      <p>
        <label for="' . esc_attr($finish_id) . '">' . __("Acabado", $this->text_domain) . '</label>
        <input type="text" name="' . esc_attr($finish_id) . '" value="' . esc_attr($finish) . '" class="widefat">
      </p>

      <p>
        <label for="' . esc_attr($quantity_id) . '">' . __("Cantidad", $this->text_domain) . '</label>
        <input type="text" name="' . esc_attr($quantity_id) . '" value="' . esc_attr($quantity) . '" class="widefat">
      </p>

      <p>
        <label for="' . esc_attr($status_id) . '">' . __("Estado", $this->text_domain) . '</label>
        <select name="' . esc_attr($status_id) . '" class="widefat">
          <option value="pending" ' . selected($status, 'pending', false) . '>' . __('Pendiente', $this->text_domain) . '</option>
          <option value="in-progress" ' . selected($status, 'in-progress', false) . '>' . __('En proceso', $this->text_domain) . '</option>
          <option value="ready" ' . selected($status, 'ready', false) . '>' . __('Listo', $this->text_domain) . '</option>
        </select>
      </p>
    ';
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

  public function add_custom_columns($columns)
  {
    $columns['ai_status'] = __('Estado', $this->text_domain);
    $columns['ai_size']   = __('Tamaño', $this->text_domain);
    return $columns;
  }

  public function render_custom_columns($column, $post_id)
  {
    $status_id   = $this->meta_prefix . 'status';
    $size_id     = $this->meta_prefix . 'size';

    switch ($column) {
      case 'ai_status':
        $status = get_post_meta($post_id, $status_id, true);
        $labels = [
          'pending'     => __('Pendiente',  $this->text_domain),
          'in-progress' => __('En proceso', $this->text_domain),
          'ready'       => __('Listo',      $this->text_domain),
        ];
        echo $labels[$status] ?? $status;
        break;
      case 'ai_size':
        $size = get_post_meta($post_id, $size_id, true);
        echo esc_html($size);
        break;
    }
  }

  public function add_status_filter($post_type)
  {
    if ($post_type !== $this->post_type) return;

    $current_status = $_GET['ai_status_filter'] ?? '';

    echo '
      <select name="ai_status_filter">
        <option value="">' . __('Todos los estados', $this->text_domain) . '</option>
        <option value="pending" ' . selected($current_status, 'pending', false) . '>' . __('Pendiente', $this->text_domain) . '</option>
        <option value="in-progress" ' . selected($current_status, 'in-progress', false) . '>' . __('En proceso', $this->text_domain) . '</option>
        <option value="ready" ' . selected($current_status, 'ready', false) . '>' . __('Listo', $this->text_domain) . '</option>
      </select>
    ';
  }

  public function apply_status_filter($query)
  {
    global $current_page;

    if (!is_admin() || $current_page !== 'edit.php') return;
    if ($query->get('post_type') !== $this->post_type) return;

    if (!empty($_GET['ai_status_filter'])) {
      $query->set('meta_query', [
        [
          'key'   => $this->meta_prefix . 'status',
          'value' => sanitize_key($_GET['ai_status_filter']),
        ]
      ]);
    }
  }
}
