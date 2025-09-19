<?php
function testimonials_post_type()
{
    $labels = array(
        'name' => _x('Testimonials', 'PostType General Name', 'chronosweep'),
        'singular_name' => _x('Testimonials', 'PostType Singular Name', 'chronosweep'),
        'menu_name' => __('Testimonials', 'chronosweep'),
        'parent_item_colon' => __('Parent News', 'chronosweep'),
        'all_items' => __('All Testimonials', 'chronosweep'),
        'view_item' => __('View Testimonials', 'chronosweep'),
        'add_new_item' => __('Add New Testimonials', 'chronosweep'),
        'add_new' => __('Add New', 'chronosweep'),
        'edit_item' => __('Edit Testimonials', 'chronosweep'),
        'update_item' => __('Update Testimonials', 'chronosweep'),
        'search_items' => __('Search Testimonials', 'chronosweep'),
        'not_found' => __('Not Found', 'chronosweep'),
        'not_found_in_trash' => __('Not found in Trash', 'chronosweep'),
    );

    $args = array(
        'label' => __('Testimonials', 'chronosweep'),
        'description' => __('Testimonials', 'chronosweep'),
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
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'rewrite' => array('slug' => 'testimonials'),
    );

    register_post_type('testimonials', $args);
}
add_action('init', 'testimonials_post_type', 0);