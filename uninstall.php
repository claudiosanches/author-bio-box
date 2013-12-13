<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Author_Bio_Box
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @copyright 2013 Claudio Sanches
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'authorbiobox_settings' );
