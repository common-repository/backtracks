<?php

namespace Backtracks\Admin\Fields;

use Backtracks\Abstracts\Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Select
 *
 * Handles functionality of the select (<select>) field type
 *
 * @package Backtracks\Admin\Fields
 * @since   1.0.0
 */
class Select extends Field {

	/**
	 * CSS class string
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $class = '';

	/**
	 * Select constructor.
	 *
	 * @param Field $field
	 *
	 * @since 1.0.0
	 */
	public function __construct( $field ) {

		$class = 'bt-field-select';

		$classes = isset( $field['class'] ) ? $field['class'] : '';
		if ( ! empty( $classes ) ) {
			if ( is_array( $classes ) ) {
				foreach ( $classes as $class ) {
					$class .= ' ' . $class;
				}
			}
		}

		if ( isset( $field['default'] ) ) {
			$this->default = $field['default'];
		}

		$this->class = $class;


		parent::__construct( $field );
	}

	/**
	 * Handles the HTML output of the select field
	 *
	 * @since 1.0.0
	 */
	public function html() {

		if ( $this->default ) {
			if ( empty( $this->value ) || $this->value == '' ) {
				$this->value = $this->default;
			}
		}

		?>
		<select name="<?php echo $this->name; ?>"
		        id="<?php echo $this->id; ?>"
		        style="<?php echo $this->style; ?>"
		        class="<?php echo $this->class; ?>"
			<?php echo $this->attributes; ?>>
			<?php

			if ( ! empty( $this->options ) && is_array( $this->options ) ) {
				foreach ( $this->options as $option => $name ) {
					if ( is_array( $this->value ) ) {
						$selected = selected( in_array( $option, $this->value ), true, false );
					} else {
						$selected = selected( $this->value, trim( strval( esc_html( $option ) ) ), false );
					}
					echo '<option value="' . $option . '" ' . $selected . '>' . esc_attr( $name ) . '</option>';
				}
			}

			?>
		</select>
		<?php

		if ( ! empty( $this->description ) ) {
			echo '<p class="description">' . wp_kses_post( $this->description ) . '</p>';
		}
	}
}
