<?php
/**
 * post-types.php – Custom post types
 *
 * All admin only (no front-end URLs), pulled into pages by page builder modules:
 * - testimonial:      Testimonials module. Title = name, featured image = photo.
 * - team-member:      People module. Title = name, featured image = photo.
 * - reusable-section: Reusable Section module. Built with the page builder, so
 *                     one edit updates every page using it.
 *
 * Member stories are normal posts in the Member Stories category.
 *
 * Fields: acf-json/group_ttng_testimonial_details.json and
 *         acf-json/group_ttng_team_member.json
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the theme's custom post types.
 *
 * @return void
 */
function ttng_register_post_types() {

	// Settings shared by every admin-only post type.
	$admin_only = array(
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_rest'        => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'has_archive'         => false,
		'rewrite'             => false,
	);

	register_post_type(
		'testimonial',
		$admin_only + array(
			'labels'        => array(
				'name'               => __( 'Testimonials', 'ttng-main-site-theme' ),
				'singular_name'      => __( 'Testimonial', 'ttng-main-site-theme' ),
				'add_new'            => __( 'Add Testimonial', 'ttng-main-site-theme' ),
				'add_new_item'       => __( 'Add New Testimonial', 'ttng-main-site-theme' ),
				'edit_item'          => __( 'Edit Testimonial', 'ttng-main-site-theme' ),
				'new_item'           => __( 'New Testimonial', 'ttng-main-site-theme' ),
				'search_items'       => __( 'Search Testimonials', 'ttng-main-site-theme' ),
				'not_found'          => __( 'No testimonials found', 'ttng-main-site-theme' ),
				'not_found_in_trash' => __( 'No testimonials found in Trash', 'ttng-main-site-theme' ),
				'all_items'          => __( 'All Testimonials', 'ttng-main-site-theme' ),
				'featured_image'     => __( 'Photo', 'ttng-main-site-theme' ),
				'set_featured_image' => __( 'Set photo', 'ttng-main-site-theme' ),
				'remove_featured_image' => __( 'Remove photo', 'ttng-main-site-theme' ),
			),
			'menu_position' => 21,
			'menu_icon'     => 'dashicons-format-quote',
			'supports'      => array( 'title', 'thumbnail', 'revisions' ),
		)
	);

	register_post_type(
		'team-member',
		$admin_only + array(
			'labels'        => array(
				'name'               => __( 'Team Members', 'ttng-main-site-theme' ),
				'singular_name'      => __( 'Team Member', 'ttng-main-site-theme' ),
				'add_new'            => __( 'Add Team Member', 'ttng-main-site-theme' ),
				'add_new_item'       => __( 'Add New Team Member', 'ttng-main-site-theme' ),
				'edit_item'          => __( 'Edit Team Member', 'ttng-main-site-theme' ),
				'new_item'           => __( 'New Team Member', 'ttng-main-site-theme' ),
				'search_items'       => __( 'Search Team Members', 'ttng-main-site-theme' ),
				'not_found'          => __( 'No team members found', 'ttng-main-site-theme' ),
				'not_found_in_trash' => __( 'No team members found in Trash', 'ttng-main-site-theme' ),
				'all_items'          => __( 'All Team Members', 'ttng-main-site-theme' ),
				'featured_image'     => __( 'Photo', 'ttng-main-site-theme' ),
				'set_featured_image' => __( 'Set photo', 'ttng-main-site-theme' ),
				'remove_featured_image' => __( 'Remove photo', 'ttng-main-site-theme' ),
			),
			'menu_position' => 22,
			'menu_icon'     => 'dashicons-groups',
			'supports'      => array( 'title', 'thumbnail', 'page-attributes', 'revisions' ),
		)
	);

	register_post_type(
		'reusable-section',
		$admin_only + array(
			'labels'        => array(
				'name'               => __( 'Reusable Sections', 'ttng-main-site-theme' ),
				'singular_name'      => __( 'Reusable Section', 'ttng-main-site-theme' ),
				'add_new'            => __( 'Add Section', 'ttng-main-site-theme' ),
				'add_new_item'       => __( 'Add New Reusable Section', 'ttng-main-site-theme' ),
				'edit_item'          => __( 'Edit Reusable Section', 'ttng-main-site-theme' ),
				'new_item'           => __( 'New Reusable Section', 'ttng-main-site-theme' ),
				'search_items'       => __( 'Search Reusable Sections', 'ttng-main-site-theme' ),
				'not_found'          => __( 'No reusable sections found', 'ttng-main-site-theme' ),
				'not_found_in_trash' => __( 'No reusable sections found in Trash', 'ttng-main-site-theme' ),
				'all_items'          => __( 'All Reusable Sections', 'ttng-main-site-theme' ),
			),
			'menu_position' => 23,
			'menu_icon'     => 'dashicons-controls-repeat',
			'supports'      => array( 'title', 'revisions' ),
		)
	);
}
add_action( 'init', 'ttng_register_post_types' );

/**
 * Testimonial and team member titles are the person's name, so say so.
 *
 * @param string  $title Placeholder text.
 * @param WP_Post $post  Current post.
 * @return string
 */
function ttng_person_title_placeholder( $title, $post ) {
	if ( in_array( $post->post_type, array( 'testimonial', 'team-member' ), true ) ) {
		return __( "Person's name", 'ttng-main-site-theme' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'ttng_person_title_placeholder', 10, 2 );

/**
 * Flush rewrite rules once when the theme is activated.
 *
 * @return void
 */
function ttng_flush_rewrites_on_switch() {
	ttng_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'ttng_flush_rewrites_on_switch' );
