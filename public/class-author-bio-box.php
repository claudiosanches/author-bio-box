<?php
/**
 * Author Bio Box.
 *
 * @package   Author_Bio_Box
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @copyright 2013 Claudio Sanches
 */

/**
 * Author_Bio_Box class.
 *
 * @package Author_Bio_Box
 * @author  Claudio Sanches <contato@claudiosmweb.com>
 */
class Author_Bio_Box {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 3.0.0
	 *
	 * @var   string
	 */
	const VERSION = '3.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since 3.0.0
	 *
	 * @var   string
	 */
	protected static $plugin_slug = 'author-bio-box';

	/**
	 * Instance of this class.
	 *
	 * @since 3.0.0
	 *
	 * @var   object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since 3.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Display the box.
		add_filter( 'the_content', array( $this, 'display' ), 9999 );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since  3.0.0
	 *
	 * @return Plugin slug variable.
	 */
	public static function get_plugin_slug() {
		return self::$plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since  3.0.0
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
	 * Fired when the plugin is activated.
	 *
	 * @since  3.0.0
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
	 * @since  3.0.0
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

				// Get all blog ids
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
	 * @since  3.0.0
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
	 * @since  3.0.0
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
	 * @since  3.0.0
	 *
	 * @return void
	 */
	private static function single_activate() {
		$options = array(
			'display' => 'posts',
			'gravatar' => 70,
			'background_color' => '#f8f8f8',
			'text_color' => '#333333',
			'title_color' => '#555555',
			'border_size' => 2,
			'border_style' => 'solid',
			'border_color' => '#cccccc'
		);

		update_option( 'authorbiobox_settings', $options );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since  3.0.0
	 *
	 * @return void
	 */
	private static function single_deactivate() {
		delete_option( 'authorbiobox_settings' );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since  3.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$domain = self::get_plugin_slug();
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since  3.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( self::get_plugin_slug() . '-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION, 'all' );
	}

	/**
	 * Checks if can display the box.
	 *
	 * @since  3.0.0
	 *
	 * @param  array $settings Author Bio Box settings.
	 *
	 * @return bool
	 */
	protected function is_display( $settings ) {
		switch( $settings['display'] ) {
			case 'posts':
				return is_single();
				break;
			case 'home_posts':
				return is_single() || is_home();
				break;

			default:
				return false;
				break;
		}
	}

	/**
	 * HTML of the box.
	 *
	 * @since  3.0.0
	 *
	 * @param  array $settings Author Bio Box settings.
	 *
	 * @return string          Author Bio Box HTML.
	 */
	public static function view( $settings ) {

		// Load the styles.
		wp_enqueue_style( self::get_plugin_slug() . '-styles' );

		// Set the gravatar size.
		$gravatar = ! empty( $settings['gravatar'] ) ? $settings['gravatar'] : 70;

		// Set the social icons
		$social = array(
			'website'    => get_the_author_meta( 'user_url' ),
			'facebook'   => get_the_author_meta( 'facebook' ),
			'flickr'	 => get_the_author_meta( 'flickr' ),
			'googleplus' => get_the_author_meta( 'googleplus' ),
			'linkedin'   => get_the_author_meta( 'linkedin' ),
			'tumblr'	 => get_the_author_meta( 'tumblr' ),
			'twitter'    => get_the_author_meta( 'twitter' ),
			'vimeo'		 => get_the_author_meta( 'vimeo' ),
			'youtube'	 => get_the_author_meta( 'youtube' )
		);

		// Set the styes.
		$styles = sprintf(
			'background: %1$s; border-top: %2$spx %3$s %4$s; border-bottom: %2$spx %3$s %4$s; color: %5$s',
			$settings['background_color'],
			$settings['border_size'],
			$settings['border_style'],
			$settings['border_color'],
			$settings['text_color']
		);

		$html = '<div id="author-bio-box" style="' . $styles . '">';
		$html .= '<h3><a style="color: ' . $settings['title_color'] . ';" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( __( 'All posts by', self::get_plugin_slug() ) . ' ' . get_the_author() ) .'" rel="author">' . get_the_author() . '</a></h3>';
		$html .= '<div class="bio-gravatar">' . get_avatar( get_the_author_meta('ID'), $gravatar ) . '</div>';

		foreach ( $social as $key => $value ) {
			if ( ! empty( $value ) ) {
				$html .= '<a target="_blank" href="' . esc_url( $value ) . '" class="bio-icon bio-icon-' . $key . '"></a>';
			}
		}

		$html .= '<p class="bio-description">' . apply_filters( 'authorbiobox_author_description', get_the_author_meta( 'description' ) ) . '</p>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Insert the box in the content.
	 *
	 * @since  3.0.0
	 *
	 * @param  string $content WP the content.
	 *
	 * @return string          WP the content with Author Bio Box.
	 */
	public function display( $content ) {
		// Get the settings.
		$settings = get_option( 'authorbiobox_settings' );

		if ( $this->is_display( $settings ) ) {
			return $content . self::view( $settings );
		}

		return $content;
	}

}
