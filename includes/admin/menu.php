<?php

namespace Backtracks\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Menu
 *
 * Handles the admin menu item(s)
 *
 * @package Backtracks\Admin
 * @since   1.0.0
 */
class Menu {

	/**
	 * Menu constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
	}

	/**
	 * Add the menu items
	 *
	 * @since 1.0.0
	 */
	public function admin_menus() {

		$svg_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCA0My40IDQzLjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQzLjQgNDMuNzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2ZpbGw6IzJEREJBRDt9DQo8L3N0eWxlPg0KPHRpdGxlPmxvZ288L3RpdGxlPg0KPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+DQo8ZyBpZD0icGFnZXMiPg0KCTxnIGlkPSJob21lcGFnZSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTEzMC4wMDAwMDAsIC00NC4wMDAwMDApIj4NCgkJPGcgaWQ9Imhlcm8iPg0KCQkJPGcgaWQ9ImxvZ28iIHRyYW5zZm9ybT0idHJhbnNsYXRlKDEzMC4wMDAwMDAsIDQ0LjAwMDAwMCkiPg0KCQkJCTxwYXRoIGlkPSJGaWxsLTEiIGNsYXNzPSJzdDAiIGQ9Ik00My40LDIxLjVsMC0wLjJjMC0wLjEtMC4xLTAuMi0wLjMtMC4ySDM1Yy0wLjMsMC0wLjUtMC4yLTAuNS0wLjZ2LTIuMmMwLTEuMi0xLTIuMS0yLjEtMi4xDQoJCQkJCWMtMS4yLDAtMi4xLDEtMi4xLDIuMXY5LjVjMCwwLjMtMC4yLDAuNi0wLjUsMC42Yy0wLjMsMC0wLjUtMC4yLTAuNS0wLjZWMTIuOWMwLTEuMi0xLTIuMS0yLjEtMi4xYy0xLjIsMC0yLjEsMS0yLjEsMi4xdjE4LjUNCgkJCQkJYzAsMC4zLTAuMiwwLjYtMC41LDAuNmMtMC4zLDAtMC41LTAuMi0wLjUtMC42VjUuNmMwLTEuMi0xLTIuMS0yLjEtMi4xYy0xLjIsMC0yLjEsMS0yLjEsMi4xdjMyLjJjMCwwLjMtMC4yLDAuNi0wLjUsMC42DQoJCQkJCXMtMC41LTAuMi0wLjUtMC42di0yNWMwLTEuMi0xLTIuMS0yLjEtMi4xYy0xLjIsMC0yLjEsMS0yLjEsMi4xdjE2YzAsMC4zLTAuMiwwLjYtMC41LDAuNmMtMC4zLDAtMC41LTAuMi0wLjUtMC42VjE4LjMNCgkJCQkJYzAtMS4yLTEtMi4xLTIuMS0yLjFjLTEuMiwwLTIuMSwxLTIuMSwyLjF2Mi4yYzAsMC4zLTAuMiwwLjYtMC41LDAuNkgxLjZDMiwxMC4zLDExLDEuNiwyMS43LDEuNmM4LjUsMCwxNi4xLDUuNCwxOSwxMy41DQoJCQkJCWMwLDAuMSwwLjEsMC4yLDAuMiwwLjJINDJoMGgwYzAuMSwwLDAuMy0wLjEsMC4zLTAuM2MwLTAuMSwwLTAuMSwwLTAuMUMzOS4zLDYsMzEsMCwyMS43LDBDOS43LDAsMCw5LjgsMCwyMS45DQoJCQkJCWMwLDAuMSwwLDAuMiwwLDAuNGwwLDAuMmMwLDAuMSwwLjEsMC4yLDAuMywwLjJoOC4xYzEuMiwwLDIuMS0xLDIuMS0yLjF2LTIuMmMwLTAuMywwLjItMC42LDAuNS0wLjZjMC4zLDAsMC41LDAuMiwwLjUsMC42DQoJCQkJCXYxMC41YzAsMS4yLDEsMi4xLDIuMSwyLjFjMS4yLDAsMi4xLTEsMi4xLTIuMXYtMTZjMC0wLjMsMC4yLTAuNSwwLjUtMC41YzAuMywwLDAuNSwwLjIsMC41LDAuNXYyNWMwLDEuMiwxLDIuMSwyLjEsMi4xDQoJCQkJCWMxLjIsMCwyLjEtMSwyLjEtMi4xVjUuNmMwLTAuMywwLjItMC42LDAuNS0wLjZjMC4zLDAsMC41LDAuMiwwLjUsMC42djI1LjdjMCwxLjIsMSwyLjEsMi4xLDIuMWMxLjIsMCwyLjEtMSwyLjEtMi4xVjEyLjkNCgkJCQkJYzAtMC4zLDAuMi0wLjUsMC41LTAuNWMwLjMsMCwwLjUsMC4yLDAuNSwwLjV2MTQuOWMwLDEuMiwxLDIuMSwyLjEsMi4xYzEuMiwwLDIuMS0xLDIuMS0yLjF2LTkuNWMwLTAuMywwLjItMC42LDAuNS0wLjYNCgkJCQkJYzAuMywwLDAuNSwwLjIsMC41LDAuNnYyLjJjMCwxLjIsMSwyLjEsMi4xLDIuMWg2LjdjLTAuNCwxMC44LTkuNCwxOS41LTIwLjEsMTkuNWMtOC41LDAtMTYuMS01LjQtMTktMTMuNQ0KCQkJCQljMC0wLjEtMC4xLTAuMi0wLjItMC4ySDEuM2MtMC4xLDAtMC4yLDAtMC4yLDAuMWMwLDAuMS0wLjEsMC4yLDAsMC4yYzMsOSwxMS4yLDE1LDIwLjYsMTVjMTIsMCwyMS43LTkuOCwyMS43LTIxLjkNCgkJCQkJQzQzLjQsMjEuNyw0My40LDIxLjYsNDMuNCwyMS41Ii8+DQoJCQk8L2c+DQoJCTwvZz4NCgk8L2c+DQo8L2c+DQo8L3N2Zz4NCg==';

		add_menu_page( __( 'Backtracks Settings', 'bt' ), __( 'Backtracks', 'bt' ), 'manage_options', 'bt_settings', function () {
			$page = new Settings( 'settings' );
			$page->html();
		}, $svg_icon );
	}
}
