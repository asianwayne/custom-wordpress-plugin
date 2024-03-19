<?php 
/**
 * Plugin Name:Wp learn form submission Api
 * Version:1.0.0
 */

register_activation_hook( __FILE__, 'wp_learn_setup_table' );


function wp_learn_setup_table() {

  global $wpdb;
  $table_name = $wpdb->prefix . 'form_submissions';
  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    PRIMARY KEY (id)

)"; 

  require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  dbDelta($sql);


}


//register_rest_route 
//use rest api init hook 
add_action( 'rest_api_init', 'wp_learn_register_routes' );


function wp_learn_register_routes() {

  //GET的路由
  register_rest_route( 'wp-learn-form-submission-api/v1', '/form-submissions/', array(
    'methods'  => 'GET',
    'callback'  => 'wp_learn_get_form_submissions',
    'permission_callback' => '__return_true'  //return true代表所有人可以进入这个路由


  ) );

  //注册POST路由 路由地址可以一样  回调函数变下就可 
  register_rest_route( 'wp-learn-form-submission-api/v1', '/form-submissions/', array(
    'methods'  => 'POST',
    'callback'  => 'wp_learn_create_form_submission',
    //'permission_callback' => '__return_true'  //return true代表所有人可以进入这个路由，所以要在回调函数中设置权限
    //或者直接设置权限回调函数 
    'permission_callback' => 'wp_learn_callback_permission'


  ) );

  //获取单个信息

   register_rest_route( 'wp-learn-form-submission-api/v1', '/form-submission/(?P<id>\d+)', array(
    'methods'  => 'GET',
    'callback'  => 'wp_learn_get_form_submission',
    //'permission_callback' => '__return_true'  //return true代表所有人可以进入这个路由，所以要在回调函数中设置权限
    //或者直接设置权限回调函数 
    'permission_callback' => '__return_true'


  ) );

}
//GET 请求的回调函数
//GET 获取的也是 
//$request是从请求传递的json的参数
function wp_learn_get_form_submissions($request ) {
//return the result as json object，  发送到response  return 返回json的对象
//这里是获取全部的

//用path variables  路径变量 
//path vatiables enable us the dynamic route  path varbiale: ?P<id>\d+    P是query parameter <{里面是name变量}> \d+ 正则匹配 \d+ 表示应该是 数字 data 
global $wpdb;
$table_name = $wpdb->prefix . 'form_submissions';

$results = $wpdb->get_results("SELECT * FROM $table_name");

return $results;

}

//POST会传递$request 包括所有传递到route的信息

function wp_learn_create_form_submission($request) {

global $wpdb;
$table_name = $wpdb->prefix . 'form_submissions';

$rows = $wpdb->insert(

$table_name, 
array(
  'name'  => $request['name'],
  'email'  => $request['email'],

)

);

return $rows;

}

function wp_learn_callback_permission() {

  //设置当前只有编辑权限的才可以
  return current_user_can( 'edit_posts' );

  //去application password那里设置password  
  //点击创建password  复制password 
  //选择authorization 选择basic auth   选择username 为你的， 输入粘贴的application password 
  //ha frontend submisstion插件里面写的太复杂了， 要更新
}


function wp_learn_get_form_submission($request ) {
//return the result as json object，  发送到response  return 返回json的对象
//这里是获取全部的
$id = $request['id'];
//用path variables  路径变量 
//path vatiables enable us the dynamic route  path varbiale: ?P<id>\d+    P是query parameter <{里面是name变量}> \d+ 正则匹配 \d+ 表示应该是 数字 data 
global $wpdb;
$table_name = $wpdb->prefix . 'form_submissions';

$results = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $id");

return $results[0];

}