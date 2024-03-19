<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://weblinks.cc
 * @since             1.0.0
 * @package           Books_Management_Tool
 *
 * @wordpress-plugin
 * Plugin Name:       Books management tool
 * Plugin URI:        https://weblinks.cc
 * Description:       Books management tool
 * Version:           1.0.0
 * Author:            Wayne
 * Author URI:        https://weblinks.cc/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       books-management-tool
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOKS_MANAGEMENT_TOOL_VERSION', '1.0.0' );

define('BOOKS_TABLE', 'wp_owt_tbl_books');
define('SHELF_TABLE', 'wp_owt_tbl_books_shelf');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-books-management-tool-activator.php
 */
function activate_books_management_tool() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-books-management-tool-activator.php';
	$activator = new Books_Management_Tool_Activator;
  $activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-books-management-tool-deactivator.php
 */
function deactivate_books_management_tool() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-books-management-tool-deactivator.php';

  $deactivator = new Books_Management_Tool_Deactivator;
	$deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_books_management_tool' );
register_deactivation_hook( __FILE__, 'deactivate_books_management_tool' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-books-management-tool.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_books_management_tool() {

	$plugin = new Books_Management_Tool();
	$plugin->run();

}
run_books_management_tool();


add_action( 'rest_api_init', 'register_book_management_entry' );

function register_book_management_entry() {
  register_rest_route( 'bookmanager/v1', '/formsubmissions/', array( 
    'methods'  => 'GET',
    'callback'  => 'book_management_form_submissions',
    'permission_callback'  => '__return_true' 

  ) 
);

   register_rest_route( 'bookmanager/v1', '/formsubmissions/', array( 
    'methods'  => 'POST',
    'callback'  => 'book_management_form_submission_post',
    'permission_callback'  => '__return_true'  //这样开启就是没限制接口使用,如果开启权限用nonce的话nonce只有rest
    //'permission_callback'  => 'bookmanager_check_permission'

  ) 
);

//单个id 查询接口 

   register_rest_route( 'bookmanager/v1', '/formsubmission/(?P<id>\d+)', array( 
    'methods'  => 'GET',
    'callback'  => 'book_management_form_single_submission',
    //'permission_callback'  => '__return_true'  这样开启就是没限制接口使用
    'permission_callback'  => '__return_true'

  ) 


);

}

function bookmanager_check_permission() {
  return current_user_can( 'edit_posts' );
}

function book_management_form_submissions($request) {
  //要获取单个的数据可以在这里设置$id 也可以用path 变量，也就是P开头的查询变量， 
  $id = $request['id'];
  global $wpdb;
  $table_name = $wpdb->prefix . 'owt_tbl_books_shelf';

  $results = $wpdb->get_results("SELECT * FROM $table_name");

  return $results;

}

function book_management_form_single_submission($request) {

  $id = $request['id'];

  global $wpdb; 
  $table_name = $wpdb->prefix . 'owt_tbl_books_shelf'; 

  $results = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $id"); 

  return $results[0];


}

function book_management_form_submission_post($request) {
  global $wpdb;
  $table_name = $wpdb->prefix . 'owt_tbl_books_shelf'; 

  $rows = $wpdb->insert($table_name,array(
    'shelf_name' => $request['shelf_name'],
    'capacity' => $request['capacity'],
    'shelf_location' => $request['shelf_location'],
    'status' => $request['shelf_status'],

  ));

  return $rows;
}

add_shortcode( 'book_list_layout', 'book_list_layout_cb' );

function book_list_layout_cb() {


  ob_start();

  require_once plugin_dir_path( __FILE__ ) . 'public/partials/book_tool_shortcode.php';

  return ob_get_clean();


}