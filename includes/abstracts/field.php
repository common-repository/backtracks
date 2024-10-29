<?php

namespace Backtracks\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Field
 *
 * Handles settings fields.
 *
 * @package Backtracks\Abstracts
 * @since   1.0.0
 */
abstract class Field {

	/**
	 * Field type
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $type = '';

	/**
	 * Name of the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $name = '';

	/**
	 * ID of the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $id = '';

	/**
	 * The title of the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $title = '';

	/**
	 * The type class added to the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $type_class = '';

	/**
	 * CSS class string for the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $class = '';

	/**
	 * Inline style for the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $style = '';


	/**
	 * Field description
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $description = '';


	/**
	 * Field attributes
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $attributes = '';


	/**
	 * Placeholder text for the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $placeholder = '';


	/**
	 * Options for the field (i.e. for radio buttons, select field, etc)
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $options = array();


	/**
	 * The value to set for the field
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $value = '';

	/**
	 * Field default value
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $default = '';

	/**
	 * Overrirde option for the text label on checkboxes
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $text = '';

	/**
	 * Validation check
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	public $validation = true;

	/**
	 * Field constructor.
	 *
	 * @param $field Field
	 *
	 * @since 1.0.0
	 */
	public function __construct( $field ) {

		// Field properties.
		if ( isset( $field['title'] ) ) {
			$this->title = esc_attr( $field['title'] );
		}

		if ( isset( $field['description'] ) ) {
			$this->description = wp_kses( $field['description'], array(
				'a'      => array(
					'href'   => array(),
					'title'  => array(),
					'class'  => array(),
					'target' => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'span'   => array(
					'class' => array(),
				),
				'p'      => array(
					'class' => array(),
				),
				'code'   => array(),
			) );
		}

		if ( isset( $field['type'] ) ) {
			$this->type = esc_attr( $field['type'] );
		}

		if ( isset( $field['name'] ) ) {
			$this->name = esc_attr( $field['name'] );
		}

		if ( isset( $field['id'] ) ) {
			$this->id = esc_attr( $field['id'] );
		}

		if ( isset( $field['placeholder'] ) ) {
			$this->placeholder = esc_attr( $field['placeholder'] );
		}

		if ( isset( $field['options'] ) && is_array( $field['options'] ) ) {
			$this->options = array_map( 'esc_attr', $field['options'] );
		}

		if ( isset( $field['text'] ) ) {
			$this->text = $field['text'];
		}

		if ( isset( $field['html'] ) ) {
			$this->custom_html = wp_kses_post( $field['html'] );
		}

		// Escaping.
		if ( ! empty( $field['escaping'] ) && ( is_string( $field['escaping'] ) || is_array( $field['escaping'] ) ) ) {
			if ( isset( $field['default'] ) ) {
				$this->default = $this->escape_callback( $field['escaping'], $field['default'] );
			}

			if ( isset( $field['value'] ) ) {
				$this->value = $this->escape_callback( $field['escaping'], $field['value'] );
			}
		} else {
			if ( isset( $field['default'] ) ) {
				$this->default = $this->escape( $field['default'] );
			}

			if ( isset( $field['value'] ) ) {
				$this->value = $this->escape( $field['value'] );
			}
		}

		// Validation.
		if ( ! empty( $field['validation'] ) ) {
			$this->validation = $this->validate( $field['validation'], $this->value );
		}

		// CSS classes and styles.
		$classes = isset( $field['class'] ) ? $field['class'] : array();
		$this->set_class( $classes );
		if ( isset( $field['style'] ) ) {
			$this->set_style( $field['style'] );
		}

		// Custom attributes.
		if ( isset( $field['attributes'] ) ) {
			$this->set_attributes( $field['attributes'] );
		}
	}

	/**
	 * Set the attributes for the field if needed.
	 *
	 * @param $attributes array
	 *
	 * @since 1.0.0
	 */
	public function set_attributes( $attributes ) {

		$attr = '';

		if ( ! empty( $attributes ) && is_array( $attributes ) ) {
			foreach ( $attributes as $k => $v ) {
				$attr .= esc_attr( $k ) . '="' . esc_attr( $v ) . '" ';
			}
		}

		$this->attributes = $attr;
	}

	/**
	 * Set styles for this field
	 *
	 * @param $css string
	 *
	 * @since 1.0.0
	 */
	public function set_style( $css ) {

		$styles = '';

		if ( ! empty( $css ) && is_array( $css ) ) {
			foreach ( $css as $k => $v ) {
				$styles .= esc_attr( $k ) . ': ' . esc_attr( $v ) . '; ';
			}
		}

		$this->style = $styles;
	}

	/**
	 * Add the CSS classes assigned
	 *
	 * @param $class array
	 *
	 * @since 1.0.0
	 */
	public function set_class( $class ) {

		$classes    = '';
		$type_class = '';
		$error      = '';

		if ( ! empty( $class ) && is_array( $class ) ) {
			$classes = implode( ' ', array_map( 'esc_attr', $class ) );
		}

		if ( ! empty( $this->type_class ) ) {
			$type_class = esc_attr( $this->type_class );
		}

		if ( true !== $this->validation && ! empty( $this->validation ) ) {
			$error = 'field-error ';
		}

		$this->class = trim( $error . 'bt-field ' . $type_class . ' ' . $classes );
	}

	/**
	 * How to escape the field
	 *
	 * @param $value
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	protected function escape( $value ) {
		return ! empty( $value ) ? ( is_array( $value ) ? array_map( 'esc_attr', $value ) : esc_attr( $value ) ) : '';
	}

	/**
	 * Run the escape callback if defined
	 *
	 * @param $callback
	 * @param $value
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	protected function escape_callback( $callback, $value ) {
		if ( $callback && ( is_string( $callback ) || is_array( $callback ) ) ) {
			return call_user_func( $callback, $value );
		}

		return esc_attr( $value );
	}

	/**
	 * Validate the field
	 *
	 * @param $callback
	 * @param $value
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	protected function validate( $callback, $value ) {
		if ( $callback && ( is_string( $callback ) || is_array( $callback ) ) ) {
			$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : '';

			return call_user_func( $callback, $value, $screen );
		}

		return true;
	}

	/**
	 * Abstract function implemented in child classes for output
	 *
	 * @return mixed
	 */
	abstract public function html();
}

