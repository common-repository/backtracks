<?php

namespace Backtracks\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Backtracks_Player
 *
 * Handle the [backtracks_player] shortcode functionality
 *
 * @package Backtracks\Shortcodes
 * @since   1.0.0
 */
class Backtracks_Player {

	/**
	 * Base URL used to construct the embed
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $player_url;

	/**
	 * Default sting to use for the data attributes added to the main div
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $data_attribute_prefix;

	/**
	 * Bool to make sure we only add the script once per page.
	 *
	 * @var bool
	 */
	public static $script_loaded = false;

	public static $load_script = true;

	/**
	 * Backtracks_Player constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->player_url = 'https://player.backtracks.fm/';

		$this->data_attribute_prefix = 'data-bt-';

		add_shortcode( 'backtracks_player', array( $this, 'output' ) );

		add_action( 'wp_footer', array( $this, 'output_script' ) );
	}

	/**
	 * Function to output our shortcode.
	 *
	 * @param $atts    array The attributes added in the shortcode tag.
	 * @param $content string The content inside of the shortcode
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function output( $atts, $content ) {

		$plugin_settings = get_option( 'bt_settings_general' );
		$plugin_settings = $plugin_settings['general'];

		// Setup some defaults.
		$defaults = array(
			'embed'                => '',
			// TODO: is this correct?
			'username'             => isset( $plugin_settings['username'] ) ? $plugin_settings['username'] : '',
			'series-slug'          => isset( $plugin_settings['series_slug'] ) ? $plugin_settings['series_slug'] : '',
			'theme'                => isset( $plugin_settings['theme'] ) ? $plugin_settings['theme'] : '',
			'artist'               => isset( $plugin_settings['artist'] ) ? $plugin_settings['artist'] : '',
			'show-art-cover'       => isset( $plugin_settings['show_cover_art'] ) ? $plugin_settings['show_cover_art'] : 'default',
			'bg-img'               => isset( $plugin_settings['background_image'] ) ? $plugin_settings['background_image'] : '',
			'thumb-src'            => isset( $plugin_settings['cover_art_thumb'] ) ? $plugin_settings['cover_art_thumb'] : '',
			'thumb-alt'            => isset( $plugin_settings['cover_art_thumb_alt'] ) ? $plugin_settings['cover_art_thumb_alt'] : '',
			'exclude-embed-script' => isset( $plugin_settings['exclude_embed_script'] ) && ! empty( $plugin_settings['exclude_embed_script'] ) ? 'true' : 'false',
			'show-comments'        => isset( $plugin_settings['show_comments'] ) ? $plugin_settings['show_comments'] : 'default',
			'show-comment-markers' => isset( $plugin_settings['show_comment_markers'] ) ? $plugin_settings['show_comment_markers'] : 'default',
			'player-class'         => isset( $plugin_settings['player_class'] ) ? $plugin_settings['player_class'] : 'backtracks-player',
			// TODO: episode slug?
			// TODO: Need main setting option check? I don't think so: 'episode-slug' => isset( $plugin_settings[''] ) ? $plugin_settings[''] : '',
			'episode-slug'         => '',
		);

		// This array is used to skip certain values when building the data-attributes.
		$skip = array(
			'embed',
			'username',
			'series-slug',
			'episode-slug',
			'player-class',
			'exclude-embed-script',
		);

		// Now get our unlisted attributes before we rewrite the $atts array
		$unlisted_atts = $this->get_unlisted_atts( $defaults, $atts );

		// Update our $atts array using the WordPress native shortcode_atts function to set the defaults and get rid of any unlisted attributes
		$atts = shortcode_atts( $defaults, $atts, 'backtracks_player' );

		// Merge our unlisted attributes together with the updated $atts array
		$atts = array_merge( $atts, $unlisted_atts );

		// Now we can use our $atts array as normal and include any extra attributes that a user may have included

		if ( ! empty( $content ) ) {
			$url = $content;
		} else {

			if ( ! empty( $atts['embed'] ) ) {
				$url = $atts['embed'];
			} else {

				// Check for username AND series slug AND episode slug. We need these to build the URL. Exit if any of them are not set.
				if ( empty( $atts['username'] ) || empty( $atts['series-slug'] ) || empty( 'episode-slug' ) ) {

					if ( current_user_can( 'manage_options' ) ) {
						return '<div style="color: #f00;"><p>' . __( 'You need to set the proper attributes for username, series slug, and episode slug if you are not going to use the embed attribute.', 'bt' ) . '</p></div>';
					}

					return '';
				} else {
					// https://player.backtracks.fm/%3Cbt_username%3E/%3Cbt_series_slug%3E/m/%3Cbt_episode_slug%3E
					$url = $this->build_embed_url( $atts['username'], $atts['series-slug'], $atts['episode-slug'] );
				}
			}
		}

		$data_attributes = '';

		foreach ( $atts as $k => $v ) {

			if ( in_array( $k, $skip ) ) {
				continue;
			}

			// If an attribute is set to "default" we want to just skip it and let the JS handle that attribute
			if ( 'default' === strtolower( $v ) ) {
				continue;
			}

			if ( ! empty( $v ) ) {
				$data_attributes .= $this->data_attribute_prefix . esc_attr( $k ) . '="' . trim( $this->check_bool_value( $v ) ) . '" ';
			}
		}

		$html = '<div class="' . esc_attr( $atts['player-class'] ) . '" data-bt-embed="' . esc_url( $url ) . '" ' . $data_attributes . '></div>';

		if ( 'true' === $this->check_bool_value( $atts['exclude-embed-script'] ) ) {
			self::$load_script = false;
		}

		return $html;
	}

	/**
	 * Check a value to get the proper bool returned that we need. If it doesn't match then just return the original string.
	 *
	 * @param $check string
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function check_bool_value( $check ) {

		switch( strtolower( $check ) ) {
			case 'yes':
			case 'true':
			case '1':
				return 'true';
			case 'no':
			case 'false':
			case '0':
				return 'false';
		}

		return $check;
	}

	/**
	 * Get the script tag to output
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function output_script() {

		if ( ! self::$script_loaded && self::$load_script ) {

			self::$script_loaded = true;

			echo "<script>(function(p,l,a,y,e,r,s){if(p[y]) return;if(p[e]) return p[e]();s=l.createElement(a);l.head.appendChild((s.async=p[y]=true,s.src=r,s))}(window,document,'script','__btL','__btR','https://player.backtracks.fm/embedder.js'))</script>";
		}
	}

	/**
	 * Builds the embed URL
	 *
	 * @param $username
	 * @param $series_slug
	 * @param $episode_slug
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function build_embed_url( $username, $series_slug, $episode_slug ) {
		return trailingslashit( $this->player_url ) . $username . '/' . $series_slug . '/m/' . $episode_slug;
	}

	/**
	 * Return an array of values that are not found in the default array.
	 *
	 * @param $defaults array The defaults to check against
	 * @param $values   array The values we are checking
	 *
	 * @return array
	 */
	function get_unlisted_atts( $defaults, $values ) {

		// First make sure we have the right data before continuing
		if ( is_array( $defaults ) && is_array( $values ) ) {

			// Temporary array
			$unlisted = array();

			// Make sure we have a valid array first before looping
			if ( ! empty( $values ) ) {
				// Loop through the passed in $values
				foreach ( $values as $k => $v ) {

					// If the value is in the defaults then we just skip this entry
					if ( in_array( $k, $defaults ) ) {
						continue;
					}

					// The WordPress $atts parameter passed in for the shortcode looks one of 2 ways:
					// If the attribute has a value set like `attribute="value"` then it will in the form of array( 'attribute' => 'value' )
					// If the attribute has no value set like `attribute` then it will get an index and the attribute name will become the value in the array like this - array( [0] => 'attribute' )
					// So right here we are checking if the type of the key value ($k) is a string or an integer and then appending it to our temp array appropriately
					if ( 'string' === gettype( $k ) ) {
						$unlisted[ $k ] = $v;
					} else if ( 'integer' === gettype( $k ) ) {
						$unlisted[ $v ] = '';
					}
				}
			}

			return $unlisted;
		}

		return array();
	}
}
