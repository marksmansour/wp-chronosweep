<div class="banner <?= get_sub_field('banner_type'); ?> c">
    <div class="bannerWrap">
        <?php
            if(get_sub_field('banner_type') == "bottomTextBanner"){
        ?>
                <div class="bannerBackgroundImage">
                    <?php
                        $getImageId = get_sub_field('banner_image');
                        $img = image_on_fly($getImageId['id'], array(3294, 'auto'), true);
                    ?>
                    <div class="sizer"></div>
                    <div class="bsz">
                        <div class="bgimage" style="background-image: url('<?= $img['src']; ?>')"></div>
                        <img src="<?= $img['src']; ?>" alt="">
                    </div>
                </div>
                <div class="middle-wrap-table">
                    <div class="middle">
                        <div class="textContent">
                            <div class="textContentWrap">
                                <div class="textContentTop lightBlueBg"></div>
                                <div class="textContentBottom lightBlueBg">
                                    <div class="textContentBottomTitleWrap wow animate__fadeInUpSmall"  data-wow-delay='0.2s'>
                                    <?php
                                        $tag = get_sub_field('banner_title_tag');
                                        $title = get_sub_field('banner_title');
                                        echo "<$tag class='bannerTitle size57 primaryBlack'>".$title."</$tag>";
                                    ?>
                                    </div>
                                    <?php
                                        if(get_sub_field('banner_text')){
                                    ?>
                                    <div class="textContentBottomTextWrap wow animate__fadeInUpSmall"  data-wow-delay='0.4s'>
                                        <p class="bannerText primaryBlack size16"><?= get_sub_field('banner_text'); ?></p>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    <?php
                                        if(get_sub_field('banner_button_label')){
                                            $link = get_sub_field('banner_page_link');
                                            if(get_sub_field('banner_link_type') == "external"){
                                                $link = get_sub_field('banner_link');
                                            }
                                    ?>
                                    <div class="textContentBottomButtonWrap wow animate__fadeInUpSmall"  data-wow-delay='0.6s'>
                                        <a href="<?= $link; ?>" class="bannerLink primaryBlue"><span class="linkLabel aTagHover"><?= get_sub_field('banner_button_label'); ?></span><span class="dropDown right"></span></a>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }else if(get_sub_field('banner_type') == "leftTextBanner"){
        ?>
                <div class="middle-wrap-table">
                    <div class="middle">
                        <div class="leftTextBannerWrap">
                            <div class="textContent">
                                <div class="textContentleftTitleWrap wow animate__fadeInUpSmall" data-wow-delay='0.2s'>
                                <?php
                                    $tag = get_sub_field('banner_title_tag');
                                    $title = get_sub_field('banner_title');
                                    echo "<$tag class='bannerTitle size57 primaryBlack'>".$title."</$tag>";
                                ?>
                                </div>
                                <?php
                                    if(get_sub_field('banner_text')){
                                ?>
                                <div class="textContentLeftTextWrap wow animate__fadeInUpSmall" data-wow-delay='0.4s'>
                                    <p class="bannerText primaryBlack size16"><?= get_sub_field('banner_text'); ?></p>
                                </div>
                                <?php
                                    }
                                ?>
                                <?php
                                    if(get_sub_field('banner_button_label')){
                                        $link = get_sub_field('banner_page_link');
                                        if(get_sub_field('banner_link_type') == "external"){
                                            $link = get_sub_field('banner_link');
                                        }
                                ?>
                                <div class="textContentLeftButtonWrap wow animate__fadeInUpSmall" data-wow-delay='0.6s'>
                                    <a href="<?= $link; ?>" class="bannerLink primaryBlue"><span class="linkLabel aTagHover"><?= get_sub_field('banner_button_label'); ?></span><span class="dropDown right"></span></a>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <div class="imageContent">
                                <div class="imageContentWrap">
                                    <div class="sizer"></div>
                                    <?= image_on_fly(get_sub_field('banner_image')['id'], array('1716', 'auto')); ?>
                                    <div class="bannerLeftLayer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
    </div>
</div>