<?php 
// Register the menu item
function my_plugin_register_menu() {
    add_menu_page(
        'Message', // Page title
        '留言列表', // Menu title
        'manage_options', // Capability required to access the menu item
        'message', // Menu slug
        'my_plugin_display_settings_page' // Callback function to display the page
    );

     add_submenu_page(
        'message',
        '插件设置',
        '插件设置',
        'manage_options',
        'my-plugin-submenu',
        'my_plugin_submenu_page'
    );

     add_action( 'admin_init', 'register_my_settings' );
}

add_action( 'admin_menu', 'my_plugin_register_menu' );

function my_plugin_submenu_page() { ?>
     <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'my-plugin-settings' ); ?>
            <?php do_settings_sections( 'my-plugin-submenu' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    
 <?php  }

 function register_my_settings() {
    // register the settings section
    add_settings_section(
        'my-settings-section',
        '留言板接口信息',
        'my_settings_section_callback',
        'my-plugin-submenu'
    );
    
    // register the settings fields
    add_settings_field(
        'application_username',
        'Username',
        'username_callback',
        'my-plugin-submenu',
        'my-settings-section'
    );
    
    add_settings_field(
        'application_password',
        'Application Password',
        'application_password_callback',
        'my-plugin-submenu',
        'my-settings-section'
    );
    
    // register the settings
    register_setting( 'my-plugin-settings', 'application_username' );
    register_setting( 'my-plugin-settings', 'application_password' );
}

function my_settings_section_callback() {
    echo '<p>插件短代码是[ha_submission]，在任何地方通过编辑器的短代码模块直接粘贴就可以使用，第一版的用户和密码是user1和password1</p>';
}

function username_callback() {
    $username = get_option( 'application_username' );
    echo '<input type="text" id="username" name="application_username" value="' . esc_attr( $username ) . '" />';
}

function application_password_callback() {
    $application_password = get_option( 'application_password' );
    echo '<input type="text" id="application_password" name="application_password" value="' . esc_attr( $application_password ) . '" />';
}

// Display the settings page
function my_plugin_display_settings_page() {
    // Include the WP_List_Table class
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    
    // Define the List Table class
    class My_Custom_Table_List_Table extends WP_List_Table {


        function __construct() {
            parent::__construct( array(
                'singular' => 'Data',
                'plural'   => 'Datas',
                'ajax'     => false
            ) );
        }
        
        function get_columns() {
            $columns = array(
              'cb' => '<input type="checkbox" />',
                'id'         => 'ID',
                'name' => 'Name',
                'email'  => 'Email',
                'phone'      => 'Phone',
                'title'      => 'Title',
                'content'  => 'Content',
                'create_at'  => '留言时间'
            );
            return $columns;
        }
        
        function prepare_items() {
            global $wpdb;
            
            $table_name = $wpdb->prefix . 'ha_submission';
             $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();

        // Define the columns that are sortable.
        $sortable_columns = array(
            'name' => array( 'name', true ),
            'phone' => array( 'phone', true ),
            'email' => array( 'email', true ),
            'title' => array( 'title', true ),
            'create_at'  => array('create_at',true),
        );

        // Check which column to sort by.
        $orderby = ( isset( $_GET['orderby'] ) && array_key_exists( $_GET['orderby'], $sortable_columns ) ) ? $_GET['orderby'] : 'name';

        // Check which order to sort by.
        $order = ( isset( $_GET['order'] ) && in_array( strtoupper( $_GET['order'] ), array( 'ASC', 'DESC' ) ) ) ? $_GET['order'] : 'ASC';


        $this->_column_headers = array( $columns, $hidden, $sortable_columns );

        $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );


        $paged = $this->get_pagenum();

        $offset = ( $paged - 1 ) * $per_page;


        $orderby = ( isset( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'create_at';
        $order = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'DESC';


        $search_query = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : '';


        $where = '';

        if ( ! empty( $search_query ) ) {
            $where = "WHERE name LIKE '%$search_query%' OR email LIKE '%$search_query%' OR phone LIKE '%$search_query%' OR title LIKE '%$search_query%' OR content LIKE '%$search_query%'";
        }


        $data = $wpdb->get_results( "SELECT * FROM $table_name $where ORDER BY $orderby $order LIMIT $per_page OFFSET $offset", ARRAY_A );



        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page )
        ) );

        
        }
        
         public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
          case 'id':
            case 'name':
            case 'phone':
            case 'email':
            case 'title' :
             case 'content':
             case 'create_at' :
            return $item[ $column_name ];
        default:
            //return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
        }
      }

    public function column_name( $item ) {
        $delete_nonce = wp_create_nonce( 'ha_submission_delete_' . $item['id'] );

        $title = '<strong>' . $item['name'] . '</strong>';
        $actions = array(
            'delete' => sprintf( '<a href="?page=%s&action=%s&submission=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }

    public function get_bulk_actions() {
        $actions = array(
            'bulk-delete' => 'Delete'
        );

        return $actions;
    }

    public function process_bulk_action() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'ha_submission';

        if ( 'delete' === $this->current_action() ) {
            $ids = isset( $_REQUEST['submission'] ) ? $_REQUEST['submission'] : array();

            if ( is_array( $ids ) ) {
                $ids = implode( ',', $ids );
            }

            if ( ! empty( $ids ) ) {
                $wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );
            }
        } elseif ( 'bulk-delete' === $this->current_action() ) {
            $ids = isset( $_REQUEST['submission'] ) ? $_REQUEST['submission'] : array();

            if ( is_array( $ids ) ) {
                $ids = implode( ',', $ids );
            }

            if ( ! empty( $ids ) ) {
                $wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );
            }
        }
    }

    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="submission[]" value="%s" />', absint( $item['id'] )
        );
    }


    }
    
    // Create an instance of the List Table class
    $my_custom_table_list_table = new My_Custom_Table_List_Table();
    
    // Fetch data from the custom table
    $my_custom_table_list_table->prepare_items();

    echo '<div class="wrap"><h2>HA Submissions</h2>';

if ( isset( $_REQUEST['message'] ) && $_REQUEST['message'] == 'deleted' ) {
    echo '<div class="updated"><p>Submission(s) deleted.</p></div>';
}

echo '<form method="post">';
$my_custom_table_list_table->search_box( 'Search Submissions', 'ha_submission_search' );
echo '</form>';
    
    // Display the table
    echo '<div class="wrap"><h2>List all the message</h2>';
    $my_custom_table_list_table->display();
    echo '</div>';

    // 函数结束 
}

// Handle bulk actions.
function ha_submission_list_table_bulk_action_handler() {
$list_table = new My_Custom_Table_List_Table();
$list_table->process_bulk_action();
wp_redirect( esc_url( add_query_arg() ) );
exit;
}

add_action( 'admin_post_ha_submission_bulk_action', 'ha_submission_list_table_bulk_action_handler' );

