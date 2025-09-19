<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

$formType = $_GET['form'];
$myAccountPageId = get_woocommerce_my_account_page_id();
function get_woocommerce_my_account_page_id() {
    $myAccountPage = get_option( 'woocommerce_myaccount_page_id' );
    return $myAccountPage;
}
$termsPageId = wc_get_page_id( 'terms' );
$termsLink = get_permalink( $termsPageId );
$termsPageId = wc_get_page_id( 'terms' );
$termsLink = get_permalink( $termsPageId );
$privacyPageId = wc_privacy_policy_page_id();
$privacyLink = get_permalink( $privacyPageId );
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>

<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration') && $formType == "") : ?>

<div class="c">
	<div class="section">
		<div class="tabSection myAccountForm loginForm sidebarWithAccordionWrap">
			<div class="tabSectionWrap whiteBg">
				<div class="tabSectionBody">
					<div class="cartTab">
						<div class="cartTabWrap formDiv">
							<div class="formRow">
								<div class="formLeft cardTextContentEqualHeight">
									<div class="formLeftWrap">
										<div class="sizer"></div>
										<?= image_on_fly(get_field('login_form_left_image',$myAccountPageId), array('1140', 'auto')); ?>
									</div>
								</div>
								<div class="formRight cardTextContentEqualHeight">
									<div class="msgWrap"></div>
									<div class="formHeader">
										<h1 class="size36 primaryBlue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>Welcome back!</h1>
										<p class="size16 lightBlack <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>Dont have an account? <a class="underLineHover" href="<?= get_site_url(); ?>/my-account/?form=register">Create an account</a></p>
									</div>
									<form class="woocommerce-form woocommerce-form-login login" method="post">
										<?php do_action('woocommerce_login_form_start'); ?>
										<p class="form-row form-row-wide customTitleStyle formLabel">Email</p>
										<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?php echo ($_POST['username']) ? 'hasInput' : ''; ?>">
											<label for="username"><?php esc_html_e('Your email', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
											<span class="woocommerce-input-wrapper">
												<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="off" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
											</span>
										</p>
										<p class="form-row form-row-wide customTitleStyle formLabel">Password</p>
										<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput">
											<label for="password"><?php esc_html_e('Enter your password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
											<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="new-password" />
										</p>
										<?php do_action('woocommerce_login_form'); ?>
										<p class="formInputRow">
											<label class="widthAuto woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme formInput">
												<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
											</label>
											<label class="widthAuto btnWrap">
												<a class="aTagHover formInput" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
											</label>
										</p>
										<p class="woocommerce-LostPassword lost_password">
										<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
											<button type="submit" data-wow-delay='0.2s' class="<?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?> btn btnPrimaryBlue woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
										</p>
										<?php echo apply_shortcodes('[miniorange_social_login theme="default"]') ?>
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

<?php endif; ?>

<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration') && $formType == "register") : ?>

<div class="c">
	<div class="section">
		<div class="tabSection myAccountForm loginForm sidebarWithAccordionWrap">
			<div class="tabSectionWrap whiteBg">
				<div class="tabSectionBody">
					<div class="cartTab">
						<div class="cartTabWrap formDiv">
							<div class="formRow">
								<div class="formLeft cardTextContentEqualHeight">
									<div class="formLeftWrap">
										<div class="sizer"></div>
										<?= image_on_fly(get_field('login_form_left_image',$myAccountPageId), array('480', 'auto')); ?>
									</div>
								</div>
								<div class="formRight cardTextContentEqualHeight">
									<div class="msgWrap"></div>
									<div class="formHeader">
										<h1 class="size36 primaryBlue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>Register for an account</h1>
										<p class="size16 lightBlack <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>Already have an account? <a class="underLineHover" href="<?= get_site_url(); ?>/my-account/">Login here</a></p>
									</div>
									<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
										<?php do_action('woocommerce_register_form_start'); ?>
										<p class="form-row form-row-wide customTitleStyle formLabel">Name</p>
										<div class="form-row formInputRow">
											<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?php echo ($_POST['first_name']) ? 'hasInput' : ''; ?>">
												<label for="reg_email"><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
												<span class="woocommerce-input-wrapper">
													<input required type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="first_name" autocomplete="off" value="<?php echo (!empty($_POST['first_name'])) ? esc_attr(wp_unslash($_POST['first_name'])) : ''; ?>" />
												</span>
											</p>
											<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?php echo ($_POST['last_name']) ? 'hasInput' : ''; ?>">
												<label for="reg_email"><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
												<span class="woocommerce-input-wrapper">
													<input required type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="last_name" autocomplete="off" value="<?php echo (!empty($_POST['last_name'])) ? esc_attr(wp_unslash($_POST['last_name'])) : ''; ?>" />
												</span>
											</p>
										</div>
										<?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
											<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput">
												<label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
												<span class="woocommerce-input-wrapper">
													<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
												</span>
											</p>
										<?php endif; ?>
										<p class="form-row form-row-wide customTitleStyle formLabel">Email</p>
										<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?php echo ($_POST['email']) ? 'hasInput' : ''; ?>">
											<label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
											<span class="woocommerce-input-wrapper">
												<input required type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="new-email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
											</span>
										</p>
										<p class="form-row form-row-wide customTitleStyle formLabel">Password</p>
										<?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
											<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput">
												<label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
												<input required type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
											</p>
										<?php else : ?>
											<p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>
										<?php endif; ?>
										<p class="formInputRow">
											<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme formInput formTextRow">
												<input required class="woocommerce-form__input woocommerce-form__input-checkbox" name="termsLink" type="checkbox" id="rememberme" value="forever"> <span>By clicking the checkbox you confirm that you are 18+ and agree to our <a class="underLineHover" href="<?= $termsLink; ?>">T&Cs</a> and <a class="underLineHover" href="<?= $privacyLink; ?>">Privacy Policy</a>.</span>
											</label>
										</p>
										<p class="formInputRow">
											<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme formInput formTextRow">
												<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="receive_communications" type="checkbox" id="receive_communications" value="1"> <span>I agree to receive communications about Chrono Sweep that might be of interest to me.</a></span>
											</label>
										</p>
										<?php do_action('woocommerce_register_form'); ?>
										<p class="woocommerce-form-row form-row <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
											<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
											<button type="submit" class="btn btnPrimaryBlue woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
										</p>
										<?php echo apply_shortcodes('[miniorange_social_login theme="default"]') ?>
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

<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>