<?php

namespace Backtracks\Admin\Pages;

use Backtracks\Abstracts\Page;

use Backtracks\Api\Backtracks_API as API;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class General
 *
 * Handles the functionality of the General Settings page
 *
 * @package Backtracks\Admin\Pages
 * @since   1.0.0
 */
class General extends Page {

	/**
	 * General constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->id           = 'general';
		$this->option_group = 'settings';
		$this->label        = esc_html__( 'General', 'bt' );
		$this->link_text    = esc_html__( 'Help docs for General Settings', 'bt' );

		$this->sections = $this->add_sections();
		$this->fields   = $this->add_fields();

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



					if ( ! API::has_valid_keys() || API::has_empty_keys() ) {
						$series_slug_options = array(
							'title'       => esc_html__( 'Backtracks Series Slug', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][series_slug]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-series-slug',
							'value'       => $this->get_setting_value( $section, 'series_slug' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Backtracks series slug', 'bt' ),
						);
					} else {
						$series_slug = $this->get_setting_value( $section, 'series_slug' );
						$series_options = array();

						if ( ! empty( $series_slug ) ) {
							$series_options = array(
								$series_slug => $series_slug,
							);
						}

						$series_slug_options = array(
							'title'       => esc_html__( 'Backtracks Series Slug', 'bt' ),
							'type'        => 'select',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][series_slug]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-series-slug',
							'value'       => $series_slug,
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Backtracks series slug', 'bt' ),
							'options' => $series_options,
						);
					}


					$fields[ $section ] = array(
						'username'             => array(
							'title'       => esc_html__( 'Backtracks Username', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][username]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-username',
							'value'       => $this->get_setting_value( $section, 'username' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Backtracks account username', 'bt' ),
						),
						'series_slug'          => $series_slug_options,
						'theme'                => array(
							'title'       => esc_html__( 'Theme', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][theme]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-theme',
							'value'       => $this->get_setting_value( $section, 'theme' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Name or ID of the player theme', 'bt' ),
						),
						'artist'               => array(
							'title'       => esc_html__( 'Author/Artist', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][artist]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-artist',
							'value'       => $this->get_setting_value( $section, 'artist' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Set or override the author/artist of the media', 'bt' ),
						),
						'show_cover_art'       => array(
							'title'       => esc_html__( 'Show Cover Art', 'bt' ),
							'type'        => 'select',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][show_cover_art]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-show-cover-art',
							'value'       => $this->get_setting_value( $section, 'show_cover_art' ),
							'options'     => array(
								'default' => esc_html__( 'Series Default', 'bt' ),
								'no'              => esc_html__( 'No', 'bt' ),
								'yes'             => esc_html__( 'Yes', 'bt' ),
							),
							'default'     => 'default',
							'description' => __( 'Show the art cover next to the author/artist/host/show name and episode work/title. Series default falls back to the default in your Backtracks account.', 'bt' ),
						),
						'background_image'     => array(
							'title'       => esc_html__( 'Background Image', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][background_image]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-background-image',
							'value'       => $this->get_setting_value( $section, 'background_image' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Set or override the large background image that the player supports. The player will automatically tint the image. When setting a custom image we recommend a minimum size of 600px x 300px', 'bt' ),
						),
						'cover_art_thumb'      => array(
							'title'       => esc_html__( 'Cover Art Thumbnail URL', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][cover_art_thumb]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-cover-art-thumb',
							'value'       => $this->get_setting_value( $section, 'cover_art_thumb' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Set or override the cover art displayed when the player has the option to show cover art set to `true`.', 'bt' ),
						),
						'cover_art_thumb_alt'  => array(
							'title'       => esc_html__( 'Cover Art Thumbnail Alt Text', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][cover_art_thumb_alt]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-cover-art-thumb-alt',
							'value'       => $this->get_setting_value( $section, 'cover_art_thumb_alt' ),
							'class'       => array (
								'regular-text',
							),
							'description' => __( "Set or override the cover art's alt text.", 'bt' ),
						),
						'exclude_embed_script' => array(
							'title'       => esc_html__( 'Exclude Embed Script', 'bt' ),
							'type'        => 'checkbox',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][exclude_embed_script]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-exclude-embed-script',
							'value'       => $this->get_setting_value( $section, 'exclude_embed_script' ),
							'text'        => esc_html__( 'Check this to exclude the JavaScript for embedding the player', 'bt' ),
							'description' => __( 'i.e. your template already includes the script', 'bt' ),
						),
						'show_comments'        => array(
							'title'       => esc_html__( 'Show Transcript', 'bt' ),
							'type'        => 'select',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][show_comments]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-show-comments',
							'value'       => $this->get_setting_value( $section, 'show_comments' ),
							'options'     => array(
								'default' => esc_html__( 'Series Default', 'bt' ),
								'no'              => esc_html__( 'No', 'bt' ),
								'yes'             => esc_html__( 'Yes', 'bt' ),
							),
							'default'     => 'default',
							'description' => __( 'Show transcript by default on load. False by default. Series default falls back to the default in your Backtracks account.', 'bt' ),
						),
						'show_comment_markers' => array(
							'title'       => esc_html__( 'Show Transcript Markers', 'bt' ),
							'type'        => 'select',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][show_comment_markers]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-show-comment-markers',
							'value'       => $this->get_setting_value( $section, 'show_comment_markers' ),
							'options'     => array(
								'default' => esc_html__( 'Series Default', 'bt' ),
								'no'              => esc_html__( 'No', 'bt' ),
								'yes'             => esc_html__( 'Yes', 'bt' ),
							),
							'default'     => 'default',
							'description' => __( 'Show "transit stops" where speaker sections start. For densely annotated sources, this does not display well.', 'bt' ),
						),
						'player_class'         => array(
							'title'       => esc_html__( 'Player Class', 'bt' ),
							'type'        => 'standard',
							'subtype'     => 'text',
							'name'        => 'bt_' . $this->option_group . '_' . $this->id . '[' . $section . '][player_class]',
							'id'          => 'bt-' . $this->option_group . '-' . $this->id . '-' . $section . '-player-class',
							'value'       => $this->get_setting_value( $section, 'player_class' ),
							'default'     => 'backtracks-player',
							'class'       => array (
								'regular-text',
							),
							'description' => __( 'Player CSS/HTML class name(s). Separate multiple classes with a space. It is highly unlikely this will need to be modified.', 'bt' ),
						),
					);
				}
			}
		}

		return apply_filters( 'bt_add_' . $this->option_group . '_' . $this->id . '_fields', $fields );
	}
}
