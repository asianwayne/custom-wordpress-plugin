<?php 
function ml_add_fields_metabox()
{
  add_meta_box( 
    'ml_listing_info', 
    __('Listing Info'),
    'ml_add_fields_cb',
    'movie_listing',
    'normal',
    'default'

   );
  
}

add_action( 'add_meta_boxes', 'ml_add_fields_metabox' );

function ml_add_fields_cb($post)
{
  wp_nonce_field( basename(__FILE__),'ml_movie_listing_nonce' );
  $ml_stored_meta = get_post_meta( $post->ID ); ?>
  <div class="wrap movie-listing-form">
    <div class="form-group">
      <label for="movie-id"><?php esc_html_e( 'Movie ID', 'ml-domain' ); ?></label>
      <input class="" type="text" id="movie-id" name="movie_id" value="<?php if (!empty($ml_stored_meta['movie_id'])) {
        echo $ml_stored_meta['movie_id'][0];
      } ?>">
    </div>

    <div class="form-group">
      <label for="mpaa_rating"><?php esc_html_e( 'Mpaa rating', 'ml-domain' ); ?></label>
      <select name="mpaa_rating" id="mpaa_rating">
        <?php $option_values = array('G','PG','PG-13','R','NR');
        foreach ($option_values as $key=>$value) { if ($value == $ml_stored_meta['mpaa_rating'][0]) { ?>
          <option selected><?=$value?></option>

          <?php 
        } else { ?>
          <option value=""><?=$value?></option>
          <?php 

        }
        }
         ?>
      </select>
    </div>

    <?php if (get_option( 'ml_setting_show_editor' )): ?>
      <div class="form-group">
      <label for="details"><?php esc_html_e( 'Details', 'ml-domain' ); ?></label>
      <?php 
      $content = get_post_meta( $post->ID, 'details',true );
      $editor = 'details';
      $settings = array(
        'textarea_rows' => 5,
        'media_buttons' => get_option('ml_setting_show_media_buttons')

      );

      wp_editor( $content, $editor, $settings );
       ?>
    </div>
  <?php else : ?>
    <div class="form-group">
      <label for="details"><?php esc_html_e( 'Details', 'ml-domain' ); ?></label>
      <textarea class="full" name="details" id="details"><?php if (!empty($ml_stored_meta['details'])) {
        echo $ml_stored_meta['details'][0];
      } ?></textarea>
    </div>
      
    <?php endif ?>
    

    <div class="form-group">
      <label for="release-date"><?php esc_html_e( 'Release Date', 'ml-domain' ); ?></label>
      <input class="" type="date" id="release-date" name="release_date" value="<?php if (!empty($ml_stored_meta['release_date'])) {
        echo $ml_stored_meta['release_date'][0];
      } ?>">
    </div>

    <div class="form-group">
      <label for="director"><?php esc_html_e( 'Derictor', 'ml-domain' ); ?></label>
      <input class="" type="text" id="director" name="director" value="<?php if (!empty($ml_stored_meta['director'])) {
        echo $ml_stored_meta['director'][0];
      } ?>">
    </div>
    <div class="form-group">
      <label for="stars"><?php esc_html_e( 'Stars', 'ml-domain' ); ?></label>
      <input class="" type="text" id="stars" name="stars" value="<?php if (!empty($ml_stored_meta['stars'])) {
        echo $ml_stored_meta['stars'][0];
      } ?>">
    </div>

    <div class="form-group">
      <label for="runtime"><?php esc_html_e( 'Runtime', 'ml-domain' ); ?></label>
      <input class="" type="text" id="runtime" name="runtime" value="<?php if (!empty($ml_stored_meta['runtime'])) {
        echo $ml_stored_meta['runtime'][0];
      } ?>"><span class="mins">mins</span>
    </div>
    <div class="form-group">
      <label for="trailor"><?php esc_html_e( 'Youtube Id', 'ml-domain' ); ?></label>
      <input class="" type="text" id="trailor" name="trailor" value="<?php if (!empty($ml_stored_meta['trailor'])) {
        echo $ml_stored_meta['trailor'][0];
      } ?>">
    </div>
  </div>

  <?php 
}

function ml_meta_save($post_id)
{
  $is_autosave = wp_is_post_autosave( $post_id );
  $is_rebision = wp_is_post_revision( $post_id );
  $is_valid_nonce = (isset($_POST['ml_movie_listing_nonce']) && wp_verify_nonce( $_POST['ml_movie_listing_nonce'], basename(__FILE__) )) ? 'true' : 'false';

  if ($is_autosave || $is_rebision || !$is_valid_nonce) {
    return;
    
  }

  if (isset($_POST['movie_id'])) {
    update_post_meta( $post_id, 'movie_id', sanitize_text_field( $_POST['movie_id'] ) );
  }

  if (isset($_POST['mpaa_rating'])) {
    update_post_meta( $post_id, 'mpaa_rating', sanitize_text_field( $_POST['mpaa_rating'] ) );
  }
  if (isset($_POST['details'])) {
    update_post_meta( $post_id, 'details',  $_POST['details']  );
  }
  if (isset($_POST['release_date'])) {
    update_post_meta( $post_id, 'release_date', sanitize_text_field( $_POST['release_date'] ) );
  }
  if (isset($_POST['director'])) {
    update_post_meta( $post_id, 'director', sanitize_text_field( $_POST['director'] ) );
  }
  if (isset($_POST['stars'])) {
    update_post_meta( $post_id, 'stars', sanitize_text_field( $_POST['stars'] ) );
  }
  if (isset($_POST['runtime'])) {
    update_post_meta( $post_id, 'runtime', sanitize_text_field( $_POST['runtime'] ) );
  }
  if (isset($_POST['trailor'])) {
    update_post_meta( $post_id, 'trailor', sanitize_text_field( $_POST['trailor'] ) );
  }
  
}

add_action( 'save_post', 'ml_meta_save' );