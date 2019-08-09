<?php
  // Declare Woocommerce Support
  function ex_woocommerce_support() {
  	add_theme_support('woocommerce');
  }
  add_action('after_setup_theme', 'ex_woocommerce_support');

  // Kill the Stylesheets
  add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

  // Remove Unnecessary Woocomerce Items
  function ex_wcStrip_metaboxes() {
    remove_meta_box('woocommerce-product-images', 'product', 'side');
    remove_meta_box('postexcerpt', 'product', 'normal');
    remove_meta_box('postimagediv', 'product', 'side');
    remove_meta_box('product_catdiv', 'product', 'side');
    remove_meta_box('tagsdiv-product_tag', 'product', 'side');
  }
  add_action('add_meta_boxes', 'ex_wcStrip_metaboxes', 40);

  // Remove Extraneous Product Details
  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title');

  // Add Order Notes onto Cart Page
  function ex_wcOrderNotesField() {
    echo '<textarea id="customer_notes_text"></textarea>';
  }
  function woocommerce_button_proceed_to_checkout() {
    $checkoutUrl = get_permalink(wc_get_page_id('checkout'));
?>
  <form id="checkout_form" method="POST" action="<?php echo $checkoutUrl; ?>">
    <input type="hidden" name="customer_notes" id="customer_notes" value="">
      <a href="#" onclick="document.getElementById('customer_notes').value=document.getElementById('customer_notes_text').value;document.getElementById('checkout_form').submit()" class="checkout-button button alt wc-forward">
    <?php _e( 'Proceed to checkout', 'woocommerce' ); ?></a>
  </form>
<?php
  }
  function ex_wcOrderNotesJs() {
?>
  <script>
    jQuery(document).ready(function() {
      jQuery('#order_comments').val("<?php echo sanitize_text_field($_POST['customer_notes']); ?>");
    });
  </script>
<?php
  }
  add_action('woocommerce_cart_collaterals', 'ex_wcOrderNotesField');
  add_action('woocommerce_checkout_before_customer_details', 'ex_wcOrderNotesJs');

  // Change label name of Order Notes
  function webendev_woocommerce_checkout_fields($fields) {
    $fields['order']['order_comments']['label'] = 'Location Details';
    return $fields;
  }
  add_filter('woocommerce_checkout_fields', 'webendev_woocommerce_checkout_fields');

  // Allow only one item in cart at a time
  function ex_wcOnlyOneItem( $passed, $added_product_id ) {
    wc_empty_cart();
    return $passed;
  }
  add_filter('woocommerce_add_to_cart_validation', 'ex_wcOnlyOneItem', 99, 2);
