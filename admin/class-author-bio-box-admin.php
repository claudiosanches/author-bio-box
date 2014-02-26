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
 * Author_Bio_Box_Admin class.
 *
 * @package   Author_Bio_Box_Admin
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 */
class Author_Bio_Box_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since 3.0.0
	 *
	 * @var   object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since 3.0.0
	 *
	 * @var   string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 3.0.0
	 */
	private function __construct() {

		$this->plugin_slug = Author_Bio_Box::get_plugin_slug();

		// Custom contact methods.
		add_filter( 'user_contactmethods', array( $this, 'contact_methods' ), 10, 1 );

		// Load admin JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Init plugin options form.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );
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
	 * Sets default settings.
	 *
	 * @since  3.0.0
	 *
	 * @return array Plugin default settings.
	 */
	protected function default_settings() {

		$settings = array(
			'settings' => array(
				'title' => __( 'Settings', $this->plugin_slug ),
				'type' => 'section',
				'menu' => 'authorbiobox_settings'
			),
			'display' => array(
				'title' => __( 'Display in', $this->plugin_slug ),
				'default' => 'posts',
				'type' => 'select',
				'description' => sprintf( __( 'You can display the box directly into your theme using: %s', $this->plugin_slug ), '<br /><code>&lt;?php if ( function_exists( \'get_author_bio_box\' ) ) echo get_author_bio_box(); ?&gt;</code>' ),
				'section' => 'settings',
				'menu' => 'authorbiobox_settings',
				'options' => array(
					'posts' => __( 'Only in Posts', $this->plugin_slug ),
					'home_posts' => __( 'Homepage and Posts', $this->plugin_slug ),
					'none' => __( 'None', $this->plugin_slug ),
				)
			),
			'design' => array(
				'title' => __( 'Design', $this->plugin_slug ),
				'type' => 'section',
				'menu' => 'authorbiobox_settings'
			),
			'gravatar' => array(
				'title' => __( 'Gravatar size', $this->plugin_slug ),
				'default' => 70,
				'type' => 'text',
				'description' => sprintf( __( 'Set the Gravatar size (only integers). To configure the profile picture of the author you need to register in %s.', $this->plugin_slug ), '<a href="gravatar.com">gravatar.com</a>' ),
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'background_color' => array(
				'title' => __( 'Background color', $this->plugin_slug ),
				'default' => '#f8f8f8',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'text_color' => array(
				'title' => __( 'Text color', $this->plugin_slug ),
				'default' => '#333333',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'title_color' => array(
				'title' => __( 'Title color', $this->plugin_slug ),
				'default' => '#555555',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'border_size' => array(
				'title' => __( 'Border size', $this->plugin_slug ),
				'default' => 2,
				'type' => 'text',
				'section' => 'design',
				'description' => __( 'Thickness of the top and bottom edge of the box (only integers).', $this->plugin_slug ),
				'menu' => 'authorbiobox_settings'
			),
			'border_style' => array(
				'title' => __( 'Border style', $this->plugin_slug ),
				'default' => 'solid',
				'type' => 'select',
				'section' => 'design',
				'menu' => 'authorbiobox_settings',
				'options' => array(
					'none' => __( 'None', $this->plugin_slug ),
					'solid' => __( 'Solid', $this->plugin_slug ),
					'dotted' => __( 'Dotted', $this->plugin_slug ),
					'dashed' => __( 'Dashed', $this->plugin_slug )
				)
			),
			'border_color' => array(
				'title' => __( 'Border color', $this->plugin_slug ),
				'default' => '#cccccc',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
		);

		return $settings;
	}

	/**
	 * Custom contact methods.
	 *
	 * @since  3.0.0
	 *
	 * @param  array $methods Old contact methods.
	 *
	 * @return array          New contact methods.
	 */
	public function contact_methods( $methods ) {
		// Add new methods.
		$methods['facebook']   = __( 'Facebook', $this->plugin_slug );
		$methods['twitter']    = __( 'Twitter', $this->plugin_slug );
		$methods['googleplus'] = __( 'Google Plus', $this->plugin_slug );
		$methods['linkedin']   = __( 'LinkedIn', $this->plugin_slug );
		$methods['flickr']     = __( 'Flickr', $this->plugin_slug );
		$methods['tumblr']     = __( 'Tumblr', $this->plugin_slug );
		$methods['vimeo']      = __( 'Vimeo', $this->plugin_slug );
		$methods['youtube']    = __( 'YouTube', $this->plugin_slug );

		// Remove old methods.
		unset( $methods['aim'] );
		unset( $methods['yim'] );
		unset( $methods['jabber'] );

		return $methods;
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since  3.0.0
	 *
	 * @return null Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				$this->plugin_slug . '-admin',
				plugins_url( 'assets/js/admin.min.js', __FILE__ ),
				array( 'jquery', 'wp-color-picker' ),
				null,
				true
			);
		}
	}

	/**
	 * Register the administration menu for this plugin into the
	 * WordPress Dashboard menu.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Author Bio Box', $this->plugin_slug ),
			__( 'Author Bio Box', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @since  3.0.0
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
						$value['title'],
						'__return_false',
						$value['menu']
					);
					break;
				case 'text':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'text_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu' => $value['menu'],
							'id' => $key,
							'class' => 'small-text',
							'description' => isset( $value['description'] ) ? $value['description'] : ''
						)
					);
					break;
				case 'select':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'select_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu' => $value['menu'],
							'id' => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : '',
							'options' => $value['options']
						)
					);
					break;
				case 'color':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'color_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu' => $value['menu'],
							'id' => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : ''
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
	 * Text element fallback.
	 *
	 * @since  3.0.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Text field.
	 */
	public function text_element_callback( $args ) {
		$menu  = $args['menu'];
		$id    = $args['id'];
		$class = isset( $args['class'] ) ? $args['class'] : 'small-text';

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '';
		}

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" />', $id, $menu, $current, $class );

		// Displays option description.
		if ( isset( $args['description'] ) ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Select field fallback.
	 *
	 * @since  3.0.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Select field.
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

		$html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $menu );
		foreach( $args['options'] as $key => $label ) {
			$key = sanitize_title( $key );

			$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $label );
		}
		$html .= '</select>';

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Color element fallback.
	 *
	 * @since  3.0.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Color field.
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

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="author-bio-box-color-field" />', $id, $menu, $current );

		// Displays option description.
		if ( isset( $args['description'] ) ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Valid options.
	 *
	 * @since  3.0.0
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

				// Strip all HTML and PHP tags and properly handle quoted strings.
				$output[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		return $output;
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 3.0.0
	 */
	public function display_plugin_admin_page() {
		include_once 'views/admin.php';
	}

}
