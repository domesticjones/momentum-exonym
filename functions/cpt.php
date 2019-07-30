<?php
/*
===========================
  [[[ Custom Post Types ]]]
===========================
*/

// Clear Rewrite Rules for CPT's
function ex_theme_terlet() {
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'ex_theme_terlet');
