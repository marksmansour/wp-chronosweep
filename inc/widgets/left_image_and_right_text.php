<div class="section leftImageAndRightText <?= get_sub_field('left_image_and_right_text_type') ?> setWhite">
    <div class="c">
        <?php
            if(get_sub_field('left_image_and_right_text_type') == "play_fair"){
        ?>
        <img class="leftImageAndRightTextPlaceholder" src="<?= get_bloginfo('template_directory'); ?>/assets/images/leftImageAndRightTextPlaceholder.png" />
        <?php
            }
        ?>
        <div class="leftImageAndRightTextWrap whiteBg">
            <div class="leftImageAndRightTextColumn imageColumn">

                <div class="imageColumnWrap">
                    <?php
                        if(get_sub_field('left_image_and_right_text_type') == "donate"){
                            $titleTag = get_sub_field('left_image_and_right_text_title_tag');
                            echo "<$titleTag class='size16 fontWeight500 primaryBlack wow animate__fadeInUpSmall' data-wow-delay='0.2s'>".get_sub_field('left_image_and_right_text_title')."</$titleTag>";
                            $logoList = get_sub_field('left_image_and_right_text_logo_list');
                    ?>
                            <div id="leftImageAndRightTextLogoSlider<?= $wid; ?>" class="leftImageAndRightTextLogoSlider swiper-container">
                                <div class="swiper-wrapper leftImageAndRightTextLogoListBlocks">
                                    <?php
                                        $sec = 0.2;
                                        foreach($logoList as $logo){
                                            $image = $logo['left_image_and_right_text_logo_list_image'];
                                            $link = $logo['left_image_and_right_text_logo_list_link'];
                                            $tag = ($link) ? "href='$link' target='_black'" : "";
                                            include('blocks/logoCard.php');
                                            $sec = $sec + 0.2;
                                        }
                                    ?>
                                </div>
                            </div>
                    <?php
                        }else if(get_sub_field('left_image_and_right_text_type') == "play_fair"){
                    ?>
                            <div class="textContent">
                                <?php
                                    $titleTag = get_sub_field('left_image_and_right_text_title_tag');
                                    echo "<$titleTag class='size36 fontWeight500 primaryBlack wow animate__fadeInUpSmall' data-wow-delay='0.2s'>".get_sub_field('left_image_and_right_text_title')."</$titleTag>";
                                ?>
                                <p class="size16 primaryBlack <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'><?= get_sub_field('left_image_and_right_text_text') ?></p>
                                <div class="buttonWrap">
                                    <?php
                                        $btnLink = get_sub_field('left_image_and_right_text_page_link');
                                        if(get_sub_field('left_image_and_right_text_link_type') == "external"){
                                            $btnLink = get_sub_field('left_image_and_right_text_link');
                                        }
                                    ?>
                                    <a class="btn btnPrimaryBlue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s' href="<?= $btnLink; ?>"><?= get_sub_field('left_image_and_right_text_button_label'); ?></a>
                                    <a href="<?= get_sub_field('left_image_and_right_text_faqs_page_link'); ?>" class="bannerLink primaryBlue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s'><span class="linkLabel aTagHover"><?= get_sub_field('left_image_and_right_text_faqs_button_label'); ?></span><span class="dropDown right"></span></a>
                                </div>
                            </div>
                            <div class="textWithImageWrap">
                                <div class="sizer"></div>
                                <?= image_on_fly(get_sub_field('left_image_and_right_text_bottom_image'), array('2040', 'auto')); ?>
                            </div>
                    <?php
                        }else if(get_sub_field('left_image_and_right_text_type') == "partners"){
                    ?>
                            <div class="imageWrap">
                                <div class="sizer"></div>
                                <?= image_on_fly(get_sub_field('left_image_and_right_text_bottom_top_image'), array('1822', 'auto')); ?>
                            </div>
                            <div class="textContent">
                                <?php
                                    $titleTag = get_sub_field('left_image_and_right_text_title_tag');
                                    echo "<$titleTag class='size16 fontWeight500 primaryBlack wow animate__fadeInUpSmall' data-wow-delay='0.2s'>".get_sub_field('left_image_and_right_text_title')."</$titleTag>";
                                    $logoList = get_sub_field('left_image_and_right_text_logo_list');
                                ?>
                            </div>
                            <div class="leftImageAndRightTextLogoSlider">
                                <div class="leftImageAndRightTextLogoListBlocks">
                                    <?php
                                        $sec = 0.2;
                                        foreach($logoList as $logo){
                                            $image = $logo['left_image_and_right_text_logo_list_image'];
                                            $link = $logo['left_image_and_right_text_logo_list_link'];
                                            $tag = ($link) ? "href='$link' target='_black'" : "";
                                            include('blocks/logoCard.php');
                                            $sec = $sec + 0.2;
                                        }
                                    ?>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div class="leftImageAndRightTextColumn textColumn">
                <div class="textColumnWrap">
                    <div id="leftImageAndRightTextTextContentSlider<?= $wid; ?>" class="leftImageAndRightTextTextContentSlider swiper-container">
                        <div class="swiper-wrapper leftImageAndRightTextTextContentBlocks">
                            <?php
                                $textList = get_sub_field('left_image_and_right_text_text_list');
                                $sec = 0.2;
                                foreach($textList as $text){
                                    $image = $text['left_image_and_right_text_text_list_icon'];
                                    $title = $text['left_image_and_right_text_text_list_title'];
                                    $text = $text['left_image_and_right_text_text_list_text'];
                            ?>
                                <div class="textListItem swiper-slide <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec; ?>s">
                                    <div class="textListItemWrap">
                                        <div class="textListItemHeader">
                                            <div class="textListItemHeaderIcon">
                                                <?php
                                                    $img = image_on_fly($image, array(64, 'auto'), true);
                                                ?>
                                                <img src="<?= $img['src']; ?>" />
                                            </div>
                                            <div class="textListItemHeaderTitle">
                                                <p class="primaryBlack size16 fontWeight500"><?= $title; ?></p>
                                            </div>
                                        </div>
                                        <div class="textListItemBody">
                                            <p class="black size16"><?= $text; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                    $sec = $sec + 0.2;
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>