<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

$post_id = $post->ID;
if ( isset( $_POST['html-upload'] ) && ! empty( $_FILES ) ) {
	require_once( ABSPATH . 'wp-admin/includes/admin.php' );
	$manualUpload = media_handle_upload( 'async-upload', 0 );
	unset( $_FILES );
	if( is_wp_error( $manualUpload ) ) {
		$uploadError['upload_error'] = $manualUpload;
		$manualUpload = false;
	}
}
	$shopId = wc_get_page_id('shop');
	$previous = get_field('step_three', $shopId)['previous_button'];

	echo ex_wrap('start', 'schedule-details');
	wc_get_template_part('header', 'schedule');
	echo '<aside class="inspection-details-sidebar">';
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

			// Name & Services
			echo '<div id="product-service">' . wp_kses_post( apply_filters( 'woocommerce_cart_item_name', '<h2>' . $_product->get_name() . '</h2>', $cart_item, $cart_item_key ) . '&nbsp;' ) . '</div>';

			// Booking Date
			echo wc_get_formatted_cart_item_data($cart_item);
			?>

			<form id="file-form" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
				<?php if($uploadError) { '<p>There was an error uploading your file. Please check the file format.</p>'; } ?>
				<?php if($manualUpload): echo '<p>Your Manual J was uploaded successfully!<br /><strong>' . basename(get_attached_file($manualUpload)) . '</strong></p>'; else: ?>
	        <div id="async-upload-wrap">
						<label for="async-upload">Upload your Manual J (Optional, PDF format only)</label>
						<div class="manualj-upload-fields">
							<input type="file" id="async-upload" accept=".pdf" name="async-upload">
							<input type="submit" value="Upload" name="html-upload">
						</div>
					</div>
	        <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id ?>" />
	        <?php wp_nonce_field( 'client-file-upload' ); ?>
	        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
	        <input type="submit" value="Save all changes" name="save" style="display: none;">
				<?php endif; ?>
	    </form>
			<p style="font-size: .75em;"><?php echo wc_get_privacy_policy_text('checkout'); ?></p>


			<?php

			// Remove
			echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
				'<a href="' . $product_permalink . '" aria-label="%s" data-product_id="%s" data-product_sku="%s" class="inspection-cancel remove">' . $previous . '</a>',
				esc_url(wc_get_cart_remove_url($cart_item_key)),
				__('Cancel this Inspection', 'woocommerce'),
				esc_attr($product_id),
				esc_attr($_product->get_sku())
			), $cart_item_key);
		}
	echo '</aside>';
?>
	<section class="schedule-details-info">
		<form id="schedule-details">
			<div class="form-row"><input type="text" id="details-supervisor-name" placeholder="Builder/HVAC Name"></div>
			<div class="form-row"><input type="text" id="details-supervisor-phone" placeholder="Best Contact Phone"></div>
			<div class="form-row"><input type="number" id="details-sqft" placeholder="Total Square Feet"></div>
			<div class="form-row"><input type="text" id="details-subdivision" placeholder="Subdivision"></div>
			<div class="form-row"><input type="text" id="details-lot" placeholder="Lot/Block #"></div>
			<div class="form-row"><input type="text" id="details-address" placeholder="Address"></div>
			<div class="form-row">
				<input type="text" id="details-city" placeholder="City">
				<input type="text" id="details-state" placeholder="State" maxlength="2">
				<input type="text" id="details-zip" placeholder="ZIP" maxlength="10">
			</div>
			<div class="form-row"><input type="hidden" id="details-manualj" placeholder="Manual J Upload" value="<?php echo $manualUpload ? $manualUpload : ''; ?>"></div>
		</form>
		<?php do_action('woocommerce_cart_collaterals'); ?>
	</section>
<?php
echo ex_wrap('end');
	//do_action( 'woocommerce_after_cart' );
	do_action( 'woocommerce_account_navigation' );
?>
