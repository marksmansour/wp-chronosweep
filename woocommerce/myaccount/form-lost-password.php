<?php

/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>
<div class="c">
	<div class="section">
		<div class="tabSection myAccountForm lostPasswordForm sidebarWithAccordionWrap">
			<div class="tabSectionWrap whiteBg">
				<div class="tabSectionBody">
					<div class="cartTab">
						<div class="cartTabWrap formDiv">
							<div class="formRow">
								<div class="formCenter">
									<div class="msgWrap"></div>
									<form method="post" class="woocommerce-ResetPassword lost_reset_password">
										<div class="formHeader">
										<h1 class="size36 primaryBlue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>Forgot Password?</h1>
										<p class="size16 lightBlack <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>Don’t worry! It happens. Please enter your email and if it’s on our system you’ll get a password reset link.</p>
										</div>
										<p class="form-row form-row-wide customTitleStyle formLabel">Email</p>
										<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first  customInputStyle formInput">
											<label for="user_login"><?php esc_html_e('Your email', 'woocommerce'); ?></label>
											<span class="woocommerce-input-wrapper">
												<input required class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="new-username" />
											</span>
										</p>
										<p class="form-row form-row-wide customTitleStyle formLabel errMsglabel red">Error - there is no account with that email address</p>
										<div class="clear"></div>
										<?php do_action('woocommerce_lostpassword_form'); ?>
										<p class="woocommerce-form-row form-row <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
											<input type="hidden" name="wc_reset_password" value="true" />
											<button type="submit" class="woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" value="<?php esc_attr_e('Reset password', 'woocommerce'); ?>"><?php esc_html_e('Reset password', 'woocommerce'); ?></button>
										</p>
										<?php echo apply_shortcodes('[miniorange_social_login theme="default"]') ?>
										<?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
do_action('woocommerce_after_lost_password_form');
