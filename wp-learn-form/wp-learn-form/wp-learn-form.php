<?php 
/*
 * Plugin Name:       Wp learn form
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       wp-learn-form
 * Domain Path:       /languages
 */


//activation 创建数据表 
// 字段： id,name, email,phone,message title,message content, create_at  首先我们去创建一个数据表，命名为wp_learn_form

// if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
//     echo "GD is enabled";
// } else {
//     echo "GD is not enabled";
// }


register_activation_hook( __FILE__, 'wlf_create_form_table' );

register_deactivation_hook( __FILE__, 'wlf_uninstall_form_table' );

function wlf_create_form_table() {
	global $wpdb; 

	//导入db_delta 
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

$table_name = $wpdb->prefix . 'learn_form'; 

    //check if the table exists 注意if 条件里是 != 不是绝对不等 !== 第一个条件一定要写完整的不能直接 $table_name 无法 搜索
    if ($wpdb->get_var("SHOW tables LIKE '" .$wpdb->prefix."learn_form'") != $table_name) {
        $table_query = "CREATE TABLE $table_name (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"; 

    dbDelta($table_query);
        
    }

	//但是如果存在这个表的话 就无法生成， 所以要验证 
    // 创建初始字段 
    $options = get_option('up_options'); 

    if (!$options) { 
        add_option('up_options',array(
            'og_title'  => get_bloginfo('name'),
            'og_img'  => '',
            'og_description'  => get_bloginfo('description'),
            'enable_og'  => 1


        ));
        
    }

}


//deactivation 
function wlf_uninstall_form_table() {
    global $wpdb; 
    $table_name = $wpdb->prefix . 'learn_form';
    $wpdb->query(" DROP TABLE IF EXISTS " . $table_name);
}


//shortcode for form html 

add_shortcode( 'wp_learn_form', 'wp_learn_form_shortcode' );

function wp_learn_form_shortcode() { 

    ob_start(); 
    require plugin_dir_path( __FILE__ )  . 'shortcode/learn_form_html.php';

    return ob_get_clean();

}

//enqueue style 
add_action( 'wp_enqueue_scripts', 'wlf_enqueue_scripts' );

function wlf_enqueue_scripts() {

    wp_enqueue_style( 'wlf_style', plugin_dir_url( __FILE__ ). 'index.css' );

    wp_enqueue_script('wlf_main_js', plugin_dir_url( __FILE__ ) . 'js/handle_form.js',array('jquery'),'10.0.1',true);

    wp_localize_script( 'wlf_main_js', 'wlf_handle', array(
        'ajax_url'  => admin_url('admin-ajax.php')

    ) );
}


//admin page of the plugin  


//handl ajax request 

add_action( 'wp_ajax_handle_wlf_form_ajax', 'wlf_handle_ajax_cb' );
add_action( 'wp_ajax_nopriv_handle_wlf_form_ajax', 'wlf_handle_ajax_cb' );

function wlf_handle_ajax_cb() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'learn_form'; 

    $actual_verification_code = $_POST['actual_verification_code'];
    $user_verification_code = $_POST['user_verification_code'];

    if (!isset($_POST['wlf_form']) || !wp_verify_nonce( $_POST['wlf_form'], 'wlf' )) {


        echo json_encode(array(
            'status'  => 0, 
            'message'  => 'error with the nonce '


        ));

        die;
        
    }


    if ($user_verification_code === $actual_verification_code) {

        /////////////////////////////////////////
        //insert the form data into data base  //
        /////////////////////////////////////////


        //check if the data is not empty or what 
        
         $wlf_name = !empty($_POST['wlf_name']) ? sanitize_text_field( $_POST['wlf_name'] ) : '' ;
         $wlf_email = !empty($_POST['wlf_email']) ? sanitize_text_field( $_POST['wlf_email'] ) : '' ;
         $wlf_phone = !empty($_POST['wlf_phone']) ? sanitize_text_field( $_POST['wlf_phone'] ) : '' ;
         $wlf_title = !empty($_POST['wlf_title']) ? sanitize_text_field( $_POST['wlf_title'] ) : '' ;
         $wlf_message = !empty($_POST['message']) ? sanitize_text_field( $_POST['message'] ) : '' ;

         if (get_option( 'enable_email' ) == 'open') {

            //寄出邮件之后仍然要备份到数据库
             // Customize your email subject and message
        $subject = '[表单插件]来自' .$wlf_name . '的消息';
        $email_message = $wlf_message;

        // // Set your email address to receive the form submissions
        $to = get_bloginfo( 'admin_email' );

        $headers[]  = 'From:'.get_bloginfo( 'admin_email' );
        // //可以加cc $header[] = 'cc:admin@admin.com';
        $headers[] = 'Content-Type: text/html';
        $headers[] = 'charset=UTF-8';

        $template = file_get_contents( plugin_dir_path( __FILE__ ) . '/wlf_mail_template.html');

        $template = str_replace('%NAME%', $wlf_name, $template);
        $template = str_replace('%EMAIL%', $wlf_email, $template);
        $template = str_replace('%PHONE%', $wlf_phone, $template);
        $template = str_replace('%CREATE_TIME%', date("Y-m-d H:i:s"), $template);
        $template = str_replace('%TITLE%', $wlf_title, $template);
        $template = str_replace('%MESSAGE%', $wlf_message, $template);
        $template = str_replace('%SITE_DESC%', get_bloginfo('description'), $template);
        $template = str_replace('%SITE_URL%', home_url('/'), $template);

            
        // // Send the email
        $result = wp_mail($to, $subject, $template,$headers);

        // Optionally, you can redirect the user to a "Thank You" page after submission.
        
        if ($result) {
            echo json_encode( array(
                'status'  => 1, 
                'message'  => '成功发送邮件，页面即将刷新'


            ) );
            
        } else {
            echo json_encode(array(
                'status'  => 0,
                'message'  => '内部服务器错误，邮件无法发送，请联系网站管理员'


            ));
        }
           
             
         } else {

            $result = $wpdb->insert($table_name,array(
            'name'  => $wlf_name,
            'email'  => $wlf_email,
            'phone'  => $wlf_phone,
            'title'  => $wlf_title,
            'content'  => $wlf_message

         ));

            if ($result) {
                echo json_encode(array(
                    'status'  => 1,
                    'message'  => '操作成功，页面即将刷新'


                ));
                
            } else {
                echo json_encode(array(
                    'status'  => 0,
                    'message'  => '操作失败，内部服务器错误，请联系网站管理员'


                ));
            }

         }

         

        
    } else {

        echo json_encode( array(
            'status'  => 0,
            'message'  => '验证码填写错误请填写正确的验证码'

        ) );
    }

    wp_die();
}

//display the form message 用datatable 或者 wp list table 

function generate_verification_code($length = 6) {
    $characters = '0123456789';
    $verification_code = '';
    for ($i = 0; $i < $length; $i++) {
        $verification_code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $verification_code;
}


function generate_verification_image($verification_code) {
    $image = imagecreatetruecolor(200, 75);
    $bg_color = imagecolorallocate($image, 255, 255, 255);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    imagefill($image, 0, 0, $bg_color);

    // Use a font that's available on your system
    $font_size = 30;
    
    $font_path = plugin_dir_path( __FILE__ ) . '/Roboto-Regular.ttf';

    $font = 'arial.ttf';

    imagettftext($image, $font_size, 0, 10, 50, $text_color, $font_path, $verification_code);

    // Get the uploads directory information
    $upload_dir_info = wp_upload_dir();
    $image_path = $upload_dir_info['basedir'] . '/' . uniqid() . '.png'; // Adjust the path

    imagepng($image, $image_path);
    imagedestroy($image);

    $image_url = str_replace($upload_dir_info['basedir'], $upload_dir_info['baseurl'], $image_path);
    return $image_url; // Return the URL of the generated image
}


//include admin 
require_once plugin_dir_path( __FILE__ ) . '/admin/wlf-admin.php';


//检测wp mail 发送的错误 
// add_action( 'wp_mail_failed', 'onMailError', 10, 1 );
// function onMailError( $wp_error ) {
//     echo "<pre>";
//     print_r($wp_error);
//     echo "</pre>";
// } 

//打印wp mail 错误 
add_action('wp_mail_failed', 'log_mailer_errors', 10, 1);
function log_mailer_errors( $wp_error ){
  $fn = ABSPATH . '/mail.log'; // say you've got a mail.log file in your server root
  $fp = fopen($fn, 'a');
  fputs($fp, "Mailer Error: " . $wp_error->get_error_message() ."\n");
  fclose($fp);
}


//配置smtp  这样只要你端口开了  通过这个就可以配置smtp 

add_action( 'phpmailer_init', 'wlf_mailer_config', 10, 1);

function wlf_mailer_config($phpmailer){


    if (get_option('wlf_smtp_server') === 'on') {

        $smtp_port = !empty(get_option('smtp_server_port')) ? sanitize_text_field(get_option('smtp_server_port')) : 25;
        $smtp_name = !empty(get_option('smtp_server_name')) ? sanitize_text_field(get_option('smtp_server_name')) : '';
        $smtp_pass = !empty(get_option('smtp_server_pass')) ? sanitize_text_field(get_option('smtp_server_pass')) : '';

        $phpmailer->isSMTP();     
    $phpmailer->Host = 'smtp.qq.com';
    $phpmailer->SMTPAuth = true; // Ask it to use authenticate using the Username and Password properties
    $phpmailer->Port = $smtp_port;  // 或者 465 (常用， 服务器上25被屏蔽，要先开启465)
    $phpmailer->Username = $smtp_name;
    $phpmailer->Password = $smtp_pass;


        
    }
   
}