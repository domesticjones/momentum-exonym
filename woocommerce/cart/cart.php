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
 * @version 3.7.0
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

//do_action( 'woocommerce_before_cart' );

/*
?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-thumbnail">&nbsp;</th>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
								// @codingStandardsIgnoreLine
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key );
							?>
						</td>

						<td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
						<?php
						if ( ! $product_permalink ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						}

						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}
						?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input( array(
								'input_name'   => "cart[{$cart_item_key}][qty]",
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $_product->get_max_purchase_quantity(),
								'min_value'    => '0',
								'product_name' => $_product->get_name(),
							), $_product, false );
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
	?>
</div>

*/

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
			echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', '<h2>' . $_product->get_name() . '</h2>', $cart_item, $cart_item_key ) . '&nbsp;' );

			// Booking Date
			echo wc_get_formatted_cart_item_data($cart_item);
			?>

			<form id="file-form" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
				<?php if($uploadError) { '<p>There was an error uploading your file. Please check the file format.</p>'; } ?>
				<?php if($manualUpload): echo '<p>Your Manual J was uploaded successfully!<br /><strong>' . basename(get_attached_file($manualUpload)) . '</strong></p>'; else: ?>
	        <div id="async-upload-wrap">
						<label for="async-upload">Upload your Manual J (Optional, PDf format only)</label>
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
			<div class="form-row"><input type="text" id="details-supervisor-name" placeholder="Supervisor Name"></div>
			<div class="form-row"><input type="text" id="details-supervisor-phone" placeholder="Supervisor Phone"></div>
			<div class="form-row"><input type="number" id="details-sqft" placeholder="Total Square Feet"></div>
			<div class="form-row"><input type="text" id="details-address" placeholder="Address"></div>
			<div class="form-row"><input type="text" id="details-lot" placeholder="Lot/Block #"></div>
			<div class="form-row"><input type="text" id="details-subdivision" placeholder="Subdivision"></div>
			<div class="form-row"><input type="hidden" id="details-manualj" placeholder="Manual J Upload" value="<?php echo $manualUpload ? $manualUpload : ''; ?>"></div>
			<div class="form-row">
				<input type="text" id="details-city" placeholder="City">
				<input type="text" id="details-state" placeholder="State" maxlength="2">
				<input type="text" id="details-zip" placeholder="ZIP" maxlength="10">
			</div>
		</form>
		<?php do_action('woocommerce_cart_collaterals'); ?>
	</section>
<?php
echo ex_wrap('end');
	//do_action( 'woocommerce_after_cart' );
	do_action( 'woocommerce_account_navigation' );
?>
