<?php 
function ml_register_post_types()
{
  $singular_name = apply_filters( 'ml_label_single', 'Movie Listing' );
  $plural_name = apply_filters( 'ml_label_plural', 'Movie Listings' );
  $labels = array(
    'singular_name'  => $singular_name,
    'name'  => $plural_name,
    'add_new'  => 'Add New',
    'add_new_item'  => 'Add New ' . $singular_name,
    'edit'  => 'Edit',
    'edit_item'  => 'Edit ' . $singular_name,
    'new_item'  => 'New ' . $singular_name,
    'view'  => 'View ',
    'view_item'  => 'View ' . $singular_name,
    'search_items'  => 'Search ' . $plural_name,
    'search_items'  => 'Search ' . $plural_name,
    'menu_name'  => $plural_name
  );

  $args = apply_filters( 'ml_args', array(
    'labels'  => $labels,
    'hierarchical'  => true,
    'description'  => 'Movie listings by genre',
    'taxonomy'  =>  array('genres'),
    'public'  =>  true,
    'show_ui'  =>  true,
    'show_in_menu'  =>  true,
    'menu_position'  =>  5,
    'menu_icon'  =>  'dashicons-video-alt2',
    'show_in_nav_menus'  =>  true,
    'has_archive'  =>  true,
    'supports' => array('title','thumbnail'),

  ) );
  register_post_type( 'movie_listing', $args );
}

add_action( 'init', 'ml_register_post_types' );

function ml_genres_tax()
{
  register_taxonomy( 'genres', 'movie_listing', array(
    'label'  => 'Genres',
    'query_var'  => true,
    'hierarchical'  => true,
    'rewrite'  => array(
      'slug' => 'genre',
      'with_front' => false

    )

  ) );
  
}

add_action( 'init', 'ml_genres_tax' );