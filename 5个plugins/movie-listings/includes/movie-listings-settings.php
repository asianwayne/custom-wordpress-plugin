<?php 
function ml_movie_list_settings()
{
  add_settings_section( 'ml_setting_section', 'Movie List Settings', 'ml_settings_section_cb', 'reading' );

  add_settings_field( 'ml_setting_show_editor', 'Show Editor', 'ml_setting_show_editor_cb', 'reading', 'ml_setting_section' );
  register_setting( 'reading', 'ml_setting_show_editor' );
  add_settings_field( 'ml_setting_show_media_buttons', 'Show Media Buttons', 'ml_setting_show_media_btn_cb', 'reading', 'ml_setting_section' );
    register_setting( 'reading', 'ml_setting_show_media_buttons' );

}

add_action('admin_init','ml_movie_list_settings');

function ml_settings_section_cb()
{
  echo '<p>Settings</p>';
}

function ml_setting_show_editor_cb()
{
  echo '<input type="checkbox" name="ml_setting_show_editor" id="ml_setting_show_editor" value="1" class="code" ' . checked( 1, get_option('ml_setting_show_editor'),false ) .'>Check if details should be editor';
  
}

function ml_setting_show_media_btn_cb()
{
  echo '<input type="checkbox" name="ml_setting_show_media_buttons" id="ml_setting_show_media_buttons" value="1" class="code" ' . checked( 1, get_option('ml_setting_show_media_buttons'),false ) .'>Check if media button should be displayed';
  
}