<?php

function wallet_post_type()
{
    $labels = array(
        'name' => _x('Wallet', 'PostType General Name', 'chronosweep'),
        'singular_name' => _x('Wallet', 'PostType Singular Name', 'chronosweep'),
        'menu_name' => __('Wallet', 'chronosweep'),
        'all_items' => __('All Wallet', 'chronosweep'),
        'add_new_item' => __('Add New Wallet', 'chronosweep'),
        'edit_item' => __('Edit Wallet', 'chronosweep'),
        'update_item' => __('Update Wallet', 'chronosweep'),
        'search_items' => __('Search Wallet', 'chronosweep'),
        'not_found' => __('Not Found', 'chronosweep'),
        'not_found_in_trash' => __('Not found in Trash', 'chronosweep'),
    );

    $args = array(
        'label' => __('Wallet', 'chronosweep'),
        'description' => __('Wallet', 'chronosweep'),
        'labels' => $labels,
        'supports' => array('title', 'custom-fields'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-money-alt',
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'wallet'),
        'show_in_rest' => true,
    );

    register_post_type('wallet', $args);
}
add_action('init', 'wallet_post_type', 0);

// Create & Save Users As Posts
function users_as_posts() {
    global $wpdb, $users, $post_id;
    $users_table = $wpdb->prefix . 'users';
    $users = $wpdb->get_results("SELECT * FROM $users_table");

    // Get existing posts
    $existing_posts = get_posts(array(
        'post_type' => 'wallet',
        'numberposts' => -1,
    ));

    // Store existing post authors
    $existing_users = array();
    foreach ($existing_posts as $existing_post) {
        $existing_users[] = $existing_post->post_author;
    }

    // Loop through users from users table
    foreach ($users as $user) {
        // Check if user already has a post, if not, insert new post
        if (!in_array($user->ID, $existing_users)) {
            $post_title = $user->display_name;
            $post_content = ''; // Added post_content as an empty string
            $post_author = $user->ID;

            $post_id = wp_insert_post(array(
                'post_title'    => $post_title,
                'post_content'  => $post_content,
                'post_author'   => $post_author,
                'post_type'     => 'wallet',
                'post_status'   => 'publish',
                'meta_input'    => array(
                    'user_id'       => $post_author, // Save user ID as post meta
                    'credit_amount' => '', // Add the custom field credit_amount
                    'wallet_amount' => '', // Add the custom field wallet_amount
                    'wallet_remark' => '', // Add the custom field wallet_remark
                    'wallet_type'   => ''  // Add the custom field wallet_type
                )
            ));

            if (!is_wp_error($post_id)) {
                $existing_users[] = $user->ID;
            }
        }
    }
    
    // Delete posts that are no longer in the users table
    foreach ($existing_posts as $existing_post) {
        if (!in_array($existing_post->post_author, wp_list_pluck($users, 'ID'))) {
            wp_delete_post($existing_post->ID, true);
        }
    }
}
add_action('init', 'users_as_posts');

// To Remove Links
function remove_wallet_links($actions, $post) {
    if ($post->post_type == 'wallet') {
        unset($actions['view']);
        unset($actions['trash']);
        unset($actions['inline hide-if-no-js']);
        unset($actions['duplicate']);
    }
    return $actions;
}
add_filter('post_row_actions', 'remove_wallet_links', 10, 2);
add_filter('page_row_actions', 'remove_wallet_links', 10, 2);

// To Remove View Link 
function remove_view_link_edit_page_wallet() {
    global $post;
    if ($post->post_type == 'wallet') {
        echo '<style>#edit-slug-box, .view { display: none; }</style>';
    }
}
add_action('admin_head-post.php', 'remove_view_link_edit_page_wallet');
add_action('admin_head-post-new.php', 'remove_view_link_edit_page_wallet');

// To Remove Add New User button from Wallet Post Type
function remove_add_new_wallet() {
    remove_submenu_page('edit.php?post_type=wallet', 'post-new.php?post_type=wallet');
}
add_action('admin_menu', 'remove_add_new_wallet');

// To Remove Bulk Action Field
function remove_bulk_actions($actions) {
    global $post_type;
    if ($post_type == 'wallet') {
        unset($actions['edit']);
        unset($actions['trash']);
    }
    return $actions;
}
add_filter('bulk_actions-edit-wallet', 'remove_bulk_actions');

// To Remove FIlter Options
function remove_bulk_actions_filter() {
    global $post_type;
    if ($post_type == 'wallet') {
        echo '<style>.tablenav.top .alignleft.actions:not(.bulkactions), .tablenav.bottom .bulkactions, a.page-title-action { display: none; }</style>';
    }
}
add_action('admin_head-edit.php', 'remove_bulk_actions_filter');

// To Hide checkboxes In the Table List
function remove_add_new_wallet_button() {
    global $post_type;
    if ($post_type == 'wallet') {
        echo '<style>#wpbody-content .page-title-action, .wrap .page-title-action, #wpbody-content .subtitle { display: none; }</style>';
    }
}
add_action('admin_head', 'remove_add_new_wallet_button');

// To Hide checkboxes In the Table List
function hide_wallet_checkboxes($columns) {
    unset($columns['cb']);
    return $columns;
}
add_filter('manage_wallet_posts_columns', 'hide_wallet_checkboxes');


function remove_wallet_submenus() {
    global $submenu;

    // To Remove Add New Wallet button
    remove_submenu_page('edit.php?post_type=wallet', 'post-new.php?post_type=wallet');

    // To Remove submenus
    if (isset($submenu['edit.php?post_type=wallet'])) {
        unset($submenu['edit.php?post_type=wallet'][5]); // Remove 'All Wallet' submenu
    }
}
add_action('admin_menu', 'remove_wallet_submenus');

// To Save input field value when updating the post
function save_input_field_value($post_id) {
    global $wpdb;
    $users_wallet_table = $wpdb->prefix . 'wallet'; // user_id

    // Check if the nonce is set.
    if (!isset($_POST['wallet_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['wallet_meta_box_nonce'], 'save_input_field_value')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (array_key_exists('user_id', $_POST)) {
        $user_id = $_POST['user_id'];
    }

    // Retrieve user ID
    $user_id = get_post_meta($post_id, 'user_id', true);
        
    // Retrieve form data
    $wallet_amount = $_POST['wallet_amount'];
    $wallet_remark = $_POST['wallet_remark'];
    $wallet_type = $_POST['wallet_type'];
    $total_wallet_amount = $_POST['total_wallet_amount'];
    // Get current date and time
    $current_time = current_time('mysql');

    if (array_key_exists('wallet_amount', $_POST)) {
        $wallet_amount = $_POST['wallet_amount'];
        
        // Check if wallet_amount is between 0 and 20
        if ($wallet_amount <= 0 || $wallet_amount == '') {
            // If wallet_amount is less than 1 or greater than 20, display an error message
            $url = add_query_arg(array('wallet_error' => 'invalid_amount'), wp_get_referer());
            wp_redirect($url);
            exit;
        }
        // update_post_meta($post_id, 'wallet_amount', $wallet_amount);
    }
    
    if (array_key_exists('wallet_remark', $_POST)) {
        // Check if wallet_remark is not empty
        if($wallet_remark === '') {
            // If wallet_remark is empty, display an error message
            $url = add_query_arg(array('wallet_error' => 'empty_remark'), wp_get_referer());
            wp_redirect($url);
            exit;
        }
        // update_post_meta($post_id, 'wallet_remark', $wallet_remark);
    }

    if (array_key_exists('wallet_type', $_POST)) {
        if($wallet_amount > $total_wallet_amount && $wallet_type != 1) {
            // If wallet_type is invalid, display an error message
            $url = add_query_arg(array('wallet_error' => 'invalid_type'), wp_get_referer());
            wp_redirect($url);
            exit;
        }
        update_post_meta($post_id, 'wallet_type', $wallet_type);
    }
    
    // Insert data into the wallet table
    $wpdb->insert(
        $users_wallet_table,
        array(
            'userId' => $user_id,
            'type' => $wallet_type,
            'amount' => $wallet_amount,
            'remark' => $wallet_remark,
            'created_at' => $current_time,
            'server' => json_encode($_SERVER)
        )
    );
}
add_action('save_post', 'save_input_field_value');

// Admin Notice for credit amount error
function wallet_update_error_notice() {
    global $post;
    
    if (isset($_GET['wallet_error'])) {
        $error = $_GET['wallet_error'];
        $message = '';

        switch ($error) {
            case 'invalid_amount':
                $message = 'Credit amount should be atleast 1';
                break;
            case 'empty_remark':
                $message = 'Remark cannot be empty.';
                break;
            case 'invalid_type':
                $message = 'Deduct amount should be less than the Total Amount.';
                break;
            default:
                $message = 'An error occurred.';
        }

        echo "<div class='error'><p>$message</p></div>";
    }
}
add_action('admin_notices', 'wallet_update_error_notice');

function wallet_updated_messages($messages) {
    global $post_ID;
    $post_type = get_post_type($post_ID);

    if (isset($_GET['wallet_error'])) {
        return array();
    }

    if ($post_type === 'wallet') {
        $messages[$post_type] = array(
            0 => '', 
            1 => sprintf(__('Wallet updated.'), esc_url(get_permalink($post_ID))),
        );
    }

    return $messages;
}
add_filter('post_updated_messages', 'wallet_updated_messages');

// Add meta box with input field and label
function add_custom_meta_box() {
    add_meta_box(
        'wallet_meta_box',
        'Custom Metas Box',
        'custom_meta_box_callback',
        'wallet',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_box');

// Meta box callback function
function custom_meta_box_callback($post) {
    // Retrieve existing value from the database.
    $user_id = get_post_meta($post->ID, 'user_id', true);
    
    // Retrieve total credit amount from the wp_wallet table based on user ID
    global $wpdb;
    $users_wallet_table = $wpdb->prefix . 'wallet';
    $wallet_table_details = $wpdb->get_results("SELECT * FROM $users_wallet_table WHERE userId = $user_id ORDER BY id");
    wp_nonce_field('save_input_field_value', 'wallet_meta_box_nonce');

    $total_credit_amount = $wpdb->get_var("SELECT SUM(amount) FROM $users_wallet_table WHERE userId = $user_id AND type = '1'");
    $total_deduct_amount = $wpdb->get_var("SELECT SUM(amount) FROM $users_wallet_table WHERE userId = $user_id AND type = '0'");
    if ($total_credit_amount != '' || $total_credit_amount != 0) {
        $total_credit_amount = $total_credit_amount - $total_deduct_amount;
    } else {
        $total_credit_amount = 0;
    }
    
    // Retrieve existing meta values
    $input_field_value = get_post_meta($post->ID, 'wallet_amount', true);
    $input_remark_value = get_post_meta($post->ID, 'wallet_remark', true);
    $input_wallet_type = get_post_meta($post->ID, 'wallet_type', true);
    ?>
        <style>
            #wallet_meta_box .postbox-header {
                display: none;
            }
            #wallet_meta_box .inside {
                background: #fff;
                padding: 20px;
                border: none;
            }
            .wallet_amount_details {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .input_fields {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }
            .input_fields label {
                width: 150px;
            }
            .input_fields input, .input_fields select {
                width: 150px;
            }
            .pos_rel {
                position: relative;
            }
        </style>
        <div class="wallet_wrap">
            <div class="wallet_amount_details">
                <div class="input_fields">
                    <label for="wallet_amount"><strong>Add Credit</strong></label>
                    <input type="number" id="wallet_amount" name="wallet_amount" value="0" /> 
                </div>
                <div class="input_fields">
                    <label for="total_wallet_amount"><strong>Total Amount</strong></label>
                    <div class="pos_rel">
                        <input type="number" id="total_wallet_amount" name="total_wallet_amount" value="<?php echo esc_attr($total_credit_amount); ?>" readonly style="padding-left: 18px;" /> 
                        <span style="position: absolute; left: 10px; transform: translateY(-50%); top: 50%; font-size: 14px;"><?php echo get_woocommerce_currency_symbol(); ?></span>
                    </div>
                </div>
            </div>
            <div class="input_fields">
                <label for="wallet_remark"><strong>Remark</strong></label>
                <input type="text" id="wallet_remark" name="wallet_remark" value="" />
            </div>
            <div class="input_fields">
                <label for="wallet_type"><strong>Type</strong></label>
                <select name="wallet_type" id="wallet_type">
                    <?php
                        if($wallet_table_details){
                            $types = array();
                            foreach ($wallet_table_details as $wallet_table_type) {
                                $types[] = $wallet_table_type->type;
                            }
                            $types = array_unique($types); // Get unique types
                            foreach ($types as $type) {
                                if($type != 1) {
                                    $text = 'Deduct';
                                } else {
                                    $text = 'Add';
                                }
                        ?>
                            <option value="<?php echo esc_attr($type); ?>" <?php selected($input_wallet_type, $type); ?>><?php echo esc_html(ucfirst($text)); ?></option>
                            <?php
                                if(in_array('1',$types) && !in_array('0',$types)) { // If user has only type 1, shows deduct option
                                    ?>
                                        <option value="0">Deduct</option>
                                    <?php
                                }
                            ?>
                        <?php
                            }
                        } else {
                        ?>
                            <option value="1" <?php selected($input_wallet_type, '1'); ?>>Add</option>
                        <?php
                            
                        }
                    ?>
                </select>
            </div>
            <input type="hidden" id="user_id" name="user_id" value="<?php echo esc_attr($user_id); ?>" />
        </div>
        <?php
            if($wallet_table_details) {
                ?>
                    <div class="wallet_table_wrap">
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th scope="col">S.no</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Created At</th>
                                </tr>
                            </thead>
                            <tbody id="the-list">
                                <?php
                                $sno = 1;
                                foreach ($wallet_table_details as $wallet_table_detail) {
                                    $type = $wallet_table_detail->type;
                                    if($type != 1) {
                                        $type = 'Deducted';
                                    } else {
                                        $type = 'Credited';
                                    }
                                    $amount = $wallet_table_detail->amount;
                                    $created = $wallet_table_detail->created_at;
                                    $remark = $wallet_table_detail->remark;
                                    ?>
                                    <tr>
                                        <td><?= $sno; ?></td>
                                        <td><?= $type; ?></td>
                                        <td><?php echo get_woocommerce_currency_symbol(). '' .$amount ; ?></td>
                                        <td><?= $remark; ?></td>
                                        <td><?= $created; ?></td>
                                    </tr>
                                    <?php
                                    $sno++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
            }
        ?>
    <?php
}

add_action('woocommerce_admin_order_data_after_billing_address', 'custom_meta_box_callback');