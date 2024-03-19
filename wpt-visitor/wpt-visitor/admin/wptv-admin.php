<?php 
//注册admin menu page 

function record_visitor_ips_page() {
  add_menu_page(
    'Visitor IPs',
    '访客访问记录',
    'manage_options',
    'record-visitor-ips',
    'display_visitor_ips_record_page',
    'dashicons-admin-generic',
    30
  );
}
add_action('admin_menu', 'record_visitor_ips_page');

 //初始化wp list table 添加数据表信息
//添加wp list class 父系table 
// Include the WP_List_Table class
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class Record_Visitor_IP_List_Table extends WP_List_Table {

  function __construct() {
    parent::__construct( array(
      'singular' => 'visitor',
      'plural' => 'visitors',
      'ajax' => false
    ) );
  }

  function column_default( $item, $column_name ) {
  switch( $column_name ) {
    case 'id' :
    case 'ip':
    case 'browser':
      return $item[ $column_name ];
    case 'route':
      return '<a href="' . esc_url( $item['route'] ) . '">' . esc_html( $item['route'] ) . '</a>';
    case 'time':
      return $item[ $column_name ];
    default:
      return print_r( $item, true );
  }
}


  function get_columns() {
    $columns = array(
      'cb' => '<input type="checkbox" />',
      'id'         => 'ID',
      'ip' => 'IP Address',
      'browser' => 'Browser',
      'route'  => 'Route',
      'time' => 'Visit Time'
    );
    return $columns;
  }

  function prepare_items() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'visitor_ips'; // Replace "visitor_ips" with the name of the new table

    $per_page = 20; // Number of records per page

    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();

    $this->_column_headers = array( $columns, $hidden, $sortable );

    $current_page = $this->get_pagenum();


    //按最新时间排序
    $orderby = ( isset( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'time';
    $order = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'DESC';

    $offset = ( $current_page - 1 ) * $per_page;

    $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );

    $search_query = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : '';


        $where = '';

        if ( ! empty( $search_query ) ) {
            $where = "WHERE id LIKE '%$search_query%' OR ip LIKE '%$search_query%' OR browser LIKE '%$search_query%' OR time LIKE '%$search_query%' OR route LIKE '%$search_query%'";
        }

    $data = $wpdb->get_results( "SELECT * FROM $table_name $where ORDER BY $orderby $order LIMIT $per_page OFFSET $offset", ARRAY_A );

    $this->items = $data;

    $this->set_pagination_args( array(
      'total_items' => $total_items,
      'per_page' => $per_page,
      'total_pages' => ceil( $total_items / $per_page )
    ) );
  }

  function get_sortable_columns() {
    $sortable_columns = array(
      'id'  => 'id',
      'ip' => array( 'ip', true ),
      'browser' => array( 'browser', true ),
      'route'  => array('route',true),
      'time' => array( 'time', true )
    );
    return $sortable_columns;
  }

public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="submission[]" value="%s" />', absint( $item['id'] )
        );
    }


}

//调出wp list table 
function display_visitor_ips_record_page() {
 

  $table = new Record_Visitor_IP_List_Table();
  $table->prepare_items();
    

?>

  <div class="wrap">
    <h2>访客信息记录</h2>
    <?php 
    echo '<form method="post">';
  $table->search_box( '搜索', 'ha_submission_search' );
  echo '</form>';
    $table->display(); ?>
  </div>
<?php
}