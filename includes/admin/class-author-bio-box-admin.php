<?php
/**
 * Admin class.
 *
 * @package ClaudioSanches/AuthorBioBox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Author Bio Box Admin class.
 */
class Author_Bio_Box_Admin {

	/**
	 * Slug of the plugin screen.
	 *
	 * @var string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin admin.
	 */
	public function __construct() {

		// Custom contact methods.
		add_filter( 'user_contactmethods', array( $this, 'contact_methods' ), 13, 1 );

		// Load admin JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Init plugin options form.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );
	}

	/**
	 * Sets default settings.
	 *
	 * @return array Plugin default settings.
	 */
	protected function default_settings() {

		$settings = array(
			'settings'         => array(
				'title' => __( 'Settings', 'author-bio-box' ),
				'type'  => 'section',
				'menu'  => 'authorbiobox_settings',
			),
			'display'          => array(
				'title'       => __( 'Display in', 'author-bio-box' ),
				'default'     => 'posts',
				'type'        => 'select',
				/* translators: %s: function example */
				'description' => sprintf( __( 'You can display the box directly into your theme using: %s', 'author-bio-box' ), '<br /><code>&lt;?php if ( function_exists( \'get_author_bio_box\' ) ) echo get_author_bio_box(); ?&gt;</code>' ),
				'section'     => 'settings',
				'menu'        => 'authorbiobox_settings',
				'options'     => array(
					'posts'      => __( 'Only in Posts', 'author-bio-box' ),
					'home_posts' => __( 'Homepage and Posts', 'author-bio-box' ),
					'none'       => __( 'None', 'author-bio-box' ),
				),
			),
			'design'           => array(
				'title' => __( 'Design', 'author-bio-box' ),
				'type'  => 'section',
				'menu'  => 'authorbiobox_settings',
			),
			'gravatar'         => array(
				'title'       => __( 'Gravatar size', 'author-bio-box' ),
				'default'     => 70,
				'type'        => 'number',
				/* translators: %s: gravatar.com link */
				'description' => sprintf( __( 'Set the Gravatar size (only integers). To configure the profile picture of the author you need to register in %s.', 'author-bio-box' ), '<a href="gravatar.com">gravatar.com</a>' ),
				'section'     => 'design',
				'menu'        => 'authorbiobox_settings',
			),
			'background_color' => array(
				'title'   => __( 'Background color', 'author-bio-box' ),
				'default' => '#f8f8f8',
				'type'    => 'color',
				'section' => 'design',
				'menu'    => 'authorbiobox_settings',
			),
			'text_color'       => array(
				'title'   => __( 'Text color', 'author-bio-box' ),
				'default' => '#333333',
				'type'    => 'color',
				'section' => 'design',
				'menu'    => 'authorbiobox_settings',
			),
			'title_color'      => array(
				'title'   => __( 'Title color', 'author-bio-box' ),
				'default' => '#555555',
				'type'    => 'color',
				'section' => 'design',
				'menu'    => 'authorbiobox_settings',
			),
			'border_size'      => array(
				'title'       => __( 'Border size', 'author-bio-box' ),
				'default'     => 2,
				'type'        => 'number',
				'section'     => 'design',
				'description' => __( 'Thickness of the top and bottom edge of the box (only integers).', 'author-bio-box' ),
				'menu'        => 'authorbiobox_settings',
			),
			'border_style'     => array(
				'title'   => __( 'Border style', 'author-bio-box' ),
				'default' => 'solid',
				'type'    => 'select',
				'section' => 'design',
				'menu'    => 'authorbiobox_settings',
				'options' => array(
					'none'   => __( 'None', 'author-bio-box' ),
					'solid'  => __( 'Solid', 'author-bio-box' ),
					'dotted' => __( 'Dotted', 'author-bio-box' ),
					'dashed' => __( 'Dashed', 'author-bio-box' ),
				),
			),
			'border_color'     => array(
				'title'   => __( 'Border color', 'author-bio-box' ),
				'default' => '#cccccc',
				'type'    => 'color',
				'section' => 'design',
				'menu'    => 'authorbiobox_settings',
			),
		);

		return $settings;
	}

	/**
	 * Custom contact methods.
	 *
	 * @param  array $methods Old contact methods.
	 *
	 * @return array          New contact methods.
	 */
	public function contact_methods( $methods ) {
		// Add new methods.
		$methods['twitter']    = __( 'Twitter', 'author-bio-box' );
		$methods['facebook']   = __( 'Facebook', 'author-bio-box' );
		$methods['twitter']    = __( 'Twitter', 'author-bio-box' );
		$methods['googleplus'] = __( 'Google Plus', 'author-bio-box' );
		$methods['linkedin']   = __( 'LinkedIn', 'author-bio-box' );
		$methods['flickr']     = __( 'Flickr', 'author-bio-box' );
		$methods['tumblr']     = __( 'Tumblr', 'author-bio-box' );
		$methods['vimeo']      = __( 'Vimeo', 'author-bio-box' );
		$methods['youtube']    = __( 'YouTube', 'author-bio-box' );
		$methods['instagram']  = __( 'Instagram', 'author-bio-box' );
		$methods['pinterest']  = __( 'Pinterest', 'author-bio-box' );
		$methods['mastodon']   = __( 'Mastodon', 'author-bio-box' );
		$methods['pixelfed']   = __( 'Pixelfed', 'author-bio-box' );
		$methods['peertube']   = __( 'PeerTube', 'author-bio-box' );

		// Remove old methods.
		unset( $methods['aim'] );
		unset( $methods['yim'] );
		unset( $methods['jabber'] );
		//unset( $methods['googleplus'] );

		return $methods;
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @return null Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( $this->plugin_screen_hook_suffix === $screen->id ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script( 'author-bio-box-admin', Author_Bio_Box::get_assets_url() . 'js/admin' . $suffix . '.js', array( 'jquery', 'wp-color-picker' ), Author_Bio_Box::VERSION, true );
		}
	}

	/**
	 * Register the administration menu.
	 *
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Author Bio Box', 'author-bio-box' ),
			__( 'Author Bio Box', 'author-bio-box' ),
			'manage_options',
			'author-bio-box',
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @return void
	 */
	public function plugin_settings() {
		$settings = 'authorbiobox_settings';

		foreach ( $this->default_settings() as $key => $value ) {

			switch ( $value['type'] ) {
				case 'section':
					add_settings_section(
						$key,
						esc_html( $value['title'] ),
						'__return_false',
						$value['menu']
					);
					break;
				case 'number':
					add_settings_field(
						$key,
						esc_html( $value['title'] ),
						array( $this, 'number_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu'        => $value['menu'],
							'id'          => $key,
							'class'       => 'small-text',
							'description' => isset( $value['description'] ) ? $value['description'] : '',
						)
					);
					break;
				case 'select':
					add_settings_field(
						$key,
						esc_html( $value['title'] ),
						array( $this, 'select_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu'        => $value['menu'],
							'id'          => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : '',
							'options'     => $value['options'],
						)
					);
					break;
				case 'color':
					add_settings_field(
						$key,
						esc_html( $value['title'] ),
						array( $this, 'color_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu'        => $value['menu'],
							'id'          => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : '',
						)
					);
					break;

				default:
					break;
			}
		}

		// Register settings.
		register_setting( $settings, $settings, array( $this, 'validate_options' ) );
	}

	/**
	 * Number element fallback.
	 *
	 * @param array $args Field arguments.
	 */
	public function number_element_callback( $args ) {
		$menu  = $args['menu'];
		$id    = $args['id'];
		$class = isset( $args['class'] ) ? $args['class'] : 'small-text';

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '';
		}

		include __DIR__ . '/views/html-number-input.php';
	}

	/**
	 * Select field fallback.
	 *
	 * @param array $args Field arguments.
	 */
	public function select_element_callback( $args ) {
		$menu = $args['menu'];
		$id   = $args['id'];

		$options = get_option( $menu );

		// Sets current option.
		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '';
		}

		include __DIR__ . '/views/html-select-input.php';
	}

	/**
	 * Color element fallback.
	 *
	 * @param array $args Field arguments.
	 */
	public function color_element_callback( $args ) {
		$menu = $args['menu'];
		$id   = $args['id'];

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '#333333';
		}

		include __DIR__ . '/views/html-color-input.php';
	}

	/**
	 * Valid options.
	 *
	 * @param  array $input options to valid.
	 *
	 * @return array        validated options.
	 */
	public function validate_options( $input ) {
		// Create our array for storing the validated options.
		$output = array();

		// Loop through each of the incoming options.
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {
				if ( in_array( $key, array( 'gravatar', 'border_size' ), true ) ) {
					$output[ $key ] = (int) $input[ $key ];
				} elseif ( in_array( $key, array( 'background_color', 'text_color', 'title_color', 'border_color' ), true ) ) {
					$output[ $key ] = sanitize_hex_color( $input[ $key ] );
				} else {
					$output[ $key ] = sanitize_text_field( $input[ $key ] );
				}
			}
		}

		return $output;
	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function display_plugin_admin_page() {
		include_once 'views/html-plugin-settings.php';
	}

}

new Author_Bio_Box_Admin();
