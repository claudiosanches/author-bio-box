<?php
/**
 * Plugin Name: Author Bio Box
 * Plugin URI: https://github.com/claudiosmweb/author-bio-box
 * Description: Display a box with the author's biography and also social icons in bottom of the post.
 * Version: 3.3.0
 * Author: claudiosanches
 * Author URI: http://claudiosmweb.com/
 * Text Domain: author-bio-box
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/claudiosmweb/author-bio-box
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Author_Bio_Box' ) ) :

/**
 * Author Bio Box main class.
 */
class Author_Bio_Box {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '3.3.0';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin public actions.
	 */
	private function __construct() {
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			$this->admin_includes();
		}

		$this->includes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get assets url.
	 *
	 * @return string
	 */
	public static function get_assets_url() {
		return plugins_url( 'assets/', __FILE__ );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'author-bio-box' );

		load_textdomain( 'author-bio-box', trailingslashit( WP_LANG_DIR ) . 'author-bio-box/author-bio-box-' . $locale . '.mo' );
		load_plugin_textdomain( 'author-bio-box', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Includes.
	 *
	 * @return void
	 */
	private function includes() {
		include_once 'includes/class-author-bio-box-frontend.php';
		include_once 'includes/author-bio-box-functions.php';
	}

	/**
	 * Admin includes.
	 *
	 * @return void
	 */
	private function admin_includes() {
		include_once 'includes/admin/class-author-bio-box-admin.php';
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses
	 *                               "Network Activate" action, false if
	 *                               WPMU is disabled or plugin is
	 *                               activated on an individual blog.
	 *
	 * @return void
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses
	 *                               "Network Deactivate" action, false if
	 *                               WPMU is disabled or plugin is
	 *                               deactivated on an individual blog.
	 *
	 * @return void
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids.
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param  int  $blog_id ID of the new blog.
	 *
	 * @return void
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @return array|false The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @return void
	 */
	private static function single_activate() {
		$options = array(
			'display'          => 'posts',
			'gravatar'         => 70,
			'background_color' => '#f8f8f8',
			'text_color'       => '#333333',
			'title_color'      => '#555555',
			'border_size'      => 2,
			'border_style'     => 'solid',
			'border_color'     => '#cccccc'
		);

		update_option( 'authorbiobox_settings', $options );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @return void
	 */
	private static function single_deactivate() {
		delete_option( 'authorbiobox_settings' );
	}
}

/**
 * Perform installation.
 */
register_activation_hook( __FILE__, array( 'Author_Bio_Box', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Author_Bio_Box', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Author_Bio_Box', 'get_instance' ), 0 );

endif;
