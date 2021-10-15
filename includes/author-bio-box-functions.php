<?php
/**
 * Functions.
 *
 * @package ClaudioSanches/AuthorBioBox
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shows the Author Bio Box.
 *
 * @return string Author Bio Box HTML.
 */
function get_author_bio_box() {
	return Author_Bio_Box_Frontend::view( get_option( 'authorbiobox_settings' ) );
}

/**
 * Shows the Author Bio Box legacy.
 */
function authorbbio_add_authorbox() {
	echo wp_kses_post( get_author_bio_box() );
}
