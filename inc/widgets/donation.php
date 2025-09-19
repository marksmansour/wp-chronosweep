<div class="section donation setWhite">
    <div class="c">
        <div class="donationWrap primaryBlueBg">
            <img class="donationPlaceholder" src="<?= get_bloginfo('template_directory'); ?>/assets/images/donationPlaceholder.png" />
            <div class="donationRow">
                <div class="donationTextColumn donationColumn">
                    <div class="donationTextColumnHeader">
                        <?php
                            $titleTag = get_sub_field('donation_title_tag');
                            echo "<$titleTag class='size36 fontWeight500 lightWhite textContentTitle wow animate__fadeInUpSmall' data-wow-delay='0.2s'>".get_sub_field('donation_title')."</$titleTag>";

                            $btnLink = get_sub_field('donation_page_link');
                            if(get_sub_field('donation_link_type') == "external"){
                                $btnLink = get_sub_field('donation_link');
                            }
                        ?>
                        <p class="size16 lightWhite <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'><?= get_sub_field('donation_text'); ?></p>
                        <div class="btnWrap wow animate__fadeInUpSmall" data-wow-delay='0.6s'>
                            <a href="<?= $btnLink; ?>" class="bannerLink lightWhite"><span class="linkLabel aTagHover"><?= get_sub_field('donation_button_label'); ?></span><span class="dropDown right"></span></a>
                        </div>
                    </div>
                </div>
                <div class="donationImageColumn donationColumn">
                    <div class="donationLogolist">
                        <div class="donationLogolistWrap">
                        <?php
                            $logoList = get_sub_field('donation_logo_list');
                            $sec = 0.2;
                            foreach($logoList as $logo){
                                $image = $logo['donation_logo_list_image'];
                                $link = $logo['donation_logo_list_link'];
                                $tag = ($link) ? "href='$link' target='_black'" : "";
                        ?>
                                <div class="donationLogoCard <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec; ?>s">
                                    <?php
                                        $img = image_on_fly($image, array('450', 'auto'), true);
                                    ?>
                                    <div class="donationLogoCardWrap">
                                        <a <?= $tag; ?>>
                                            <img src="<?= $img['src']; ?>" class="logoImage">
                                        </a>
                                    </div>
                                </div>
                        <?php
                                $sec = $sec + 0.2;
                            }
                        ?>
                                <div class="donationLogoCard <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec + 0.2; ?>s">
                                    <div class="donationLogoCardWrap">
                                        <a class="lightWhite size18 donationLogoCardStatic">
                                            <span>Or any other charity of your choice</span>
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>