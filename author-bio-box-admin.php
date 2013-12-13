<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Author_Bio_Box_Admin class.
 *
 * @since 2.0.0
 */
class Author_Bio_Box_Admin {

	/**
	 * Class construct.
	 */
	public function __construct() {
		// Adds admin menu.
		add_action( 'admin_menu', array( $this, 'menu' ) );

		// Init plugin options form.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );

		// Custom contact methods.
		add_filter( 'user_contactmethods', array( $this, 'contact_methods' ), 10, 1 );

		// Scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		// Install default settings.
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		// Actions links.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
	}

	/**
	 * Adds custom settings url in plugins page.
	 *
	 * @param  array $links Default links.
	 *
	 * @return array        Default links and settings link.
	 */
	public function action_links( $links ) {

		$settings = array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'options-general.php?page=authorbiobox' ),
				__( 'Settings', 'authorbiobox' )
			)
		);

		return array_merge( $settings, $links );
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
		$methods['facebook']   = __( 'Facebook', 'authorbiobox' );
		$methods['twitter']    = __( 'Twitter', 'authorbiobox' );
		$methods['googleplus'] = __( 'Google Plus', 'authorbiobox' );
		$methods['linkedin']   = __( 'LinkedIn', 'authorbiobox' );

		// Remove old methods.
		unset( $methods['aim'] );
		unset( $methods['yim'] );
		unset( $methods['jabber'] );

		return $methods;
	}

	/**
	 * Register admin scripts.
	 */
	public function scripts() {
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );

		wp_register_script( 'authorbiobox-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), null, true );
		wp_enqueue_script( 'authorbiobox-admin' );
	}

	/**
	 * Sets default settings
	 *
	 * @return array Plugin default settings.
	 */
	protected function default_settings() {

		$settings = array(
			'settings' => array(
				'title' => __( 'Settings', 'authorbiobox' ),
				'type' => 'section',
				'menu' => 'authorbiobox_settings'
			),
			'display' => array(
				'title' => __( 'Display in', 'authorbiobox' ),
				'default' => 'posts',
				'type' => 'select',
				'description' => sprintf( __( 'You can display the box directly into your theme using: %s', 'authorbiobox' ), '<br /><code>&lt;?php if ( function_exists( \'get_author_bio_box\' ) ) echo get_author_bio_box(); ?&gt;</code>' ),
				'section' => 'settings',
				'menu' => 'authorbiobox_settings',
				'options' => array(
					'posts' => __( 'Only in Posts', 'authorbiobox' ),
					'home_posts' => __( 'Homepage and Posts', 'authorbiobox' ),
					'none' => __( 'None', 'authorbiobox' ),
				)
			),
			'design' => array(
				'title' => __( 'Design', 'authorbiobox' ),
				'type' => 'section',
				'menu' => 'authorbiobox_settings'
			),
			'gravatar' => array(
				'title' => __( 'Gravatar size', 'authorbiobox' ),
				'default' => 70,
				'type' => 'text',
				'description' => sprintf( __( 'Set the Gravatar size (only integers). To configure the profile picture of the author you need to register in %s.', 'authorbiobox' ), '<a href="gravatar.com">gravatar.com</a>' ),
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'background_color' => array(
				'title' => __( 'Background color', 'authorbiobox' ),
				'default' => '#f8f8f8',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'text_color' => array(
				'title' => __( 'Text color', 'authorbiobox' ),
				'default' => '#333333',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'title_color' => array(
				'title' => __( 'Title color', 'authorbiobox' ),
				'default' => '#555555',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
			'border_size' => array(
				'title' => __( 'Border size', 'authorbiobox' ),
				'default' => 2,
				'type' => 'text',
				'section' => 'design',
				'description' => __( 'Thickness of the top and bottom edge of the box (only integers).', 'authorbiobox' ),
				'menu' => 'authorbiobox_settings'
			),
			'border_style' => array(
				'title' => __( 'Border style', 'authorbiobox' ),
				'default' => 'solid',
				'type' => 'select',
				'section' => 'design',
				'menu' => 'authorbiobox_settings',
				'options' => array(
					'none' => __( 'None', 'authorbiobox' ),
					'solid' => __( 'Solid', 'authorbiobox' ),
					'dotted' => __( 'Dotted', 'authorbiobox' ),
					'dashed' => __( 'Dashed', 'authorbiobox' )
				)
			),
			'border_color' => array(
				'title' => __( 'Border color', 'authorbiobox' ),
				'default' => '#cccccc',
				'type' => 'color',
				'section' => 'design',
				'menu' => 'authorbiobox_settings'
			),
		);

		return $settings;
	}

	/**
	 * Installs default settings on plugin activation.
	 */
	public function install() {
		$settings = array();

		foreach ( $this->default_settings() as $key => $value ) {
			if ( 'section' != $value['type'] ) {
				if ( 'authorbiobox_design' == $value['menu'] ) {
					$design[ $key ] = $value['default'];
				} else {
					$settings[ $key ] = $value['default'];
				}
			}
		}

		add_option( 'authorbiobox_settings', $settings );
	}

	/**
	 * Update plugin settings.
	 */
	public function update() {
		if ( get_option( 'authorbbox_img' ) ) {

			$settings = array(
				'gravatar'         => get_option( 'authorbbox_img' ),
				'background_color' => get_option( 'authorbbox_bg' ),
				'border_size'      => get_option( 'authorbbox_bwidth' ),
				'border_color'     => get_option( 'authorbbox_bcolor' ),
				'text_color'       => '#333333',
				'title_color'      => '#555555'
			);

			switch ( get_option( 'authorbbox_bstyle' ) ) {
				case 'Solida':
					$settings['border_style'] = 'solid';
					break;
				case 'Pontilhada':
					$settings['border_style'] = 'dotted';
					break;
				case 'Tracejada':
					$settings['border_style'] = 'dashed';
					break;

				default:
					$settings['border_style'] = 'none';
					break;
			}

			if ( 2 == get_option( 'authorbbox_function' ) ) {
				$settings['display'] = 'none';
			} else {
				if ( 'posts' == get_option( 'authorbbox_show' ) )
					$settings['display'] = 'posts';
				else
					$settings['display'] = 'home_posts';
			}

			// Updates options
			update_option( 'authorbiobox_settings', $settings );

			// Removes old options.
			delete_option('authorbbox_img');
			delete_option('authorbbox_bg');
			delete_option('authorbbox_bwidth');
			delete_option('authorbbox_bstyle');
			delete_option('authorbbox_bcolor');
			delete_option('authorbbox_show');
			delete_option('authorbbox_function');

		} else {
			// Install default options.
			$this->install();
		}
	}

	/**
	 * Add plugin settings menu.
	 */
	public function menu() {
		add_options_page(
			__( 'Author Bio Box', 'authorbiobox' ),
			__( 'Author Bio Box', 'authorbiobox' ),
			'manage_options',
			'authorbiobox',
			array( &$this, 'settings_page' )
		);
	}

	/**
	 * Plugin settings page.
	 */
	public function settings_page() {
		$settings = get_option( 'authorbiobox_settings' );

		// Create tabs current class.
		$current_tab = '';
		if ( isset( $_GET['tab'] ) )
			$current_tab = $_GET['tab'];
		else
			$current_tab = 'settings';

		?>

		<div class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			<h2><?php _e( 'Author Bio Box', 'authorbiobox' ); ?></h2>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'authorbiobox_settings' );
					do_settings_sections( 'authorbiobox_settings' );
					submit_button();
				?>
			</form>
		</div>

		<?php
	}

	/**
	 * Plugin settings form fields.
	 */
	public function plugin_settings() {
		$settings = 'authorbiobox_settings';

		// Create option in wp_options.
		if ( false == get_option( $settings ) )
			$this->update();

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
						array( &$this , 'text_element_callback' ),
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
						array( &$this , 'select_element_callback' ),
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
						array( &$this , 'color_element_callback' ),
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
		register_setting( $settings, $settings, array( &$this, 'validate_options' ) );
	}

	/**
	 * Text element fallback.
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Text field.
	 */
	public function text_element_callback( $args ) {
		$menu = $args['menu'];
		$id = $args['id'];
		$class = isset( $args['class'] ) ? $args['class'] : 'small-text';

		$options = get_option( $menu );

		if ( isset( $options[$id] ) )
			$current = $options[$id];
		else
			$current = isset( $args['default'] ) ? $args['default'] : '';

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" />', $id, $menu, $current, $class );

		// Displays option description.
		if ( isset( $args['description'] ) )
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );

		echo $html;
	}

	/**
	 * Select field fallback.
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Select field.
	 */
	public function select_element_callback( $args ) {
		$menu = $args['menu'];
		$id  = $args['id'];

		$options = get_option( $menu );

		// Sets current option.
		if ( isset( $options[$id] ) )
			$current = $options[$id];
		else
			$current = isset( $args['default'] ) ? $args['default'] : '';

		$html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $menu );
		foreach( $args['options'] as $key => $label ) {
			$key = sanitize_title( $key );

			$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $label );
		}
		$html .= '</select>';

		// Displays the description.
		if ( $args['description'] )
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );

		echo $html;
	}

	/**
	 * Color element fallback.
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Color field.
	 */
	public function color_element_callback( $args ) {
		$menu = $args['menu'];
		$id = $args['id'];

		$options = get_option( $menu );

		if ( isset( $options[$id] ) )
			$current = $options[$id];
		else
			$current = isset( $args['default'] ) ? $args['default'] : '#333333';

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="author-bio-box-color-field" />', $id, $menu, $current );

		// Displays option description.
		if ( isset( $args['description'] ) )
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );

		echo $html;
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

				// Strip all HTML and PHP tags and properly handle quoted strings.
				$output[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		// Return the array processing any additional functions filtered by this action.
		return apply_filters( 'authorbiobox_validate_input', $output, $input );
	}

} // Close Author_Bio_Box class.
