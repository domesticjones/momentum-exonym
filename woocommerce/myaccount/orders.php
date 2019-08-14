<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
echo '<section class="account-data">';
echo '<h1>My Inspections</h1>';
do_action( 'woocommerce_before_account_orders', $has_orders );
if($has_orders):
  echo '<ul class="account-orders">';
  foreach($customer_orders->orders as $customer_order):
    $order = wc_get_order($customer_order);
    $item_count = $order->get_item_count();
    $order_id = $order->get_order_number();


    if(is_callable('WC_Booking_Data_Store::get_booking_ids_from_order_id')) {
  		$booking_data = new WC_Booking_Data_Store();
  		$booking_ids = $booking_data->get_booking_ids_from_order_id( $order->get_id() );
  	}
    $booking = new WC_Booking($booking_ids[0]);
    //var_dump($booking);
    printf(
      __( '<li class="account-order"><span class="order-id">Inspection ID: %1$s</span><span class="order-date">Inspection Date: %2$s</span><i>%3$s</i></li>', 'woocommerce' ),
      '<mark class="order-booking">#' . $booking->get_id() . '</mark>',
      '<mark class="order-booking-date">' .  $booking->get_start_date( null, null, wc_should_convert_timezone( $booking ) ) . '</mark>',
			'<mark class="order-booking-status">' . wc_bookings_get_status_label($booking->get_status()) . '</mark>'
    );
    echo '<li class="account-order-details">';
	    echo '<mark class="order-type"><p>' . $booking->get_product()->name . '</p></mark>';
	    echo '<mark class="order-notes">' . ex_wcParseNotes($order->get_customer_note()) . '</mark>';
	    echo '<mark class="order-created-date"> This inspection was requested in our system on ' . wc_format_datetime( $order->get_date_created() ) . '</mark>';

	    if ( $notes = $order->get_customer_order_notes() ) : ?>
	    	<h2><?php _e( 'Order updates', 'woocommerce' ); ?></h2>
	    	<ol class="woocommerce-OrderUpdates commentlist notes">
	    		<?php foreach ( $notes as $note ) : ?>
	    		<li class="woocommerce-OrderUpdate comment note">
	    			<div class="woocommerce-OrderUpdate-inner comment_container">
	    				<div class="woocommerce-OrderUpdate-text comment-text">
	    					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( __( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); ?></p>
	    					<div class="woocommerce-OrderUpdate-description description">
	    						<?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
	    					</div>
	    	  				<div class="clear"></div>
	    	  			</div>
	    				<div class="clear"></div>
	    			</div>
	    		</li>
	    		<?php endforeach; ?>
	    	</ol>
	    <?php endif; ?>

			<?php  ?>



	    <?
    echo '</li>';
  endforeach;
  echo '</ul>';
    ?>
	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php _e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="cta-button cta-icon-calendar" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php _e( 'Schedule Inspection', 'woocommerce' ); ?>
		</a>
		<?php _e( 'No Inspection has been requested yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
</section>
