<?php
add_filter( 'wp_sitemaps_enabled', '__return_false' );
if ( ! defined( 'FS_METHOD' ))
    define( 'FS_METHOD', 'direct' );

add_filter('show_admin_bar', '__return_false');
add_filter( 'woocommerce_cart_hide_zero_taxes', '__return_false' );
define('WOW_ANIMATION_CLASS_FADEOUTIN_SMALL', 'wow animate__fadeOutInSmall');
define('WOW_ANIMATION_CLASS_FADEIN_SMALL', 'wow animate__fadeIn');
define('WOW_ANIMATION_CLASS_FADEINUP_SMALL', 'wow animate__fadeInUpSmall');
define('WOW_ANIMATION_CLASS_FADEINRIGHT_SMALL', 'wow animate__fadeInRightSmall');
define('WOW_ANIMATION_CLASS_FADEINLEFT_SMALL', 'wow animate__fadeInLeftSmall');

function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['json'] = 'application/json';
    return $mimes;
}

add_filter('upload_mimes', 'cc_mime_types');
/**
 * ACF Optimise
 */
function acf_json_loc($path) {
    $path = get_stylesheet_directory() . '/acf_db/files';
    return $path;
}
add_filter('acf/settings/save_json', 'acf_json_loc');

function acf_json_load($paths) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf_db/files';
    return $paths;
}
add_filter('acf/settings/load_json', 'acf_json_load');


/**
 * Cache posts & media
 */
function get_acf_ids(&$ids, $field_id, $repeater_id = false, $depends = false) {
    if ($repeater_id === false) {
        //If not inside a repeater
        $ids[] = (int) get_field($field_id);
    } else {
        while (have_rows($repeater_id)) : the_row();
            if ($depends === false)
                $ids[] = (int) get_sub_field($field_id);
            else
                $ids[] = (int) get_sub_field($field_id . "_" . get_sub_field($depends));
        endwhile;
    }

    return $ids;
}

function get_acf_cache($post_ids) {
    if (count($post_ids))
        $posts = get_posts(array('post_type' => 'any', 'numberposts' => -1, 'post__in' => $post_ids));
}


/**
 * Disable Emoji
 */
function disable_emojicons_tinymce($plugins) {
    if (is_array($plugins))
        return array_diff($plugins, array('wpemoji'));
    else
        return array();
}

function disable_wp_emojicons() {
    // all actions related to emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    // filter to remove TinyMCE emojis
    add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');
}
add_action('init', 'disable_wp_emojicons');

//Save ACF Fields
function acf_json_save_point( $path ) {
    return get_stylesheet_directory() . '/inc/files/acf_fields';
}
add_filter( 'acf/settings/save_json', 'acf_json_save_point' );

//Get ACF Fiels
function acf_json_load_point( $paths ) {
    // Remove the original path (optional).
    unset($paths[0]);

    // Append the new path and return it.
    $paths[] = get_stylesheet_directory() . '/inc/files/acf_fields';

    return $paths;
}
add_filter( 'acf/settings/load_json', 'acf_json_load_point' );

//Date Diffrent Check
function dateDiff($endDate){
    date_default_timezone_set('Europe/London');
    $todayDate = date('Y-m-d H:i:s');
    $tDate = new DateTime($todayDate);
    $eDate = new DateTime($endDate);
    if($tDate <= $eDate){
        return 1;
    }else{
        return 0;
    }
}

//Wallet
function getWalletAmount($userId){
    global $wpdb;
    $walletTableName = $wpdb->prefix . "wallet";
    $amount = 0;
    $oneValue = $wpdb->get_var("SELECT SUM(amount) FROM $walletTableName WHERE userId='$userId' AND type='1'");
    $zeroValue = $wpdb->get_var("SELECT SUM(amount) FROM $walletTableName WHERE userId='$userId' AND type='0'");
    $amount = $oneValue - $zeroValue;
    return $amount;
}

function getActiveProductId(){
    $pId = 0;
    $homepageId = get_option('page_on_front');
    $getLayout = get_field('widget_content', $homepageId);
    foreach ($getLayout as $layout) {
        if($layout['acf_fc_layout'] == "product_banner"){
            $pId = $layout['product_banner_product'];
            break;
        }
    }
    return $pId;
}