<?php
/*
==========================================
  [[[  Theme Configuration & Shortcuts ]]]
==========================================
*/

// Add HTML5 and Media Query support for IE (NOTE: init)
function add_ie_html5_shim () {
  echo '<!--[if lt IE 9]>';
  echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
  echo '<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>';
  echo '<![endif]-->';
}

// Remove jQuery Migrate because we have eslint telling us everything we did wrong (NOTE: init)
function remove_jquery_migrate($scripts) {
   if(is_admin()) return;
   $scripts->remove('jquery');
   $scripts->add('jquery', false, array('jquery-core'), '1.10.2');
}

// Adding WP 3+ Functions & Theme Support (NOTE: init)
function ex_theme_support() {
	add_theme_support('automatic-feed-links'); // RSS for all post types
	add_theme_support('html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'caption'
	));
}

// Inject the real scripts and styles (NOTE: init)
function ex_scripts_and_styles() {
  global $wp_styles;
  if (!is_admin()) {
    // Only embed threadded comments when necessary
    if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) { wp_enqueue_script( 'comment-reply' ); }
  }
}
