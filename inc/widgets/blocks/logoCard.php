<div class="leftImageAndRightTextCard swiper-slide <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec; ?>s">
    <?php
        $img = image_on_fly($image, array('360', 'auto'), true);
    ?>
    <a <?= $tag; ?>>
        <img src="<?= $img['src']; ?>" class="logoImage">
    </a>
</div>