<?php

namespace Backtracks\Admin\Pages;

use Backtracks\Abstracts\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class API extends Page {

	/**
	 * General constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->id           = 'api';
		$this->option_group = 'settings';
		$this->label        = esc_html__( 'API', 'bt' );
		$this->link_text    = esc_html__( 'Help docs for API Settings', 'bt' );

		$this->sections = $this->add_sections();
		$this->fields   = $this->add_fields();

		add_filter( 'pre_update_option_bt_settings_api', array( $this, 'set_admin_notice_options' ), 10, 2 );
	}


	function set_admin_notice_options( $new_value, $old_value ) {

		delete_option( 'bt_dismissed_api-keys' );

		return $new_value;
	}

	/**
	 * Adds the page sections
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_sections() {

		return apply_filters( 'bt_admin_settings_general_sections', array(
			'general' => array(
				'title' => '',
			),
		) );

	}

	/**
	 * Adds the fields to the sections
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function add_fields() {

		$fields       = array();
		$this->values = get_option( 'bt_' . $this->option_group . '_' . $this->id );

		if ( isset( $this->sections ) && is_array( $this->sections ) ) {
			foreach ( $this->sections as $section => $a ) {

				$section = sanitize_key( $section );

				if ( 'general' == $section ) {

					$fields[ $section ] = array(
						'access_key'      => array(
							'title'       => esc_html__( 'Access Key', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][access_key]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-access-key',
							'value'       => $this->get_setting_value( $section, 'access_key' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Backtracks API access key', 'bt' ),
						),
						'secret_key'      => array(
							'title'       => esc_html__( 'Secret Key', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][secret_key]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-secret-key',
							'value'       => $this->get_setting_value( $section, 'secret_key' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Backtracks API secret key', 'bt' ),
						),
					);
				}
			}
		}

		return apply_filters( 'bt_add_' . $this->option_group . '_' . $this->id . '_fields', $fields );
	}
}
