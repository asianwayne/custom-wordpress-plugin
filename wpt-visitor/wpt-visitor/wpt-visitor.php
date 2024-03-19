<?php 
/*
 * Plugin Name:       WPT Visitor Recorder
 * Plugin URI:        https://wenxue.co
 * Description:       记录访客小插件
 * Version:           1.0.1
 * Author:            Wp tutor
 * Author URI:        https://wenxue.co/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       wpt-visitor
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
	die();
}

//create the table when plugin activate 
//next version update check if the table exists 

register_activation_hook( __FILE__, 'wptv_create_table' );

function wptv_create_table()
{
	  global $wpdb;
    $table_name = $wpdb->prefix . 'visitor_ips'; // Replace "visitor_ips" with the name of the new table
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
      id INT NOT NULL AUTO_INCREMENT,
      ip VARCHAR(45) NOT NULL,
      browser VARCHAR(255) NOT NULL,
      route VARCHAR(255) NOT NULL,
      time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}


//register_deactivation_hook( __FILE__, 'wlf_uninstall_form_table' );
//

function wptv_uninstall_table() {
    global $wpdb; 
    $table_name = $wpdb->prefix . 'visitor_ips';
    $wpdb->query(" DROP TABLE IF EXISTS " . $table_name);
}


require_once plugin_dir_path( __FILE__ ) . '/admin/wptv-admin.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/wptv-public.php';




