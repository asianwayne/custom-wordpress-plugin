<?php 
function ml_add_admin_scripts()
{
  wp_enqueue_style('jquery-style','https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');
  wp_enqueue_style( 'ml-style', plugins_url() . '/movie-listings/css/style.admin.css' );
  wp_enqueue_script('main-js', plugins_url() . '/movie-listings/js/main.js',array('jquery','jquery-ui-sortable'),'1.0.0.1',true);

  wp_localize_script( 'main-js', 'movieSort',array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'token'  => wp_create_nonce( 'ml-token' ),

  ) );
}
add_action( 'admin_enqueue_scripts', 'ml_add_admin_scripts' );

add_action('wp_enqueue_scripts', 'ml_add_scripts');

function ml_add_scripts() {
wp_enqueue_style('main-style',plugins_url() . '/movie-listings/css/style.css');
wp_enqueue_script('public-js', plugins_url() . '/movie-listings/js/main.js',array('jquery'),'1.0.0.1',true);
  
}