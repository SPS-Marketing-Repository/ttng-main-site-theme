<?php
// inc/post-labels.php – Posts are called "Insights" across the admin.

defined( 'ABSPATH' ) || exit;

add_action('init', function () {
    global $wp_post_types;

    if (isset($wp_post_types['post'])) {
        $labels = &$wp_post_types['post']->labels;

        $labels->name               = 'Insights';
        $labels->singular_name      = 'Insight';
        $labels->add_new            = 'Add Insight';
        $labels->add_new_item       = 'Add Insight';
        $labels->edit_item          = 'Edit Insight';
        $labels->new_item           = 'Insight';
        $labels->view_item          = 'View Insight';
        $labels->search_items       = 'Search Insights';
        $labels->not_found          = 'No insights found';
        $labels->not_found_in_trash = 'No insights found in Trash';
        $labels->all_items          = 'All Insights';
        $labels->menu_name          = 'Insights';
        $labels->name_admin_bar     = 'Insight';
    }
});
