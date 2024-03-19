<div class="wrap">
  <h2>Uc alt udemy subm page</h2>
  <form action="options.php" method="POST">

    <?php 
    settings_fields( 'up_options_group' );
    do_settings_sections( 'alt-udemy-plus' );
    submit_button();


     ?>
    
  </form>

</div>