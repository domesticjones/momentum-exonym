<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<section class="account-data">
  <?php
    /* translators: 1: user display name 2: logout url */
    printf(
      __( '<p class="account-hello">Hello %1$s <small>(not %2$s? <a href="%3$s">Logout</a>)</small></p>', 'woocommerce' ),
      '<strong>' . esc_html( $current_user->first_name . ' ' . $current_user->last_name ) . '</strong>',
      '<strong>' . esc_html( $current_user->first_name) . '</strong>',
      esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
    );
  ?>
  <div class="account-data-left">
		<?php
			if(current_user_can('manage_woocommerce')) {
				$adminBookingPageFind = [
			    'post_type' => 'page',
			    'fields' => 'ids',
			    'nopaging' => true,
			    'meta_key' => '_wp_page_template',
			    'meta_value' => 'admin-bookings.php'
				];
				$adminBookingPage = get_posts($adminBookingPageFind);
		    $timeZone = get_option('timezone_string');
		    $todayRaw = new DateTime('now', new DateTimezone($timeZone));
		    $today = $todayRaw->format('Y-m-d');
				echo '<a href="' . get_permalink($adminBookingPage[0]) . '?dateFilter=' . $todayRaw->format('Y-m-d') . '" class="account-schedule-cta"><span>Today\'s Appointments</span></a>';
			} else {
				echo '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="account-schedule-cta"><span>Schedule Inspection</span></a>';
			}
		?>
  </div>
  <div class="account-data-right">
		<?php
			if(current_user_can('manage_woocommerce')) {
				echo '<a href="' . get_permalink($adminBookingPage[0]) . '?statusFilter=pending-confirmation" class="account-schedule-cta"><span>Pending Appointments</span></a>';
			} else {
				echo '<a href="' . wc_get_account_endpoint_url('orders') . '" class="account-schedule-cta"><span>View My Inspections</span></a>';
			}
		?>
  </div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	//do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

  echo '</section>';

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
