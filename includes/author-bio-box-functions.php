<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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
 *
 * @return string Author Bio Box HTML.
 */
function authorbbio_add_authorbox() {
	echo get_author_bio_box();
}
