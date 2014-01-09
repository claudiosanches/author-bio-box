<?php
/**
 * Author Bio Box.
 *
 * @package   Author_Bio_Box
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @copyright 2013 Claudio Sanches
 *
 * @wordpress-plugin
 * Plugin Name:       Author Bio Box
 * Plugin URI:        https://github.com/claudiosmweb/author-bio-box
 * Description:       Display a box with the author's biography and also social icons in bottom of the post.
 * Version:           3.1.1
 * Author:            claudiosanches
 * Author URI:        http://claudiosmweb.com/
 * Text Domain:       author-bio-box
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/claudiosmweb/author-bio-box
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Include plugin main class.
 */
require_once plugin_dir_path( __FILE__ ) . 'public/class-author-bio-box.php';

/**
 * Perform installation.
 */
register_activation_hook( __FILE__, array( 'Author_Bio_Box', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Author_Bio_Box', 'deactivate' ) );

/**
 * Initialize the plugin instance.
 */
add_action( 'plugins_loaded', array( 'Author_Bio_Box', 'get_instance' ) );

/**
 * Plugin admin.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-author-bio-box-admin.php';

	add_action( 'plugins_loaded', array( 'Author_Bio_Box_Admin', 'get_instance' ) );
}

/**
 * Shows the Author Bio Box.
 *
 * @return string Author Bio Box HTML.
 */
function get_author_bio_box() {
	return Author_Bio_Box::view( get_option( 'authorbiobox_settings' ) );
}

/**
 * Shows the Author Bio Box legacy.
 *
 * @return string Author Bio Box HTML.
 */
function authorbbio_add_authorbox() {
	echo get_author_bio_box();
}
