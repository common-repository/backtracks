<?php
/**
 * Autoloader
 *
 * @package Backtracks
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'Backtracks_Autoload' ) ) {

	/**
	 * Plugin autoloader.
	 *
	 * Pattern (with or without <Subnamespace>):
	 *  <Namespace>/<Subnamespace>.../Class_Name (or Classname)
	 *  'includes/subdir.../class-name.php' or '...classname.php'
	 *
	 * @param $class
	 * @since 1.0.0
	 */
	function Backtracks_Autoload( $class ) {

		// Do not load unless in plugin domain.
		$namespace = 'Backtracks';

		if ( strpos( $class, $namespace ) !== 0 ) {
			return;
		}

		// Converts Class_Name (class convention) to class-name (file convention).
		$class_name = implode( '-', array_map( 'lcfirst', explode( '_', strtolower( $class ) ) ) );

		// Remove the root namespace.
		$unprefixed = substr( $class_name, strlen( $namespace ) );

		// Build the file path.
		$file_path = str_replace( '\\', DIRECTORY_SEPARATOR, $unprefixed );
		$file      = dirname( __FILE__ ) . $file_path . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}

	}

	// Register the autoloader.
	spl_autoload_register( 'Backtracks_Autoload' );

}
