<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table shopTable">
	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = $_product->get_id();
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.4s'>
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
					<td class="productTextContent">
						<div class="product-name">
						<p class="size22"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?></p>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<p class="size14 primaryBlue60 productDesc"><?= get_field('product_extra_details_description',$product_id); ?></p>
						<div class="tableRow">
							<div class="tableColumn">
								<p class="size14 primaryBlue60">Quantity:</p>
								<div data-id="<?= $product_id; ?>" data-limit="<?= getUserLimit($product_id); ?>" data-json='<?php echo htmlspecialchars(getAllTicketsQuantityJson($product_id), ENT_QUOTES, 'UTF-8'); ?>' class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
									<?php
										echo $cart_item['quantity'];
									?>
								</div>
							</div>
							<div class="tableColumn">
								<p class="size14 primaryBlue60">Total:</p>
								<div class="product-subtotal primaryBlue" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</div>
							</div>
						</div>
					</td>
					<!-- <td class="product-total">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td> -->
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>
		<tr class="couponTr <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.6s'>
			<td>
				<form id="custom-coupon-form">
					<div class="coupon">
						<label for="coupon_code" class="screen-reader-text">Coupon:</label>
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Coupon code">
						<div class="btnWrap">
							<button type="button" class="button applyCouponBtn" name="apply_coupon" value="Apply">Apply</button>
							<span class="wpcf7-spinner"></span>
						</div>
					</div>
				</form>
			</td>
		</tr>
		<tr class="cart-subtotal <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.8s'>
			<th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>  <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.8s'>
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.8s'>
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>  <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.8s'>
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.8s'>
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.8s'>
			<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
