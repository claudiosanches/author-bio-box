<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Author Bio Box Frontend class.
 */
class Author_Bio_Box_Frontend {

	/**
	 * Initialize the frontend actions.
	 */
	public function __construct() {
		// Load public-facing style sheet.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Display the box.
		add_filter( 'the_content', array( $this, 'display' ), 9999 );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( 'author-bio-box-styles', Author_Bio_Box::get_assets_url() . 'css/author-bio-box.css', array(), Author_Bio_Box::VERSION, 'all' );
	}

	/**
	 * Checks if can display the box.
	 *
	 * @param  array $settings Author Bio Box settings.
	 *
	 * @return bool
	 */
	protected function is_display( $settings ) {
		$display = false;

		if ( 'posts' == $settings['display'] ) {
			$display = is_single();
		} else if ( 'home_posts' == $settings['display'] ) {
			$display = is_single() || is_home();
		}

		return apply_filters( 'authorbiobox_display', $display );
	}

	/**
	 * HTML of the box.
	 *
	 * @param  array $settings Author Bio Box settings.
	 *
	 * @return string          Author Bio Box HTML.
	 */
	public static function view( $settings ) {

		// Load the styles.
		wp_enqueue_style( 'author-bio-box-styles' );

		// Set the gravatar size.
		$gravatar = ! empty( $settings['gravatar'] ) ? $settings['gravatar'] : 70;

		// Set the social icons
		$social = apply_filters( 'authorbiobox_social_data', array(
			'website'    => get_the_author_meta( 'user_url' ),
			'facebook'   => get_the_author_meta( 'facebook' ),
			'twitter'    => get_the_author_meta( 'twitter' ),
			'googleplus' => get_the_author_meta( 'googleplus' ),
			'linkedin'   => get_the_author_meta( 'linkedin' ),
			'flickr'	 => get_the_author_meta( 'flickr' ),
			'tumblr'	 => get_the_author_meta( 'tumblr' ),
			'vimeo'		 => get_the_author_meta( 'vimeo' ),
			'youtube'	 => get_the_author_meta( 'youtube' ),
			'instagram'	 => get_the_author_meta( 'instagram' ),
			'pinterest'	 => get_the_author_meta( 'pinterest' )
		) );

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
		$html .= '<h3><a style="color: ' . $settings['title_color'] . ';" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( __( 'All posts by', 'author-bio-box' ) . ' ' . get_the_author() ) .'" rel="author">' . get_the_author() . '</a></h3>';
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

new Author_Bio_Box_Frontend();
