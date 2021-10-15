<?php
/**
 * Color input HTML.
 *
 * @package ClaudioSanches/AuthorBioBox
 */

defined( 'ABSPATH' ) || exit;
?>

<input type="text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $menu ); ?>[<?php echo esc_attr( $id ); ?>]" value="<?php echo sanitize_hex_color( $current ); ?>" class="author-bio-box-color-field" />

<?php if ( ! empty( $args['description'] ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $args['description'] ); ?></p>
	<?php
endif;
