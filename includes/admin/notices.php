<?php

namespace Backtracks\Admin;

use Backtracks\Api\Backtracks_API;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Notices {

	public function __construct() {

		add_action( 'admin_notices', array( $this, 'show_notices' ) );
	}

	public function show_notices() {

		// Get api keys
		$api_keys = get_option( 'bt_settings_api' );
		$api_keys = $api_keys['general'];

		$api_keys_dismiss = get_option( 'bt_dismissed_api-keys' );

		if ( get_current_screen()->id === 'toplevel_page_bt_settings' ) {

			if ( false === $api_keys_dismiss && ! Backtracks_API::has_valid_keys() ) {

				if ( empty( $api_keys['access_key'] ) && empty( $api_keys['secret_key'] ) ) { } else {
					?>
					<div data-dismiss-id="api-keys" class="notice notice-error is-dismissible bt-admin-notice">
						<p>
							<?php _e( 'Your Backtracks API Keys are not valid.', 'bt' ); ?>
							<br>
							<?php _e( sprintf( '<strong>Status Code:</strong> %1$s', Backtracks_API::$api_status_code ), 'bt' ); ?>
							<br>
							<?php _e( sprintf( '<strong>Description:</strong> %1$s', Backtracks_API::$api_description ), 'bt' ); ?>
							<br>
							<?php _e( sprintf( '<strong>Error Message:</strong> %1$s', Backtracks_API::$api_error ), 'bt' ); ?>
						</p>
					</div>
					<?php
				}
			}
		}
	}
}
