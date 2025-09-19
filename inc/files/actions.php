<?php

add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );


function productSubscribeForm() {
    $productName = filter_input(INPUT_POST, 'productName');
    $productLink = filter_input(INPUT_POST, 'productLink');
    $productId = filter_input(INPUT_POST, 'productId');
    $email = filter_input(INPUT_POST, 'email');


    $data = array(
        'FNAME' => '',
        'LNAME' => '',
        'EMAIL' => $email,
        'PHONE' => '',
        'TAG' => $productId,
    );
    return mailchimp($data, 'subscribed', true);
}

add_action("wp_ajax_productSubscribeForm", "productSubscribeForm");
add_action("wp_ajax_nopriv_productSubscribeForm", "productSubscribeForm");