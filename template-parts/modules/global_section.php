<?php
/**
 * Reusable Section Module
 *
 * Outputs the modules saved in a Reusable Section post (e.g. Vision Spotlight,
 * the CTA and form), so one edit updates every page using it.
 * ttng_render_page_builder() blocks a section from including itself or another
 * section.
 *
 * No CSS of its own: each inner module enqueues its own.
**/

defined( 'ABSPATH' ) || exit;

$section_id = absint( get_sub_field( 'reusable_section' ) );

if ( $section_id && 'publish' === get_post_status( $section_id ) && 'reusable-section' === get_post_type( $section_id ) ) {
	ttng_render_page_builder( $section_id );
}
