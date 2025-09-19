<?php
    date_default_timezone_set('UTC+0');
    $productId = get_sub_field('product_banner_product');
    $currencySymbol = (get_woocommerce_currency_symbol()) ? get_woocommerce_currency_symbol() : "£";
    $price = wc_get_product($productId);
    $showDate = get_field('product_extra_details_show_draw_date',$productId);
    $drawDate = get_field('product_extra_details_draw_date',$productId);
    $drawDate = date("d.m.y", strtotime($drawDate));
    $drawDateText = get_field('product_extra_details_draw_date_text',$productId);
    $endDate = date("Y-m-d H:i:s", strtotime(get_field('product_extra_details_draw_date',$productId)));
    $totalTicket = get_field('product_extra_details_total_ticket',$productId);
    $dateDiff = dateDiff($endDate);
    $orderedCount = getOrderCountForProduct($productId);
    $fill = ($orderedCount['quantity'] / $totalTicket)*100;
    if($fill > 100){
        $fill = 100;
    }
    $status = false;
    if(($dateDiff == 1) && ($orderedCount['quantity'] < $totalTicket)){
        $status = true;
    }
    if($orderedCount['quantity'] > $totalTicket){
        $orderedCount['quantity'] = $totalTicket;
    }
?>
<div class="banner productBanner">
    <input type="hidden" value="<?= $status; ?>" class="productStatus" />
    <input type="hidden" class="endDate" value="<?= date("j/n/Y/H/i/s", strtotime(get_field('product_extra_details_draw_date',$productId))); ?>" />
    <img src="<?= get_bloginfo('template_directory'); ?>/assets/images/productBannerLeftShadow.png" class="productBannerShadow productBannerLeftShadow">
    <img src="<?= get_bloginfo('template_directory'); ?>/assets/images/productBannerRightShadow.png" class="productBannerShadow productBannerRightShadow">
    <img src="<?= get_bloginfo('template_directory'); ?>/assets/images/productBannerBottomShadow.png" class="productBannerShadow productBannerBottomShadow">
    <div class="c">
        <div class="productBannerWrap">
            <div class="productBannerTextContentWrap">
                <div class="textContentWrap">
                    <div class="textContentTextContent">
                        <div class="msgWrap"></div>
                        <div class="textContentTitle">
                            <label class="white50 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.2s'>Competition ID: #<?= $productId; ?></label>
                            <h1 class="size57 fontWeight700 lightWhite <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.4s'><?= get_the_title($productId); ?></h1>
                        </div>
                        <div class="textContentText">
                            <p class="size16 white50 <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s'><?= get_field('product_extra_details_description',$productId); ?></p>
                        </div>
                    </div>
                    <div class="textContentActionContent">
                        <div class="productDetails">
                            <div class="ticketPrice <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
                                <label class="size12 fontWeight500 white50">Ticket Price:</label>
                                <p class="size18 lightWhite ticketPriceValue"><?= $currencySymbol; ?><?= number_format($price->get_price()); ?></p>
                            </div>
                            <div class="ticketEntries <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>
                                <label class="size12 fontWeight500 white50">No. of Entries:</label>
                                <p class="size18 lightWhite ticketEntriesValue"><?= get_field('product_extra_details_total_ticket',$productId); ?></p>
                            </div>
                            <div class="ticketWatchValue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.6s'>
                                <label class="size12 fontWeight500 white50">Watch Value:</label>
                                <p class="size18 lightWhite ticketEntriesValue"><?= $currencySymbol; ?><?= get_field('product_extra_details_watch_value',$productId); ?></p>
                            </div>
                            <?php
                                if($drawDateText != '') {
                            ?>
                                <div class="ticketWatchValue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.8s'>
                                    <label class="size12 fontWeight500 white50">Draw date:</label>
                                    <p class="size18 lightWhite ticketEntriesValue drawDateText"><?= $drawDateText; ?></p>
                                </div>
                            <?php
                                } else {
                            ?>
                                <div class="ticketWatchValue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.8s'>
                                    <label class="size12 fontWeight500 white50">Draw date:</label>
                                    <p class="size18 lightWhite ticketEntriesValue"><?= $drawDate; ?></p>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="ticketBar">
                            <div class="barOuter lightBlueBg <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
                                <div style="width: <?= round($fill, 2); ?>%;" class="barInner primaryBlueBg"></div>
                            </div>
                            <div class="barTextContent">
                                <div class="ticketsPurchased <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.4s'>
                                    <p class="size16 lightWhite"><span class="purchasedTickets"><?= $orderedCount['quantity']; ?></span> / <span class="totalTickets"><?= $totalTicket; ?></span> Tickets Purchased</p>
                                    <p class="size16 lightWhite"><?= round($fill, 2); ?>% Sold</p>
                                </div>
                            </div>
                            <?php
                                if($showDate) {
                            ?>
                                 <div class="productCountdown">
                                    <div class="productCountdownWrap">
                                        <div class="productCountdownItem">
                                            <div class="productCountdownItemValue">
                                                <p class="size18 lightWhite countDownDates">00</p>
                                            </div>
                                            <p class="size12 white50">days</p>
                                        </div>
                                        <div class="productCountdownItem">
                                            <div class="productCountdownItemValue">
                                                <p class="size18 lightWhite countDownHours">00</p>
                                            </div>
                                            <p class="size12 white50">Hours</p>
                                        </div>
                                        <div class="productCountdownItem">
                                            <div class="productCountdownItemValue">
                                                <p class="size18 lightWhite countDownMins">00</p>
                                            </div>
                                            <p class="size12 white50">Mins</p>
                                        </div>
                                        <div class="productCountdownItem">
                                            <div class="productCountdownItemValue">
                                                <p class="size18 lightWhite countDownSecs">00</p>
                                            </div>
                                            <p class="size12 white50">Secs</p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                            <div class="productButtons">
                                <div class="btnWrap loadingBtnWrap <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay='0.2s'>
                                <?php
                                    if($status == true){
                                ?>
                                    <a href="<?= get_the_permalink($productId) ?>" class="btn bannerEnterNowBtn enterBtnLink">
                                        <span class='bannerEnterNowBtnValue'>Enter now</span>
                                    </a>
                                <?php
                                    }
                                ?>
                                    <form method="POST" id="productSubscribeFormDesktop" class="productSubscribeForm">
                                        <div class="formGroup">
                                            <div class="formRow">
                                                <div class="formInputWrap">
                                                    <input type="hidden" value="<?= get_the_title($productId); ?>" class="productName" name="productName" />
                                                    <input type="hidden" value="<?= get_the_permalink($productId); ?>" class="productLink" name="productLink" />
                                                    <input type="hidden" value="<?= $productId; ?>" class="productId" name="productId" />
                                                    <input required type="email" placeholder="Enter your email" class="email formInput" name="email" />
                                                </div>
                                                <div class="formButtomWrap loadingBtnWrap">
                                                    <button type="submit" class="btn btnPrimaryBlue submitBtn">Subscribe</button>
                                                    <span class="wpcf7-spinner"></span>
                                                </div>
                                            </div>
                                            <div class="formRow successMsg">
                                                <div class="formInputWrap">
                                                    <p>Thank you for contacting us.</p>
                                                </div>
                                                <div class="formButtomWrap">
                                                    <div class="ticIcon">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM10 18C5.59 18 2 14.41 2 10C2 5.59 5.59 2 10 2C14.41 2 18 5.59 18 10C18 14.41 14.41 18 10 18ZM13.88 6.29L8 12.17L6.12 10.29C5.73 9.9 5.1 9.9 4.71 10.29C4.32 10.68 4.32 11.31 4.71 11.7L7.3 14.29C7.69 14.68 8.32 14.68 8.71 14.29L15.3 7.7C15.69 7.31 15.69 6.68 15.3 6.29C14.91 5.9 14.27 5.9 13.88 6.29Z" fill="#002147"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="productDetailsOnlyMobile">
                            <div class="btnWrap <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.2s'>
                                <a class="moreDetailsBtn size18 lightWhite"><span class="linkLabel">More details</span><span class="dropDown down"></a>
                            </div>
                            <div class="productDetailsOnlyMobileWrap">
                                <div class="textContentText">
                                    <p class="size16 white50"><?= get_field('product_extra_details_description',$productId); ?></p>
                                </div>
                                <div class="productDetails">
                                    <div class="ticketPrice">
                                        <label class="size12 fontWeight500 white50">Ticket Price:</label>
                                        <p class="size18 lightWhite ticketPriceValue"><?= $currencySymbol; ?><?= number_format($price->get_price()); ?></p>
                                    </div>
                                    <div class="ticketEntries">
                                        <label class="size12 fontWeight500 white50">No. of Entries:</label>
                                        <p class="size18 lightWhite ticketEntriesValue"><?= $totalTicket; ?></p>
                                    </div>
                                    <div class="ticketWatchValue:">
                                        <label class="size12 fontWeight500 white50">Watch Value:</label>
                                        <p class="size18 lightWhite ticketEntriesValue"><?= $currencySymbol; ?><?= get_field('product_extra_details_watch_value',$productId); ?></p>
                                    </div>
                                    <?php
                                        if($drawDateText != '') {
                                    ?>
                                        <div class="ticketWatchValue">
                                            <label class="size12 fontWeight500 white50">Draw date:</label>
                                            <p class="size18 lightWhite ticketEntriesValue"><?= $drawDateText; ?></p>
                                        </div>
                                    <?php
                                        } else {
                                    ?>
                                        <div class="ticketWatchValue">
                                            <label class="size12 fontWeight500 white50">Draw date:</label>
                                            <p class="size18 lightWhite ticketEntriesValue"><?= $drawDate; ?></p>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <?php
                                    if($status == true){
                                ?>
                                    <a href="<?= get_the_permalink($productId) ?>" class="btn bannerEnterNowBtn enterBtnLink">Enter now</a>
                                <?php
                                    }
                                ?>
                                    <form method="POST" id="productSubscribeFormMobile" class="productSubscribeForm">
                                        <div class="formGroup">
                                            <div class="formRow">
                                                <div class="formInputWrap">
                                                    <input type="hidden" value="<?= get_the_title($productId); ?>" class="productName" name="productName" />
                                                    <input type="hidden" value="<?= get_the_permalink($productId); ?>" class="productLink" name="productLink" />
                                                    <input type="hidden" value="<?= $productId; ?>" class="productId" name="productId" />
                                                    <input required type="email" placeholder="Enter your email" class="email formInput" name="email" />
                                                </div>
                                                <div class="formButtomWrap loadingBtnWrap">
                                                    <button type="submit" class="btn btnPrimaryBlue submitBtn">Subscribe</button>
                                                    <span class="wpcf7-spinner"></span>
                                                </div>
                                            </div>
                                            <div class="formRow successMsg">
                                                <div class="formInputWrap">
                                                    <p>Thank you for contacting us.</p>
                                                </div>
                                                <div class="formButtomWrap">
                                                    <div class="ticIcon">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM10 18C5.59 18 2 14.41 2 10C2 5.59 5.59 2 10 2C14.41 2 18 5.59 18 10C18 14.41 14.41 18 10 18ZM13.88 6.29L8 12.17L6.12 10.29C5.73 9.9 5.1 9.9 4.71 10.29C4.32 10.68 4.32 11.31 4.71 11.7L7.3 14.29C7.69 14.68 8.32 14.68 8.71 14.29L15.3 7.7C15.69 7.31 15.69 6.68 15.3 6.29C14.91 5.9 14.27 5.9 13.88 6.29Z" fill="#002147"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="productGalleryWrap">
                    <div class="productGalleryInner">
                        <?php
                            if($showDate) {
                        ?>
                            <div class="productCountdown">
                                <div class="productCountdownWrap">
                                    <div class="productCountdownItem">
                                        <div class="productCountdownItemValue">
                                            <p class="size18 lightWhite countDownDates">00</p>
                                        </div>
                                        <p class="size12 white50">days</p>
                                    </div>
                                    <div class="productCountdownItem">
                                        <div class="productCountdownItemValue">
                                            <p class="size18 lightWhite countDownHours">00</p>
                                        </div>
                                        <p class="size12 white50">Hours</p>
                                    </div>
                                    <div class="productCountdownItem">
                                        <div class="productCountdownItemValue">
                                            <p class="size18 lightWhite countDownMins">00</p>
                                        </div>
                                        <p class="size12 white50">Mins</p>
                                    </div>
                                    <div class="productCountdownItem">
                                        <div class="productCountdownItemValue">
                                            <p class="size18 lightWhite countDownSecs">00</p>
                                        </div>
                                        <p class="size12 white50">Secs</p>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                        <div class="productGalleryTopSlider swiper-container">
                            <div class="swiper-wrapper">
                                <?php
                                    $galleryItems = get_field('product_extra_details_product_gallery',$productId);
                                    foreach($galleryItems as $items){
                                        if($items['product_extra_details_product_gallery_type'] == "image"){
                                ?>
                                    <div class="productGalleryTopItem swiper-slide">
                                        <div class="productGalleryTopItemImage">
                                            <div class="sizer"></div>
                                            <?= image_on_fly($items['product_extra_details_product_gallery_image'], array('1400', 'auto')); ?>
                                        </div>
                                    </div>
                                <?php
                                        }else{
                                ?>
                                    <div class="productGalleryTopItem swiper-slide">
                                        <div class="productGalleryTopItemImage">
                                            <div class="productGalleryTopItemVideo">
                                                <video muted="" autoplay="" loop="" playsinline="" webkit-playsinline="" id="video" data-mobile="true" preload="none"><source src="<?= $items['product_extra_details_product_gallery_video']; ?>" type="video/mp4"></video>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="productGalleryBottom">
                            <div class="productGalleryBottomLeft">
                                <div class="productGalleryBottomSlider  swiper-container">
                                    <div class="swiper-wrapper">
                                        <?php
                                            $galleryItems = get_field('product_extra_details_product_gallery',$productId);
                                            foreach($galleryItems as $items){
                                        ?>
                                        <div class="productGalleryBottomItem swiper-slide">
                                            <div class="productGalleryBottomItemImage">
                                                <div class="sizer"></div>
                                                <?= image_on_fly($items['product_extra_details_product_gallery_image'], array('128', 'auto')); ?>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="productGalleryBottomRight">
                                <div class="sliderNavWrap">
                                    <div class="prevBtn sliderNavBtn">
                                        <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.61342 2.82666L3.44009 8L8.61342 13.1733C9.13342 13.6933 9.13342 14.5333 8.61342 15.0533C8.09342 15.5733 7.25342 15.5733 6.73342 15.0533L0.613419 8.93333C0.0934187 8.41333 0.0934188 7.57333 0.613419 7.05333L6.73342 0.933331C7.25342 0.413331 8.09342 0.413331 8.61342 0.933331C9.12009 1.45333 9.13342 2.30666 8.61342 2.82666Z" fill="#F1F1F1"/>
                                        </svg>
                                    </div>
                                    <div class="nextBtn sliderNavBtn">
                                        <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.386582 13.1733L5.55992 8L0.386582 2.82667C-0.133418 2.30667 -0.133418 1.46667 0.386582 0.946671C0.906582 0.426671 1.74658 0.426671 2.26658 0.946671L8.38658 7.06667C8.90658 7.58667 8.90658 8.42667 8.38658 8.94667L2.26658 15.0667C1.74658 15.5867 0.906582 15.5867 0.386582 15.0667C-0.120085 14.5467 -0.133418 13.6933 0.386582 13.1733Z" fill="#F1F1F1"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="popup">
            <div class="popupContent">
                <span class="close">&times;</span>
                <div class="cEditor">
                    <?= get_sub_field('product_banner_popup_text') ?>
                </div>
            </div>
        </div>
    </div>
</div>