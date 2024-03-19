<?php 
/*
 * Plugin Name:       Uc option test
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       small plguin for custom option table 
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       uc-options
 * Domain Path:       /languages
 */

//check if exist 
if (!defined('ABSPATH')) {
  die;
  
}
//注册的时候启动options 
register_activation_hook( __FILE__,function() {
  $options = get_option('up_options');  

  if (!$options) {

    add_option('up_options',array(
      'og_title'  => get_bloginfo('name'),
      'og_img'  => '',
      'og_description'  => get_bloginfo('description'),
      'enable_og'  => '1'


    )); 



    
  }
} );

//use admin post to insert the options 

//add admin menu 

add_action( 'admin_menu','uc_add_admin_menu' );

function uc_add_admin_menu() {

  add_menu_page( 'UC Options', 'Uc Options', 'edit_theme_options', 'uc-options', 'uc_options_page' );
  add_submenu_page( 'uc-options', 'Alt udemy plus', 'UdemyPlus', 'edit_theme_options', 'alt-udemy-plus', 'alt_udemy_plus_page' );
}

function alt_udemy_plus_page() {
  ob_start();

  require_once plugin_dir_path( __FILE__ ) . 'partials/uc-alt-udemy-plus-page.php';

  echo ob_get_clean();
}


function uc_options_page() {
  $options = get_option('up_options');

  ob_start();

  require_once plugin_dir_path(  __FILE__  )  . 'partials/uc-options-face.php';

  echo ob_get_clean();
}

add_action('admin_post_up_save_options','up_save_options');

function up_save_options() {
  if (!current_user_can( 'edit_theme_options' )) {

    wp_die('You are not allowed');
    
  }


  check_admin_referer( 'up_options_verify' );


  $options = get_option('up_options');

  $options['og_title'] = sanitize_text_field( $_POST['up_og_title'] );
  $options['og_img'] = sanitize_url($_POST['up_og_image']);
  $options['og_description'] = sanitize_url($_POST['up_og_description']);

  $options['enable_og']  = absint( $_POST['up_enable_og'] );

  update_option( 'up_options', $options );

  wp_redirect(admin_url('admin.php?page=uc-options&status=1'));




}


add_action( 'admin_enqueue_scripts', 'up_admin_enqueue' );

function up_admin_enqueue($hook_suffix) {

  if ($hook_suffix === 'toplevel_page_uc-options') {

    wp_enqueue_style( 'up_admin', plugins_url('index.css',__FILE__) );

    wp_enqueue_script('up_admin_js', plugins_url( 'index.js', __FILE__ ),array('jquery'),'1.0.0',true);
    
  }


}

add_action( 'after_setup_theme', 'up_add_image_size' );

function up_add_image_size() {
  add_image_size( 'opengraph', 1200,630,true );
}



add_action( 'admin_init', 'up_settings_api' );

function up_settings_api() {
  register_setting('up_options_group','up_options');

  add_settings_section( 'up_options_section', 'Udemy Plus Settings', '', 'alt-udemy-plus' );

  add_settings_field( 'up_options','Up Options', 'up_options_fields','alt-udemy-plus', 'up_options_section' );
}

function up_options_fields() { ?>

  <input type="checkbox" name="up_options[enable_handle_og]" <?php checked(get_option( 'up_options')['enable_handle_og'],'on') ?>>

  <?php 

}