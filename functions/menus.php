<?php
/*
==============================
  [[[ Define all the Menus ]]]
==============================
*/

// Enable menus in WP
add_theme_support('menus');

// Define the nav bars
register_nav_menus(
  array(
    'header-menu' => __('Header', 'exonym'),
    'footer-menu' => __('Footer', 'exonym'),
    'responsive-menu' => __('Responsinve', 'exonym')
  )
);
