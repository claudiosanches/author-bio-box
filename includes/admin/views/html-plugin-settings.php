<?php
/**
 * Settings page HTML.
 *
 * @package ClaudioSanches/AuthorBioBox
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'authorbiobox_settings' );
			do_settings_sections( 'authorbiobox_settings' );
			submit_button();
		?>
	</form>
</div>
