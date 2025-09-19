<div class="section pastWinners setWhite" data-id="<?= $wid; ?>">
    <div class="c">
        <div class="spastWinnersWrap lightBlueBg sidebarWithAccordionWrap">
            <div class="pastWinnersHeader sidebarWithAccordionHeader">
                <?php
                    $titleTag = get_sub_field('past_winners_title_tag');
                    echo "<$titleTag class='size45 fontWeight400 primaryBlack pastWinnersHeaderTitle sidebarWithAccordionHeaderTitle center'>" . get_sub_field('past_winners_title') . "</$titleTag>";
                ?>
            </div>
            <div class="pastWinnersBody">
                <div class="pastWinnersCardList colRow colRowWrap rowGap24 cardColRow">
                    <?php
                        global $wpdb;
                        $tableName = $wpdb->prefix . "winner_list";
                        $usersTable = $wpdb->users;
                        $productsTable = $wpdb->prefix . "posts";
                        $msgStatus = false;

                        $resultTable = $wpdb->prepare("
                            SELECT wl.*, u.ID as user_id, u.user_login, p.ID as product_id, p.post_title as product_title
                            FROM $tableName as wl
                            LEFT JOIN $usersTable as u ON wl.winnerId = u.ID
                            LEFT JOIN $productsTable as p ON wl.productId = p.ID
                            WHERE p.post_status = 'publish'
                            ORDER BY wl.created_at DESC
                        ");

                        $results = $wpdb->get_results($resultTable, ARRAY_A);
                        if($results){
                            $sec = 0.2;
                            foreach($results as  $k=>$item){
                                $productId = $item['productId'];
                                $endDate = date("Y-m-d H:i:s", strtotime(get_field('product_extra_details_draw_date',$productId)));
                                $winner_id = get_post_meta( $productId, 'winner_id', true );
                                $winner_order_id = get_post_meta( $productId, 'winner_order_id', true );
                                $liveDrawLink = get_post_meta( $productId, 'winner_live_draw_link', true );
                                $certificateLink = get_post_meta( $productId, 'winner_certificate_link', true );
                                $dateDiff = dateDiff($endDate);
                                if(($dateDiff == 0) && ($winner_id != "" && $winner_order_id !="")){
                                    $order_obj = wc_get_order( $winner_order_id );
                                    if($order_obj){
                                    $name = $order_obj->get_billing_first_name()." ".$order_obj->get_billing_last_name();
                                    $photoList = get_field('photo_gallery_image_list', 'user_' . $winner_id);
                                    $drawDate = date("d.m.y", strtotime($endDate));
                                    //$donationValue = $item['donationValue'];
                                    $donationValue = get_post_meta( $productId, 'winner_donation_value', true )
                    ?>
                    <div class="pastWinnersCardListItem pastWinnersCardListItem<?= $k; ?> col50 cardCol <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec; ?>s">
                        <div class="pastWinnersCardListItemWrap padding32 whiteBg cardRadius24 colRow">
                            <div class="imageDiv">
                                <div id="imageSlider<?= $k; ?>" class="imageSlider imageSlider<?= $k; ?> swiper-container">
                                    <div class="swiper-wrapper imageSliders">
                                        <?php
                                            if($photoList){
                                                foreach($photoList as $pItem){
                                        ?>
                                                    <div class="imageSliderItem swiper-slide">
                                                        <div class="sliderImage">
                                                            <div class="sizer"></div>
                                                            <?= image_on_fly($pItem['photo_gallery_image_list_image'], array('400', 'auto')); ?>
                                                        </div>
                                                    </div>
                                        <?php
                                                }
                                            }else{
                                                $profileImageUrl = ($profileImageUrl) ? $profileImageUrl : get_bloginfo('template_directory')."/assets/images/user.png"; 
                                        ?>
                                                <div class="imageSliderItem swiper-slide">
                                                    <div class="sliderImage">
                                                        <div class="sizer"></div>
                                                        <div class="bsz">
                                                            <div class="bgimage" style="background-image: url(<?= $profileImageUrl; ?>)"></div>
                                                            <img width="auto" height="auto" loading="lazy" src="<?= $profileImageUrl; ?>" alt="aiony-haust-3TLl_97HNJo-unsplash-1-400x457" title="aiony-haust-3TLl_97HNJo-unsplash-1-400x457">
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="pagination"></div>
                                </div>
                            </div>
                            <div class="textDiv">
                                <div class="textDivHeader">
                                    <p class="size12 primaryBlack50">Competition <?= $productId; ?></p>
                                    <p class="size24 primaryBlue"><?= get_the_title($productId) ?></p>
                                </div>
                                <div class="textDivBody">
                                    <div class="colRow">
                                        <div class="colDiv">
                                            <p class="size12 primaryBlack50">Won by:</p>
                                            <p class="size18 primaryBlue"><?= $name; ?></p>
                                        </div>
                                        <?php
                                            if($donationValue != 0){
                                        ?>
                                        <div class="colDiv">
                                            <p class="size12 primaryBlack50">Donation</p>
                                            <p class="size18 primaryBlue"><?= $donationValue; ?></p>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <div class="colDiv">
                                            <p class="size12 primaryBlack50">Live Draw:</p>
                                            <p class="size18 primaryBlue"><?= $drawDate; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="textDivFooter">
                                    <div class="buttonWrap">
                                        <a data-live-draw-link="<?= $liveDrawLink; ?>" class="btn btnPrimaryBlue popUpBtn">Live draw</a>
                                        <a target="_black" href="<?= $certificateLink; ?>" class="size16 bannerLink primaryBlue"><span class="linkLabel aTagHover">Draw certificate</span><span class="dropDown right"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                                    }else{
                                        $msgStatus = true;
                                    }
                                }else{
                                    $msgStatus = true;
                                }
                                $sec = $sec + 0.2;
                            }
                        }else{
                            $msgStatus = true;
                        }

                        if($msgStatus){
                    ?>
                        <div class="btnWrap <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="0.2s">
                            <a href="<?= get_permalink(getActiveProductId()); ?>" class='btn btnPrimaryBlue btnWithArrow'><span>Enter now to be our <b>first</b> winner</span><span class='dropDown right'></span></a>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div id="popup" class="instaPopup">
        <div class="popupContent">
            <p class="close"><span class='closeText'>Close</span> &times;</p>
            <div class="videoDiv">
                <iframe id="instaVideoLink" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" 
                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>