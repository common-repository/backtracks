<?php

namespace Backtracks\Api;

class Backtracks_API {

	private static $secret_key = null;
	private static $access_key = null;
	private static $instance = null;

	public static $request_args = array();
	public static $access_token = null;

	public static $api_status_code = '';
	public static $api_description = '';
	public static $api_error = '';

	public function __construct() {

		// Set the keys first
		$this->set_keys();

		self::$request_args = array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'headers' => array( 'Content-type' => 'application/json' ),
			'cookies' => array()
		);

	}

	public function set_keys() {

		$api_keys = get_option( 'bt_settings_api' );
		$api_keys = $api_keys['general'];

		self::$secret_key = $api_keys['secret_key'];
		self::$access_key = $api_keys['access_key'];
	}

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	public static function get_access_token() {

		$url = 'https://api.backtracks.fm/auth';

		self::$request_args['body'] = json_encode( array( 'access_key' => self::$access_key, 'secret_key' => self::$secret_key ) );

		$response = wp_remote_post( $url, self::$request_args );


		if ( is_wp_error( $response ) ) {
			return self::send_error_message( '', '' );
		}

		$body = json_decode( $response['body'] );

		if ( isset( $body->description ) ) {
			self::$api_description = $body->description;
		}

		if ( isset( $body->error ) ) {
			self::$api_error = $body->error;
		}

		if ( isset( $body->status_code ) ) {
			self::$api_status_code = $body->status_code;
		}

		// Set class instance access token so we can re-use it later
		if ( ! empty( $body->access_token ) ) {
			self::$access_token = $body->access_token;
		}

		return self::$access_token;
	}


	public static function get_series_list() {

		$url = 'https://api.backtracks.fm/v1/series';

		$series = array();

		$request_args = array(
			'method' => 'GET',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'headers' => array( 'Content-type' => 'application/json', 'Authorization' => 'JWT ' . self::$access_token ),
			'cookies' => array()
		);

		$response = wp_remote_get( $url, $request_args );

		if ( is_wp_error( $response ) ) {

			echo '<pre>' . print_r( $response, true ) . '</pre>';

			//return self::send_error_message( '', '' );
		}

		$body = json_decode( $response['body'] );

		$series = isset( $body->series ) ? $body->series : array();


		if ( ! empty( $body->_links->next->href ) ) {

			$next = $body->_links->next->href;

			while ( ! empty( $next ) ) {
				$response = wp_remote_get( $next, $request_args );

				if ( is_wp_error( $response ) ) {

					echo '<pre>' . print_r( $response, true ) . '</pre>';
					break;
				}

				$body = json_decode( $response['body'] );

				if ( ! empty( $body->_links->next->href ) ) {
					$next = $body->_links->next->href;
					$episodes = array_merge( $series, $body->episodes );
				} else {
					$next = false;
				}
			}
		}

		if ( ! empty( $body->series ) ) {
			return $body->series;
		}

		return null;
	}

	public static function get_episodes( $series_slug_or_id ) {

		$url = 'https://api.backtracks.fm/v1/series/' . $series_slug_or_id . '/episodes';
		$episodes = array();

		$request_args = array(
			'method' => 'GET',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'headers' => array( 'Content-type' => 'application/json', 'Authorization' => 'JWT ' . self::$access_token ),
			'cookies' => array()
		);

		$response = wp_remote_get( $url, $request_args );

		if ( is_wp_error( $response ) ) {

			echo '<pre>' . print_r( $response, true ) . '</pre>';

			//return self::send_error_message( '', '' );
		}

		$body = json_decode( $response['body'] );

		$episodes = isset( $body->episodes ) ? $body->episodes : array();


		if ( ! empty( $body->_links->next->href ) ) {

			$next = $body->_links->next->href;

			while ( ! empty( $next ) ) {
				$response = wp_remote_get( $next, $request_args );

				if ( is_wp_error( $response ) ) {

					echo '<pre>' . print_r( $response, true ) . '</pre>';
					break;
				}

				$body = json_decode( $response['body'] );

				if ( ! empty( $body->episodes ) ) {
					$episodes = array_merge( $episodes, $body->episodes );
				}

				if ( ! empty( $body->_links->next->href ) ) {
					$next = $body->_links->next->href;
				} else {
					$next = false;
				}
			}
		}

		if ( ! empty( $episodes ) ) {

			$episodes = self::format_release_dates( $episodes );

			return $episodes;
		}

		return null;
	}

	public static function format_release_dates( $episodes ) {

		//wp_die( '<pre>' . print_r( $episodes, true ) . '</pre>' );

		foreach( $episodes as $episode ) {

			if ( ! empty( $episode->release_date ) ) {

				$date = $episode->release_date;

				$today = date( 'Y-m-d H:i:s' );

				if ( $date > $today ) {
					$episode->publish_status = 2;
				}

				//$episode->release_date = date_i18n( get_option( 'date_format' ) . ' - ' . get_option( 'time_format' ), strtotime( $date ) );
				// sprintf( '%1$s at %2$s', date_i18n( get_option( 'date_format' ), date_i18n( get_option( 'time_format' ) )
				$episode->release_date = get_date_from_gmt( $date, sprintf( '%1$s - %2$s', get_option( 'date_format' ), get_option( 'time_format' ) ) );
				$episode->release_date_utc = date_i18n( get_option( 'date_format' ) . ' - ' . get_option( 'time_format' ), strtotime( $date ) );


			}

		}

		return $episodes;
	}

	public static function has_valid_keys() {

		$api_keys = get_option( 'bt_settings_api' );
		$api_keys = $api_keys['general'];

		if ( ! empty( $api_keys['access_key'] ) && ! empty( $api_keys['secret_key'] ) ) {
			if ( empty( self::$api_status_code ) ) {
				return true;
			}
		}

		return false;
	}

	public static function has_empty_keys() {

		$api_keys = get_option( 'bt_settings_api' );
		$api_keys = $api_keys['general'];

		if ( empty( $api_keys['access_key'] ) && empty( $api_keys['secret_key'] ) ) {
			return true;
		}

		return false;
	}

	private static function send_error_message( $status, $message ) {

		return 'An error occurred.';
	}
}
