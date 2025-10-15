<?php

namespace AI\Pedidos\REST;

defined('ABSPATH') || exit;

class PedidosREST
{
  private $meta_prefix;
  private $text_domain;
  private $post_type;

  public function __construct()
  {
    $this->meta_prefix = AI_PEDIDOS_META_PREFIX;
    $this->text_domain = AI_PEDIDOS_TEXT_DOMAIN;
    $this->post_type   = AI_PEDIDOS_CPT;

    add_action('rest_api_init', [$this, 'register_routes']);
  }

  public function register_routes()
  {
    register_rest_route('ai/v1', '/pedidos', [
      'methods'             => 'GET',
      'callback'            => [$this, 'get_pedidos'],
      'permission_callback' => '__return_true',
      'args' => [
        'estado' => [
          'required'          => false,
          'sanitize_callback' => 'sanitize_key',
          'validate_callback' => function ($param) {
            $valid = ['pending', 'in-progress', 'ready'];
            return in_array($param, $valid, true);
          }
        ],
        'limit' => [
          'required'          => false,
          'default'           => 10,
          'sanitize_callback' => 'absint',
        ],
        'page' => [
          'required'          => false,
          'default'           => 1,
          'sanitize_callback' => 'absint'
        ],
      ],
    ]);
  }

  public function get_pedidos($request)
  {
    $size_id     = $this->meta_prefix . 'size';
    $paper_id    = $this->meta_prefix . 'paper';
    $finish_id   = $this->meta_prefix . 'finish';
    $quantity_id = $this->meta_prefix . 'quantity';
    $status_id   = $this->meta_prefix . 'status';

    $estado = $request->get_param('estado') ?? '';
    $limit  = $request->get_param('limit');
    $page   = $request->get_param('page');

    $args = [
      'post_type'      => $this->post_type,
      'posts_per_page' => $limit,
      'paged'          => $page,
      'post_status'    => 'publish',
    ];

    if ($estado) {
      $args['meta_query'] = [
        [
          'key'   => $status_id,
          'value' => $estado,
        ]
      ];
    }

    $query = new \WP_Query($args);
    $data  = [];

    foreach ($query->posts as $post) {
      $data[] = [
        'id'       => $post->ID,
        'title'    => get_the_title($post),
        'author'   => get_the_author_meta('display_name', $post->post_author),
        'size'     => get_post_meta($post->ID, $size_id, true),
        'paper'    => get_post_meta($post->ID, $paper_id, true),
        'finish'   => get_post_meta($post->ID, $finish_id, true),
        'quantity' => intval(get_post_meta($post->ID, $quantity_id, true)),
        'status'   => get_post_meta($post->ID, $status_id, true),
      ];
    }

    return rest_ensure_response([
      'total'      => $query->found_posts,
      'per_page'   => $limit,
      'current_page' => $page,
      'total_pages' => $query->max_num_pages,
      'pedidos'    => $data,
    ]);
  }
}
