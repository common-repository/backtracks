<?php
/**
 * Template layout for the editor button settings page popup
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$general_settings = get_option( 'bt_settings_general' );
$general_settings = $general_settings['general'];

?>

<div class="bt-modal-setting">
	<p><label for="bt-editor-username"><?php esc_html_e( 'Backtracks Username', 'bt' ); ?></label></p>
	<input type="text" class="regular-text" id="bt-editor-username" value="<?php echo esc_attr( $general_settings['username'] ); ?>" />
	<p class="description"><?php _e( 'Backtracks account username.', 'bt' ); ?></p>
</div>

<?php
if (  \Backtracks\Api\Backtracks_API::has_empty_keys() || ! \Backtracks\Api\Backtracks_API::has_valid_keys() ) { ?>
	<div class="bt-modal-setting">
		<p><label for="bt-editor-episode-slug"><?php esc_html_e( 'Backtracks Episode Slug', 'bt' ); ?></label></p>
		<input type="text" class="regular-text" id="bt-editor-episode-slug"  />
		<p class="description"><?php _e( 'Backtracks episode slug.', 'bt' ); ?></p>
	</div>


	<div class="bt-modal-setting">
		<p><label for="bt-editor-series-slug"><?php esc_html_e( 'Backtracks Series Slug', 'bt' ); ?></label></p>
		<input type="text" class="regular-text" id="bt-editor-series-slug" value="<?php echo esc_attr( $general_settings['series_slug'] ); ?>" />
		<p class="description"><?php _e( 'Backtracks series slug.', 'bt' ); ?></p>
	</div>
<?php } else { ?>
	<div class="bt-modal-setting">
		<p><label for="bt-editor-episode-slug"><?php esc_html_e( 'Backtracks Episode Slug', 'bt' ); ?></label></p>

		<div class="spinner">Loading Episodes...</div>
		<select id="bt-editor-episode-slug" class=""></select>

		<p class="description"><?php _e( 'Backtracks episode slug.', 'bt' ); ?></p>
	</div>

	<div class="bt-modal-setting">
		<p><label for="bt-editor-series-slug"><?php esc_html_e( 'Backtracks Series Slug', 'bt' ); ?></label></p>

		<select id="bt-editor-series-slug" class="regular-text">
			<?php
			if ( ! empty( $general_settings['series_slug'] ) ) {
				echo '<option value="' . esc_attr( $general_settings['series_slug'] ) . '" selected>' . esc_attr( $general_settings['series_slug'] ) . '</option>';
			}
			?>
		</select>

		<p class="description"><?php _e( 'Backtracks series slug.', 'bt' ); ?></p>
	</div>
<?php } ?>

<div class="bt-modal-setting">
	<p><label for="bt-editor-theme"><?php esc_html_e( 'Theme', 'bt' ); ?></label></p>
	<input type="text" class="regular-text" id="bt-editor-theme" value="<?php echo esc_attr( $general_settings['theme'] ); ?>" />
	<p class="description"><?php _e( 'Name or ID of the player theme.', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-artist"><?php esc_html_e( 'Author/Artist', 'bt' ); ?></label></p>
	<input type="text" class="regular-text" id="bt-editor-artist" value="<?php echo esc_attr( $general_settings['artist'] ); ?>" />
	<p class="description"><?php _e( 'Set or override the author/artist of the media.', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-show-cover-art"><?php esc_html_e( 'Show Cover Art', 'bt' ); ?></label></p>
	<select id="bt-editor-show-cover-art">
		<option value="default" <?php selected( $general_settings['show_cover_art'], 'default' ); ?>><?php esc_html_e( 'Series Default','bt' ); ?></option>
		<option value="no" <?php selected( $general_settings['show_cover_art'], 'no' ); ?>><?php esc_html_e( 'No','bt' ); ?></option>
		<option value="yes" <?php selected( $general_settings['show_cover_art'], 'yes' ); ?>><?php esc_html_e( 'Yes','bt' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Show the art cover next to the author/artist/host/show name and episode/work title. Series default falls back to the default in your Backtracks account.', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-background-image"><?php esc_html_e( 'Background Image', 'bt' ); ?></label></p>
	<input type="text" class="regular-text" id="bt-editor-background-image" value="<?php echo esc_attr( $general_settings['background_image'] ); ?>" />
	<p class="description"><?php _e( 'Set or override the large background image that the player supports. The player will automatically tint the image. When setting a custom image we recommend a minimum size of 600px x 300px.', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-cover-art-thumb-url"><?php esc_html_e( 'Cover Art Thumbnail URL', 'bt' ); ?></label></p>
	<input type="text" class="regular-text" id="bt-editor-cover-art-thumb-url" value="<?php echo esc_attr( $general_settings['cover_art_thumb'] ); ?>" />
	<p class="description"><?php _e( 'Set or override the cover art displayed when the player has the option to show cover art set to "true".', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-cover-art-thumb-alt"><?php esc_html_e( 'Cover Art Thumbnail Alt Text', 'bt' ); ?></label></p>
	<input type="text" class="regular-text" id="bt-editor-cover-art-thumb-alt" value="<?php echo esc_attr( $general_settings['cover_art_thumb_alt'] ); ?>" />
	<p class="description"><?php _e( "Set or override the cover art's alt text.", 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-exclude-embed-script"><?php esc_html_e( 'Exclude Embed Script', 'bt' ); ?></label></p>
	<input type="checkbox" id="bt-editor-exclude-embed-script" value="yes" <?php checked( isset( $general_settings['exclude_embed_script'] ) ? 'yes' : 'no', 'yes' ); ?>> <?php esc_html_e( 'Yes', 'bt' ); ?>
	<p class="description"><?php _e( 'Exclude JavaScript for embedding the player. [i.e. your template already includes the script]', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-show-comments"><?php esc_html_e( 'Show Transcript', 'bt' ); ?></label></p>
	<select id="bt-editor-show-comments">
		<option value="default" <?php selected( $general_settings['show_comments'], 'default' ); ?>><?php esc_html_e( 'Series Default','bt' ); ?></option>
		<option value="no" <?php selected( $general_settings['show_comments'], 'no' ); ?>><?php esc_html_e( 'No','bt' ); ?></option>
		<option value="yes" <?php selected( $general_settings['show_comments'], 'yes' ); ?>><?php esc_html_e( 'Yes','bt' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Show transcript by default on load. False by default. Series default falls back to the default in your Backtracks account.', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-show-comment-markers"><?php esc_html_e( 'Show Transcript Markers', 'bt' ); ?></label></p>
	<select id="bt-editor-show-comment-markers">
		<option value="default" <?php selected( $general_settings['show_comment_markers'], 'default' ); ?>><?php esc_html_e( 'Series Default','bt' ); ?></option>
		<option value="no" <?php selected( $general_settings['show_comment_markers'], 'no' ); ?>><?php esc_html_e( 'No','bt' ); ?></option>
		<option value="yes" <?php selected( $general_settings['show_comment_markers'], 'yes' ); ?>><?php esc_html_e( 'Yes','bt' ); ?></option>
	</select>
	<p class="description"><?php _e( 'Show "transit stops" where speaker sections start. For densely annotated sources, this does not display well.', 'bt' ); ?></p>
</div>

<div class="bt-modal-setting">
	<p><label for="bt-editor-player-class"><?php esc_html_e( 'Player Class', 'bt' ); ?></label></p>
	<input type="text" class="regular-text" id="bt-editor-player-class" value="<?php echo esc_attr( $general_settings['player_class'] ); ?>" />
	<p class="description"><?php _e( 'Player CSS/HTML class name(s). Separate multiple classes with a space. It is highly unlikely this will need to be modified.', 'bt' ); ?></p>
</div>
