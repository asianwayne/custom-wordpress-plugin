<?php 
/*
 * Plugin Name:       WPT login
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Wordpress custom login page
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            WP tutor
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
  die;
}

$wpt_admin = get_option('wpt_admin');
//插件启动时候可以通过add_option设置默认的option 
//开启插件后任何自定义弹窗的登录表格的action指向创建的登录页面

//首先禁止掉默认的login入口
//可以通过插件启动的钩子来创建页面， 现在插件通过自定义创建，后套输入页面slug方法来识别
//register_activation_hook( __FILE__, 'wpt_login_activator' );
add_action( 'init', 'wpt_login_activator' );
function wpt_login_activator() {

  if (!is_user_logged_in()) {
    if (strpos($_SERVER['REQUEST_URI'], '/wp-admin') !== false || strpos($_SERVER['REQUEST_URI'], '/wp-login.php') !== false) {

      wp_redirect(home_url('/'));
      
    }
    
  }
}

//创建自定义的登录模板 
add_filter( 'template_include', 'wpt_login_page_template' );

function wpt_login_page_template($template) {
  global $wpt_admin;
  if (!empty($wpt_admin['slug']) && is_page($wpt_admin['slug'])) {
    $custom_template = plugin_dir_path( __FILE__ ) . 'templates/login-page-template.php';

    if (file_exists($custom_template)) {
      $template = $custom_template;
      
    }
    
  }

  return $template;
}


require_once plugin_dir_path( __FILE__ ) . 'admin/wpt-login-admin.php';

add_action( 'admin_enqueue_scripts','wpt_enqueue_admin_js' );

function wpt_enqueue_admin_js() {
  if ( isset($_GET['page']) && $_GET['page'] == 'wpt-login-admin') {
    wp_enqueue_media(  );
    wp_enqueue_script('wpt_admin_js', plugins_url( 'admin/assets/wpt_admin.js', __FILE__ ),array('jquery'),'1.0.0.1',true);
      
  }
}