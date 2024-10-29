<?php
/**
* Plugin Name: Backtracks
* Description: WordPress plugin for Backtracks.
* Author: Backtracks
* Author URI:  https://backtracks.fm/?ref=wp&utm_source=wp&utm_medium=plugin_listing
* Version: 1.0.1
* Text Domain: bt
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_path = trailingslashit( dirname( __FILE__ ) );
$plugin_dir  = plugin_dir_url( __FILE__ );

// Define plugin version
if ( ! defined( 'BT_PLUGIN_VERSION' ) ) {
	define( 'BT_PLUGIN_VERSION', '1.0.1' );
}

// Define includes/ path
if ( ! defined( 'BT_INCLUDES' ) ) {
	define( 'BT_INCLUDES', $plugin_path . 'includes/' );
}

// Define assets/ path
if ( ! defined( 'BT_ASSETS' ) ) {
	define( 'BT_ASSETS', $plugin_dir . 'assets/' );
}

if ( ! defined( 'BT_MAIN_FILE' ) ) {
	define( 'BT_MAIN_FILE', __FILE__ );
}

// Init autoloader
require_once( BT_INCLUDES . 'autoload.php' );

// Init plugin
require_once( BT_INCLUDES . 'backtracks.php' );
