<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

?>
<div class="c">
	<div class="tabSection">
		<div class="tabSectionWrap whiteBg">
			<a class="backBtn" href="<?= wc_get_cart_url() ?>"><span class="dropDown left"></span><span class="linkLabel aTagHover">Back a step</span></a>
			<div class="tabSectionHeader">
				<div class="tabSectionHeaderList">
					<div class="tabSectionHeaderListItem active">
						<p class="size16 primaryBlue tabSectionHeaderTitle">Quantity</p>
						<div class="bottomBorder primaryBlueBg"></div>
					</div>
					<div class="tabSectionHeaderListItem active">
						<p class="size16 primaryBlue tabSectionHeaderTitle">Checkout</p>
						<div class="bottomBorder primaryBlueBg"></div>
					</div>
				</div>
			</div>
			<div class="tabSectionBody">
				<div class="quantityTab">
					<div class="quantityTabWrap">
					</div>
				</div>
				<div class="cartTab">
					<div class="cartTabWrap msgWrap checkOutTap">
						<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
							<div class="checkOutForm">
								<?php if ($checkout->get_checkout_fields()) : ?>

									<?php do_action('woocommerce_checkout_before_customer_details'); ?>
									<div class="colRow colGap24 avoid-responsive">
										<div class="col1-set" id="customer_details">
											<div class="col-1">
												<?php do_action('woocommerce_checkout_billing'); ?>
											</div>

											<div class="col-2">
												<?php do_action('woocommerce_checkout_shipping'); ?>
											</div>
											<?php
											if (!is_user_logged_in()) {
											?>
												<p class="size12 greyText">By checking this box and entering your phone number above, you consent to receive marketing text messages from Chrono Sweep Vintage. You can unsubscribe at any time by replying STOP or clicking the unsubscribe link in one of our messages.</p>
											<?php
											}
											?>
										</div>
										<?php do_action('woocommerce_checkout_after_customer_details'); ?>

									<?php endif; ?>
									<div class="col1-set" id="cart_details">
										<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
										<h3 id="order_review_heading" class="primaryBlue size36 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'><?php esc_html_e('Your order', 'woocommerce'); ?></h3>
										<?php do_action('woocommerce_checkout_before_order_review'); ?>

										<div id="order_review" class="woocommerce-checkout-review-order">
											<?php do_action('woocommerce_checkout_order_review'); ?>
										</div>

										<?php do_action('woocommerce_checkout_after_order_review'); ?>
									</div>
									</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php do_action('woocommerce_after_checkout_form', $checkout); ?>