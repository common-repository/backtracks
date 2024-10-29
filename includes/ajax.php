<?php

namespace Backtracks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax {

	public function __construct() {

		add_action( 'wp_ajax_update_episode_list', array( $this, 'update_episode_list' ) );
		add_action( 'wp_ajax_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );
	}

	public function update_episode_list() {

		check_ajax_referer( 'ajax_nonce', 'security' );

		$slug = sanitize_text_field( $_POST['slug'] );

		if ( ! empty( $slug ) ) {
			wp_send_json( get_selectize_episodes_list( $slug ) );
			wp_die();
		}

		wp_die( -1 );
	}

	public function dismiss_admin_notice() {

		check_ajax_referer( 'ajax_nonce', 'security' );

		$id = absint( $_POST['id'] );

		if ( ! empty( $id ) ) {
			add_option( 'bt_dismissed_' . $id, true );
		}
	}
}
