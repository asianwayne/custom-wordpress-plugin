<?php 
function ml_add_submenu_page()
{

  add_submenu_page( 'edit.php?post_type=movie_listing', __( 'Custom Order','ml-domain' ), __( 'Custom Order','ml-domain' ), 'manage_options', 'custom-order', 'ml_reorder_movies_cb' );

}

add_action( 'admin_menu', 'ml_add_submenu_page' );

function ml_reorder_movies_cb()
{
  $args = array(
    'post_type' => 'movie_listing',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'publish',
    'no_found_rows' => true,
    'update_post_term_cache' => false,
    'posts_per_page' => 50

  );

  $movie_listings = new WP_Query($args);  ?>
  <div id="movie-sort" class="wrap">
    <h2><?php esc_html_e( 'Sort Movie Listings', 'ml-domain' ); ?><img class="loading" src="<?php echo admin_url() . '/images/loading.gif'; ?>" alt=""></h2>
    <div class="order-save-msg updated">Listing order saved</div>
    <div class="order-save-err error">Some thing went wrong</div>

    <?php if ($movie_listings->have_posts()):  ?>
      <ul class="movie-sort-list">
        <?php while ($movie_listings->have_posts()) {
          $movie_listings->the_post(); ?>
          <li id="<?php the_ID(); ?>"><?php the_title(); ?></li>

          <?php 
            
        } ?>
      
      </ul>
      
      <?php   else :  ?>
        <p>No movie list</p>
    <?php endif ?>
  </div>

  <?php 

}

function ml_save_order()
{
  // Check nonce token
  if (!check_ajax_referer( 'ml-token', 'token' )) {
    wp_send_json_error('Invalid token');
    return;
  }

  // Check user capability 
  if (!current_user_can( 'manage_options' )) {
    wp_send_json_error('Not authorized');
    return;
  }

  $order = $_POST['order'];

  $counter = 0;

  foreach ($order as $listing_id) {
    $listing = array(
      'ID'  => (int)$listing_id,
      'menu_order'  => $counter
    );

    // Debugging statement
    //var_dump($listing);

    $result = wp_update_post($listing);

    // Debugging statement
    //var_dump($result);

    if ($result === 0 || $result === false) {
      wp_send_json_error('Error updating post ID ' . $listing_id);
      return;
    }

    $counter++;
  }

  wp_send_json_success('LISTING ORDER SAVED');
}

add_action( 'wp_ajax_save_order', 'ml_save_order' );
