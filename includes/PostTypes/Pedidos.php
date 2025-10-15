<?php

namespace AI\Pedidos\PostTypes;

defined('ABSPATH') || exit;

class Pedidos
{
  private $text_domain;
  private $post_type;


  public function __construct()
  {
    $this->text_domain = AI_PEDIDOS_TEXT_DOMAIN;
    $this->post_type   = AI_PEDIDOS_CPT;

    add_action('init', [$this, 'register_post_type']);
  }

  public function register_post_type()
  {
    $labels = [
      'name'               => __('Pedidos de Impresión',                     $this->text_domain),
      'singular_name'      => __('Pedido',                                   $this->text_domain),
      'menu_name'          => __('Pedidos',                                  $this->text_domain),
      'add_new'            => __('Añadir Pedido',                            $this->text_domain),
      'add_new_item'       => __('Añadir Nuevo Pedido',                      $this->text_domain),
      'edit_item'          => __('Editar Pedido',                            $this->text_domain),
      'new_item'           => __('Nuevo Pedido',                             $this->text_domain),
      'view_item'          => __('Ver Pedido',                               $this->text_domain),
      'search_items'       => __('Buscar Pedidos',                           $this->text_domain),
      'not_found'          => __('No se encontraron pedidos',                $this->text_domain),
      'not_found_in_trash' => __('No se encontraron pedidos en la papelera', $this->text_domain),
    ];

    $args = [
      'labels'                => $labels,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 20,
      'menu_icon'             => 'dashicons-printer',
      'supports'              => ['title', 'author'],
      'capability_type'       => 'post',
      'has_archive'           => false,
      'show_in_rest'          => true,
      'rest_base'             => 'pedidos',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
    ];

    register_post_type($this->post_type, $args);
  }
}
