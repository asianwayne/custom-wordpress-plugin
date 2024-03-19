<?php 
/**
 * Plugin Name:Ha Frontend Submission
 * Plugin URI:https://webtown.cn
 * Author:Wayne
 * Author URI:https://webtown.cn
 * Description:留言插件，询盘插件，等等，在编辑器里添加短代码，然后复制[ha_submission]带中括号，就行
 * Version:1.2
 * 
 */

if (!defined('ABSPATH')) {
  die();
}

//enqueue scripts 

function ha_submission_form_style() {

  wp_enqueue_style( 'ha_submission_style', plugins_url( '/css/style.css', __FILE__ ));
  wp_enqueue_script('ha_submission_script_1', plugins_url( '/js/submission.js', __FILE__ ),array('jquery'),'2.0.0',true);
  

  wp_localize_script( 'ha_submission_script_1', 'ha_submission',array(
    'root'  => rest_url(),
    'nonce'  => wp_create_nonce( 'wp_rest' ),
    
  ) );
  
}

add_action('wp_enqueue_scripts','ha_submission_form_style',1);


require_once plugin_dir_path( __FILE__ ) . 'admin.php';



//插件启动后加载添加表格函数
register_activation_hook( __FILE__,'ha_submission_setup' );

function ha_submission_setup()
{

  global $wpdb;
  //also  add the time from javascript submit 

  $table_name = $wpdb->prefix . 'ha_submission';

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    name varchar (100) NOT NULL,
    email varchar (100) NOT NULL,
    phone varchar (100) NOT NULL,
    title varchar (255) NOT NULL,
    content varchar (255) NOT NULL,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    PRIMARY KEY (id))";

  require_once ABSPATH . 'wp-admin/includes/upgrade.php';

  dbDelta( $sql );

  
}

//注册自定义路由用于提交post请求

add_action('rest_api_init','register_ha_submission_route',4);

function register_ha_submission_route() {

  register_rest_route( 
    'ha_submission/v1',
    '/submission/',
    array(
      'methods'  => 'POST',
      'callback' => 'ha_post_submission_cb',
      'permission_callback'  => '__return_true'  //这里设置了return true就是这个路由可以用做所有人

    ),

   );
}


//验证权限  这里要设置 nonce  无需再用application password 
function validateCredentials($username, $password) {
  // Hard-coded list of valid users and passwords
  $valid_users = array(
    'user1' => 'password1',
    'user2' => 'password2',
    'user3' => 'password3'
  );

  // Check if the username exists in the list of valid users
  if (array_key_exists($username, $valid_users)) {
    // If the username exists, check if the password matches
    if ($valid_users[$username] == $password) {
      // If the password matches, return true to indicate that the credentials are valid
      return true;
    }
  }

  // If the username or password is invalid, return false to indicate that the credentials are invalid
  return false;
}


//路由的回调函数  设置nonce之后前面的验证已无用  用下面一个函数， js里面要改成nonce beforesSend
// function ha_post_submission_cb($request) {
// //因为是留言列表，所以这里不需要验证是否是存在的用户，不需要验证生产力 /笑哭 但是需要验证是否添加了application 的用户和密码 是否为空
// // Get the authentication credentials from the headers
// if (!empty($_SERVER['HTTP_AUTHENTICATION'])) {
//     $authHeader = trim($_SERVER['HTTP_AUTHENTICATION']);
// } else {
//     // No authentication credentials were sent, return an error
//     http_response_code(401);
//     die("Unauthorized");
// }

// // Validate the authentication credentials
// if (strpos($authHeader, 'Basic') !== 0) {
//     // The authentication credentials are not in the correct format, return an error
//     http_response_code(401);
//     die("Unauthorized");
// }

// list($username, $password) = explode(':', base64_decode(substr($authHeader, 6)));

// // Your implementation here - this could involve checking a database or other data source to validate the credentials
// if (!validateCredentials($username, $password)) {
//     // The authentication credentials are invalid, return an error
//     http_response_code(401);
//     die("Unauthorized");
// }
  
//   $data = [];
//   // Get the form data from the request
//   $paramters = $request->get_params();

//   $name = sanitize_text_field($paramters['name']);
//   $email = sanitize_text_field( $paramters['email'] );
//   $phone = sanitize_text_field( $paramters['phone'] );
//   $title = sanitize_text_field($paramters['title']);
//   $content = sanitize_textarea_field( $paramters['content'] );

//   if (isset($name) && isset($email) && isset($phone) && isset($title) && isset($content)) {
//     $name = sanitize_text_field( $name );
//     $email = sanitize_text_field( $email );
//     $phone = sanitize_text_field( $phone );
//     $title = sanitize_text_field( $title );
//     $content = sanitize_text_field( $content );

//     // Insert the data into the database
//   global $wpdb;
//   $table_name = $wpdb->prefix . 'ha_submission';

//   $wpdb->insert(
//     $table_name,
//     array(
//       'name' => $name,
//       'email' => $email,
//       'phone' => $phone,
//       'title' => $title,
//       'content' => $content,
//     ),
//     array('%s', '%s', '%s', '%s', '%s')
//   );

//   $data['status'] = 'OK';

//   $data['recieved_data'] = array(
//     'name' => $name,
//       'email' => $email,
//       'phone' => $phone,
//       'title' => $title,
//       'content' => $content,
//   );

//   $data['message'] = 'You have reached the server'; 

//   // Return a success message
    
//   } else {
    
//     $data['status'] = 'fail';
//     $data['message'] = '参数错误';
//   }

//   return $data;// Get the form data from the request

// }



function ha_post_submission_cb($request) { 

  //$request['name']  == $paramters['name']; 

  $data = [];
  // Get the form data from the request
  //$paramters = $request->get_params();

  $name = sanitize_text_field($request['name']);
  $email = sanitize_text_field( $request['email'] );
  $phone = sanitize_text_field( $request['phone'] );
  $title = sanitize_text_field($request['title']);
  $content = sanitize_textarea_field( $request['content'] );

  if (isset($name) && isset($email) && isset($phone) && isset($title) && isset($content)) {

    //后台检测数据不为空 继续下一步
    // Insert the data into the database
  global $wpdb;
  $table_name = $wpdb->prefix . 'ha_submission';

  $wpdb->insert(
    $table_name,
    array(
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'title' => $title,
      'content' => $content,
    ),
    array('%s', '%s', '%s', '%s', '%s')
  );

  $data['status'] = 'OK';

  $data['recieved_data'] = array(
    'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'title' => $title,
      'content' => $content,
  );

  $data['message'] = 'You have reached the server'; 

  // Return a success message
    
  } else {
    
    $data['status'] = 'fail';
    $data['message'] = '数据缺失，请验证数据是否填写完整';
  }

  return $data;// Get the form data from the request
  }

//表格的短代码
function ha_submossion_form_shortcode() {
  
  ob_start();
  ?>

  <h2>联系我们</h2>
  <div id="formAlert"></div>
  <form action="#" method="post" id="submission_form">
    
    <div class="form-group">
       <label for="name">姓名</label>
    <input type="text" id="name" name="name" required>
      
    </div>
   
    <div class="form-group">
      <label for="email">邮箱地址</label>
    <input type="email" id="email" name="email" required>
      
    </div>
    
    <div class="form-group">
      <label for="phone">电话号码</label>
    <input type="text" id="phone" name="phone" pattern="^1[3-9]\d{9}$" required>
      
    </div>
    
    <div class="form-group">
      <label for="title">留言标题</label>
    <input type="text" id="title" name="title" required>
      
    </div>
    

    <div class="form-group">
      <label for="content">留言内容</label>
    <textarea style="margin-bottom:30px" id="content" name="content" rows="5" required></textarea>
      
    </div>
    
    <div class="form-group" style="margin-bottom: 30px;">
      <label style="width:20%;float: left;" for="verify">请回答: 2566 + 3981 = ?</label>
<input style="width:70%;float: right;height:40px" type="number" id="verify" name="verify" required>
      <div style="clear: both;"></div>
      <div id="verifyAlert"></div>
    </div>
    


    <input type="submit" class="submit btn btn-lg" value="Submit">
  </form>

  

  <?php
  return ob_get_clean();
}

add_shortcode( 'ha_submission', 'ha_submossion_form_shortcode' );



//执行ajax 
// add_action( 'wp_footer', 'add_ajax_function',20 );

//为什么放在php文件执行script 是因为传递 application password 信息  现在可以放到js文件里面去执行 






