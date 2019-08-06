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
    'services-menu' => __('Services', 'exonym'),
    'responsive-menu' => __('Responsinve', 'exonym')
  )
);
