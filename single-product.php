<?php
get_header();
global $post;
$productName = $post->post_title;
$productId = $post->ID;
$endDate = date("Y-m-d H:i:s", strtotime(get_field('product_extra_details_draw_date',$productId)));
$dateDiff = dateDiff($endDate);
$_product = wc_get_product( $productId );
$regularPrice = $_product->get_regular_price();
$totalTickets = get_field('product_extra_details_total_ticket',$productId);
$orderedCount = getOrderCountForProduct($productId);
$availableTickets = $totalTickets - $orderedCount['quantity'];
$limit = getUserLimit($productId);
if($dateDiff > 0 && $availableTickets > 0 && base64_decode($limit) > 0){
?>
<div class="c">
    <div class="tabSection">
        <nav class="navTabMenu">
            <ul class="navTabList">
                <li class="navTabItem active" data-tab-target="#quantityTab">Quantity</li> <span>/</span>
                <li class="navTabItem" data-tab-target="#checkoutTab">Postal entry (no obligation to buy)</li>
            </ul>
        </nav>
        <div class="navTabContent active" id="quantityTab">
            <div class="tabSectionWrap whiteBg">
                <div class="tabSectionHeader">
                    <div class="tabSectionHeaderList">
                        <div class="tabSectionHeaderListItem active">
                            <p class="size16 primaryBlue tabSectionHeaderTitle">Quantity</p>
                            <div class="bottomBorder primaryBlueBg"></div>
                        </div>
                        <div class="tabSectionHeaderListItem deActive">
                            <p class="size16 primaryBlue tabSectionHeaderTitle">Checkout</p>
                            <div class="bottomBorder primaryBlueBg"></div>
                        </div>
                    </div>
                    <p class="size36 primaryBlue <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>"  data-wow-delay='0.2s'>Select your tickets</p>
                </div>
                <div class="tabSectionBody">
                    <div class="quantityTab">
                        <div class="quantityTabWrap">
                            <div class="ticketsWrap">
                                <?php
                                $currencySymbol = (get_woocommerce_currency_symbol()) ? get_woocommerce_currency_symbol() : "£";
                                $billAmount = 0;
                                $getOfferRule = get_field('product_extra_details_offer_rule',$productId);
                                if ($getOfferRule) {
                                    $sum = 0;
                                    $resultArray = array();
                                    foreach ($getOfferRule as $key => $rule) {
                                        $quantity = $rule['product_extra_details_offer_rule_quantity'];
                                        if ($quantity <= $availableTickets) {
                                            $resultArray[$key] = $rule;
                                            $sum += $quantity;
                                            if ($sum >= $availableTickets) {
                                                break;
                                            }
                                        } else {
                                            break;
                                        }
                                    }
                                    $value = [];
                                    $tempValue = [];
                                    $i = 1;
                                    $sec = 0.2;
                                    foreach ($resultArray as $k=>$ticketItem) {
                                        if($ticketItem['product_extra_details_offer_rule_quantity'] == 1)
                                            $ticketItem['product_extra_details_offer_rule_title'] = $ticketItem['product_extra_details_offer_rule_quantity']." Ticket";
                                        else
                                            $ticketItem['product_extra_details_offer_rule_title'] = $ticketItem['product_extra_details_offer_rule_quantity']." Tickets";

                                        $value['ticket'.$i] = $ticketItem['product_extra_details_offer_rule_quantity'];
                                        $price = $regularPrice * $ticketItem['product_extra_details_offer_rule_quantity'];
                                        if ($ticketItem['product_extra_details_offer_rule_discount'] > 0) {
                                            $discountAmount = ($price / 100) * $ticketItem['product_extra_details_offer_rule_discount'];
                                            $price = $price - $discountAmount;
                                        }
                                        if($i == 1){
                                            $billAmount = $price;
                                        }

                                        $odds = 0;
                                        if($totalTickets){
                                            $odds = round($totalTickets / $ticketItem['product_extra_details_offer_rule_quantity']);
                                        }

                                        $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['quantity'] = $ticketItem['product_extra_details_offer_rule_quantity'];
                                        $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['price'] = $price;
                                        $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['discount'] = $ticketItem['product_extra_details_offer_rule_discount'];
                                        $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['odds'] = "1:".round($odds,3);

                                        $status = true;

                                        if(base64_decode($limit) < $ticketItem['product_extra_details_offer_rule_quantity']){
                                            $status = false;
                                        }
                                ?>
                                        <label class="<?= (!$status) ? 'ticketItemNotAllowed' : ''; ?> ticketItem ticketItem<?= $ticketItem['product_extra_details_offer_rule_quantity']; ?> <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec; ?>s">
                                            <input <?= ($i == 1) ? 'checked' : ''; ?> type="radio" name="ticket" data-odds="1:<?= $odds; ?>" data-value="<?= $currencySymbol.$price; ?>" value="<?= $ticketItem['product_extra_details_offer_rule_quantity']; ?>" class="ticketCheckbox" id="ticketCheckbox<?= $ticketItem['product_extra_details_offer_rule_quantity']; ?>" />
                                            <div class="ticketItemWrap cardTextContentEqualHeight">
                                                <div class="ticketItemRow">
                                                    <div class="ticketItemColumn">
                                                        <p class="size16 primaryBlue ticketItemTitle"><?= $ticketItem['product_extra_details_offer_rule_title']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="ticketItemRow">
                                                    <div class="ticketItemColumn">
                                                        <p class="size22 primaryBlue ticketItemPrice"><?= $currencySymbol . $price; ?></p>
                                                    </div>
                                                    <?php
                                                    if ($ticketItem['product_extra_details_offer_rule_discount'] > 0) {
                                                    ?>
                                                        <div class="ticketItemColumn">
                                                            <p class="size16 green ticketItemDiscount">-<?= $ticketItem['product_extra_details_offer_rule_discount']; ?>%</p>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </label>
                                <?php
                                        $i++;
                                        $sec = $sec + 0.2;
                                    }

                                    $reArrangeTickets = array();
                                    $discountPertage = 0;
                                    for ($i = 1; $i <= $availableTickets; $i++) {
                                        
                                        if (isset($tempValue['ticket'.$i])) {
                                            $reArrangeTickets['ticket'.$i]['quantity'] = $tempValue['ticket'.$i]['quantity'];
                                            $reArrangeTickets['ticket'.$i]['price'] = $tempValue['ticket'.$i]['price'];
                                            $reArrangeTickets['ticket'.$i]['discount'] = $tempValue['ticket'.$i]['discount'];
                                            $reArrangeTickets['ticket'.$i]['odds'] = $tempValue['ticket'.$i]['odds'];
                                            if($tempValue['ticket'.$i]['discount'] > 0){
                                                $discountPertage = $tempValue['ticket'.$i]['discount'];
                                            }
                                        }else{
                                            $reArrangeTickets['ticket'.$i]['quantity'] = $i;
                                            $price = $regularPrice * $i;
                                            if ($discountPertage > 0) {
                                                $discountAmount = ($price / 100) * $discountPertage;
                                                $price = $price - $discountAmount;
                                            }
                                            $reArrangeTickets['ticket'.$i]['price'] = $price;
                                            $reArrangeTickets['ticket'.$i]['discount'] = $discountPertage;
                                            $odds = 0;
                                            if($totalTickets){
                                                $odds = $totalTickets / $i;
                                            }
                                            $reArrangeTickets['ticket'.$i]['odds'] = "1:".round($odds, 3);
                                        }
                                    }

                                    $jsonData = json_encode($reArrangeTickets);
                                }
                                ?>
                            </div>
                            <div class="buttonsWrap">
                                <div data-wow-delay='0.2s' class="counterButton <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-limit="<?= $limit; ?>" data-json='<?php echo htmlspecialchars($jsonData, ENT_QUOTES, 'UTF-8'); ?>'>
                                    <a class="counterButtonDecrement" data-item="ticket1">
                                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.5 10C5.5 10.55 5.95 11 6.5 11H14.5C15.05 11 15.5 10.55 15.5 10C15.5 9.45 15.05 9 14.5 9H6.5C5.95 9 5.5 9.45 5.5 10ZM10.5 0C4.98 0 0.5 4.48 0.5 10C0.5 15.52 4.98 20 10.5 20C16.02 20 20.5 15.52 20.5 10C20.5 4.48 16.02 0 10.5 0ZM10.5 18C6.09 18 2.5 14.41 2.5 10C2.5 5.59 6.09 2 10.5 2C14.91 2 18.5 5.59 18.5 10C18.5 14.41 14.91 18 10.5 18Z" fill="#002147" />
                                        </svg>
                                    </a>
                                    <input type="hidden" data-available-tickets="<?= $availableTickets ?>" value="1" class="itemQuantity counterButtonValue input-text qty text" name="quantity" aria-label="Product quantity" />
                                    <p class="size18 primaryBlue counterButtonLabel">1 Ticket</p>
                                    <a class="counterButtonIncrement" data-item="ticket2">
                                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.5 7C11.95 7 11.5 7.45 11.5 8V11H8.5C7.95 11 7.5 11.45 7.5 12C7.5 12.55 7.95 13 8.5 13H11.5V16C11.5 16.55 11.95 17 12.5 17C13.05 17 13.5 16.55 13.5 16V13H16.5C17.05 13 17.5 12.55 17.5 12C17.5 11.45 17.05 11 16.5 11H13.5V8C13.5 7.45 13.05 7 12.5 7ZM12.5 2C6.98 2 2.5 6.48 2.5 12C2.5 17.52 6.98 22 12.5 22C18.02 22 22.5 17.52 22.5 12C22.5 6.48 18.02 2 12.5 2ZM12.5 20C8.09 20 4.5 16.41 4.5 12C4.5 7.59 8.09 4 12.5 4C16.91 4 20.5 7.59 20.5 12C20.5 16.41 16.91 20 12.5 20Z" fill="#002147" />
                                        </svg>
                                    </a>
                                </div>
                                <div data-wow-delay='0.4s' class="bottomSection <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>">
                                    <div class="bottomSectionRow">
                                        <div class="bottomSectionColumn">
                                            <p class="bottomSectionColumnTitle size12 oddsLabel">Odds:</p>
                                            <p class="bottomSectionColumnValue size18 primaryBlue oddsValue"><span class='oddsLeftValue'></span><span class='oddsRightValue'>0</span></p>
                                        </div>
                                        <div class="bottomSectionColumn">
                                            <p class="bottomSectionColumnTitle size12 billLabel">Total:</p>
                                            <p class="bottomSectionColumnValue size18 primaryBlue billAmount"><?= $currencySymbol.$billAmount.".00"; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div data-wow-delay='0.6s' class="btnWrap <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>">
                                    <button data-product-id="<?= $productId; ?>" data-check="<?= get_current_user_id(); ?>" name="add-to-cart" class="btn btnPrimaryBlue addToCartBtn" href="<?= $btnLink; ?>">Continue</button>
                                    <span class="wpcf7-spinner"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navTabContent" id="checkoutTab">
            <div class="tabSectionWrap whiteBg">
                <div class="cEditor">
                    <?= get_field('postal_entry_details') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    $homePage = get_site_url();
?>
    <script>
        window.location.href = "<?= $homePage; ?>?msg=Limit reached";
    </script>
<?php
}
get_footer();