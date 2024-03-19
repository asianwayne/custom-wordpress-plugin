<?php 

add_action( 'admin_menu', 'add_wlf_to_tools' );

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


	add_settings_section( 'wlf_settings_section', 'wlf_settings_section', 'wlf_setting_section_cb', 'wlf-settings' );

	add_settings_field( 'enable_email', '发送邮箱', 'wlf_enable_field_cb', 'wlf-settings', 'wlf_settings_section', array('one' => 'two') );

}


function wlf_setting_section_cb() {
	echo 'this is the section display page'; 
}

function wlf_enable_field_cb($args) {  ?>

	<input type="checkbox" name="enable_email" id="enable_email" value="open" <?php checked( get_option( 'enable_email' ), 'open'); ?>>
	

	<p>选中发送到邮箱</p>


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