<?php
/**
 * section-header.php – Shared label / title / content block for modules.
 *
 * Reads the shared sub fields section_label, section_title, section_content
 * from the current page_builder row. Call inside a have_rows() loop:
 *
 *   get_template_part( 'template-parts/snippets/section-header', null, [
 *       'tag'   => 'h2',      // h1 | h2 (home banner uses h1)
 *       'class' => 'text-center',
 *   ] );
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$args    = wp_parse_args( $args ?? [], [ 'tag' => 'h2', 'class' => '' ] );
$tag     = in_array( $args['tag'], [ 'h1', 'h2' ], true ) ? $args['tag'] : 'h2';
$label   = get_sub_field( 'section_label' );
$title   = get_sub_field( 'section_title' );
$content = get_sub_field( 'section_content' );

if ( ! $label && ! $title && ! $content ) {
	return;
}
?>
<div class="<?php echo esc_attr( trim( 'section-header content-block ' . $args['class'] ) ); ?>">
	<?php if ( $label ) : ?>
		<p class="section-label"><?php echo ttng_format_label( $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
	<?php endif; ?>

	<?php if ( $title ) : ?>
		<<?php echo esc_html( $tag ); ?> class="section-title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $tag ); ?>>
	<?php endif; ?>

	<?php if ( $content ) : ?>
		<div class="section-content"><?php echo wp_kses_post( $content ); ?></div>
	<?php endif; ?>
</div>
