<?php 
/*
 * Plugin Name:       WP Highlighter
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       代码高亮小插件
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

//如果可以直接路径访问的话 就退出 
if (!defined('ABSPATH')) {
	die;
}


//加载prism js 和css 文件  wp_enqueue_scripts 

add_action( 'wp_enqueue_scripts', 'wph_scripts' );

function wph_scripts() {
	wp_enqueue_style( 'wph_prism_css', plugins_url( 'prism.css',__FILE__ ) );
	wp_enqueue_script('wph-prism-js', plugins_url( 'prism.js', __FILE__ ),array('jquery'),'1.29.0',false);
}

//code lang-php (class)  
