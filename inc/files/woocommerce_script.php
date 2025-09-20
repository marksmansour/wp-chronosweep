<?php
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

add_action( 'admin_enqueue_scripts', 'load_admin_style' );
function load_admin_style() {
    wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/assets/css/admin_panel.css', false, '1.5' );
    wp_enqueue_style( 'toast_css', get_template_directory_uri() . '/assets/css/jquery.toast.min.css', false, '1.1' );
    wp_enqueue_script( 'product.popup-jquery', get_template_directory_uri() . '/assets/js/utils/product.popup.js', array( 'jquery' ), '1.1', true );
    wp_enqueue_script( 'toast-jquery', get_template_directory_uri() . '/assets/js/libs/jquery.toast.min.js', array( 'jquery' ), '1.1', true );
    wp_enqueue_script( 'ajaxq-jquery', get_template_directory_uri() . '/assets/js/libs/jquery.ajaxq.js', array( 'jquery' ), '1.1', true );
    wp_enqueue_script( 'custom-admin-jquery', get_template_directory_uri() . '/assets/js/admin_script.js', array( 'jquery' ), '1.6', true );
}

function getOrderCountForProduct($productId) {
	$productId = (int) $productId;
	$cache_key = 'cs_order_counts_' . $productId;
	$cached = wp_cache_get($cache_key, 'cs');
	if ($cached !== false) return $cached;

	global $wpdb;
	$opl = $wpdb->prefix . 'wc_order_product_lookup';
	$os  = $wpdb->prefix . 'wc_order_stats';

	$opl_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $opl));
	$os_exists  = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $os));

	if ($opl_exists && $os_exists) {
		$statuses = ['wc-completed','wc-processing'];
		$placeholders = implode(',', array_fill(0, count($statuses), '%s'));
		$sql = $wpdb->prepare("
			SELECT COUNT(DISTINCT os.order_id) AS orders, COALESCE(SUM(opl.product_qty),0) AS qty
			FROM {$opl} AS opl
			INNER JOIN {$os} AS os ON os.order_id = opl.order_id
			WHERE opl.product_id = %d
			  AND os.status IN ($placeholders)
		", array_merge([$productId], $statuses));
		$row = $wpdb->get_row($sql, ARRAY_A);
		$result = ['count' => (int) $row['orders'], 'quantity' => (int) $row['qty']];
	} else {
		// Fallback (old Woo): still works, but slower
		$result = ['count' => 0, 'quantity' => 0];
		if (function_exists('wc_get_orders')) {
			$orders = wc_get_orders([
				'limit'  => -1,
				'status' => ['wc-completed','wc-processing'],
				'return' => 'objects',
			]);
			$ordersCount = 0; $qty = 0;
			foreach ($orders as $order) {
				$has = false;
				foreach ($order->get_items() as $item) {
					if ((int) $item->get_product_id() === $productId) {
						$qty += (int) $item->get_quantity();
						$has = true;
					}
				}
				if ($has) $ordersCount++;
			}
			$result = ['count' => $ordersCount, 'quantity' => $qty];
		}
	}

	// short cache; invalidated on order status change below
	wp_cache_set($cache_key, $result, 'cs', 60);
	return $result;
}

function getUserLimit($productId, $userId = 0){
	$productId = (int) $productId;
	$adminLimit = (int) get_field('product_extra_details_maximum_tickets_a_user_can_purchase', $productId);
	if ($adminLimit <= 0) return base64_encode(0);

	if (!$userId && is_user_logged_in()) {
		$userId = get_current_user_id();
	}
	if (!$userId) return base64_encode($adminLimit);

	$cache_key = 'cs_user_qty_' . $productId . '_' . (int)$userId;
	$cached = wp_cache_get($cache_key, 'cs');
	if ($cached !== false) return base64_encode(max(0, $adminLimit - (int)$cached));

	global $wpdb;
	$opl = $wpdb->prefix . 'wc_order_product_lookup';
	$os  = $wpdb->prefix . 'wc_order_stats';

	$opl_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $opl));
	$os_exists  = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $os));

	$userQty = 0;

	if ($opl_exists && $os_exists) {
		$statuses = ['wc-completed','wc-processing'];
		$placeholders = implode(',', array_fill(0, count($statuses), '%s'));
		$sql = $wpdb->prepare("
			SELECT COALESCE(SUM(opl.product_qty),0) AS qty
			FROM {$opl} AS opl
			INNER JOIN {$os} AS os ON os.order_id = opl.order_id
			WHERE opl.product_id = %d
			  AND os.customer_id = %d
			  AND os.status IN ($placeholders)
		", array_merge([$productId, (int)$userId], $statuses));
		$userQty = (int) $wpdb->get_var($sql);
	} else {
		if (function_exists('wc_get_orders')) {
			$orders = wc_get_orders([
				'limit'       => -1,
				'status'      => ['wc-completed','wc-processing'],
				'customer_id' => (int)$userId,
				'return'      => 'objects',
			]);
			foreach ($orders as $order) {
				foreach ($order->get_items() as $item) {
					if ((int) $item->get_product_id() === $productId) {
						$userQty += (int) $item->get_quantity();
					}
				}
			}
		}
	}

	wp_cache_set($cache_key, $userQty, 'cs', 60);
	return base64_encode(max(0, $adminLimit - $userQty));
}

function getCartCount(){
    if (isset($_COOKIE['woocommerce_items_in_cart'])) return (int) $_COOKIE['woocommerce_items_in_cart'];
    if (function_exists('is_cart') && (is_cart() || is_checkout())) {
        if (function_exists('WC') && WC()->cart) return WC()->cart->get_cart_contents_count();
    }
    return 0;
}

// Add to cart action
add_action('woocommerce_add_to_cart', 'custom_add_to_cart_action', 10, 6);
function custom_add_to_cart_action($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
{
    $_SESSION['wc_notice'] = 'Product has been added to the cart.';
}

//addToCartManually
add_action('rest_api_init', function () {
    register_rest_route("v1", '/addToCartManually', array(
        'methods'  => 'POST',
        'callback' => 'addToCartManually',
        'args' => array(
            'productId' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return $param;
                }
            ),
        ),
    ));
});

function addToCartManually($request)
{
    // Load cart functions which are loaded only on the front-end.
    include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
    include_once WC_ABSPATH . 'includes/class-wc-cart.php';


    $getCount = getOrderCountForProduct($request['productId']);
    $totalTicket = get_field('product_extra_details_total_ticket',$request['productId']);

    $availableTickets =  ($totalTicket - $getCount['quantity']);

    if($availableTickets < 0){
        $availableTickets = 0;
    }

    $limit = getUserLimit($request['productId'], $request['userId']);

    $limit = base64_decode($limit);

    if($limit >= $request['itemQuantity']){
        if($availableTickets >= $request['itemQuantity']){
            if (is_null(WC()->cart)) {
                wc_load_cart();
            }
            WC()->cart->get_cart_from_session();

            $ret['status'] = false;
            $productId = $request['productId'];
            $itemQuantity = $request['itemQuantity'];
            if ($productId) {
                WC()->cart->empty_cart();
                $isAdded = WC()->cart->add_to_cart($productId,$itemQuantity);
                if ($isAdded) {
                    $ret['status'] = true;
                    $ret['message'] = "Product added to cart. Go to cart page to see products available in the cart";
                }
            }else{
                $ret['message'] = "Failed to add to cart. Please try again.";
            }
            $ret['cartCount'] = getCartCount();
            print_r(json_encode($ret));
        }else{
            $ret['status'] = false;
            $ret['availableTickets'] = $availableTickets;
            if($availableTickets == 0){
                $ret['message'] = " - Tickets is not available";
            }else{
                $ret['message'] = "Only tickets available in ".$availableTickets;
            }
            print_r(json_encode($ret));
        }
    }else{
        $ret['status'] = false;
        $ret['message'] = "Only tickets allowed in ".$limit;
        print_r(json_encode($ret));
    }
}

function getAllTicketsQuantityJson($productId){
    $getOfferRule = get_field('product_extra_details_offer_rule',$productId);
    $totalTickets = get_field('product_extra_details_total_ticket',$productId);
    $orderedCount = getOrderCountForProduct($productId);
    $availableTickets = $totalTickets - $orderedCount['quantity'];
    $_product = wc_get_product( $productId );
    $regularPrice = $_product->get_regular_price();

    $value = [];
    $reArrangeTickets = [];
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
        foreach ($resultArray as $k=>$ticketItem) {
            $value['ticket'.$i] = $ticketItem['product_extra_details_offer_rule_quantity'];
            $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['quantity'] = $ticketItem['product_extra_details_offer_rule_quantity'];
            $price = $regularPrice * $ticketItem['product_extra_details_offer_rule_quantity'];
            if ($ticketItem['product_extra_details_offer_rule_discount'] > 0) {
                $discountAmount = ($price / 100) * $ticketItem['product_extra_details_offer_rule_discount'];
                $price = $price - $discountAmount;
            }
            $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['price'] = $price;
            $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['discount'] = $ticketItem['product_extra_details_offer_rule_discount'];
            $odds = 0;
            if($totalTickets){
                $odds = $totalTickets / $ticketItem['product_extra_details_offer_rule_quantity'];
            }
            $tempValue['ticket'.$ticketItem['product_extra_details_offer_rule_quantity']]['odds'] = "1:".round($odds,3);
            $i++;
        }

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

    }

    return json_encode($reArrangeTickets);
}

function getTicketPrice($quantity){
    $price = 0;
    $arg =  array(
        'post_type' => "tickets",
        'post_status' => 'publish',
    );
    $query = new WP_Query($arg);
    if (count($query->posts) > 0) {
        foreach ($query->posts as $k=>$ticketItem) {
            $postId = $ticketItem->ID;
            if(get_field('tickets_details_count',$postId) == $quantity){
                $price = get_field('tickets_details_price',$postId);
            }
        }
    }
    return $price;
}

// Add to Cart Notification
function add_to_cart_notification() {
    ?>
    <script>
    jQuery(document).ready(function($){
        if(jQuery('.single-product')[0]){
            $('body').on('added_to_cart', function(event, fragments, cart_hash, button) {
                productPopup.showToast('Product added to cart!', 'success');
            });

            $(document.body).on('updated_cart_totals', function(event) {
                productPopup.showToast('Cart updated.', 'success');
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'add_to_cart_notification');


add_action('wp', function () {
	if ( function_exists('is_cart') && ( is_cart() || is_checkout() ) ) {
		add_action('woocommerce_cart_calculate_fees', 'apply_quantity_based_discount');
		add_action('woocommerce_after_cart_item_quantity_update', 'check_custom_field_on_cart_quantity_change', 10, 4);
		add_action('woocommerce_before_calculate_totals', 'check_product_expiry_in_cart');
	}
}, 9);

function apply_quantity_based_discount() {

    global $woocommerce;

    if ($woocommerce->cart->has_discount()) {
        return; // Exit the function if a coupon is applied
    }

    $discount_total = 0;
    $prevDiscount = null;

    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        $quantity = $cart_item['quantity'];
        $productId = $cart_item['product_id'];
        $discount = 0;

        $getOfferRule = get_field('product_extra_details_offer_rule', $productId);

        foreach ($getOfferRule as $k => $ticketItem) {
            if ($ticketItem['product_extra_details_offer_rule_quantity'] == $quantity) {
                $discount = $ticketItem['product_extra_details_offer_rule_discount'];
                break;
            }else{
                if ($ticketItem['product_extra_details_offer_rule_quantity'] < $quantity) {
                    $discount = $ticketItem['product_extra_details_offer_rule_discount'];
                }
            }
        }

        // Calculate discount for each item
        if ($discount > 0) {
            $product_price = $cart_item['data']->get_price();
            $discount_amount = ($product_price / 100) * $discount;
            $discount_total += $discount_amount * $quantity;
        }
    }

    // Apply the discount as a fee
    if ($discount_total > 0) {
        $woocommerce->cart->add_fee(__('Quantity Discount', 'your-text-domain'), -$discount_total);
    }
}


add_action('woocommerce_after_cart_item_quantity_update', 'check_custom_field_on_cart_quantity_change', 10, 4);

function check_custom_field_on_cart_quantity_change($cart_item_key, $quantity, $old_quantity, $cart) {
    // Get the cart item
    $cart_item = $cart->get_cart_item($cart_item_key);

    // Get the product ID
    $product_id = $cart_item['product_id'];
    $productName = get_the_title($product_id);
    $getCount = getOrderCountForProduct($product_id);
    $totalTicket = get_field('product_extra_details_total_ticket',$product_id);
    $endDate = date("Y-m-d H:i:s", strtotime(get_field('product_extra_details_draw_date',$product_id)));
    $dateDiff = dateDiff($endDate);
    if($dateDiff == 1){
        $availableTickets =  ($totalTicket) ? ($totalTicket - $getCount['quantity']) : 0;
        // Perform your checks based on the custom field value and the new quantity
        if ($availableTickets == 0 || $availableTickets == '') {
            wc_add_notice(__($productName.' - Tickets is not available'), 'error');
            $cart_item['quantity'] = 0; // Revert to the old quantity
            $cart->cart_contents[$cart_item_key] = $cart_item; // Update cart contents with old quantity
            $cart->set_session(); // Save cart session
            $cart->calculate_totals(); // Recalculate cart totals
            return false;
        }else if($availableTickets < $quantity){
            wc_add_notice(__($productName.' - Only tickets available in : '.$availableTickets), 'error');
            $cart_item['quantity'] = $old_quantity; // Revert to the old quantity
            $cart->cart_contents[$cart_item_key] = $cart_item; // Update cart contents with old quantity
            $cart->set_session(); // Save cart session
            $cart->calculate_totals(); // Recalculate cart totals
            return false;
        }
    }else{
        wc_add_notice(__($productName.' - Tickets is not available'), 'error');
        $cart_item['quantity'] = 0; // Revert to the old quantity
        $cart->cart_contents[$cart_item_key] = $cart_item; // Update cart contents with old quantity
        $cart->set_session(); // Save cart session
        $cart->calculate_totals(); // Recalculate cart totals
        return false;
    }
}

add_action('woocommerce_before_calculate_totals', 'check_product_expiry_in_cart');

function check_product_expiry_in_cart($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Iterate through each cart item
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        // Get the product ID
        $product_id = $cart_item['product_id'];

        $endDate = date("Y-m-d H:i:s", strtotime(get_field('product_extra_details_draw_date',$product_id)));
        $dateDiff = dateDiff($endDate);
        if($dateDiff != 1){
            $cart->remove_cart_item($cart_item_key);
            wc_add_notice(__('One or more products in your cart have expired and have been removed.', 'woocommerce'), 'error');
        }
    }

    if(WC()->cart->is_empty()){

    }
}

function add_empty_cart_body_class( $classes ) {
    if ( WC()->cart->is_empty() ) {
        $classes[] = 'empty-cart';
    }
    return $classes;
}
add_filter( 'body_class', 'add_empty_cart_body_class' );

add_filter('woocommerce_checkout_fields', 'custom_terms_and_conditions_label');

function custom_terms_and_conditions_label($fields) {
        $fields['terms']['terms'] = str_replace('By clicking checkbox you confirm that you are 18+ and agree to our T&Cs and Privacy Policy. Look out for your confirmation email and remember to check your junk folder if not received.', 'Your New Text Here', $fields['terms']['terms']);
        return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'email_special_offer' );
function email_special_offer( $fields ){
    $fields['account']['email_special_offer'] = array(
        'type'      => 'checkbox',
        'label'     => sprintf(__('<span>Email me with competition updates and special offers! Unsubscribe at any time</span>', 'woocommerce')),
        'required'  => false,
        'class'     => array('woocommerce-form__input', 'woocommerce-form__input-checkbox', 'input-checkbox'),
        'clear'     => true,
    );
    return $fields;
}

add_action( 'woocommerce_checkout_update_customer', 'save_checkout_account_birthday_field', 10, 2 );
function save_checkout_account_birthday_field( $customer, $data ){
    if ( isset($_POST['user_dob']) && ! empty($_POST['user_dob']) ) {
         $customer->update_meta_data( 'user_dob', sanitize_text_field($_POST['user_dob']) );
    }
}


remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
//add_action( 'woocommerce_review_order_after_order_total', 'woocommerce_checkout_coupon_form', 10 );

// Add an extra class to the field container
add_filter( 'woocommerce_form_field_args', 'custom_form_field_args', 10, 3 );

function custom_form_field_args( $args, $key, $value ) {

    if($args['type'] == "checkbox")
        $args['class'][] = 'customCheckBoxStyle';
    else
        $args['class'][] = 'customInputStyle';

    if ($key && $value ) {
        $args['class'][] = 'hasInput';
    }
    return $args;
}

add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

function custom_override_default_address_fields( $address_fields ) {
    $address_fields['address_2']['placeholder'] = '';
    $address_fields['address_1']['placeholder'] = '';
    return $address_fields;
}

add_filter( 'woocommerce_checkout_fields', 'remove_password_placeholder_checkout', 10, 1 );
add_filter( 'woocommerce_reset_password_validation_errors', 'remove_password_placeholder_reset', 10, 1 );

function remove_password_placeholder_checkout( $fields ) {
    // Remove placeholder from password field in checkout
    $fields['account']['account_password']['placeholder'] = '';
    return $fields;
}

function remove_password_placeholder_reset( $errors ) {
    // Remove placeholder from password field in password reset form
    $errors->remove( 'password_reset_empty_password' );
    return $errors;
}


add_filter( 'woocommerce_form_field_args', 'remove_screen_reader_text_class', 10, 3 );

function remove_screen_reader_text_class( $args, $key, $value ) {
    // Check if the 'label_class' key exists and if it contains the 'screen-reader-text' class
    if ( isset( $args['label_class'] ) ) {
        // If $args['label_class'] is an array, convert it to a string
        if ( is_array( $args['label_class'] ) ) {
            $args['label_class'] = implode( ' ', $args['label_class'] );
        }

        // Check if the label class contains the 'screen-reader-text' class
        if ( strpos( $args['label_class'], 'screen-reader-text' ) !== false ) {
            // Remove the 'screen-reader-text' class from the label_class
            $args['label_class'] = str_replace( 'screen-reader-text', '', $args['label_class'] );
        }
    }
    return $args;
}


add_action( 'woocommerce_form_field_text','reigel_custom_heading', 10, 2 );
function reigel_custom_heading( $field, $key ){
    // will only execute if the field is billing_company and we are on the checkout page...
    if ( is_checkout() && ( $key == 'billing_postcode') ) {
        $field .= '<p class="form-row form-row-wide customTitleStyle">Contact</p>';
    }else if( is_checkout() && ( $key == 'billing_last_name') ){
        $field .= '<p class="form-row form-row-wide customTitleStyle">Address</p>';
    }else if (is_checkout() & $key === 'billing_first_name') {
        $a = '<p class="form-row form-row-wide customTitleStyle">Name</p>';
        return $a . $field;
      }
    return $field;
}

// Function to process the custom coupon application
add_action('wp_ajax_apply_custom_coupon', 'apply_custom_coupon');
add_action('wp_ajax_nopriv_apply_custom_coupon', 'apply_custom_coupon');

function apply_custom_coupon() {
    $coupon_code = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : '';

    if (empty($coupon_code)) {
        wc_add_notice(__('Please enter a coupon code.', 'woocommerce'), 'error');
        wp_die();
    }else{
        // Check if any coupons are already applied
        if (WC()->cart->has_discount()) {
            wc_add_notice(__('Only one coupon can be applied at a time.', 'woocommerce'), 'error');
            wp_die();
        }
        
        if(strtolower(get_user_meta(get_current_user_id(), 'referral_coupon', true)) == strtolower($coupon_code)){
            wc_add_notice(__('Invalid coupon code.', 'woocommerce'), 'error');
            wp_die();
        }else if (has_user_used_coupon($coupon_code, get_current_user_id())) {
            wc_add_notice(__('Already used this coupon code.', 'woocommerce'), 'error');
            wp_die();
        }else{
            $upperCC = strtoupper($coupon_code);
            if(get_user_meta(get_current_user_id(), 'referral_coupon', true) && preg_match('/^RE/i', $upperCC)){
                wc_add_notice(__('Invalid coupon code.', 'woocommerce'), 'error');
                wp_die();
            }else{
                $coupon_result = WC()->cart->apply_coupon($coupon_code);
                if ($coupon_result) {
                    echo '1';
                } else {
                    wc_add_notice(__('Invalid coupon code.', 'woocommerce'), 'error');
                    wp_die();
                }
            }
        }
    }
    wp_die();
}

add_filter( 'woocommerce_order_button_text', 'change_place_order_button_text' );

function change_place_order_button_text( $button_text ) {
    $button_text = 'Pay now';
    return $button_text;
}

add_action('woocommerce_checkout_update_order_meta', 'save_custom_checkout_field');

function save_custom_checkout_field($order_id) {
    $currentUser = wp_get_current_user();
    $userID = $currentUser->ID;
    if($_POST['email_special_offer']){
        update_field('email_special_offer', $_POST['email_special_offer'], 'user_' . $userID);
    }
}

add_action( 'user_register', 'save_custom_registration_fields' );
function save_custom_registration_fields( $user_id ) {
    if ( isset( $_POST['first_name'] ) ) {
        update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
    }
    if ( isset( $_POST['last_name'] ) ) {
        update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
    }
    if ( isset( $_POST['receive_communications'] ) ) {
        update_user_meta( $user_id, 'email_special_offer', 1 );
        $data = array(
            'FNAME' => $_POST['first_name'],
            'LNAME' => $_POST['last_name'],
            'EMAIL' => $_POST['email'],
            'PHONE' => '',
            'TAG' => '',
        );
        mailchimp($data, 'subscribed', false);
    }
}

// Add extra fields to user edit page
function add_extra_user_profile_fields( $user ) {
    ?>
    <h3><?php _e( 'Subscription Details', 'your_textdomain' ); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="email_special_offer">Email</label></th>
            <td><input <?= (get_the_author_meta( 'email_special_offer', $user->ID )) ? 'checked' : ''; ?> type="checkbox" name="email_special_offer_admin" id="email_special_offer_admin" value="<?php echo esc_attr( get_the_author_meta( 'email_special_offer', $user->ID ) ); ?>" class="email_special_offer_admin"></td>
        </tr>
    </table>
    <?php
}
add_action( 'edit_user_profile', 'add_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) || is_admin() ) {
        $subscribe_user = isset($_POST['email_special_offer_admin']) ? 1 : 0;
        update_user_meta( $user_id, 'email_special_offer', $subscribe_user );
        if($subscribe_user != 1){
            $data = array(
                'FNAME' => '',
                'LNAME' => '',
                'EMAIL' => $_POST['billing_email'],
                'PHONE' => '',
                'TAG' => '',
            );
            mailchimp($data, 'unsubscribed', false);
        }else if($subscribe_user == 1){
            $data = array(
                'FNAME' => $_POST['billing_first_name'],
                'LNAME' => $_POST['billing_first_name'],
                'EMAIL' => $_POST['billing_email'],
                'PHONE' => $_POST['billing_phone'],
                'TAG' => '',
            );
            mailchimp($data, 'subscribed', false);
        }
    }
}
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

add_action('wp_ajax_save_account_details_ajax', 'save_account_details_ajax');
add_action('wp_ajax_nopriv_save_account_details_ajax', 'save_account_details_ajax');

function save_account_details_ajax() {
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'save_account_details_ajax') {
        $current_user = wp_get_current_user();
        
        // Retrieve submitted data
        $first_name = sanitize_text_field($_POST['account_first_name']);
        $last_name = sanitize_text_field($_POST['account_last_name']);
        $email = sanitize_email($_POST['account_email']);
        $phone = sanitize_text_field($_POST['phone']);
        $new_password = $_POST['password_1'];
        $subscribe_user = isset($_POST['subscribe_user']) ? 1 : 0;
        
        // Update user information
        $user_id = $current_user->ID;
        $user_data = array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_email' => $email
        );
        wp_update_user($user_data);
        
        // Update user meta for phone
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'billing_first_name', $first_name);
        update_user_meta($user_id, 'billing_last_name', $last_name);

        // Change password if new password is provided
        if (!empty($new_password)) {
            wp_set_password($new_password, $user_id);
        }


        // MailChimp integration
        $data = array(
            'EMAIL' => $email,
            'FNAME' => $first_name,
            'LNAME' => $last_name,
            'PHONE' => $phone
        );
        if ($subscribe_user != 1) {
            mailchimp($data, 'unsubscribed', false);
        } else {
            mailchimp($data, 'subscribed', false);
        }

        update_user_meta($user_id, 'email_special_offer', $subscribe_user);

        $referral_id = "";
        if(!get_user_meta($user_id, 'referral_coupon', true)){
            $coupon_code = generate_referral_coupon($user_id);
            $referral_id = $coupon_code;
            $coupon = new WC_Coupon($coupon_code);
            $user = new WP_User($user_id);
            if ($coupon->get_id() && !get_user_meta($user_id, 'referral_coupon', true)) {
                $coupon->set_usage_count(1); // Set usage count to 1 for the new user
                update_user_meta($user_id, 'referral_coupon', $coupon_code); // Update user meta
                $coupon->save();
            }
        }

        $data = array(
            'firstName' => $first_name,
            'lastName' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'referralId' => $referral_id,
            'subscribeUser' => $subscribe_user,
        );
        $dataJson = json_encode($data);
        $ret['message'] = "Details Updated";
        $ret['data'] = $dataJson;
        $ret['status'] = true;
        print_r(json_encode($ret));
        exit;
    }
    
    // If the function reaches here, it means there was an error
    $ret['message'] = "Try again later";
    $ret['status'] = false;
    print_r(json_encode($ret));
    exit;
}

add_action('wp_ajax_save_billing_details_ajax', 'save_billing_details_ajax');
add_action('wp_ajax_nopriv_save_billing_details_ajax', 'save_billing_details_ajax');

function save_billing_details_ajax() {
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'save_billing_details_ajax') {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $address_1 = sanitize_text_field($_POST['billing_address_1']);
        $address_2 = sanitize_text_field($_POST['billing_address_2']);
        $city = sanitize_text_field($_POST['billing_city']);
        $country = sanitize_text_field($_POST['billing_country']);
        $post_code = sanitize_text_field($_POST['billing_postcode']);

        if($address_1){
            update_user_meta($user_id, 'billing_address_1', $address_1);
        }
        if($address_2){
            update_user_meta($user_id, 'billing_address_2', $address_2);
        }
        if($city){
            update_user_meta($user_id, 'billing_city', $city);
        }
        if($country){
            update_user_meta($user_id, 'billing_country', $country);
        }
        if($post_code){
            update_user_meta($user_id, 'billing_postcode', $post_code);
        }
        
        
        $data = array(
            'address_1' => $address_1,
            'address_2' => $address_2,
            'city' => $city,
            'post_code' => $post_code,
        );
        $dataJson = json_encode($data);
        $ret['message'] = "Details Updated";
        $ret['data'] = $dataJson;
        $ret['status'] = true;
        print_r(json_encode($ret));
        exit;
    }
    
    // If the function reaches here, it means there was an error
    $ret['message'] = "Details Updated";
    $ret['status'] = false;
    print_r(json_encode($ret));
    exit;
}


add_action( 'add_meta_boxes', 'add_buyers_meta_box' );

function add_buyers_meta_box() {
    add_meta_box(
        'product_buyers_meta_box',
        'Buyers of this Product',
        'display_buyers_meta_box',
        'product',
        'normal',
        'default'
    );
}

function display_buyers_meta_box( $post ) {
    $productId = $post->ID;

    $orders = wc_get_orders( array(
        'numberposts' => -1,
        'post_type' => 'shop_order',
        'post_status' => array('wc-completed')
    ) );
    $btnStatus = true;
    $btnText = "Add";
    $errorRow = "";
    $getLiveDrawLink = "";
    $getCertificateLink = "";
    $getDonationValue = '';
    $getDonationType = 0;
    echo '<form method="POST" id="winnerSetForm">';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>' . __( 'User ID', 'text-domain' ) . '</th><th>' . __( 'Name', 'text-domain' ) . '</th><th>' . __( 'Email', 'text-domain' ) . '</th><th>Tickets</th><th>Order Id</th></tr></thead>';
    echo '<tbody>';
    if($orders){
        foreach($orders as $order){
            if($order){
                foreach($order->get_items() as $itemValues){
                    if( $itemValues['product_id'] == $productId ){
                        $order_id = $order->ID;
                        $order_obj = wc_get_order( $order_id );
                        $user_id = $order_obj->get_customer_id();
                        $user_info = get_userdata( $user_id );
                        $quantity = $itemValues->get_quantity();
                        $endDate = date("Y-m-d H:i:s", strtotime(get_field('product_extra_details_draw_date',$product_id)));
                        $winner_id = get_post_meta( $productId, 'winner_id', true );
                        $winner_order_id = get_post_meta( $productId, 'winner_order_id', true );
                        $getLiveDrawLink = get_post_meta( $productId, 'winner_live_draw_link', true );
                        $getCertificateLink = get_post_meta( $productId, 'winner_certificate_link', true );
                        $getDonationValue = get_post_meta( $productId, 'winner_donation_value', true );
                        $orderEditUrl = admin_url('post.php?post=' . $order_id . '&action=edit');
                        $name = $order_obj->get_billing_first_name()." ".$order_obj->get_billing_last_name();
                        $email = $order->get_billing_email();
                        if(($winner_id == $user_id) && ($order_id == $winner_order_id)){
                            $btnText = "Update";
                            $status = 'checked';
                        }else{
                            $status = '';
                        }
                        echo "<tr>";
                        echo "<td>
                            <input type='hidden' value='addProductWinner' name='ajaxAction' />
                            <input type='hidden' value='$winner_order_id' class='winner_order_id' name='winner_order_id' />
                            <input type='hidden' value='$order_id' class='orderId orderId$order_id' name='orderId' />
                            <input type='hidden' value='$winner_id' name='winnerId' />
                            <input type='hidden' value='$endDate' class='endDate' name='endDate' />
                            <input type='hidden' value='$productId' class='productId' name='productId' />
                            <input $status value='$user_id' data-order-id='orderId$order_id' class='userId$user_id' name='userId' type='radio' />
                            ".$user_id.
                        "</td>";
                        echo "<td>".$name."</td>";
                        echo "<td>".$email."</td>";
                        echo "<td>".$quantity."</td>";
                        echo "<td><a target='_black' href=".$orderEditUrl.">".$order_id."</a></td>";
                        echo "</tr>";
                    }
                }
            }else {
                $btnStatus = false;
                $errorRow =  '<tr><td colspan="5">' . __( 'No buyers found for this product.', 'text-domain' ) . '</td></tr>';
            }
        }
    }else {
        $btnStatus = false;
        $errorRow =  '<tr><td colspan="5">' . __( 'No buyers found for this product.', 'text-domain' ) . '</td></tr>';
    }
    if($errorRow){
        echo $errorRow;
    }
    if($btnStatus){
        $getDonationValue = $getDonationValue != 0 ? $getDonationValue : '' ;
        echo "<tr>";
        echo "<td colspan='1'><label>Video link</label><input type='url' value='".$getLiveDrawLink."' placeholder='Video link' name='liveDrawLink' class='liveDrawLink'/></td>";
        echo "<td colspan='1'><label>Draw Certificate</label><input type='url' value='".$getCertificateLink."' placeholder='Draw Certificate' name='certificateLink' class='certificateLink'/></td>";
        echo "<td colspan='1'><label>Charity of choice</label><input type='text' value='".$getDonationValue."' placeholder='Charity of choice' name='donationValue' class='donationValue'/></td>";
        echo "<td colspan='2'><label style='opacity:0;'>Submit</label><br><div class='btnWrap'><button type='button' data-admin-ajax='".admin_url()."admin-ajax.php' class='addToWinnerBtn button'>$btnText To Winner</button><span class='wpcf7-spinner'></span></div></td>";
        echo "</tr>";
    }
    echo '</tbody></table>';
    echo '</form>';
}

add_action('wp_ajax_addProductWinner', 'addProductWinner');
add_action('wp_ajax_nopriv_addProductWinner', 'addProductWinner');

function addProductWinner() {
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addProductWinner') {
        $productId = $_POST['productId'];
        $endDate = date("Y-m-d H:i:s", strtotime($_POST['endDate']));
        $userId = $_POST['userId'];
        $orderId = $_POST['orderId'];
        $certificateLink = $_POST['certificateLink'];
        $liveDrawLink = $_POST['liveDrawLink'];
        $donationValue = $_POST['donationValue'];

        $dateDiff = dateDiff($endDate);
        if($dateDiff == 0){

            global $wpdb;
            $tableName = $wpdb->prefix . "winner_list";
            $resultTable = $wpdb->query("SELECT * FROM `$tableName` LIMIT 1");

            if(empty($resultTable)) {
                $output = $wpdb->query(
                    $wpdb->prepare(
                        "CREATE TABLE IF NOT EXISTS " . $tableName . " (
                        id int NOT NULL AUTO_INCREMENT,
                        productId longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        winnerId longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        orderId longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        certificateLink longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        liveDrawLink longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        donationValue longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 0,
                        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        server longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        PRIMARY KEY (id))"
                    )
                );
            }

            $resultTable = $wpdb->query("SELECT * from `$tableName` WHERE productId='$productId'");
            if($resultTable){
                update_post_meta( $productId, 'winner_id', $userId );
                update_post_meta( $productId, 'winner_order_id', $orderId );
                update_post_meta( $productId, 'winner_certificate_link', $certificateLink );
                update_post_meta( $productId, 'winner_live_draw_link', $liveDrawLink );
                update_post_meta( $productId, 'winner_donation_value', $donationValue );
                $result['status'] = true;
                $result['message'] = "Updated";
                $result['userId'] = $userId;
                global $wpdb;
                $tableName = $wpdb->prefix . "winner_list";
                $data = array(
                    'winnerId' => $userId,
                    'orderId' => $orderId,
                    'certificateLink' => $certificateLink,
                    'liveDrawLink' => $liveDrawLink,
                    'donationValue' => $donationValue,
                    'server' => json_encode($_SERVER)
                );
                $where = array(
                    'productId' => $productId,
                );
                $wpdb->update($tableName, $data, $where);
                print_r(json_encode($result));
                exit;
            }else if(add_post_meta( $productId, 'winner_id', $userId ) && add_post_meta( $productId, 'winner_order_id', $orderId ) && add_post_meta( $productId, 'winner_certificate_link', $certificateLink ) && add_post_meta( $productId, 'winner_live_draw_link', $liveDrawLink ) && add_post_meta( $productId, 'winner_donation_value', $donationValue )){
                $result['status'] = true;
                $result['message'] = "Added";
                $result['userId'] = $userId;
                $tableName = $wpdb->prefix . "winner_list";
                global $wpdb;
                $wpdb->insert($tableName, array(
                    'productId' => $productId,
                    'winnerId' => $userId,
                    'orderId' => $orderId,
                    'certificateLink' => $certificateLink,
                    'liveDrawLink' => $liveDrawLink,
                    'donationValue' => $donationValue,
                    'server' => json_encode($_SERVER)
                ));
                print_r(json_encode($result));
                exit;
            }else{
                $result['status'] = false;
                $result['message'] = "Try again later";
                print_r(json_encode($result));
                exit;
            }
        }else{
            $result['status'] = false;
            $result['message'] = "Date is not end";
            print_r(json_encode($result));
            exit;
        }
    }
}


// Capture referral ID from registration form (if applicable) and store it
function saveReferralId($user_id) {
    $coupon_code = generate_referral_coupon($user_id);

    // Apply the coupon to the newly registered user
    $coupon = new WC_Coupon($coupon_code);
    $user = new WP_User($user_id);

    if ($coupon->get_id() && !get_user_meta($user_id, 'referral_coupon', true)) {
        $coupon->set_usage_count(1); // Set usage count to 1 for the new user
        update_user_meta($user_id, 'referral_coupon', $coupon_code); // Update user meta
        $coupon->save();
    }
}
add_action('user_register', 'saveReferralId');

function generate_referral_coupon($user_id) {
    // Generate a unique coupon code
    $preFix = "RE".$user_id."UI";
    $preFixCount = strlen($preFix);
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 10;
    if ($preFixCount <= 6) {
        $length = $length - $preFixCount;
    } else {
        $length = $length - $preFixCount;
    }
    $referralId = '';
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $referralId .= $characters[rand(0, $maxIndex)];
    }

    $coupon_code = $preFix . $referralId;

    // Create the coupon in WooCommerce
    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'shop_coupon'
    );

    $new_coupon_id = wp_insert_post($coupon);

    // Set coupon properties
    update_post_meta($new_coupon_id, 'discount_type', 'percent');
    update_post_meta($new_coupon_id, 'coupon_amount', 5); // 5% discount
    update_post_meta($new_coupon_id, 'individual_use', 'no'); // Can be used in combination with other coupons
    update_post_meta($new_coupon_id, 'usage_limit', PHP_INT_MAX); // Set a very large limit to avoid coupon exhaustion

    return $coupon_code;
}

// Check if the coupon has been used by the current user
function has_user_used_coupon($coupon_code, $user_id) {
    $coupon = get_page_by_title($coupon_code, OBJECT, 'shop_coupon');
    if (!$coupon) {
        return false; // Coupon not found
    }
    $used_users = get_post_meta($coupon->ID, 'referral_coupon_users', true);
    return $used_users && in_array($user_id, $used_users);
}

// Mark the coupon as used by the current user
function mark_coupon_as_used($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();
    $coupons = $order->get_coupon_codes();

    foreach ($coupons as $coupon_code) {
        // Get the coupon object
        $coupon = get_page_by_title($coupon_code, OBJECT, 'shop_coupon');

        if ($coupon) {
            $coupon_code = strtoupper($coupon_code);
            if(preg_match('/^RE/i', $coupon_code) || preg_match('/^FE/i', $coupon_code)){
                // Get the list of used users for this coupon
                $used_users = get_post_meta($coupon->ID, 'referral_coupon_users', true);

                // Initialize $used_users as an empty array if it's not already an array
                if (!is_array($used_users)) {
                    $used_users = array();
                }

                // Add the current user to the list of used users if not already in the list
                if (!in_array($user_id, $used_users)) {
                    $used_users[] = $user_id;
                    // Update the meta value with the new list of used users
                    update_post_meta($coupon->ID, 'referral_coupon_users', $used_users);
                }
            }
        }
    }

    global $wpdb;
    $getEmail = $order->get_billing_email();
    $tableName = $wpdb->prefix . "temp_users";

    if (count($coupons) === 0) {
        $resultTable = $wpdb->prepare("SELECT couponCode FROM `$tableName` WHERE email='$getEmail' AND couponCodeStatus='0' ORDER BY id DESC LIMIT 1");
        $results = $wpdb->get_results($resultTable, ARRAY_A);
        if($results){
            if($results[0]['couponCode'] != ""){
                $data = array(
                    'couponCodeStatus' => 1,
                );
                $where = array(
                    'email' => $getEmail,
                );
                if($wpdb->update($tableName, $data, $where)){
                    $coupon = new WC_Coupon($results[0]['couponCode']);
                    if ($coupon) {
                        $usage_count = $coupon->get_usage_count();
                        $coupon->set_usage_count($usage_count + 1);
                        $coupon->save();
                    }
                }
            }
        }
    }

    if($_POST['email_special_offer'] == 1){
        $data = array(
            'FNAME' => $_POST['billing_first_name'],
            'LNAME' => $_POST['billing_last_name'],
            'EMAIL' => $_POST['billing_email'],
            'PHONE' => $_POST['billing_phone'],
            'TAG' => "",
        );
        mailchimp($data, 'subscribed', false);
    }

    if (count($coupons) != 0) {
        $code = strtoupper($coupons[0]);
        if(preg_match('/^RE/i', $code)){
            preg_match('/RE(\d+)UI/', $code, $matches);
            $referBy = $matches[1];
            $value = 5;
            $valueType = 1; // 1 => Fixed , 0 Perpercentage
            global $wpdb;
            $tableName = $wpdb->prefix . "refer_details";
            $resultTable = $wpdb->query("SELECT * FROM `$tableName` LIMIT 1");
            $walletTable = $wpdb->prefix . "wallet";
            $resultWalletTable = $wpdb->query("SELECT * FROM `$walletTable` LIMIT 1");

            if(empty($resultTable)) {
                $output = $wpdb->query(
                    $wpdb->prepare(
                        "CREATE TABLE IF NOT EXISTS " . $tableName . " (
                        id int NOT NULL AUTO_INCREMENT,
                        referBy longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        referFor longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        value longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        valueType longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        server longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        PRIMARY KEY (id))"
                    )
                );
            }

            if(empty($resultWalletTable)) {
                //Type Column 1 => + , 0 => -
                $output = $wpdb->query(
                    $wpdb->prepare(
                        "CREATE TABLE IF NOT EXISTS " . $walletTable . " (
                        id int NOT NULL AUTO_INCREMENT,
                        userId longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        type longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        amount longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        remark longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        server longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        PRIMARY KEY (id))"
                    )
                );
            }

            $resultTable = $wpdb->query("SELECT * from `$tableName` WHERE referBy='$referBy' AND referFor='$user_id'");

            if($resultTable == 1){
            }else{
                $result = $wpdb->insert($tableName, array(
                    'referBy' => $referBy,
                    'referFor' => $user_id,
                    'value' => $value,
                    'valueType' => $valueType,
                    'server' => json_encode($_SERVER)
                ));
                $result = $wpdb->insert($walletTable, array(
                    'userId' => $referBy,
                    'type' => 1,
                    'amount' => $value,
                    'remark' => 'From Refer Cost',
                    'server' => json_encode($_SERVER)
                ));
            }
        }
    }

    if ( is_user_logged_in() && $user_id && $_POST['wallet_used_amount']){
        $walletTable = $wpdb->prefix . "wallet";
        $result = $wpdb->insert($walletTable, array(
            'userId' => $user_id,
            'type' => 0,
            'amount' => $_POST['wallet_used_amount'],
            'remark' => 'Buying product',
            'server' => json_encode($_SERVER)
        ));

    }
}
add_action('woocommerce_payment_complete', 'mark_coupon_as_used');


add_action('wp_ajax_accountCreatePopupForm', 'accountCreatePopupForm');
add_action('wp_ajax_nopriv_accountCreatePopupForm', 'accountCreatePopupForm');

function accountCreatePopupForm(){
    $account_first_name = $_POST['account_first_name'];
    $account_last_name = $_POST['account_last_name'];
    $account_email = $_POST['account_email'];
    $phone = $_POST['phone'];


    $email_prefix = substr($account_email, 0, 5);
    $unique_identifier = substr(uniqid(), 0, 3);
    $coupon_code = strtoupper($email_prefix.$unique_identifier);
    $coupon_code = "FE".$coupon_code;

    // Create the coupon in WooCommerce
    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'shop_coupon'
    );

    $new_coupon_id = wp_insert_post($coupon);

    // Optionally, you can set coupon properties like discount type, amount, expiry date, etc.
    update_post_meta($new_coupon_id, 'discount_type', 'percent');
    update_post_meta($new_coupon_id, 'coupon_amount', 15); // 10% discount
    update_post_meta($new_coupon_id, 'individual_use', 'yes'); // Only one time per user
    update_post_meta($new_coupon_id, 'usage_limit', 1); // Set usage limit to 1
    // Add allowed email(s)
    $allowed_emails = $account_email; // Replace with your list of allowed emails
    update_post_meta($new_coupon_id, 'customer_email', $allowed_emails);

    global $wpdb;
    $tableName = $wpdb->prefix . "temp_users";
    $resultTable = $wpdb->query("select * from `$tableName` LIMIT 1");

    if(empty($resultTable)) {
        $output = $wpdb->query(
            $wpdb->prepare(
                "CREATE TABLE IF NOT EXISTS " . $tableName . " (
                id int NOT NULL AUTO_INCREMENT,
                firstName longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                lastName longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                email varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                couponCode varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                couponCodeStatus longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 0,
                created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                server longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                PRIMARY KEY (id))"
            )
        );
    }

    $resultTable = $wpdb->query("SELECT * from `$tableName` WHERE email='$account_email' AND couponCodeStatus='1'");

    if($resultTable == 1){
        die(json_encode(array('status'=>false,'message'=>'Already send please check email inbox')));
    }else{
        if(email_exists($account_email)){
            die(json_encode(array('status'=>false,'message'=>'The email is already registered in Chronosweep.')));
        }else{
            $data = array(
                'FNAME' => $account_first_name,
                'LNAME' => $account_last_name,
                'EMAIL' => $account_email,
                'PHONE' => $phone,
                'TAG' => '',
            );
            if(mailchimp($data, 'subscribed', false) == 200){
                $result = $wpdb->insert($tableName, array(
                    'firstName' => $account_first_name,
                    'lastName' => $account_last_name,
                    'couponCode' => $coupon_code,
                    'email' => $account_email,
                    'server' => json_encode($_SERVER)
                ));

                if($result){
                    $password = wp_generate_password();
                    list($username, $domain) = explode('@', $account_email);
                    $user_data = array(
                        'user_login' => $account_email,
                        'user_email' => $account_email,
                        'user_pass'  => $password,
                        'first_name' => $account_first_name,
                        'last_name' => $account_last_name,
                        'role'       => 'customer', // Set the user role here
                    );
                    $user_id = wp_insert_user( $user_data );
                    if ( ! is_wp_error( $user_id ) ) {
                        $key = get_password_reset_key( get_userdata( $user_id ) );
                        $reset_link = network_site_url("my-account/lost-password?action=rp&key=$key&login=" . rawurlencode($account_email), 'login');
                        $subject = 'Chronosweep Coupon';
                        $message = '
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                        </head>
                        <body>
                            <p>Hey, '.ucfirst(strtolower($account_first_name)).'<p>
                            <p>Congratulations! You have received a coupon. Use code : '.$coupon_code.'</p>
                            <p>Username : '.$account_email.'</p>
                            <p>Password set link : <a href='.$reset_link.'>here</a></p>
                        </body>
                        </html>
                        ';
                        $result = wp_mail($account_email, $subject, $message, array('Content-Type: text/html; charset=UTF-8'));

                        if($result){
                            update_user_meta( $user_id, 'email_special_offer', 1 );
                            die(json_encode(array('status'=>true,'message'=>'Successfully subscribed! please check your email')));
                        }else{
                            die(json_encode(array('status'=>false,'message'=>'Email sending failed')));
                        }
                    }else{
                        die(json_encode(array('status'=>false,'message'=>'Try again later')));
                    }
                }
            }else{
                die(json_encode(array('status'=>false,'message'=>'Please enter correct details')));
            }
        }
    }
}

function mailchimp($data, $status, $echo){
    if ($data) {
        // MailChimp API credentials
        $apiKey = '69ade13d680d4cd1a9de718e81d88b93-us13';
        $listID = 'ec9a68583b';
        
        // MailChimp API URL
        $memberID = md5(strtolower($data['EMAIL']));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
        
        // member information
        if($status == 'subscribed'){
            if($data['TAG']){
                $field = json_encode([
                    'email_address' => strtolower($data['EMAIL']),
                    'status'        => $status,
                    'tags'  => array($data['TAG']),
                    'merge_fields'  => [
                        'FNAME'     => $data['FNAME'],
                        'LNAME'     => $data['LNAME'],
                        'PHONE'     => $data['PHONE'],
                    ],
                ]);
            }else{
                $field = json_encode([
                    'email_address' => strtolower($data['EMAIL']),
                    'status'        => 'subscribed',
                    'merge_fields'  => [
                        'FNAME'     => $data['FNAME'],
                        'LNAME'     => $data['LNAME'],
                        'PHONE'     => $data['PHONE'],
                    ],
                ]);
            }
        }else if($status == 'unsubscribed'){
            $field = json_encode([
                'status' => 'unsubscribed'
            ]);
        }
        
        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $r = json_decode($result);
        $msg = $r->detail;
        if($echo == 1){
            wp_die($httpCode);
        }else{
            return $httpCode;
        }
    }
}

// Apply a wallet amount
add_action( 'woocommerce_cart_calculate_fees', 'apply_discount_to_cart_total' );
function apply_discount_to_cart_total() {
    global $woocommerce;
    $getAnyFees = WC()->cart->get_fees();
    if ( is_user_logged_in() && get_current_user_id()){
        $result = getWalletAmount(get_current_user_id());
        if($result > 0){
            $oldTotal = $woocommerce->cart->subtotal;
            if($oldTotal > $result){
                $woocommerce->cart->add_fee( 'Credit Discount', - $result );
                $getAnyFees = WC()->cart->get_fees();

                $otherDiscount = 0;
                if($getAnyFees['quantity-discount']){
                    if($getAnyFees['quantity-discount']->amount){
                        $otherDiscount = $getAnyFees['quantity-discount']->amount;
                        $otherDiscount = str_replace(['-', '+'], '', $creditDiscount);
                    }
                }
                $couponDiscount = 0;
                if ( $woocommerce->cart->has_discount() ) {
                    foreach ( $woocommerce->cart->get_coupons() as $code => $coupon ) {
                        $couponDiscount += $woocommerce->cart->get_coupon_discount_amount( $code );
                    }
                }
                $creditDiscount = 0;
                if($getAnyFees['credit-discount']){
                    if($getAnyFees['credit-discount']->amount){
                        $creditDiscount = $getAnyFees['credit-discount']->amount;
                        $creditDiscount = str_replace(['-', '+'], '', $creditDiscount);
                        
                    }
                }

                if($couponDiscount > 0){
                    $creditDiscount = $creditDiscount + $couponDiscount;
                }else{
                    $creditDiscount = $creditDiscount;
                }

                WC()->session->set('credit_discount', $creditDiscount);

            } elseif ($oldTotal <= $result) {
                $woocommerce->cart->add_fee( 'Credit Discount', - $result );
                $getAnyFees = WC()->cart->get_fees();
                
                $quantityDiscount = 0;
                if($getAnyFees['quantity-discount']){
                    if($getAnyFees['quantity-discount']->amount){
                        $quantityDiscount = $getAnyFees['quantity-discount']->amount;
                        $quantityDiscount = preg_replace('/\D/', '', $quantityDiscount);
                    }
                }
                $couponDiscount = 0;
                if ( $woocommerce->cart->has_discount() ) {
                    foreach ( $woocommerce->cart->get_coupons() as $code => $coupon ) {
                        $couponDiscount += $woocommerce->cart->get_coupon_discount_amount( $code );
                    }
                }
                $creditDiscount = 0;
                if($getAnyFees['credit-discount']){
                    if($getAnyFees['credit-discount']->amount){
                        $creditDiscount = $getAnyFees['credit-discount']->amount;
                        $creditDiscount = preg_replace('/\D/', '', $creditDiscount);
                    }
                }

                if($quantityDiscount > 0){
                    $creditDiscount = $oldTotal - $quantityDiscount;
                }else{
                    $creditDiscount = $oldTotal;
                }

                WC()->session->set('credit_discount', $creditDiscount);
            }
        }
    }
}

add_action( 'woocommerce_removed_coupon', 'update_wallet_amount_on_coupon_change' );
function update_wallet_amount_on_coupon_change() {
?>
<script>
    setTimeout(() => {
        location.reload();
    }, 500);
</script>
<?php
}

// Add a hidden input field to the WooCommerce checkout page
add_action('woocommerce_after_checkout_billing_form', 'add_hidden_wallet_input_field');
function add_hidden_wallet_input_field(){
    global $woocommerce;
    if ( is_user_logged_in() && get_current_user_id()){
        $walletAmount = getWalletAmount(get_current_user_id());
        $creditDiscount = WC()->session->get('credit_discount', 0);
        $couponDiscount = 0;
        if ( $woocommerce->cart->has_discount() ) {
            foreach ( $woocommerce->cart->get_coupons() as $code => $coupon ) {
                $couponDiscount += $woocommerce->cart->get_coupon_discount_amount( $code );
            }
        }
        $totalDiscount = $creditDiscount - $couponDiscount;
        //$totalDiscount = $creditDiscount;
        if($walletAmount > 0){

            // Ensure the value is not negative
            if ( $totalDiscount < 0 ) {
                $totalDiscount = 0;
            }

            echo '<input type="hidden" name="wallet_used_amount" value="' . esc_attr( $totalDiscount ) . '">';
        }
    }
}
add_filter( 'woocommerce_cart_totals_coupon_html', 'custom_coupon_remove_text', 10, 2 );
function custom_coupon_remove_text( $coupon_html, $coupon ) {
    $coupon_html = str_replace( 'Remove', 'x', $coupon_html );
    return $coupon_html;
}


function custom_prefill_billing_fields($fields) {

    $current_user = wp_get_current_user();

    if ($current_user->ID && isset($current_user->first_name)) {
        $fields['billing']['billing_first_name']['default'] = $current_user->first_name;
        $fields['billing']['billing_first_name']['class'][] = 'hasInput';
    }

    if ($current_user->ID && isset($current_user->last_name)) {
        $fields['billing']['billing_last_name']['default'] = $current_user->last_name;
        $fields['billing']['billing_last_name']['class'][] = 'hasInput';
    }

    $phone = get_user_meta($current_user->ID, 'phone', true);

    if ($current_user->ID && isset($phone)) {
        $fields['billing']['billing_phone']['default'] = $phone;
        $fields['billing']['billing_phone']['class'][] = 'hasInput';
    }

    if ($current_user->ID && isset($current_user->billing_address_1)) {
        $fields['billing']['billing_address_1']['default'] = $current_user->billing_address_1;
        $fields['billing']['billing_address_1']['class'][] = 'hasInput';
    }

    if ($current_user->ID && isset($current_user->billing_address_2)) {
        $fields['billing']['billing_address_2']['default'] = $current_user->billing_address_2;
        $fields['billing']['billing_address_2']['class'][] = 'hasInput';
    }

    if ($current_user->ID && isset($current_user->billing_city)) {
        $fields['billing']['billing_city']['default'] = $current_user->billing_city;
        $fields['billing']['billing_city']['class'][] = 'hasInput';
    }

    if ($current_user->ID && isset($current_user->billing_state)) {
        $fields['billing']['billing_state']['default'] = $current_user->billing_state;
        $fields['billing']['billing_state']['class'][] = 'hasInput';
    }

    if ($current_user->ID && isset($current_user->billing_postcode)) {
        $fields['billing']['billing_postcode']['default'] = $current_user->billing_postcode;
        $fields['billing']['billing_postcode']['class'][] = 'hasInput';
    }

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'custom_prefill_billing_fields');

// Function to update user account details after order is placed
function update_user_account_details_after_order($order_id) {
    // Get the order object
    $order = wc_get_order($order_id);
    
    $current_user = wp_get_current_user();

    // If billing email exists
    if ($current_user->ID) {
        $billing_first_name = $order->get_billing_first_name();
        if ($billing_first_name) {
            wp_update_user(array(
                'ID' => $current_user->ID,
                'first_name' => $billing_first_name,
            ));
        }
        $billing_last_name = $order->get_billing_last_name();
        if ($billing_last_name) {
            wp_update_user(array(
                'ID' => $current_user->ID,
                'last_name' => $billing_last_name,
            ));
        }
        $billing_email = $order->get_billing_email();
        if ($billing_email) {
            $args = array(
                'ID' => $current_user->ID,
                'user_email' => $billing_email,
            );
            wp_update_user($args);
        }
        $billing_phone = $order->get_billing_phone();
        if ($billing_phone) {
            update_user_meta($current_user->ID, 'phone', $billing_phone);
        }
        $billing_address_1 = $order->get_billing_address_1();
        if ($billing_phone) {
            wp_update_user(array(
                'ID' => $current_user->ID,
                'billing_address_1' => $billing_address_1,
            ));
        }
        $billing_address_2 = $order->get_billing_address_2();
        if ($billing_phone) {
            wp_update_user(array(
                'ID' => $current_user->ID,
                'billing_address_2' => $billing_address_2,
            ));
        }
    }
}
add_action('woocommerce_thankyou', 'update_user_account_details_after_order');

add_filter('woocommerce_checkout_fields', 'custom_remove_field');

function custom_remove_field($fields) {
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_company']);
    return $fields;
}

add_filter('woocommerce_checkout_fields', 'billing_country_priority');

function billing_country_priority($fields) {
    $fields['billing']['billing_country']['label'] = __('Country', 'woocommerce');
    $fields['billing']['billing_address_1']['priority'] = 40;
    $fields['billing']['billing_address_2']['priority'] = 50;
    $fields['billing']['billing_city']['priority'] = 60;
    $fields['billing']['billing_country']['priority'] = 70;
    $fields['billing']['billing_postcode']['priority'] = 80;
    return $fields;
}

// Add confirm email field to WooCommerce checkout billing form
function add_confirm_email_field($fields) {
    $fields['billing']['billing_email_confirm'] = array(
        'label'         => __('Confirm your email', 'woocommerce'),
        'required'      => true,
        'type'          => 'email',
        'class'         => array('form-row-wide'),
        'validate'      => array('email'),
        'autocomplete'  => 'email-confirm',
        'priority'      => 120,
    );
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'add_confirm_email_field');

// Validate confirm email field
function validate_confirm_email_field($posted) {
    // Get cart contents
    $cart_items = WC()->cart->get_cart();

    $total_quantity = 0;
    $product_ids = array();

    // Loop through cart items to calculate total quantity
    foreach ($cart_items as $item_key => $item) {
        $product = $item['data'];
        $quantity = $item['quantity'];
        $product_ids[] = $item['product_id'];
        $total_quantity += $quantity;
    }


    $limit = getUserLimit($product_ids[0], get_current_user_id());

    $limit = base64_decode($limit);
    
    $errors = WC()->session->get('wc_notices', array());
    
    $email = isset($_POST['billing_email']) ? wc_clean($_POST['billing_email']) : '';
    $confirm_email = isset($_POST['billing_email_confirm']) ? wc_clean($_POST['billing_email_confirm']) : '';

    if ($email !== $confirm_email) {
        $errors[] = __('Email addresses do not match.', 'woocommerce');
    }

    if($limit < $total_quantity){
        $errors[] = __('Only tickets allowed in '.$limit, 'woocommerce');
    }

    if (!empty($errors)) {
        wc_add_notice(implode('<br/>', $errors), 'error');
    }

    return $posted;
}
add_action('woocommerce_checkout_process', 'validate_confirm_email_field');

add_action('woocommerce_order_status_changed', function($order_id){
	$order = wc_get_order($order_id);
	if (!$order) return;
	foreach ($order->get_items() as $item) {
		$product_id = (int) $item->get_product_id();
		wp_cache_delete('cs_order_counts_' . $product_id, 'cs');

		$customer_id = (int) $order->get_user_id();
		if ($customer_id) {
			wp_cache_delete('cs_user_qty_' . $product_id . '_' . $customer_id, 'cs');
		}
	}
}, 10, 1);
