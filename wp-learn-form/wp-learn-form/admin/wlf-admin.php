<?php 

add_action( 'admin_menu', 'add_wlf_to_tools' );


add_action( 'admin_menu', 'wlf_add_admin_page' );

function wlf_add_admin_page() {

	add_menu_page( __( 'Udemy Plus' ), __( 'Udemy Plus' ), 'edit_theme_options','up-plugins-options', 'up_plugins_options_page' );
}

function up_plugins_options_page() {  
	$options = get_option('up_options');
	ob_start(); 


	require plugin_dir_path( __FILE__ ) . 'partials/udemy-plus-page.php';


	echo  ob_get_clean(); 
}


function add_wlf_to_tools() {
	add_management_page( 'wlf settings', 'wlf设置', 'manage_options', 'wlf-settings', 'wlf_settings_cb' );
	add_management_page( 'wlf list', 'wlf清单', 'manage_options', 'wlf-list', 'wlf_admin_list_cb' );
}

function wlf_admin_list_cb() {  
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'learn_form';
	ob_start(); 

	require_once plugin_dir_path( __FILE__ ) . 'partials/wlf_admin_lists.php';

	echo ob_get_clean();

}


function wlf_settings_cb() { ?>
	<div class="wrap">
		<h1>插件设置页面</h1>
		<form action="options.php" method="post">

			<?php 
			settings_fields( 'wlf_group' );
			do_settings_sections( 'wlf-settings' );
			settings_errors();
			submit_button(); 

			 ?>
		

		</form>
	</div>


<?php 
}



add_action( 'admin_init', 'wlf_add_plugin_options' );


function wlf_add_plugin_options() {

	register_setting( 'wlf_group', 'enable_email' );
	register_setting( 'wlf_group', 'enable_text' );
	register_setting('wlf_group','wlf_smtp_server');
	register_setting('wlf_group','smtp_server_name');
	register_setting('wlf_group','smtp_server_pass');
	register_setting('wlf_group','smtp_server_port');


	add_settings_section( 'wlf_settings_section', 'wlf表单设置--短代码是[wp_learn_form]', '', 'wlf-settings' );

	add_settings_field( 'enable_email', '发送邮箱', 'wlf_enable_field_cb', 'wlf-settings', 'wlf_settings_section', array('one' => 'two') );
	add_settings_field( 'wlf_smtp_server', 'SMTP服务器设置<p>目前只支持QQ邮箱</p>', 'wlf_smtp_server_field_cb', 'wlf-settings', 'wlf_settings_section', array('one' => 'two') );

}

function wlf_smtp_server_field_cb() { ?>
	 <label for="wlf_smtp_server">开启SMTP服务器：</label> 
  <input type="checkbox" id="wlf_smtp_server" name="wlf_smtp_server" <?php checked( get_option( 'wlf_smtp_server'), 'on' ); ?>>
  
  <div id="hiddeninputs" style="display: none;">
  	<p>
  		<label for="smtp_server_name">SMTP用户名（你的QQ邮箱名字）</label>
    <input type="text" id="smtp_server_name" name="smtp_server_name" value="<?php echo get_option( 'smtp_server_name' );  ?>">
  	</p>
     <br>
    
    <p>
    	<label for="smtp_server_pass">SMTP密码（QQ邮箱获取的授权码）</label>
    <input type="text" id="smtp_server_pass" name="smtp_server_pass" value="<?php echo get_option( 'smtp_server_pass' ); ?>">
    </p>
     <br>

     <p>
     	<label for="smtp_server_port">SMTP端口(一般是465，25在很多服务器被屏蔽)</label>
    <input type="number" id="smtp_server_port" name="smtp_server_port" value="<?php echo get_option( 'smtp_server_port' ); ?>">
     </p>
    
  </div>


 <?php }


function wlf_setting_section_cb() {
	echo 'this is the section display page'; 
}

function wlf_enable_field_cb($args) {  ?>

	<input type="checkbox" name="enable_email" id="enable_email" value="open" <?php checked( get_option( 'enable_email' ), 'open'); ?>>
	

	<p>选中发送到邮箱，不会在数据库保存，如果发送邮件出现错误，请配置下面smtp服务器</p>


	<?php 

}

add_action( 'admin_enqueue_scripts', 'wlf_add_admin_scripts');

function wlf_add_admin_scripts($hook) {

	wp_enqueue_script('handle_admin', plugins_url( 'js/handle_admin.js', dirname(__FILE__) ), array('jquery'), '1.0.0.1', true);

	if ($hook === 'tools_page_wlf-list') {
		wp_enqueue_style('wlf_datatable', plugins_url( 'vendor/datatables.min.css', dirname(__FILE__) ));
		wp_enqueue_script('wl_datatable_js', plugins_url('vendor/datatables.min.js', dirname(__FILE__)),array(),'1.0.0.1',true);
	}
	
}