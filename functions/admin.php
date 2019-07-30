<?php
/*
=====================================
  [[[ Admin & Login Customization ]]]
=====================================
*/

// Admin footer attribution
function remove_footer_admin() {
  echo '<span id="footer-thankyou">Exstructae : <a href="http://domesticjones.com" target="_blank">Exonym</a></span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

// Kill the admin bar on the front-end
add_filter('show_admin_bar', '__return_false');

// Custom Login Logo
function custom_login_logo() { ?>
  <style type="text/css">
    #login h1 a,
    .login h1 a {
      background-image: url(<?php echo ex_logo('alternate', 'dark'); ?>);
    }
  </style>
<?php }
add_action('login_enqueue_scripts', 'custom_login_logo');

// Enable SVG upload in admin
function wpcontent_svg_mime_type($mimes = array()) {
  $mimes['svg']  = 'image/svg+xml';
  $mimes['svgz'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'wpcontent_svg_mime_type');

// Allow Editors to edit Menus
$roleObject = get_role( 'editor' );
if (!$roleObject->has_cap('edit_theme_options')) {
  $roleObject->add_cap('edit_theme_options');
}
