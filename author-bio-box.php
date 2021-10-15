<?php
/**
 * Plugin Name: Author Bio Box
 * Plugin URI: https://github.com/claudiosanches/author-bio-box
 * Description: Display a box with the author's biography and also social icons in bottom of the post.
 * Version: 3.3.3
 * Author: claudiosanches
 * Author URI: http://claudiosanches.com/
 * Text Domain: author-bio-box
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/claudiosanches/author-bio-box
 *
 * @package ClaudioSanches/AuthorBioBox
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Author_Bio_Box' ) ) {
	include_once __DIR__ . '/includes/class-author-bio-box.php';

	/**
	 * Perform installation.
	 */
	register_activation_hook( __FILE__, array( 'Author_Bio_Box', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Author_Bio_Box', 'deactivate' ) );

	add_action( 'plugins_loaded', array( 'Author_Bio_Box', 'get_instance' ), 0 );
}
