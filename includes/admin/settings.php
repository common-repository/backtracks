<?php

namespace Backtracks\Admin;

use Backtracks\Abstracts\Field;
use Backtracks\Abstracts\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Settings
 *
 * Handles the admin settings
 *
 * @package Backtracks\Admin
 * @since 1.0.0
 */
class Settings {

	/**
	 * Admin page
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $page = '';

	/**
	 * Admin page tab
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $tab = '';

	/**
	 * Page settings
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $settings = array();


	/**
	 * Settings constructor.
	 *
	 * @param string $page
	 * @since 1.0.0
	 */
	public function __construct( $page = 'settings' ) {

		$this->page     = $page;
		$settings_pages = ! is_null( \Backtracks\Backtracks()->objects ) ? backtracks_get_admin_pages() : '';

		$settings_page_tabs = array();
		$tabs               = isset( $settings_pages[ $page ] ) ? $settings_pages[ $page ] : false;

		if ( $tabs && is_array( $tabs ) ) {
			foreach ( $tabs as $tab ) {

				$settings_page = backtracks_get_admin_page( $tab );

				if ( $settings_page instanceof Page ) {
					$settings_page_tabs[ $settings_page->id ] = $settings_page;
				}
			}

			$this->settings = $settings_page_tabs;
		}

		// The first tab is the default tab when opening a page.
		$this->tab = isset( $tabs[0] ) ? $tabs[0] : '';

		do_action( 'bt_admin_pages', $page );
	}

	/**
	 * Get the page settings
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_settings() {

		$settings = array();

		if ( ! empty( $this->settings ) && is_array( $this->settings ) ) {
			foreach ( $this->settings as $id => $object ) {

				if ( $object instanceof Page ) {

					$settings_page = $object->get_settings();

					if ( isset( $settings_page[ $id ] ) ) {
						$settings[ $id ] = $settings_page[ $id ];
					}
				}

			}
		}

		return $settings;
	}

	/**
	 * Register the page settings
	 *
	 * @param array $settings
	 * @since 1.0.0
	 */
	public function register_settings( $settings = array() ) {

		$settings = $settings ? $settings : $this->get_settings();

		if ( ! empty( $settings ) && is_array( $settings ) ) {

			foreach ( $settings as $tab_id => $settings_page ) {

				if ( isset( $settings_page['sections'] ) ) {

					$sections = $settings_page['sections'];

					if ( ! empty( $sections ) && is_array( $sections ) ) {

						foreach ( $sections as $section_id => $section ) {

							add_settings_section( $section_id, isset( $section['title'] ) ? $section['title'] : '', isset( $section['callback'] ) ? $section['callback'] : '', 'bt_' . $this->page . '_' . $tab_id );

							if ( isset( $section['fields'] ) ) {

								$fields = $section['fields'];

								if ( ! empty( $fields ) && is_array( $fields ) ) {

									foreach ( $fields as $field ) {

										if ( isset( $field['id'] ) && isset( $field['type'] ) ) {

											$field_object = backtracks_get_field( $field, $field['type'] );

											if ( $field_object instanceof Field ) {

												add_settings_field( $field['id'], isset( $field['title'] ) ? $field['title'] : '', array(
													$field_object,
													'html',
												), 'bt_' . $this->page . '_' . $tab_id, $section_id, array( 'label_for' => $field['id'] ) );
											}
										}
									}
								}
							}

							$page = backtracks_get_admin_page( $tab_id );

							register_setting( 'bt_' . $this->page . '_' . $tab_id, 'bt_' . $this->page . '_' . $tab_id, $page instanceof Page ? array(
								$page,
								'validate',
							) : '' );

						}
					}
				}
			}
		}
	}

	/**
	 * Output the page settings
	 *
	 * @since 1.0.0
	 */
	public function html() {

		global $current_tab;

		// Get current tab/section
		$current_tab = empty( $_GET['tab'] ) ? $this->tab : sanitize_title( $_GET['tab'] );
		$this->tab   = $current_tab;

		?>
		<div class="wrap" id="bt-main-settings">
			<h1><?php echo get_admin_page_title(); ?></h1>

			<form id="bt-settings-page-form"
			      method="post"
			      action="options.php">
				<?php

				// Include settings pages
				$settings_pages = self::get_settings();
				if ( ! empty( $settings_pages ) && is_array( $settings_pages ) ) {

					echo '<h2 class="nav-tab-wrapper bt-nav-tab-wrapper">';

					// Get tabs for the settings page
					if ( ! empty( $settings_pages ) && is_array( $settings_pages ) ) {

						foreach ( $settings_pages as $id => $settings ) {

							$tab_id    = isset( $id ) ? $id : '';
							$tab_label = isset( $settings['label'] ) ? $settings['label'] : '';
							$tab_link  = admin_url( 'admin.php?page=bt_settings&tab=' . $tab_id );

							echo '<a href="' . $tab_link . '" class="nav-tab ' . ( $current_tab == $tab_id ? 'nav-tab-active' : '' ) . '">' . $tab_label . '</a>';
						}

					}

					do_action( 'bt_admin_page_' . $this->page . '_tabs' );

					echo '</h2>';

					settings_errors();

					foreach ( $settings_pages as $tab_id => $contents ) {

						if ( $tab_id === $current_tab ) {

							echo isset( $contents['description'] ) ? '<p>' . $contents['description'] . '</p>' : '';

							do_action( 'bt_admin_page_' . $this->page . '_' . $current_tab . '_start' );

							settings_fields( 'bt_' . $this->page . '_' . $tab_id );
							do_settings_sections( 'bt_' . $this->page . '_' . $tab_id );

							do_action( 'bt_admin_page_' . $this->page . '_' . $current_tab . '_end' );


							$submit = apply_filters( 'bt_admin_page_' . $this->page . '_' . $current_tab . '_submit', true );

							if ( true === $submit ) {
								submit_button();
							}
						}
					}
				}

				?>
			</form>
		</div>
		<?php
	}
}
