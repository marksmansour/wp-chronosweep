<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;
?>
<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>
<div class="c">
	<div class="section">
		<div class="tabSection myAccountForm lostPasswordForm sidebarWithAccordionWrap successMsgYes">
			<div class="tabSectionWrap whiteBg">
				<div class="tabSectionBody">
					<div class="cartTab">
						<div class="cartTabWrap formDiv">
							<div class="formRow">
								<div class="formCenter">
									<div class="msgWrap"></div>
									<div class="formHeader">
										<h1 class="size36 primaryBlue">Success</h1>
										<p class="size16 lightBlack">Check your email for the confirmation link</p>
									</div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
