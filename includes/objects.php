<?php

namespace Backtracks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Objects
 *
 * Backtracks object factory
 *
 * @package Backtracks
 * @since 1.0.0
 */
class Objects {

	/**
	 * Objects constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$is_admin = is_admin();

		do_action( 'bt_load_objects', $is_admin );
	}

	/**
	 * Get a field object
	 *
	 * @param        $args
	 * @param string $name
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	public function get_field( $args, $name = '' ) {

		if ( empty( $name ) ) {
			$name = isset( $args['type'] ) ? $args['type'] : false;
		}

		return $name ? $this->get_object( $name, 'field', $args ) : null;
	}

	/**
	 * Get all the admin pages
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_admin_pages() {
		return apply_filters( 'bt_admin_pages_object', array(
			'settings' => array(
				'general',
				'api',
			)
		) );
	}

	/**
	 * Get a page object
	 *
	 * @param $name
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function get_page( $name ) {
		return $name ? $this->get_object( $name, 'page' ) : null;
	}

	/**
	 * Helper function to get the appropriate object being asked for
	 *
	 * @param        $name
	 * @param        $type
	 * @param string $args
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	private function get_object( $name, $type, $args = '' ) {

		$types = array(
			'page',
			'field',
		);

		if ( in_array( $type, $types ) ) {

			$class_name = $this->make_class_name( $name, $type );
			$parent     = '\\' . __NAMESPACE__ . '\Abstracts\\' . implode( '_', array_map( 'ucfirst', explode( '-', $type ) ) );
			$class      = class_exists( $class_name ) ? new $class_name( $args ) : false;

			return $class instanceof $parent ? $class : null;
		}

		return null;
	}

	/**
	 * Make the class name for the object we need
	 *
	 * @param $name
	 * @param $type
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function make_class_name( $name, $type ) {

		if ( 'field' == $type ) {
			$namespace = '\\' . __NAMESPACE__ . '\Admin\Fields\\';
		} elseif ( 'page' == $type ) {
			$namespace = '\\' . __NAMESPACE__ . '\Admin\Pages\\';
		} else {
			return '';
		}

		$class_name = implode( '_', array_map( 'ucfirst', explode( '-', $name ) ) );

		return $namespace . $class_name;
	}
}
