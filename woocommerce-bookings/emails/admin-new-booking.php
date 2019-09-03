<?php
/**
 * Admin new booking email.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/emails/admin-new-booking.php
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

if ( wc_booking_order_requires_confirmation( $booking->get_order() ) && $booking->get_status() === 'pending-confirmation' ) {
	/* translators: 1: billing first and last name */
	$opening_paragraph = __( 'An appointment has been made by %s and is awaiting your approval. The details of this appointment are as follows:', 'woocommerce-bookings' );
} else {
	/* translators: 1: billing first and last name */
	$opening_paragraph = __( 'A new appointment has been made by %s. The details of this appointment are as follows:', 'woocommerce-bookings' );
}
?>

<?php
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

<?php if ( ! empty( $first_name ) && ! empty( $last_name ) ) : ?>
	<p><?php echo esc_html( sprintf( $opening_paragraph, $first_name . ' ' . $last_name . ' of ' . $company ) ); ?></p>
<?php endif; ?>

<?php
?>

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

<?php if ( wc_booking_order_requires_confirmation( $booking->get_order() ) && $booking->get_status() === 'pending-confirmation' ) : ?>
<p><?php esc_html_e( 'This appointment is pending your confirmation.', 'woocommerce-bookings' ); ?></p>
<?php endif; ?>

<p style="font-size: 1.25em; font-weight: bold; text-align: center;">
<?php
/* translators: 1: a href to booking */
$adminBookingPageFind = [
	'post_type' => 'page',
	'fields' => 'ids',
	'nopaging' => true,
	'meta_key' => '_wp_page_template',
	'meta_value' => 'admin-bookings.php'
];
$adminBookingPage = get_posts($adminBookingPageFind);
echo wp_kses_post( make_clickable( sprintf( __( 'Confirm appointment: %s', 'woocommerce-bookings' ), get_permalink($adminBookingPage[0]) . '?idFilter=' . $booking->get_id() ) ) );
?>
</p>

<?php do_action( 'woocommerce_email_footer' ); ?>
