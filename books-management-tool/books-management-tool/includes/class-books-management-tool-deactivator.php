<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://weblinks.cc
 * @since      1.0.0
 *
 * @package    Books_Management_Tool
 * @subpackage Books_Management_Tool/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Books_Management_Tool
 * @subpackage Books_Management_Tool/includes
 * @author     Wayne <asianwayne@qq.com>
 */
class Books_Management_Tool_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
    global $wpdb; 

    $wpdb->query("DROP TABLE IF EXISTS wp_owt_tbl_books");
    $wpdb->query("DROP TABLE IF EXISTS wp_owt_tbl_books_shelf");

    $page_data = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM wp_posts where post_name = %s",'book_tool'

      )

    );
    //delete page when plugin deactivate 
    //或者SELECT ID FROM wp_posts where post_name = 'book_tool'

    if (!empty($page_data)) {

      wp_delete_post( $page_data->ID,true);
      
    }

	}

  

}
