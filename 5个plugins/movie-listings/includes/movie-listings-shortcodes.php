<?php 
function ml_list_movies($atts,$content = null)
{
  ob_start();
  $atts = shortcode_atts( array(
    'title'  => 'Latest movies',
    'count'  => 3,
    'genre'  => 'all',
    'pagination'  => 'on',

  ),$atts );

  // condition === () ? : 
  $pagination = $atts['pagination'] == 'on' ? false : true;
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;

  //check genre 
  if ($atts['genre'] == 'all') {
    $terms = '';
    
  } else {
    $terms = array(array(
      'taxonomy'  => 'genres',
      'field'  => 'slug',
      'terms'  => $atts['genre'],

    ));
  }

  //query 
  $args = array(
    'post_type'  => 'movie_listing',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'no_found_rows'  => $pagination,
    'posts_per_page'  => $atts['count'],
    'paged'  => $paged,
    'tax_query'  => $terms
  );

  $movies = new WP_Query($args);

  
  if ($movies->have_posts()) {
    //get genre slug
    $genre = str_replace('-', '', $atts['genre']);

       ?>
    <div class="movie-list">
      <?php while ($movies->have_posts()) {
        $movies->the_post(); $image = wp_get_attachment_image_url( get_post_thumbnail_id(  ),'single-post-thumbnail' );   ?>
        <a href="<?php the_permalink(  ); ?>">
        <div class="movie-col">

          <img src="<?php echo $image;?>" alt="" class="feat-img">
          <h5 class="movie-title-f"><?=the_title(  );?></h5>
          View Details
        </div>
      </a>
        

        <?php 
      } 
      echo '<div style="clear: both;"></div>';
      wp_reset_postdata(); 
      
      // Output pagination links
    echo paginate_links(array(
      'total' => $movies->max_num_pages,
      'current' => $paged,
    ));
      

       ?>
    </div>


    <?php 
    
  } else {
    return '<p>No movies found!</p>';
  }


  return ob_get_clean();

}

add_shortcode( 'movies', 'ml_list_movies' );