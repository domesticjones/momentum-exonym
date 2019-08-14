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
        ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') || // in case of LOST PASSWORD
        ( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') ) return;    // in case of REGISTER
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
  function ex_wcParseNotes($origin) {
    $notes = sanitize_text_field($origin);
    $servicesRaw = get_string_between($notes, '[services]', '[/services]');
    $services = '';
    if(!empty($servicesRaw)) {
      $services = '<p class="services"><strong>Services: </strong>' . $servicesRaw . '</p>';
    }
    $areaRaw = get_string_between($notes, '[area]', '[/area]');
    $area = '';
    if(!empty($areaRaw)) {
       $area = '<br />' . $areaRaw;
    }
    $sup = '<p class="supervisor"><strong>Supervisor: </strong>' . get_string_between($notes, '[sup]', '[/sup]') . '</p>';
    $sqft = '<p class="sqft"><strong>Square Feet: </strong>' . get_string_between($notes, '[sqft]', '[/sqft]') . '</p>';
    $address = '<p class="address"><strong>Address: </strong>' . get_string_between($notes, '[address]', '[/address]') . $area . '<br />' . get_string_between($notes, '[locale]', '[/locale]') . '</p>';
    return $services . $sup . $sqft . $address;
  }

  function ex_wcOrderNotesJs() {
    $reviewString = '<h3>Inspection Site Details</h3>' . ex_wcParseNotes($_POST['customer_notes']) . '<h3>Inspection Details</h3>';
?>
  <script>
    jQuery(document).ready(function() {
      jQuery('#order_comments').val('<?php echo sanitize_text_field($_POST['customer_notes']); ?>');
      jQuery('#order_review').prepend('<?php echo $reviewString; ?>');
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
  add_shortcode( 'ex_wcRegistrationForm', 'ex_wcRegistrationFormSeparate' );
  function ex_wcRegistrationFormSeparate() {
    if(is_admin()) return;
    if(is_user_logged_in()) return;
    ob_start();
      wc_get_template_part('myaccount/form', 'register');
    return ob_get_clean();
  }

  // Additional Registration Fields
  function startsWith($haystack, $needle){
    return $needle === '' || strpos($haystack, $needle) === 0;
  }

  // Display the Billing Address form to registration page
  function ex_add_billing_form_to_registration(){
    global $woocommerce;
    $checkout = $woocommerce->checkout();
    $billingFields = $checkout->get_checkout_fields( 'billing' );
    ?>
    <?php foreach ($billingFields  as $key => $field ) : ?>
      <?php if($key!='billing_email'){
        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
      } ?>
    <?php endforeach;
  }
  add_action('woocommerce_register_form_start','ex_add_billing_form_to_registration');

  // Custom function to save Usermeta or Billing Address of registered user
  function ex_save_billing_address($user_id){
    global $woocommerce;
    $address = $_POST;
    foreach ($address as $key => $field){
      if(startsWith($key,'billing_')){
        if($key == 'billing_first_name' || $key == 'billing_last_name'){
          $new_key = explode('billing_',$key);
          update_user_meta( $user_id, $new_key[1], $_POST[$key] );
        }
        update_user_meta( $user_id, $key, $_POST[$key] );
      }
    }
  }
  add_action('woocommerce_created_customer','ex_save_billing_address');

  // Registration page billing address form Validation
  function ex_validation_billing_address( $errors ) {
    $address = $_POST;
    foreach ($address as $key => $field) :
      if(startsWith($key,'billing_')){
        if($key == 'billing_country' && $field == ''){
          add_the_error($errors, $key, 'Country');
        }
        if($key == 'billing_first_name' && $field == ''){
          add_the_error($errors, $key, 'First Name');
        }
        if($key == 'billing_last_name' && $field == ''){
          add_the_error($errors, $key, 'Last Name');
        }
        if($key == 'billing_address_1' && $field == ''){
          add_the_error($errors, $key, 'Address');
        }
        if($key == 'billing_city' && $field == ''){
          add_the_error($errors, $key, 'City');
        }
        if($key == 'billing_state' && $field == ''){
          add_the_error($errors, $key, 'State');
        }
        if($key == 'billing_postcode' && $field == ''){
          add_the_error($errors, $key, 'Post Code');
        }
        if($key == 'billing_phone' && $field == ''){
          add_the_error($errors, $key, 'Phone Number');
        }
      }
    endforeach;
    return $errors;
  }
  add_filter( 'woocommerce_registration_errors', 'ex_validation_billing_address', 10 );
  function add_the_error( $errors, $key, $field_name ) {
    $message = sprintf( __( '%s is a required field.', 'iconic' ), '<strong>' . $field_name . '</strong>' );
    $errors->add( $key, $message );
  }

  // Make Billing Company required field
  add_filter( 'woocommerce_billing_fields', 'ts_unrequire_wc_phone_field');
  function ts_unrequire_wc_phone_field( $fields ) {
    $fields['billing_company']['required'] = true;
  return $fields;
  }

  // Add password Validation
  function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email) {
  	global $woocommerce;
  	extract( $_POST );
  	if ( strcmp( $password, $password2 ) !== 0 ) {
  		return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'woocommerce' ) );
  	}
  	return $reg_errors;
  }
  add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10,3);
  function wc_register_form_password_repeat() {
  	?>
  	<p class="form-row form-row-wide">
  		<label for="reg_password2"><?php _e( 'Password Repeat', 'woocommerce' ); ?> <span class="required">*</span></label>
  		<input type="password" class="input-text" name="password2" id="reg_password2" value="<?php if ( ! empty( $_POST['password2'] ) ) echo esc_attr( $_POST['password2'] ); ?>" />
  	</p>
  	<?php
  }
  add_action( 'woocommerce_register_form', 'wc_register_form_password_repeat' );

  function lit_woocommerce_confirm_password_validation( $posted ) {
      $checkout = WC()->checkout;
      if ( ! is_user_logged_in() && ( $checkout->must_create_account || ! empty( $posted['createaccount'] ) ) ) {
          if ( strcmp( $posted['account_password'], $posted['account_confirm_password'] ) !== 0 ) {
              wc_add_notice( __( 'Passwords do not match.', 'woocommerce' ), 'error' );
          }
      }
  }
  add_action( 'woocommerce_after_checkout_validation', 'lit_woocommerce_confirm_password_validation', 10, 2 );

  function lit_woocommerce_confirm_password_checkout( $checkout ) {
      if ( get_option( 'woocommerce_registration_generate_password' ) == 'no' ) {
          $fields = $checkout->get_checkout_fields();
          $fields['account']['account_confirm_password'] = array(
              'type'              => 'password',
              'label'             => __( 'Confirm password', 'woocommerce' ),
              'required'          => true,
              'placeholder'       => _x( 'Confirm Password', 'placeholder', 'woocommerce' )
          );
          $checkout->__set( 'checkout_fields', $fields );
      }
  }
  add_action( 'woocommerce_checkout_init', 'lit_woocommerce_confirm_password_checkout', 10, 1 );

  // Force Populate Billing Fields
  function ex_wcBillingFillOut($input, $key) {
    global $current_user;
    switch ($key) :
      case 'billing_first_name': case 'shipping_first_name': return $current_user->first_name; break;
      case 'billing_last_name': case 'shipping_last_name': return $current_user->last_name; break;
      case 'billing_email': return $current_user->user_email; break;
      case 'billing_company': return $current_user->billing_company; break;
      case 'billing_address_1': return $current_user->billing_address_1; break;
      case 'billing_address_2': return $current_user->billing_address_2; break;
      case 'billing_phone': return $current_user->billing_phone; break;
      case 'billing_city': return $current_user->billing_city; break;
      case 'billing_postcode': return $current_user->billing_postcode; break;
    endswitch;
  }
  add_filter('woocommerce_checkout_get_value', 'ex_wcBillingFillOut', 10, 2);

  // Make Display Name Not Required
  function wc_save_account_details_required_fields( $required_fields ){
    unset( $required_fields['account_display_name'] );
    return $required_fields;
  }
  add_filter('woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields' );
