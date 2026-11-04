<?php
/**
 * ctas.php – Renders a CTA repeater (cta_link + cta_style) as buttons.
 *
 * Call inside a page_builder have_rows() loop (or a nested layout row):
 *
 *   get_template_part( 'template-parts/snippets/ctas', null, [
 *       'field' => 'section_ctas', // repeater name
 *       'class' => 'justify-content-center',
 *   ] );
 *
 * @package TTNG_Main_Site_Theme
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args ?? [], [ 'field' => 'section_ctas', 'class' => '' ] );

if ( ! have_rows( $args['field'] ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( trim( 'section-ctas ' . $args['class'] ) ); ?>">
	<?php
	while ( have_rows( $args['field'] ) ) :
		the_row();
		ttng_the_button( get_sub_field( 'cta_link' ), get_sub_field( 'cta_style' ) );
	endwhile;
	?>
</div>
