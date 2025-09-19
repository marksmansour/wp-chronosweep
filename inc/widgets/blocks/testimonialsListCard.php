<div class="testimonialsListCard swiper-slide whiteBg">
    <div class="testimonialsListCardWrap">
        <div class="testimonialMessage cardTextContentEqualHeight <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
            <p class="testimonialMessageText size36 primaryBlue"><?= $message ?></p>
        </div>
        <div class="testimonialName <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>
            <p class="size22"><span class="testimonialNameText"><?= $name ?></span> - <span class="testimonialSubText"><?= $subText ?></span></p>
        </div>
        <?php
            $tag = "href='$link' target='_blank'";
        ?>
        <a <?= $tag; ?> class="testimonialLogo <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s'>
            <div class="testimonialLogoText">
                <p class="logoText size16 primaryBlue aTagHover"><?= $logoText ?></p>
            </div>
            <?php
                $img = image_on_fly($logo['ID'], array('240', 'auto'), true);
            ?>
            <div class="testimonialLogoImage" style="background-image:url('<?= $img['src']; ?>');">

            </div>
        </a >
    </div>
</div>