(function( $ ) {
	'use strict';

	// initalise the dialog
	$('#bt-admin-modal').dialog({
		title: $( '.open-bt-admin-modal' ).attr( 'title' ),
		dialogClass: 'wp-dialog',
		autoOpen: false,
		draggable: false,
		width: 'auto',
		height: $(window).height()*.7,
		modal: true,
		resizable: false,
		closeOnEscape: true,
		position: {
			my: "center",
			at: "center",
			of: window
		},
		open: function () {
			// close dialog by clicking the overlay behind it
			$('.ui-widget-overlay').bind('click', function(){
				$('#bt-admin-modal').dialog( 'close' );
			})
		},
		create: function () {
			// style fix for WordPress admin
			$('.ui-dialog-titlebar-close').addClass('ui-button');
		},
	});

	// bind a button or a link to open the dialog
	$('a.open-bt-admin-modal').click(function(e) {
		e.preventDefault();
		$('#bt-admin-modal').dialog('open');
	});

	$('#bt-cancel-add-player').on( 'click', function( e ) {
		e.preventDefault();
		$('#bt-admin-modal').dialog( 'close' );
	} );


}( jQuery ) );

function insertBacktracksPlayer() {

	var episode_slug = jQuery( '#bt-editor-episode-slug' ).val(),
		username = jQuery( '#bt-editor-username' ).val(),
		series_slug = jQuery( '#bt-editor-series-slug' ).val(),
		theme = jQuery( '#bt-editor-theme' ).val(),
		artist = jQuery( '#bt-editor-artist' ).val(),
		show_cover_art = jQuery( '#bt-editor-show-cover-art' ).find( ':selected' ).val(),
		background_image = jQuery( '#bt-editor-background-image' ).val(),
		cover_art_thumb = jQuery( '#bt-editor-cover-art-thumb-url' ).val(),
		cover_art_alt = jQuery( '#bt-editor-cover-art-thumb-alt' ).val(),
		exclude_embed_script = jQuery( '#bt-editor-exclude-embed-script' ).is( ':checked' ),
		show_comments = jQuery( '#bt-editor-show-comments' ).find( ':selected' ).val(),
		show_comment_markers = jQuery( '#bt-editor-show-comment-markers' ).find( ':selected' ).val(),
		player_class = jQuery( '#bt-editor-player-class' ).val(),
		attribute_string = '',
		embed_url = '';

	if ( episode_slug.length && username.length && series_slug.length ) {
		embed_url = 'https://player.backtracks.fm/' + username + '/' + series_slug + '/m/' + episode_slug;

		attribute_string += ' embed="' + embed_url + '"';
	} else {
		// TODO: check and add each slug as an attribute if not all are entered?
	}

	if ( theme.length ) {
		attribute_string += ' theme="' + theme + '"';
	}

	if ( artist.length ) {
		attribute_string += ' artist="' + artist + '"';
	}

	if ( show_cover_art ) {
		attribute_string += ' show-art-cover="' + show_cover_art + '"';
	}

	if ( background_image.length ) {
		attribute_string += ' bg-img="' + background_image + '"';
	}

	if ( cover_art_thumb.length ) {
		attribute_string += ' thumb-src="' + cover_art_thumb + '"';
	}

	if ( cover_art_alt.length ) {
		attribute_string += ' thumb-alt="' + cover_art_alt + '"';
	}

	if ( exclude_embed_script ) {
		attribute_string += ' exclude-embed-script="' + exclude_embed_script + '"';
	}

	if ( show_comments.length ) {
		attribute_string += ' show-comments="' + show_comments + '"';
	}

	if ( show_comment_markers.length ) {
		attribute_string += ' show-comment-markers="' + show_comment_markers + '"';
	}

	if ( player_class.length ) {
		attribute_string += ' player-class="' + player_class + '"';
	}

	// Send the shortcode to the editor
	window.send_to_editor( '[backtracks_player' + attribute_string + ']' );
	jQuery( '#bt-admin-modal' ).dialog( 'close' );
}
