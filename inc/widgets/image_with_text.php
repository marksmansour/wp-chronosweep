<div class="section imageWithText setWhite">
    <div class="imageWithTextWrap c">
        <div class="imageWithTextColumn textWithImage whiteBg">
            <div class="imageWithTextColumnTextContent">
            <?php
                if(get_sub_field('image_with_text_small_title')){
            ?>
                    <p class="primaryBlack fontWeight500 imageWithTextSmallText <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'><?= get_sub_field('image_with_text_small_title') ?></p>
            <?php
                }

                if(get_sub_field('image_with_text_logo')){
            ?>
                    <div class="imageWithTextLogoWrap <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>
                        <img src="<?= get_sub_field('image_with_text_logo'); ?>" class="imageWithTextLogo" />
                    </div>
            <?php
                }

                $titleTag = get_sub_field('image_with_text_title_tag');
                echo "<$titleTag class='size45 fontWeight400 imageWithTextMainText  wow animate__fadeInUpSmall'  data-wow-delay='0.6s'>".get_sub_field('image_with_text_title')."</$titleTag>";

                if(get_sub_field('image_with_text_text')){
            ?>
                    <p class="primaryBlack size16 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.8s'><?= get_sub_field('image_with_text_text'); ?></p>
            <?php
                }

                if((get_sub_field('image_with_text_button_label')) && (get_sub_field('image_with_text_page_link') || get_sub_field('image_with_text_link'))){
                    $btnLink = get_sub_field('image_with_text_page_link');
                    if(get_sub_field('image_with_text_link_type') == "external"){
                        $btnLink = get_sub_field('image_with_text_link');
                    }
            ?>
                <div class="btnWrap <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.10s'>
                    <a class="btn btnPrimaryBlue" href="<?= $btnLink; ?>"><?= get_sub_field('image_with_text_button_label'); ?></a>
                </div>
            <?php
                }
            ?>
            </div>
            <div class="textWithImageWrap">
                <div class="sizer"></div>
                <?= image_on_fly(get_sub_field('image_with_text_left_image'), array('2040', 'auto')); ?>
            </div>
        </div>
        <div class="imageWithTextColumn imageOnly">
            <div class="imageWithTextColumnWrap">
                <div class="sizer"></div>
                <?= image_on_fly(get_sub_field('image_with_text_right_image'), array('1440', 'auto')); ?>
            </div>
        </div>
    </div>
</div>