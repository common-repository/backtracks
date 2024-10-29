<?php

namespace Backtracks\Admin\Fields;

use Backtracks\Abstracts\Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Checkbox
 *
 * Handles the functionality of the checkbox field type.
 *
 * @package Backtracks\Admin\Fields
 * @since   1.0.0
 */
class Checkbox extends Field {

	/**
	 * Checkbox constructor.
	 *
	 * @param Field $field
	 *
	 * @since 1.0.0
	 */
	public function __construct( $field ) {
		$this->type_class = 'bt-field-checkboxes';
		parent::__construct( $field );
	}

	/**
	 * Handles checkbox HTML output
	 *
	 * @since 1.0.0
	 */
	public function html() {

		if ( ! empty( $this->options ) && is_array( $this->options ) && count( $this->options ) > 0 ) {

			if ( ! empty( $this->description ) ) {
				echo '<p class="description">' . wp_kses_post( $this->description ) . '</p>';
			}

			?>
			<fieldset class="<?php echo $this->class; ?>" <?php echo ! empty( $this->style ) ? 'style="' . $this->style . '"' : ''; ?>>
				<?php

				if ( ! empty( $this->title ) ) {
					echo '<legend class="screen-reader-text"><span>' . $this->title . '</span></legend>';
				}

				?>
				<ul>
					<?php foreach ( $this->options as $option => $name ) : ?>
						<li>
							<label for="<?php echo $this->id . '-' . trim( strval( $option ) ); ?>">
								<input name="<?php echo $this->name; ?>"
								       id="<?php echo $this->id . '-' . trim( strval( $option ) ); ?>"
								       class="bt-field bt-field-checkbox"
								       type="checkbox"
								       value="<?php echo trim( strval( $option ) ); ?>"
									<?php checked( $this->value, 'yes', true ); ?>
									<?php echo $this->attributes; ?>
								/><?php echo esc_attr( $name ); ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			</fieldset>
			<?php

		} else {

			?>
			<span class="bt-field-bool" <?php echo $this->style ? 'style="' . $this->style . '"' : ''; ?>>
				<?php if ( ! empty( $this->title ) ) : ?>
					<span class="screen-reader-text"><?php echo $this->title; ?></span>
				<?php endif; ?>
				<input name="<?php echo $this->name; ?>"
				       type="checkbox"
				       id="<?php echo $this->id; ?>"
				       class="bt-field bt-field-checkbox <?php echo $this->class; ?>"
				       value="yes"
					<?php checked( $this->value, 'yes', true ); ?>
					<?php echo $this->attributes; ?>/><?php echo( ! empty( $this->text ) ? $this->text : esc_html__( 'Yes', 'bt' ) ); ?>
			</span>
			<?php

			if ( ! empty( $this->description ) ) {
				echo '<p class="description">' . wp_kses_post( $this->description ) . '</p>';
			}

		}
	}
}
