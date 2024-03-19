<div class="wrap">
	<h2>WPT LOGIN Admin</h2>
	<form action="options.php" method="post">
		<?php 
		settings_fields( 'wpt_admin' );
		do_settings_sections( 'wpt-login-admin' );
		settings_errors();
		submit_button();

		 ?>
	</form>
</div>