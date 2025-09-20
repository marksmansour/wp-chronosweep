                </div>
                <?php
                    $page_id = get_the_ID();
                    $popUpSetting = get_field('account_creating_popup', $page_id);
                    $fullWidthCta = get_field('full_width_cta', $page_id);
                    $headerid = url_to_postid('/header/header-footer');
                    $post = get_post($headerid);
                    setup_postdata($post);
                    if($fullWidthCta) {
                ?>
                    <div class="section fullWidthCTA setWhite">
                        <div class="c">
                            <div class="fullWidthCTAWrap lightBlackBg">
                                <div class="leftImage">
                                    <div class="leftImageWrap">
                                        <div class="sizer"></div>
                                        <?= image_on_fly(get_field('full_width_cta_left_image'), array('480', 'auto')); ?>
                                    </div>
                                </div>
                                <div class="textContent">
                                    <div class="textContentWrap">
                                        <?php
                                        $titleTag = get_field('full_width_cta_title_tag');
                                        echo "<$titleTag class='size57 fontWeight400 lightWhite textContentTitle wow animate__fadeInUpSmall' data-wow-delay='0.2s'>" . get_field('full_width_cta_title') . "</$titleTag>";
                                        ?>
                                        <p class="size16 lightWhite textContentText wow animate__fadeInUpSmall" data-wow-delay='0.4s'><?= get_field('full_width_cta_text'); ?></p>
                                        <?php
                                        $link = get_field('full_width_cta_page_link');
                                        if (get_field('full_width_cta_link_type') == "external") {
                                            $link = get_field('full_width_cta_link');
                                        }else if(get_field('full_width_cta_link_type') == "currentActiveProduct"){
                                            $link = get_permalink(getActiveProductId());
                                        }
                                        ?>
                                        <div class="btnWrap wow animate__fadeInUpSmall" data-wow-delay='0.6s'>
                                            <a class="btn btnWhite" href="<?= $link; ?>"><?= get_field('full_width_cta_button_label'); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="rightImage">
                                    <div class="rightImageWrap">
                                        <div class="sizer"></div>
                                        <?= image_on_fly(get_field('full_width_cta_right_image'), array('480', 'auto')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
                <div class="stickyBtnCtaWrap">
                    <a href="<?= get_permalink(getActiveProductId()); ?>" class="btn bannerEnterNowBtn stickyBtnCta enterBtnLink">
                        <span class='bannerEnterNowBtnValue'>Enter now</span>
                    </a>
                </div>
                <div class="footer">
                    <div class="footerWrap">
                        <div class="footerTop primaryBlueBg">
                            <div class="c">
                                <div class="footerTopWrap">
                                    <div class="footerColumn footerColumn1">
                                        <img class="footerLogo" src="<?= get_bloginfo('template_directory'); ?>/assets/images/footerLogoWhite.svg" />
                                    </div>
                                    <div class="footerColumn footerColumn2">
                                        <div class="footerColumnWrap">
                                            <div class="footerColumnMenu">
                                                <?php
                                                $mainMenu = get_field('footer_menu');
                                                foreach ($mainMenu as $mainMenuItem) {
                                                    $menuItemLink = $mainMenuItem['footer_menu_page_link'];
                                                    if ($mainMenuItem['footer_menu_link_type'] == "external") {
                                                        $menuItemLink = $mainMenuItem['footer_menu_link'];
                                                    }
                                                ?>
                                                    <div class="footerMenuItem menuItem lightWhite">
                                                        <a class="linkHover" href="<?= $menuItemLink; ?>"><span class="footerMenuItemlabel"><?= $mainMenuItem['footer_menu_label']; ?></span></a>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footerColumn footerColumn3">
                                        <div class="footerColumnWrap">
                                            <div class="footerColumnSocialMediaMenu">
                                                <?php
                                                $socialMedia = get_field('social_media');
                                                foreach ($socialMedia as $socialMediaItem) {
                                                ?>
                                                    <div class="footerMenuItem menuItem lightWhite linkHover">
                                                        <a href="<?= $socialMediaItem['social_media_link']; ?>"><span class="footerMenuItemlabel"><?= ucfirst($socialMediaItem['social_media_label']); ?></span></a>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footerColumn footerColumn4">
                                        <div class="footerColumnWrap">
                                            <div class="footerTextContent">
                                                <p class="lightWhite"><?= get_field('footer_subscribe_title'); ?></p>
                                            </div>
                                            <div class="footerForm">
                                                <div class="subscribeForm">
						 <div class="subscribeFormWrap">
							<div class="klaviyo-form-QYQiKV"></div>                                                      
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footerBottom lightBlue">
                            <div class="c">
                                <div class="footerBottomWrap">
                                    <div class="footerBottomColumn footerBottomColumn1">
                                        <div class="footerBottomColumnWrap">
                                            <div class="footerColumnBottomMenu">
                                                <?php
                                                $footerBottomMenu = get_field('footer_bottom_menu');
                                                foreach ($footerBottomMenu as $footerBottomMenuItem) {
                                                    $footerBottomMenuItemLink = $footerBottomMenuItem['footer_bottom_menu_page_link'];
                                                    if ($footerBottomMenuItem['footer_bottom_menu_link_type'] == "external") {
                                                        $footerBottomMenuItemLink = $footerBottomMenuItem['footer_bottom_menu_link'];
                                                    }
                                                ?>
                                                    <div class="footerBottomMenuItem menuItem primaryBlack linkHover">
                                                        <a href="<?= $footerBottomMenuItemLink; ?>"><span class="footerMenuItemlabel"><?= $footerBottomMenuItem['footer_bottom_menu_label']; ?></span></a>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <div class="footerBottomMenuItem cookiePrefernceWrap menuItem primaryBlack linkHover">
                                                    <a class="cookiePrefernce"><span class='footerMenuItemlabel'>Cookie Preferences</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footerBottomColumn footerBottomColumn2">
                                        <p class="primaryBlack copyrightText">© Copyright <?= date('Y'); ?> <?= get_field('copyright_text') ?></p>
                                    </div>
                                    <div class="footerBottomColumn footerBottomColumn3">
                                        <img class="footerCartIcon" src="<?= get_bloginfo('template_directory'); ?>/assets/images/cartIcons.svg" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <?php
                if(!is_user_logged_in() && $popUpSetting){
                ?>
                    <div id="popup" class="accountCreatePopup">
                        <div class="popupContent">
                            <span id="closeBtn">&times;</span>
                            <form class="accountCreatePopupForm" method="POST">
                                <div class="colRow">
                                    <div class="accountCreatePopupHeader">
                                        <p class='size45 primaryBlack'>Save on your first entry</p>
                                        <p class='size16 primaryBlack'>Join our community and get 15% off your first order.</p>
                                    </div>
                                </div>
                                <div class="colRowWithOutFlex">
                                    <div class="cardColRow">
                                        <div class="inlineDisplay inlineDisplayCol50 cardCol">
                                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= ($current_user->first_name) ? 'hasInput' : ''; ?>">
                                                <label for="account_first_name">First name</label>
                                                <span class="woocommerce-input-wrapper">
                                                    <input required type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="off" value="">
                                                </span>
                                            </p>
                                        </div><div class="inlineDisplay inlineDisplayCol50 cardCol">
                                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= ($current_user->last_name) ? 'hasInput' : ''; ?>">
                                                <label for="account_last_name">Last name</label>
                                                <span class="woocommerce-input-wrapper">
                                                    <input required type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="off" value="">
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="colRow">
                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= ($current_user->last_name) ? 'hasInput' : ''; ?>">
                                        <label for="account_email">Email</label>
                                        <span class="woocommerce-input-wrapper">
                                            <input required type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="account_email" id="account_email" autocomplete="off" value="">
                                        </span>
                                    </p>
                                </div>
                                <div class="colRow">
                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide customInputStyle formInput <?= ($current_user->last_name) ? 'hasInput' : ''; ?> phoneNumberField">
                                        <label for="account_email">Phone number</label>
                                        <span class="woocommerce-input-wrapper">
                                            <input type="number" class="woocommerce-Input woocommerce-Input--text input-number" name="phone" id="phone" autocomplete="off" value="">
                                        </span>
                                    </p>
                                    <div class="btnWrap">
                                        <button type="submit" class="button btn btnPrimaryBlue" disabled name="" value="Subscribe">Get your 15% discount</button>
                                        <span class="wpcf7-spinner"></span>
                                    </div>
                                </div>
                                <div class="colRow">
                                    <?php
                                    $termsPageId = wc_get_page_id( 'terms' );
                                    $termsLink = get_permalink( $termsPageId );
                                    $privacyPageId = wc_privacy_policy_page_id();
                                    $privacyLink = get_permalink( $privacyPageId );
                                    ?>
                                    <p class='size12'>By submitting this form and signing up for texts, you consent to receive marketing text messages from Chrono Sweep Vintage. Unsubscribe at any time by replying STOP or clicking the unsubscribe link. <a class="underLineHover" href="<?= $termsLink; ?>">Privacy Policy</a> & <a  class="underLineHover" href="<?= $termsLink; ?>">Terms.</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>
                <?php wp_footer(); ?>
    </body>
</html>
