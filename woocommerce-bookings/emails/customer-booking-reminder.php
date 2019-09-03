<?php
/**
 * Customer booking reminder email.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/emails/customer-booking-reminder.php
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/bookings-templates/
 * @author  Automattic
 * @version 1.10.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
do_action( 'woocommerce_email_header', $email_heading );

	$order = $booking->get_order();
	$notes = $order->customer_note;
	$services = ex_wcParseNotes($notes, 'services');

if ( $order ) {
	if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
		$first_name = $order->billing_first_name;
		$last_name  = $order->billing_last_name;
	} else {
		$first_name 		= $order->get_billing_first_name();
		$last_name  		= $order->get_billing_last_name();
		$company				= $order->get_billing_company();
		$accounts				= get_user_meta($order->user->ID, 'accounts_person')[0];
		$accountsEmail	= get_user_meta($order->user->ID, 'accounts_email')[0];
	}
}
?>

<?php if ( $booking->get_order() ) : ?>
	<p>
	<?php
	/* translators: 1: billing first name */
	echo esc_html( sprintf( __( 'Hello %s,', 'woocommerce-bookings' ), ( is_callable( array( $booking->get_order(), 'get_billing_first_name' ) ) ? $booking->get_order()->get_billing_first_name() : $booking->get_order()->billing_first_name ) ) );
	?>
	</p>
<?php endif; ?>

<p>
<?php
/* translators: 1: booking start date */
echo esc_html( sprintf( __( 'This is a reminder that your inspection appointment will take place on %1$s.', 'woocommerce-bookings' ), $booking->get_start_date() ) );
?>
</p>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<tbody>
		<tr>
			<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Billing Info', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo $company . '<br />' . $accounts . '<br/><a href="mailto:' . $accountsEmail . '">' . $accountsEmail . '</a>'; ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Inspection Request', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $booking->get_product()->get_title() ); echo $services ? $services : ''; ?></td>
		</tr>
		<tr>
			<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Appointment ID', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $booking->get_id() ); ?></td>
		</tr>
		<?php
		$resource = $booking->get_resource();

		if ( $booking->has_resources() && $resource ) :
			?>
			<tr>
				<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Booking Type', 'woocommerce-bookings' ); ?></th>
				<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $resource->post_title ); ?></td>
			</tr>
		<?php endif; ?>
		<tr>
			<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Appointment Date', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $booking->get_start_date( null, null, wc_should_convert_timezone( $booking ) ) ); ?></td>
		</tr>
		<tr>
			<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Appointment Location', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo ex_wcParseNotes($notes, 'area') . ex_wcParseNotes($notes, 'address'); ?></td>
		</tr>
		<tr>
			<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Appointment Details', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo ex_wcParseNotes($notes, 'sup') . ex_wcParseNotes($notes, 'sqft') . ex_wcParseNotes($notes, 'manualj'); ?></td>
		</tr>
		<?php /*
		<tr>
			<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Booking End Date', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $booking->get_end_date( null, null, wc_should_convert_timezone( $booking ) ) ); ?></td>
		</tr>
		*/ ?>
		<?php if ( wc_should_convert_timezone( $booking ) ) : ?>
		<tr>
			<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php esc_html_e( 'Time Zone', 'woocommerce-bookings' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( str_replace( '_', ' ', $booking->get_local_timezone() ) ); ?></td>
		</tr>
		<?php endif; ?>
		<?php if ( $booking->has_persons() ) : ?>
			<?php
				foreach ( $booking->get_persons() as $id => $qty ) :
					if ( 0 === $qty ) {
						continue;
					}
				$person_type = ( 0 < $id ) ? get_the_title( $id ) : __( 'Person(s)', 'woocommerce-bookings' );
			?>
			<tr>
				<th style="text-align:left; border: 1px solid #eee;" scope="row"><?php echo esc_html( $person_type ); ?></th>
				<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $qty ); ?></td>
			</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>

<p><?php echo wc_get_privacy_policy_text('checkout'); ?></p>
<p style="font-size: 1.25em; font-weight: bold; text-align: center;">If anything about this inspection needs to change, please <a href="<?php echo home_url('/contact'); ?>">Contact Us</a><small style="display: block; font-size: 0.75em"></small></p>

<?php do_action( 'woocommerce_email_footer' ); ?>
