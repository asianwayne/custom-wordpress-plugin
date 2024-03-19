<?php 
/**
 * Plugin Name: Wp learn rest api 
 * Description: Learn about rest api 
 * Author: WordPress
 * Version: 1.0
 */

/**
 * Create admin page to show form submission 
 */

add_action( 'admin_menu', 'wp_learn_rest_submenu' );

function wp_learn_rest_submenu() {

  add_menu_page( 
    'Wp Learn admin page', 
    'Wp Learn admin page', 
    'manage_options', 
    'wp_learn_admin', 
    'wp_learn_rest_render_option_page', 
    'dashicons-admin-tools' );

}


//render form submission admin page 

function wp_learn_rest_render_option_page() { ?>

  <div class="wrap" id="wp_learn_admin">
    <h1>Admin</h1>
    <button id="wp-learn-rest-api-button">Load posts via Rest Api </button>
    <button id="wp-learn-clear-posts">Clear Posts </button>
    <h2>Posts</h2>
    <textarea id="wp-learn-posts" cols="125" rows="125"></textarea>
  </div>

  <?php 


}

//enqueue js scripts to make the request 
add_action( 'admin_enqueue_scripts', 'wp_learn_rest_enqueue_script' );

function wp_learn_rest_enqueue_script() {


  wp_enqueue_script('wp-learn-rest-api', plugin_dir_url( __FILE__ ) . 'wp-learn-rest-api.js',array('wp-api'),time(),true);

  
}

