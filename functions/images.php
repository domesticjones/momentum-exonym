<?php
/*
==================================
  [[[ Image Settings & Support ]]]
==================================
*/

// WP Theme Checker Compliance
if (!isset($content_width)) {
  $content_width = 1920;
}

// Enable the featured image
add_theme_support('post-thumbnails');

// Define theme image sizes
if (function_exists('add_image_size')) {
  add_image_size('small', 400, 400);
  add_image_size('medium', 800, 800);
  add_image_size('large', 1200, 1200);
  add_image_size('jumbo', 2000, 2000);
  add_image_size('thumbnail', 200, 200, true);
  add_image_size('thumbnail-medium', 400, 400, true);
  add_image_size('thumbnail-large', 800, 800, true);
}

// Name the different sizes
function custom_image_size_names($sizes) {
  return array_merge($sizes, array(
  'thumbnail' => ('Thumbnail - Small'),
  'thumbnail-medium' => ('Thumbnail - Medium'),
  'thumbnail-large' => ('Thumbnail - Large'),
  'jumbo' => ('Jumbo')
  ));
}
add_filter('image_size_names_choose', 'custom_image_size_names');
