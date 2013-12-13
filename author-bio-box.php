<?php
/**
 * Plugin Name: Author Bio Box
 * Plugin URI: https://github.com/claudiosmweb/author-bio-box
 * Description: Display a box with the author's biography and also social icons in bottom of the post.
 * Author: claudiosanches
 * Author URI: http://claudiosmweb.com/
 * Version: 2.0.0
 * License: GPLv2 or later
 * Text Domain: authorbiobox
 * Domain Path: /languages/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Sets the plugin path.
define( 'AUTHOR_BIO_BOX_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Author_Bio_Box class.
 *
 * @since 2.0.0
 */
class Author_Bio_Box {

	/**
	 * Class construct.
	 */
	public function __construct() {
		// Load textdomain.
		add_action( 'plugins_loaded', array( $this, 'languages' ), 0 );

		// Scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Display the box.
		add_filter( 'the_content', array( $this, 'display' ), 9999 );
	}

	/**
	 * Load translations.
	 */
	public function languages() {
		load_plugin_textdomain( 'authorbiobox', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Register scripts.
	 */
	public function scripts() {
		wp_register_style( 'authorbiobox-style', plugins_url( 'assets/css/box.css', __FILE__ ), array(), '2.0.0', 'all' );
	}

	/**
	 * Checks if can display the box.
	 *
	 * @param  array $settings Author Bio Box settings.
	 *
	 * @return bool
	 */
	public function is_display( $settings ) {
		$display = false;

		switch( $settings['display'] ) {
			case 'posts':
				$display = is_single();
				break;
			case 'home_posts':
				$display = is_single() || is_home();
				break;

			default:
				$display = false;
				break;
		}

		return $display;
	}

	/**
	 * HTML of the box.
	 *
	 * @param  array $settings Author Bio Box settings.
	 *
	 * @return string          Author Bio Box HTML.
	 */
	public function html_box( $settings ) {

		// Load the styles.
		wp_enqueue_style( 'authorbiobox-style' );

		// Set the gravatar size.
		$gravatar = ! empty( $settings['gravatar'] ) ? $settings['gravatar'] : 70;

		// Set the social icons
		$social = array(
			'facebook'   => get_the_author_meta( 'facebook' ),
			'twitter'    => get_the_author_meta( 'twitter' ),
			'googleplus' => get_the_author_meta( 'googleplus' ),
			'linkedin'   => get_the_author_meta( 'linkedin' )
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
		$html .= '<h3><a style="color: ' . $settings['title_color'] . ';" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( __( 'All posts by', 'authorbiobox' ) . ' ' . get_the_author() ) .'" rel="author">' . get_the_author() . '</a></h3>';
		$html .= '<div class="bio-gravatar">' . get_avatar( get_the_author_meta('ID'), $gravatar ) . '</div>';

		foreach ( $social as $key => $value ) {
			if ( ! empty( $value ) )
				$html .= '<a target="_blank" href="' . esc_url( $value ) . '" class="bio-icon bio-icon-' . $key . '"></a>';
		}

		$html .= '<p class="bio-description">' . apply_filters( 'authorbiobox_author_description', get_the_author_meta( 'description') ) . '</p>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Insert the box in the content.
	 *
	 * @param  string $content WP the content.
	 *
	 * @return string          WP the content with Author Bio Box.
	 */
	public function display( $content ) {
		// Get the settings.
		$settings = get_option( 'authorbiobox_settings' );

		if ( $this->is_display( $settings ) )
			return $content . $this->html_box( $settings );
		else
			return $content;
	}
}

require_once AUTHOR_BIO_BOX_PATH . 'author-bio-box-admin.php';

$author_bio_box = new Author_Bio_Box;
$author_bio_box_admin = new Author_Bio_Box_Admin;

/**
 * Shows the Author Bio Box.
 *
 * @return string Author Bio Box HTML.
 */
function get_author_bio_box() {
	global $author_bio_box;

	return $author_bio_box->html_box( get_option( 'authorbiobox_settings' ) );
}

/**
 * Shows the Author Bio Box legacy.
 *
 * @return string Author Bio Box HTML.
 */
function authorbbio_add_authorbox() {
	echo get_author_bio_box();
}
