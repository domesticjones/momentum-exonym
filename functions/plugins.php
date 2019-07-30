<?php
/*
===================================
  [[[ Activate Required Plugins ]]]
===================================
*/

// Clean plugin activation and retention
function run_activate_plugin($plugin) {
  $current = get_option('active_plugins');
  $plugin = plugin_basename(trim($plugin));
  if (!in_array($plugin, $current)) {
    $current[] = $plugin;
    sort($current);
    do_action('activate_plugin', trim($plugin));
    update_option('active_plugins', $current);
    do_action('activate_' . trim($plugin));
    do_action('activated_plugin', trim($plugin));
  }
  return null;
}

// Activate ACF Pro
$activate_acf = (WP_PLUGIN_DIR . '/advanced-custom-fields-pro/acf.php');
run_activate_plugin($activate_acf);

// Activate Favicon Generator
$activate_favicons = (WP_PLUGIN_DIR . '/favicon-by-realfavicongenerator/favicon-by-realfavicongenerator.php');
run_activate_plugin($activate_favicons);
