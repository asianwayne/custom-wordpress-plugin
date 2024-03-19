<?php
add_action( 'admin_menu', 'wpt_login_admin_page' );


function wpt_login_admin_page() {
	add_menu_page( 'WPT ADMIN', 'WPT ADMIN', 'edit_theme_options', 'wpt-login-admin', 'wpt_login_admin_page_cb' );
}

function wpt_login_admin_page_cb() {

	ob_start(); 

	require_once plugin_dir_path( __FILE__ ) . 'partials/wpt_login_admin_ui.php';

	echo ob_get_clean(); 

}

//register settings with globale varial option $wpt_admin

add_action( 'admin_init', 'wpt_admin_fields' );

function wpt_admin_fields()
{
	//这里option group 和 field 一样， 也可以不一样，推荐不一样 
	register_setting('wpt_admin','wpt_admin'); 

	add_settings_section( 'wpt_admin_section', 'WPT Plugin Control', '', 'wpt-login-admin' );

	add_settings_field( 'wpt_page_slug', 'Page Slug', 'wpt_page_slug_input', 'wpt-login-admin', 'wpt_admin_section' );
	add_settings_field( 'wpt_page_bg_video', 'Bg Video', 'wpt_page_bg_video_input', 'wpt-login-admin', 'wpt_admin_section' );
	
}

function wpt_page_slug_input($value='')
{
global $wpt_admin;
 ?>

	<input type="text" class="large-text" name="wpt_admin[slug]" value="<?php echo !empty($wpt_admin['slug']) ? $wpt_admin['slug'] : '' ?>">

	<?php 
	
	
}

function wpt_page_bg_video_input() { global $wpt_admin; ?>
	<input type="text" class="regular-text" name="wpt_admin[bg_video]" id="wpt_admin_bg_video" value="<?php echo !empty($wpt_admin['bg_video']) ? $wpt_admin['bg_video'] : '' ?>">
	<p>
		<video width="250" autoplay muted>
			<source src="<?php echo $wpt_admin['bg_video'] ?>" type="video/mp4">
		</video>
	</p>
	

<p>
	<input type="button" class="button-outline-primary" id="wpt_bg_video_btn" value="UPLOAD">
</p>
	

	<?php 

}