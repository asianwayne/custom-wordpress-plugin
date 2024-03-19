<?php 
/*
 * Plugin Name:Movie Listings
 * Description:small plugin for dispalying the movie like imdb
 * Version:1.0.0
 * Plugin URI:https://webtown.cn
 * Author:Wayne
 * Author URI:https://webtown.cn
 */
if (!defined('ABSPATH')) {
  exit;
  
}


require_once plugin_dir_path( __FILE__ ) . 'includes/movie-listings-scripts.php';


require_once plugin_dir_path( __FILE__ ) . 'includes/movie-listings-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/movie-listings-fields.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/movie-listings-reorder.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/movie-listings-settings.php';
  

require_once plugin_dir_path( __FILE__ ) . 'includes/movie-listings-shortcodes.php';


function my_plugin_template( $template ) {
    if ( is_singular('movie_listing') ) {
        $new_template = plugin_dir_path( __FILE__ ) . 'templates/single-movie.php';
        if ( file_exists( $new_template ) ) {
            return $new_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'my_plugin_template' );


add_action( 'template_redirect', 'redirect_movies_archive_to_movies_page' );
function redirect_movies_archive_to_movies_page() {
  if ( is_post_type_archive( 'movie_listing' ) ) {
    wp_redirect( home_url( '/movies/' ) );
    exit;
  }
}





