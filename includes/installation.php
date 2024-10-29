<?php

namespace Backtracks;

use Backtracks\Admin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Installation.
 *
 * Static class that deals with plugin activation and deactivation events.
 *
 * @since 1.0.0
 */
class Installation {

	/**
	 * What happens when the plugin is activated.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {

		self::create_options();

		do_action( 'bt_activated' );
	}

	/**
	 * Sets the default options.
	 *
	 * @since 1.0.0
	 */
	public static function create_options() {

		$default         = array();
		$page            = 'settings';
		$settings_pages  = new Settings( $page );
		$plugin_settings = $settings_pages->get_settings();

		if ( $plugin_settings && is_array( $plugin_settings ) ) {

			foreach ( $plugin_settings as $id => $settings ) {

				$group = 'bt_' . $page . '_' . $id;

				if ( isset( $settings['sections'] ) ) {

					if ( $settings['sections'] && is_array( $settings['sections'] ) ) {

						foreach ( $settings['sections'] as $section_id => $section ) {

							if ( isset( $section['fields'] ) ) {

								if ( $section['fields'] && is_array( $section['fields'] ) ) {

									foreach ( $section['fields'] as $key => $field ) {

										if ( isset ( $field['type'] ) ) {
											// Maybe an associative array.
											if ( is_int( $key ) ) {
												$default[ $section_id ] = self::get_field_default_value( $field );
											} else {
												$default[ $section_id ][ $key ] = self::get_field_default_value( $field );
											}
										}

									} // Loop fields.

								} // Are fields non empty?

							} // Are there fields?

						} // Loop fields sections.

					} // Are sections non empty?

				} // Are there sections?

				add_option( $group, $default, '', true );

				// Reset before looping next settings page.
				$default = array();
			}

		}
	}

	/**
	 * Get field default value.
	 *
	 * Helper function to set the default value of a field.
	 *
	 * @since  1.0.0
	 *
	 * @param  $field
	 *
	 * @return mixed
	 */
	private static function get_field_default_value( $field ) {

		$saved_value   = isset( $field['value'] ) ? $field['value'] : '';
		$default_value = isset( $field['default'] ) ? $field['default'] : '';

		return ! empty( $saved_value ) ? $saved_value : $default_value;
	}

}
