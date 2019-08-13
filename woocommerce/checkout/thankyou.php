<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wc_get_template_part('header', 'schedule');
echo ex_wrap('start', 'fullwidth') . '<div class="module-inner">';
?>


	<?php if ( $order ) : ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php //do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php //do_action( 'woocommerce_thankyou', $order->get_id() );


		if(is_callable('WC_Booking_Data_Store::get_booking_ids_from_order_id')) {
			$booking_data = new WC_Booking_Data_Store();
			$booking_ids = $booking_data->get_booking_ids_from_order_id( $order->get_id() );
		}
		$booking = new WC_Booking($booking_ids[0]);
		$title = '<h3>' . $booking->get_product()->get_title() . '</h3>';
		$date = '<p><strong>Inspection Request Date: </strong>' . $booking->get_start_date(null, null, wc_should_convert_timezone($booking)) . '</p>';
		echo '<section class="order-received-left">';
			echo $title . $date;
			echo ex_wcParseNotes($order->get_customer_note());
		echo '</section>';
		echo '<section class="order-received-right">';
			echo ex_wcParseNotes($order->get_customer_note());
		echo '</section>';
		echo ex_cta('schedule', 'Schedule Another');
    echo '<a href="' . get_permalink(wc_get_page_id('myaccount')) . '" class="order-received-accountlink inspection-cancel">Go to My Account</a>';

		?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

	<?php endif; ?>

<?php echo '</div>' . ex_wrap('end'); do_action( 'woocommerce_account_navigation' );
