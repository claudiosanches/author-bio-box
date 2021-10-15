<?php
/**
 * Select input HTML.
 *
 * @package ClaudioSanches/AuthorBioBox
 */

defined( 'ABSPATH' ) || exit;
?>

<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $menu ); ?>[<?php echo esc_attr( $id ); ?>]">
	<?php foreach ( $args['options'] as $key => $label ) : ?>
		<?php $key = sanitize_title( $key ); ?>
		<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current, $key, true ); ?>><?php echo esc_attr( $label ); ?></option>
	<?php endforeach; ?>
</select>

<?php if ( ! empty( $args['description'] ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $args['description'] ); ?></p>
	<?php
endif;
