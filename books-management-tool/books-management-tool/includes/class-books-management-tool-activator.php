<?php

/**
 * Fired during plugin activation
 *
 * @link       https://weblinks.cc
 * @since      1.0.0
 *
 * @package    Books_Management_Tool
 * @subpackage Books_Management_Tool/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Books_Management_Tool
 * @subpackage Books_Management_Tool/includes
 * @author     Wayne <asianwayne@qq.com>
 */
class Books_Management_Tool_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
    global $wpdb; 

       //导入dbDelta 
      require_once (ABSPATH . 'wp-admin/includes/upgrade.php'); 

      $table_name = $wpdb->prefix . 'owt_tbl_books';
    //check if the table is already exist 
    //下面的if就说明这个表名称不存在 
    //
    if ($wpdb->get_var("SHOW tables LIKE '" . $wpdb->prefix . "owt_tbl_books'") != $wpdb->prefix . 'owt_tbl_books') {
      $table_query = "CREATE TABLE `" . $wpdb->prefix . "owt_tbl_books` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `name` varchar(150) DEFAULT NULL,
     `amount` int(11) DEFAULT NULL,
     `description` text DEFAULT NULL,
     `book_image` varchar(200) DEFAULT NULL,
     `language` varchar(150) DEFAULT NULL,
     `shelf_id` INT NULL,
     `status` int(11) NOT NULL DEFAULT 1,
     `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
     PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    dbDelta($table_query);
        }


    if ( $wpdb->get_var("SHOW tables LIKE '".$wpdb->prefix."owt_tbl_books_shelf'") != $wpdb->prefix . 'owt_tbl_books_shelf') {
      
      $shelf_table = "CREATE TABLE `" . $wpdb->prefix . "owt_tbl_books_shelf` (
       `id` int(11) NOT NULL AUTO_INCREMENT,
       `shelf_name` varchar(150) NOT NULL,
       `capacity` int(11) NOT NULL,
       `shelf_location` varchar(200) NOT NULL,
       `status` int(11) NOT NULL DEFAULT 1,
       `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
       PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
      dbDelta($shelf_table);


      //PLUGIN 启动时候插入原始数据
      $insert_query = "INSERT INTO " . $wpdb->prefix ."owt_tbl_books_shelf (shelf_name,capacity,shelf_location,status) VALUES ('SHELF 1',230,'LEFT corner',1),('SHELF 2',300,'CENTER',0),('SHELF 3',350,'RIGHT CORNER',1)";


      $wpdb->query($insert_query);

    }

    //create page on plugin activation 
    
    $page_data = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM wp_posts where post_name = %s",'book_tool'

      )

    );

    if (!empty($page_data)) {

      //page exists 
      
      
    } else {
      //create the page 
      //用wp insert post 来创建page 
      wp_insert_post(array(

        'post_title'  => 'Book tool',
        'post_name'  => 'book_tool',
        'post_status'  => 'publish',
        'post_author'  => 1,
        'post_content'  => 'Simple page content',
        'post_type'  => 'page'
      ));
    }
        
	}

    

  

}
