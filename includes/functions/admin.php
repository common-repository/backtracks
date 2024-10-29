<?php

/**
 * Backtracks misc admin functions
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Grab all of the admin pages from an object
 *
 * @return array
 * @since 1.0.0
 */
function backtracks_get_admin_pages() {
	$objects = \Backtracks\Backtracks()->objects;

	return $objects instanceof \Backtracks\Objects ? $objects->get_admin_pages() : array();
}

/**
 * Get a specific admin page object
 *
 * @param $page
 *
 * @return bool
 * @since 1.0.0
 */
function backtracks_get_admin_page( $page ) {
	$objects = \Backtracks\Backtracks()->objects;

	return $objects instanceof \Backtracks\Objects ? $objects->get_page( $page ) : null;
}

/**
 * Grab a field object
 *
 * @param        $args
 * @param string $name
 *
 * @return bool
 * @since 1.0.0
 */
function backtracks_get_field( $args, $name = '' ) {
	$objects = \Backtracks\Backtracks()->objects;

	return $objects instanceof \Backtracks\Objects ? $objects->get_field( $args, $name ) : null;
}


function get_selectize_episodes_list( $series_slug = '' ) {
	\Backtracks\Api\Backtracks_API::instance();
	$token = \Backtracks\Api\Backtracks_API::get_access_token();

	//echo $token;

	//echo '<pre>' . print_r( API::get_series_list(), true ) . '</pre>';
	//echo '<pre>' . print_r( API::get_episodes( 'y-combinator' ), true ) . '</pre>';

	$series = \Backtracks\Api\Backtracks_API::get_series_list();

	if ( empty( $series_slug ) ) {
		// Try default slug first
		$general_settings = get_option( 'bt_settings_general' );
		$general_settings = $general_settings['general'];

		if ( ! empty( $general_settings['series_slug'] ) ) {
			$series_slug = $general_settings['series_slug'];
		}
	}

	if ( $series_slug ) {
		return \Backtracks\Api\Backtracks_API::get_episodes( $series_slug );
	}

	return array();
}

function get_selectize_series_list() {
	\Backtracks\Api\Backtracks_API::instance();
	$token = \Backtracks\Api\Backtracks_API::get_access_token();

	//echo $token;

	//echo '<pre>' . print_r( API::get_series_list(), true ) . '</pre>';
	//echo '<pre>' . print_r( API::get_episodes( 'y-combinator' ), true ) . '</pre>';

	$series = \Backtracks\Api\Backtracks_API::get_series_list();



	return $series;

}


