<?php

/**
 * Header & Footer post type
 */
function header_post_type() {
    $labels = array(
        'name' => _x('Header', 'Post Type General Name', 'chronosweep'),
        'singular_name' => _x('Header', 'Post Type Singular Name', 'chronosweep'),
        'menu_name' => __('Header & Footer', 'chronosweep'),
        'parent_item_colon' => __('Parent Header', 'chronosweep'),
        'all_items' => __('All Header', 'chronosweep'),
        'view_item' => __('View Header', 'chronosweep'),
        'add_new_item' => __('Add New Header', 'chronosweep'),
        'add_new' => __('Add New', 'chronosweep'),
        'edit_item' => __('Edit Header', 'chronosweep'),
        'update_item' => __('Update Header', 'chronosweep'),
        'search_items' => __('Search Header', 'chronosweep'),
        'not_found' => __('Not Found', 'chronosweep'),
        'not_found_in_trash' => __('Not found in Trash', 'chronosweep'),
    );

    $args = array(
        'label' => __('header-footer', 'chronosweep'),
        'description' => __('Header', 'chronosweep'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 20,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );

    register_post_type('header', $args);
}

add_action('init', 'header_post_type', 0);

function header_footer_register() {
    remove_menu_page('edit.php?post_type=header');
    $headerPageId = url_to_postid('/header/header-footer');
    add_menu_page(
            'Header & Footer', // page title
            'Header & Footer', // menu title
            'manage_options', // capability
            '/post.php?post=' . $headerPageId . '&action=edit', // menu slug
            '', 'dashicons-admin-page', 21
    );
}
add_action('admin_menu', 'header_footer_register');