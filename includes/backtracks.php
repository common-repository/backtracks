<?php

namespace Backtracks;

use Backtracks\Api\Backtracks_API;
use Backtracks\Shortcodes\Backtracks_Player;
use Backtracks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Backtracks
 *
 * Main plugin class. This is where the magic starts.
 *
 * @package Backtracks
 * @since   1.0.0
 */
final class Backtracks {

	/**
	 * Class instance variable
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Class objects
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $objects = null;

	/**
	 * Backtracks constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->load();

		// Installation hook
		register_activation_hook( BT_MAIN_FILE, array( 'Backtracks\Installation', 'activate' ) );

		// Load plugin settings
		add_action( 'admin_init', array( $this, 'register_settings' ), 5 );

		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

		// Load plugin text domain for localization
		add_action( 'plugins_loaded', 'load_plugin_textdomain' );
	}

	function load_plugin_textdomain() {
		load_plugin_textdomain( 'bt', FALSE, BT_INCLUDES . 'languages/' );
	}

	function load_scripts() {

		// Scripts
		wp_enqueue_script( 'bt-selectize', BT_ASSETS . 'js/selectize.min.js', array( 'jquery' ), BT_PLUGIN_VERSION, true );
		wp_enqueue_script( 'bt-jquery-date', BT_ASSETS . 'js/jquery-dateFormat.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'bt-admin', BT_ASSETS . 'js/admin-main.js', array( 'jquery', 'bt-jquery-date', 'bt-selectize' ), BT_PLUGIN_VERSION, true );

		wp_localize_script( 'bt-admin', 'bt_admin',  array(
			'series_list' => get_selectize_series_list(),
			'episode_list' => get_selectize_episodes_list(),
			'has_valid_keys' => Backtracks_API::has_valid_keys(),
			'has_empty_keys' => Backtracks_API::has_empty_keys(),
			'ajax_nonce' => wp_create_nonce( 'ajax_nonce' ),
		) );

		// Styles
		wp_enqueue_style( 'bt-admin', BT_ASSETS . 'admin.css', array(), BT_PLUGIN_VERSION, 'all' );
		wp_enqueue_style( 'bt-selectize', BT_ASSETS . 'selectize.default.css', array(), BT_PLUGIN_VERSION, 'all' );
	}

	/**
	 * Register the plugin settings
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$settings = new Admin\Settings();
			$settings->register_settings( $settings->get_settings() );
		}
	}

	/**
	 * Get the class instance
	 *
	 * @return Backtracks|bool
	 * @since 1.0.0
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load the plugin
	 *
	 * @since 1.0.0
	 */
	public function load() {

		require_once( 'functions/admin.php' );

		$this->objects = new Objects();

		if ( is_admin() ) {

			new Admin\Menu();

			new Admin\Editor_Button();

			new Admin\Notices();
		} else {

			new Backtracks_Player();
		}

		new Ajax();
	}
}

/**
 * Return the class instance
 *
 * @return Backtracks|bool
 * @since 1.0.0
 */
function Backtracks() {
	return Backtracks::instance();
}

// Initialize the plugin
Backtracks();
