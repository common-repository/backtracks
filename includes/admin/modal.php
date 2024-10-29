<?php

namespace Backtracks\Admin;

class Modal {

	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

		add_action( 'admin_footer', array( $this, 'output' ) );
	}

	public function load_scripts() {

		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		wp_enqueue_script( 'bt-admin-modal', BT_ASSETS . 'js/admin-modal.js', array( 'jquery' ), BT_PLUGIN_VERSION, true );
	}

	public function output() {
		?>

		<div id="bt-admin-modal" class="hidden" style="max-width: 800px;">


				<?php include_once( 'views/editor-button-settings.php' ); ?>

				<p class="submit">
					<input type="button" id="bt-add-player" class="bt-button bt-button-primary" value="<?php esc_attr_e( 'Insert Backtracks Player', 'bt' ); ?>" onclick="insertBacktracksPlayer();" />
					<a id="bt-cancel-add-player" class="bt-button bt-button-secondary" onclick="tb_remove();"><?php esc_html_e( 'Cancel', 'bt' ); ?></a>
				</p>

		</div>

		<?php
	}
}
