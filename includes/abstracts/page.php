<?php

namespace Backtracks\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Page
 *
 * Handles admin settings pages
 *
 * @package Backtracks\Abstracts
 * @since 1.0.0
 */
abstract class Page {

	/**
	 * Page ID
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $id = '';

	/**
	 * Option group to assign page to
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $option_group = '';

	/**
	 * Label of the page
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $label = '';

	/**
	 * Page description
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $description = '';

	/**
	 * Sections on the page
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $sections;

	/**
	 * Fields for the page
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $fields;

	/**
	 * Saved values
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $values = array();

	/**
	 * Abstract function implemented by child classes to add the page sections
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	abstract public function add_sections();

	/**
	 * Abstract class implemented by child classes to add the page fields
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	abstract public function add_fields();

	/**
	 * Get the settings for this page
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_settings() {

		$settings = array();

		$settings[ $this->id ] = array(
			'label'       => $this->label,
			'description' => $this->description,
		);

		if ( ! empty( $this->sections ) && is_array( $this->sections ) ) {

			foreach ( $this->sections as $section => $content ) {

				$section = sanitize_key( $section );

				$settings[ sanitize_key( $this->id ) ]['sections'][ $section ] = array(
					'title'       => isset( $content['title'] ) ? sanitize_text_field( $content['title'] ) : '',
					'description' => isset( $content['description'] ) ? sanitize_text_field( $content['description'] ) : '',
					'callback'    => array( $this, 'add_settings_section_callback' ),
					'fields'      => isset( $this->fields[ $section ] ) ? $this->fields[ $section ] : '',
				);

			}

		}

		return apply_filters( 'bt_get_' . $this->option_group . '_' . $this->id, $settings );
	}

	/**
	 * Get a specific setting value
	 *
	 * @param $section
	 * @param $setting
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_setting_value( $section, $setting ) {

		$option = $this->values;

		if ( ! empty( $option ) && is_array( $option ) ) {
			return isset( $option[ $section ][ $setting ] ) ? $option[ $section ][ $setting ] : '';
		}

		return '';
	}

	/**
	 * Add the settings page callbacks
	 *
	 * @param $section
	 *
	 * @since 1.0.0
	 */
	public function add_settings_section_callback( $section ) {

		$callback    = isset( $section['callback'][0] ) ? $section['callback'][0] : '';
		$sections    = isset( $callback->sections ) ? $callback->sections : '';
		$description = isset( $sections[ $section['id'] ]['description'] ) ? $sections[ $section['id'] ]['description'] : '';
		$default     = $description ? '<p>' . $description . '</p>' : '';

		echo apply_filters( 'bt_' . $this->option_group . '_' . $this->id . '_sections_callback', $default );
	}

	/**
	 * Validate settings
	 *
	 * @param $settings
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function validate( $settings ) {

		$trimmed = array();

		if ( is_array( $settings ) ) {
			foreach ( $settings as $k => $v ) {
				foreach ( $v as $k2 => $v2 ) {
					$trimmed[ $k ][ $k2 ] = trim( $v2 );
				}
			}

			return $trimmed;
		}

		return $settings;
	}
}
