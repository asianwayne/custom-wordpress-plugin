<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://weblinks.cc
 * @since      1.0.0
 *
 * @package    Books_Management_Tool
 * @subpackage Books_Management_Tool/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Books_Management_Tool
 * @subpackage Books_Management_Tool/admin
 * @author     Wayne <asianwayne@qq.com>
 */
class Books_Management_Tool_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Books_Management_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Books_Management_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/books-management-tool-admin.css', array(), $this->version, 'all' );

		$valid_pages = ['toplevel_page_books-manager','booksmanager_page_books-management-create-book','booksmanager_page_books-management-list-books','booksmanager_page_create-book-shelf','booksmanager_page_list-book-shelf'];

		if (in_array($hook, $valid_pages)) {
			wp_enqueue_style( $this->plugin_name . 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'datatable', plugin_dir_url( __FILE__ ) . 'css/datatables.min.css', array(), $this->version, 'all' );
		wp_enqueue_script( $this->plugin_name . 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . 'datatables', plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . 'sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweetalert.min.js', array( 'jquery' ), $this->version, true );

			
		}
		

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Books_Management_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Books_Management_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/books-management-tool-admin.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'book_rest', array(
			'rest_url'  => rest_url(),
			'ajax_url'  => admin_url( 'admin-ajax.php' ),

		) );
		

	}

	public function admin_menu() {
		add_menu_page( 'books management', 'Booksmanager', 'manage_options', 'books-manager', array($this,'books_manager_menu_cb'), '', 21 );

		add_submenu_page( 'books-manager', 'Dashboard', 'Dashboard','manage_options', 'books-manager',array($this,'books_manager_menu_cb') );

		add_submenu_page( 'books-manager', 'Create Book Shelf', 'Create Book Shelf','manage_options', 'create-book-shelf',array($this,'create_book_shelf_cb') );

		add_submenu_page( 'books-manager', 'List Book Shelf', 'List Book Shelf','manage_options', 'list-book-shelf',array($this,'list_book_shelf_cb') );
		add_submenu_page( 'books-manager', 'Create Books', 'Create Books','manage_options', 'books-management-create-book',array($this,'books_management_create_book_cb') );
		add_submenu_page( 'books-manager', 'List Books', 'List Books','manage_options', 'books-management-list-books',array($this,'books_management_list_books_cb') );
		

	}

	public function books_manager_menu_cb() {
		global $wpdb; 
		//get var是获取单个数据， get_row()  是获取整行数据, 第二个传递的参数是output 格式， ARRAY_A， OBJECT, ARRAY_N 这种 N 是数字的array, A 是关联数组
		//$user_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID=1");

			//$user_data = $wpdb->get_row(
			// 	"SELECT * FROM wp_users WHERE ID = 1",ARRAY_A

			// );
			// get col 来获取列的数据，比方说获取到所有post title内容 
			//$post_title = $wpdb->get_col("SELECT post_title FROM wp_posts");
			//get results method 
			//PREPARE 方法来安全查询，避免sql注入 
			//$wpdb->insert, delete, update 这种方法都是自带prepare， 在原始函数里已经定义 
			//$all_posts = $wpdb->get_results("SELECT ID,post_title FROM wp_posts",ARRAY_A);

			// $single = $wpdb->get_row(
			// 	$wpdb->prepare("SELECT * FROM wp_posts WHERE ID = %d",3)

			// ); 


		
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/book-management-dashboard.php';

		echo ob_get_clean();


	}

	public function books_management_create_book_cb() {
			echo '<h1>Create Book submenu</h1>';

			//php buffuer 或者直接 require 文件 
			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'partials/tmpl-create-book.php';
			$templates = ob_get_clean();
			echo $templates;

	} 

	public function books_management_list_books_cb() {
		echo '<h1>List Books</h1>';

		ob_start();

		require_once plugin_dir_path( __FILE__ ) . 'partials/tpml-list-books.php';

		echo  ob_get_clean();
	}

	public function create_book_shelf_cb() {
		echo 'Create book shelf';
		ob_start();

		require_once plugin_dir_path( __FILE__ ) . 'partials/tmpl-create-book-shelf.php';

		echo ob_get_clean();
	}

	public function list_book_shelf_cb() {
		echo 'list books shefl';
			ob_start();

		require_once plugin_dir_path( __FILE__ ) . 'partials/tmpl-list-book-shelf.php';

		echo  ob_get_clean();
	}
	
	

	function handle_book_ajax_cb() {
		$param = isset($_REQUEST['param']) ? $_REQUEST['param'] : ''; 
		global $wpdb;
		//$wpdb->suppress_errors(false);
		if (!empty($param)) {

			if ($param == 'create_book_shelf') {
				$table_name = $wpdb->prefix . 'owt_tbl_books_shelf';
				//写入数据库之前过滤 
				$shelf_name =  !empty($_REQUEST['shelf_name']) ? sanitize_text_field($_REQUEST['shelf_name']) : '';
				$capacity =  !empty($_REQUEST['capacity']) ? sanitize_text_field($_REQUEST['capacity']) : '';
				$shelf_location =  !empty($_REQUEST['shelf_location']) ? sanitize_text_field($_REQUEST['shelf_location']) : '';
				$status =  !empty($_REQUEST['shelf_status']) ? sanitize_text_field($_REQUEST['shelf_status']) : '';


				$rows = $wpdb->insert($table_name,array(
		    'shelf_name' => $shelf_name,
		    'capacity' => $capacity,
		    'shelf_location' => $shelf_location,
		    'status' => $status,

		  ));
				if ($rows) {
					echo json_encode(array(
						'status'  => 1,
						'message'  => "Book shelf created successfully!"

					));
					
				} else {
					echo json_encode( array(
						'status'  => 0,
						'message'  => "There is an error in the query please check again"


					) );

				}

				
			} elseif ($param == 'delete_book_shelf') {
				$table_name = $wpdb->prefix . 'owt_tbl_books_shelf';

				$shelf_id = $_REQUEST['shelf_id'];

				$wpdb->delete($table_name,array(
					'id'  => $shelf_id

				));

				echo json_encode(array(
					'status'  => 1,
					'message'  => 'successfully deleted the row'


				));


			} elseif($param == 'create-book') {

				$table_name = $wpdb->prefix.'owt_tbl_books';
				// var_dump($_REQUEST);

				$book_image = '';

				if (isset($_FILES['txt_image'])) {

					$file = $_FILES['txt_image'];

					$attachment_id = media_handle_upload( 'txt_image', 0 );

					//media_handle_upload成功上传之后返回$attachment_id；
					$book_image = wp_get_attachment_url( $attachment_id );

					//save the book image id to the data base 
					//要获取其他尺寸用wp_get_attachment_image_src

					//下面不能删除
				// 	if (is_wp_error($attachment_id)) {
        //     echo 'Error uploading image: ' . $attachment_id->get_error_message();
        // } else {
        //     echo 'Image uploaded successfully. Attachment ID: ' . $attachment_id;
        // }
					
				} 

				$book_shelf = isset($_REQUEST['book_shelf']) ? $_REQUEST['book_shelf'] : 0;
				$txt_name = isset($_REQUEST['txt_name']) ? sanitize_text_field($_REQUEST['txt_name']) : '';
				$txt_email = isset($_REQUEST['txt_email']) ? $_REQUEST['txt_email'] : '';
				$txt_publication = isset($_REQUEST['txt_publication']) ? $_REQUEST['txt_publication'] : '';
				$txt_description = isset($_REQUEST['txt_description']) ? $_REQUEST['txt_description'] : '';
				
				$txt_cost = isset($_REQUEST['txt_cost']) ? $_REQUEST['txt_cost'] : '';
				$txt_status = isset($_REQUEST['txt_status']) ? $_REQUEST['txt_status'] : '';


				$rows = $wpdb->insert($table_name,array(

					'name'  => $txt_name,
					'description'  => $txt_description,
					'book_image'  => $book_image,
					'language'  => $txt_publication,
					'shelf_id'  => $book_shelf,
					'amount'  => $txt_cost,
					'status'  => $txt_status

				));

				

				//$rows如果不对的话php代码自动阻断

				if ($rows) {
					echo json_encode(array(
						'status'  => 1,
						'message'  => 'successfully insert the book'
					));
					
				} else {
					echo json_encode( array(
						'status'  => 0,
						'message'  => 'Fail to insert the book please try another day'

					) );
				}

				//上传图片 
				//
				

			} elseif($param == 'delete-book') {

				$table_name = $wpdb->prefix . 'owt_tbl_books';
				$book_id = isset($_REQUEST['book_id']) ? $_REQUEST['book_id'] : 0;

				$result = $wpdb->delete($table_name,array(
					'id'  => $book_id

				));

				if ($result) {

					echo json_encode( array(
						'status'  => 1,
						'message'  => 'Successully delete the book'


					) );
					
				} else {
					echo json_encode( array(
						'status'  => 0,
						'message'  => 'Fail to delete the book'

					) );
				}
			}
			
		}
 
		//从数据库提取要逃避 

		wp_die();
	}

}
