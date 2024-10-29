<?php
/**
 * Uninstall routine
 *
 * @since 1.0.0
 */

// Exit if not uninstalling from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove general settings
delete_option( 'bt_settings_general' );

// Remove API Key Settings
delete_option( 'bt_settings_api' );

// Admin notice option
delete_option( 'bt_dismissed_api-keys' );
