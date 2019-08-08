<?php
  // Declare Woocommerce Support
  function ex_woocommerce_support() {
  	add_theme_support('woocommerce');
  }
  add_action('after_setup_theme', 'ex_woocommerce_support');
