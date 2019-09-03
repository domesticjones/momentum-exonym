<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<nav class="woocommerce-MyAccount-navigation">
	<ul>
		<?php /* foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; */ ?>

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
			?>
				<li class="account-nav-dashboard<?php if(is_account_page() && !is_wc_endpoint_url('edit-account')) { echo ' is-active'; } ?>"><a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>"><span>Dashboard</span><i>Dashboard</i></a></li>
				<li class="account-nav-schedule<?php if(get_the_id() == $adminBookingPage[0]) { echo ' is-active'; } ?>"><a href="<?php echo get_permalink($adminBookingPage[0]) . '?statusFilter=pending-confirmation'; ?>"><span>View Appointments</span><i>Appointments</i></a></li>
				<li class="account-nav-profile<?php if(is_wc_endpoint_url('edit-account')) { echo ' is-active'; } ?>"><a href="<?php echo wc_get_account_endpoint_url('edit-account'); ?>"><span>Account Details</span><i>Profile</i></a></li>
				<?php if(current_user_can('administrator')) { echo '<li class="account-nav-logout"><a href="' . get_admin_url() . '"><span>WP Admin</span><i>WP Admin</i></a></li>'; } ?>
				<li class="account-nav-logout"><a href="<?php echo wc_get_account_endpoint_url('logout'); ?>"><span>Logout</span><i>Logout</i></a></li>
				<?php
			} else {
				?>
				<li class="account-nav-dashboard<?php if(is_account_page() && !is_wc_endpoint_url('orders') && !is_wc_endpoint_url('edit-account')) { echo ' is-active'; } ?>"><a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>"><span>Dashboard</span><i>Account</i></a></li>
				<li class="account-nav-schedule<?php if(is_shop() || is_cart() || is_checkout() || is_product()) { echo ' is-active'; } ?>"><a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span>Schedule Inspection</span><i>Schedule</i></a></li>
				<li class="account-nav-orders<?php if(is_wc_endpoint_url('orders')) { echo ' is-active'; } ?>"><a href="<?php echo wc_get_account_endpoint_url('orders'); ?>"><span>My Inspections</span><i>Inspections</i></a></li>
				<li class="account-nav-profile<?php if(is_wc_endpoint_url('edit-account')) { echo ' is-active'; } ?>"><a href="<?php echo wc_get_account_endpoint_url('edit-account'); ?>"><span>Account Details</span><i>Profile</i></a></li>
				<li class="account-nav-logout"><a href="<?php echo wc_get_account_endpoint_url('logout'); ?>"><span>Logout</span><i>Logout</i></a></li>
			<?php
			}
		?>
	</ul>
</nav>

<?php

do_action( 'woocommerce_before_account_navigation' ); do_action( 'woocommerce_after_account_navigation' ); ?>
