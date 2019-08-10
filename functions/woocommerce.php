<?php
  // Declare Woocommerce Support
  function ex_woocommerce_support() {
  	add_theme_support('woocommerce');
  }
  add_action('after_setup_theme', 'ex_woocommerce_support');

  // Redirect login requests to Account page
  function ex_redirect_wc_public(){
    global $pagenow;
    if( 'wp-login.php' == $pagenow ) {
      if ( isset( $_POST['wp-submit'] ) ||   // in case of LOGIN
        ( isset($_GET['action']) && $_GET['action']=='logout') ||   // in case of LOGOUT
        ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') ) return;  // in case of LOST PASSWORD
        //( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') ) return;    // in case of REGISTER
      else {
        wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')), 301);
        exit();
      }
    }
  }
  add_action('init','ex_redirect_wc_public');

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
        <?php echo ex_cta('arrow', 'Review &amp; Complete Appointment', '#'); ?>
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

  // Change Empty Cart Message
  function ex_wcEmptyCartMsg() {
    return 'No inspection or date has been selected.';
  }
  add_filter('wc_empty_cart_message', 'ex_wcEmptyCartMsg');



  // Detach Registration page and make it on its own section

add_shortcode( 'ex_wcRegistrationForm', 'bbloomer_separate_registration_form' );

function bbloomer_separate_registration_form() {
   if ( is_admin() ) return;
   if ( is_user_logged_in() ) return;
   ob_start();

   // NOTE: THE FOLLOWING <FORM></FORM> IS COPIED FROM woocommerce\templates\myaccount\form-login.php
   // IF WOOCOMMERCE RELEASES AN UPDATE TO THAT TEMPLATE, YOU MUST CHANGE THIS ACCORDINGLY

   wc_get_template_part('myaccount/form', 'register');

   return ob_get_clean();
}
