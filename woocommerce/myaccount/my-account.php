<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
$logoutUrl = wp_logout_url();
$myAccountPageId = get_option( 'woocommerce_myaccount_page_id' );
$current_user = wp_get_current_user();

// Retrieve billing address details
$billing_address = get_user_meta($current_user->ID, 'billing_address_1', true);
$billing_apartment = get_user_meta($current_user->ID, 'billing_address_2', true);
$billing_city = get_user_meta($current_user->ID, 'billing_city', true);
$billing_country = get_user_meta($current_user->ID, 'billing_country', true);
$billing_postcode = get_user_meta($current_user->ID, 'billing_postcode', true);
$referralId = get_user_meta($current_user->ID, 'referral_coupon', true);
$firstName = $current_user->first_name;
$currencySymbol = (get_woocommerce_currency_symbol()) ? get_woocommerce_currency_symbol() : "£";
global $wpdb;
$tableName = $wpdb->prefix . "refer_details";
$winnerTableName = $wpdb->prefix . "winner_list";
$referCount = $wpdb->get_var("SELECT COUNT(id) FROM $tableName WHERE referBy='$current_user->ID'");
$donationValue = $wpdb->get_var("SELECT SUM(donationValue) FROM $winnerTableName WHERE winnerId='$current_user->ID'");
$earn = getWalletAmount($current_user->ID);
?>

<div class="c">
	<div class="section">
		<div class="myAccountDashBoard">
			<div class="myAccountDashBoardWrap">
				<div class="myAccountDashBoardHeader whiteBg">
					<div class="myAccountDashBoardHeaderWrap">
						<div class="titleContent colRow colAlignCenter">
							<p class="size36 primaryBlue title col50 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>Nice to see you again <?= ucfirst($firstName); ?></p>
							<p class="size24 primaryBlue title col50 textRight <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>Discount: <span><?= wc_price($earn); ?></span></p>
						</div>
						<div class="textContent colRow">
							<p class="<?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.4s'>Not you? Click <a class="underLineHover" href="<?= wp_logout_url(home_url( '/my-account/' )); ?>">here</a> to log in</p>
						</div>
						<div class="tabContent">
							<div class="colRow colRowWrap">
								<div class="col90">
									<div class="tabNav">
										<div class="tabItem <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.6s'>
											<a data-id="dashboardTab" class="size16 tabActiveBlue linkHover fontWeight500">Dashboard</a>
										</div>
										<div class="tabItem <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.6s'>
											<a data-id="accountDetailsTab" class="size16 primaryBlack fontWeight500 linkHover">Account details</a>
										</div>
									</div>
								</div>
								<div class="col10">
									<div class="btnWrap <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.6s'>
										<a href="<?= wp_logout_url(home_url()); ?>" class="fontWeight500"><span class="linkLabel aTagHover">Logout</span><span class="dropDown right"></span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="myAccountDashBoardBody">
					<div class="myAccountDashBoardBodyWrap">
						<div class="dashboard dashBoardTap" id="dashboardTab">
							<div class="colRow cardColRow">
								<div class="col50 card cardCol">
									<div class="cardColWrap">
										<div class="cardRow padding40 whiteBg cardRadius24">
											<div class="cardHeader">
												<div class="cardText pb40">
													<p class="size22 primaryBlue cardTextTitle pb24 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>Refer & Earn</p>
													<p class="size16 primaryBlue fontWeight500 cardTextText pb8 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>Give your friends 5% off</p>
													<p class="size16 primaryBlue primaryBlue60 cardTextText <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s'>Give your friends 5% off their ticket purchase and in return get a £5 discount. (new accounts only)</p>
												</div>
												<div class="cardButton">
													<div class="colRow">
														<div class="col colRow copyBtnRow">
															<input readonly type="text" id="referId" class="referId" value="<?= $referralId; ?>">
															<button class="copyBtn"><span>Copy</span> <span class="dropDown right"></span></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col25 card cardCol">
									<div class="cardColWrap">
										<div class="cardRow padding40 whiteBg cardRadius24 justifyContentSpaceBetween">
											<div class="cardHeader">
												<div class="cardTitle <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
													<p class="size22 primaryBlue pb30">Referrals:</p>
												</div>
												<div class="cardText <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>
													<input readonly type="text" class="size45 earnValue" value="<?= ($referCount) ? $referCount : 0; ?>" />
												</div>
											</div>
											<div class="cardFooter <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s'>
												<p class="size14 primaryBlue">You haven’t made any referrals yet</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col25 card cardCol">
									<div class="cardColWrap">
										<div class="cardRow padding40 whiteBg cardRadius24 justifyContentSpaceBetween">
											<div class="cardHeader">
												<div class="cardTitle pb30 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
													<p class="size22 primaryBlue">Charities donated:</p>
												</div>
												<div class="cardText <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>
													<input readonly type="text" class="size45 earnValue" value="<?= ($donationValue) ? $donationValue : 0; ?>" />
												</div>
											</div>
											<div class="cardFooter <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s'>
												<p class="size16 primaryBlue fontWeight500">Share</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="colRow flexDirectionRow">
								<?php
									$getLogo = get_field('contributed_logo',$myAccountPageId);
									if($getLogo){
								?>
								<div class="contributedLogoHeader pb40 pt60">
									<p class="prprimaryBlue size22">Charities you’ve contributed to from entering:</p>
								</div>
								<div class="contributedLogoBody">
									<div class="contributedLogoSlider  swiper-container">
										<div class="swiper-wrapper contributedLogoSliderBlocks">
											<?php
												$getLogo = get_field('contributed_logo',$myAccountPageId);
												foreach($getLogo as $getLogoItem){
													$image = $getLogoItem['contributed_logo_logo'];
													$link = $getLogoItem['contributed_logo_link'];
													$tag = ($link) ? "href='$link' target='_black'" : "";
											?>
													<div class="contributedLogoSliderItem swiper-slide">
														<?php
															$img = image_on_fly($image, array('360', 'auto'), true);
														?>
														<a <?= $tag; ?>>
															<img src="<?= $img['src']; ?>" class="logoImage">
														</a>
													</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<?php
									}else{
										$headerid = url_to_postid('/header/header-footer');
										$buttonLabel = get_post_meta( $headerid, 'button_label', true );
										$buttonLink = get_permalink(getActiveProductId());
										if(get_post_meta( $headerid, 'button_link_type', true ) == "email"){
											$buttonLink = "mailto:".get_post_meta( $headerid, 'email', true );
										}else if(get_post_meta( $headerid, 'button_link_type', true ) == "external"){
											$buttonLink = get_post_meta( $headerid, 'external_link', true );
										}
								?>
									<div class="btnWrap contributedLogoBtnWrap pt60  <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
										<a href="<?= $buttonLink; ?>" class="btn btnPrimaryBlue"><?= $buttonLabel; ?></a>
									</div>
								<?php
									}
								?>
							</div>
						</div>
						<div class="accoountDetails dashBoardTap" id="accountDetailsTab">
							<div class="colRow cardColRow">
								<div class="col50 card cardCol">
									<div class="cardColWrap">
										<div class="cardRow padding40 whiteBg cardRadius24">
											<div class="cardHeader">
												<div class="cardText pb40">
													<p class="size22 primaryBlue cardTextTitle">Personal:</p>
												</div>
											</div>
											<div class="cardBody">
												<div class="formCard">
													<form id="account-details-form" class="woocommerce-EditAccountForm edit-account" action="" method="POST">
														<p class="form-row form-row-wide customTitleStyle formLabel">Name</p>
														<div class="formInputRow">
															<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= ($current_user->first_name) ? 'hasInput' : ''; ?>">
																<label for="account_first_name">First name&nbsp;<span class="required">*</span></label>
																<span class="woocommerce-input-wrapper">
																	<input required type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="off" value="<?= ($current_user->first_name) ? $current_user->first_name : ''; ?>">
																</span>
															</p>
															<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= ($current_user->last_name) ? 'hasInput' : ''; ?>">
																<label for="account_last_name">Last name&nbsp;<span class="required">*</span></label>
																<span class="woocommerce-input-wrapper">
																	<input required type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="off" value="<?= ($current_user->last_name) ? $current_user->last_name : ''; ?>">
																</span>
															</p>
														</div>
														<p class="form-row form-row-wide customTitleStyle formLabel">Email</p>
														<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= ($current_user->user_email) ? 'hasInput' : ''; ?>">
															<label for="account_email"><?php esc_html_e('Email', 'woocommerce'); ?></label>
															<span class="woocommerce-input-wrapper">
																<input required class="woocommerce-Input woocommerce-Input--text input-text" type="email" name="account_email" id="account_email" autocomplete="new-email" value="<?= ($current_user->user_email) ? $current_user->user_email : ''; ?>" />
															</span>
														</p>
														<p class="form-row form-row-wide customTitleStyle formLabel">Contact</p>
														<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= (get_user_meta($current_user->ID, 'phone', true)) ? 'hasInput' : ''; ?>">
															<label for="password"><?php esc_html_e('Phone', 'woocommerce'); ?></label>
															<span class="woocommerce-input-wrapper">
																<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="phone" id="phone" autocomplete="new-phone" value="<?= (get_user_meta($current_user->ID, 'phone', true)) ? get_user_meta($current_user->ID, 'phone', true) : ''; ?>" />
															</span>
														</p>
														<p class="form-row form-row-wide customTitleStyle formLabel">Change password</p>
														<div class="formInputRow">
															<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput">
																<label for="password_1">New Password</label>
																<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="off">
															</p>
															<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput">
																<label for="password_2">Confirm password</label>
																<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="off">
															</p>
														</div>
														<div class="formInputRow">
															<div class="col50 checkboxWrap">
																<?php 
																	$email_special_offer_admin = get_the_author_meta('email_special_offer', get_current_user_id());
																	$subscribe_user = $email_special_offer_admin == '1' ? '1' : '0';
																?>
																<input type="checkbox" id="subscribe_user" name="subscribe_user" value="<?= $subscribe_user ?>" <?= $subscribe_user == '1' ? 'checked' : ''; ?>>
																<label for="subscribe_user">Subscribe User</label>
															</div>
															<p class="col50"> </p>
														</div>
														<div class="formInputRow">
															<p class="col50 btnWrap">
																<button type="submit" class="btn woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
																<span class="wpcf7-spinner"></span>
																<input type="hidden" name="action" value="save_account_details" />
															</p>
															<p class="col50"> </p>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col50 card cardCol">
									<div class="cardColWrap">
										<div class="cardRow padding40 whiteBg cardRadius24">
											<div class="cardHeader">
												<div class="cardText pb40">
													<p class="size22 primaryBlue cardTextTitle">Billing:</p>
												</div>
											</div>
											<div class="cardBody">
												<div class="formCard">
													<form  id="billing-details-form" action="" method="POST">
														<p class="form-row form-row-wide customTitleStyle formLabel">Address</p>
														<p class="form-row address-field customInputStyle formInput <?= ($billing_address) ? 'hasInput' : ''; ?>" id="billing_address_1_field" data-priority="50">
															<label for="billing_address_1" class="">Street address&nbsp;<abbr class="required" title="required">*</abbr></label>
															<span class="woocommerce-input-wrapper">
																<input required type="text" class="input-text " name="billing_address_1" id="billing_address_1" placeholder="" value="<?= ($billing_address) ? $billing_address : ''; ?>" autocomplete="address-line1" data-placeholder="">
															</span>
														</p>
														<p class="form-row address-field customInputStyle formInput <?= ($billing_apartment) ? 'hasInput' : ''; ?>" id="billing_address_2_field" data-priority="60">
															<label for="billing_address_2" class="">Apartment, suite, unit, etc.</label>
															<span class="woocommerce-input-wrapper"><input type="text" required class="input-text " name="billing_address_2" id="billing_address_2" placeholder="" value="<?= ($billing_apartment) ? $billing_apartment : ''; ?>" autocomplete="address-line2" data-placeholder=""></span>
														</p>
														<p class="form-row address-field customInputStyle <?= ($billing_city) ? 'hasInput' : ''; ?>" id="billing_city_field" data-priority="80" data-o_class="form-row form-row-wide address-field customInputStyle hasInput validate-required validate-state">
															<label for="billing_state" class="">Town / City</label>
															<span class="woocommerce-input-wrapper">
																<input required type="text" class="input-text " placeholder="" name="billing_city" id="billing_city" value="<?= ($billing_city) ? $billing_city : ''; ?>" autocomplete="address-level2" data-input-classes="">
															</span>
														</p>
														<div class="formInputRow">
															<div class="col50">
																<p class="form-row address-field customInputStyle hasInput" id="billing_country_field" data-priority="80" data-o_class="form-row form-row-wide address-field customInputStyle hasInput validate-required validate-state">
																	<label for="billing_state" class="">Country</label>
																	<span class="woocommerce-input-wrapper">
																	<?php
																		global $woocommerce;
																		$countries_obj = new WC_Countries();
																		$countries = $countries_obj->get_countries();
																		$select_field = '<select id="billing_country" name="billing_country" class="billing_country">';
																		foreach ($countries as $country_code => $country_name) {
																			$selected = ($country_code == $billing_country) ? 'selected="selected"' : '';
																			$select_field .= '<option value="' . esc_attr($country_code) . '" ' . $selected . '>' . esc_html($country_name) . '</option>';
																		}
																		$select_field .= '</select>';
																		echo $select_field;
																	?>
																	</span>
																</p>
															</div>
															<div class="col50">
																<p class="form-row address-field customInputStyle formInput  <?= ($billing_postcode) ? 'hasInput' : ''; ?>" id="billing_postcode_field" data-priority="60">
																	<label for="billing_postcode" class="">Post Code</label>
																	<span class="woocommerce-input-wrapper"><input type="text" required class="input-text " name="billing_postcode" id="billing_postcode" placeholder="" value="<?= ($billing_postcode) ? $billing_postcode : ''; ?>" autocomplete="address-line2" data-placeholder=""></span>
																</p>
															</div>
														</div>
														<div class="formInputRow">
															<p class="col50 btnWrap">
																<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_billing_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
																<span class="wpcf7-spinner"></span>
																<input type="hidden" name="action" value="save_billing_details" />
															</p>
															<p class="col50"> </p>
														</div>
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
			</div>
		</div>
	</div>
</div>