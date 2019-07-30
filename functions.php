<?php
/*
================================
  [[[ Global Theme Functions ]]]
================================
*/
require_once('functions/security.php');
require_once('functions/config.php');
require_once('functions/cleanup.php');
require_once('functions/init.php');
require_once('functions/images.php');
require_once('functions/menus.php');
require_once('functions/posts.php');
require_once('functions/admin.php');
require_once('functions/plugins.php');
require_once('functions/cpt.php');
require_once('functions/exonym-business/exonym-business.php');

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    $manifest = json_decode(file_get_contents('build/assets.json', true));
    $main = $manifest->main;
    wp_enqueue_style('theme-css', get_template_directory_uri() . "/build/" . $main->css,  false, null);
    wp_enqueue_script('theme-js', get_template_directory_uri() . "/build/" . $main->js, ['jquery'], null, true);
}, 100);


add_action('login_enqueue_scripts', 'custom_login_assets'); // customized login
// Custom Login Styles & Scripts (NOTE: init)
function custom_login_assets() {
  $manifest = json_decode(file_get_contents('build/assets.json', true));
  $main = $manifest->main;
  wp_enqueue_style('custom-login', get_template_directory_uri() . "/build/" . $main->css,  false, null);
}
