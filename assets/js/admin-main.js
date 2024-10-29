
(function($) {

	/** Admin Notices **/
	$( '.bt-admin-notice' ).on( 'click', '.notice-dismiss', function( event, el ) {
		var notice = $(this).parent('.notice.is-dismissible');
		var dismiss_id = notice.attr( 'data-dismiss-id' );
		if ( dismiss_id ) {
			data = {
				'action': 'dismiss_admin_notice',
				'id': dismiss_id,
				security: bt_admin.ajax_nonce
			};

			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			$.post(ajaxurl, data);
		}
	} );

	if ( bt_admin.has_valid_keys && ! bt_admin.has_empty_keys ) {
		var episode_list = bt_admin.episode_list;
		var current_slug = $( '#bt-editor-series-slug' ).val();

		$( '#bt-editor-series-slug' ).on( 'change', function() {

			current_slug = $( this ).val();

			var custom_slug = true;
			for ( var i = 0; i < bt_admin.series_list.length - 1; i++ ) {
				if ( bt_admin.series_list[i].slug === current_slug ) {
					custom_slug = false;
				}
			}

			if ( custom_slug ) {

				episodes.clearOptions();

				return;
			}

			$( '#bt-editor-episode-slug' ).parent().find( '.selectize-control' ).hide();
			$( '#bt-editor-episode-slug' ).parent().find( '.description' ).hide();
			$( '.bt-modal-setting .spinner' ).show();

			var data = {
				action: 'update_episode_list',
				slug: current_slug,
				security: bt_admin.ajax_nonce
			};

			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function( response ) {

					episode_list = response;

					episodes.clearOptions();
					episodes.addOption( episode_list );

					$( '#bt-editor-episode-slug' ).parent().find( '.selectize-control' ).show();
					$( '#bt-editor-episode-slug' ).parent().find( '.description' ).show();
					$( '.bt-modal-setting .spinner' ).hide();
				},
				error: function( response ) {
					console.log( 'error', response );
				}
			} );
		} );

		var series = $( '#bt-settings-general-general-series-slug, #bt-editor-series-slug' ).selectize( {
			create: true,
			valueField: 'slug',
			labelField: 'slug',
			searchField: [ 'title', 'slug' ],
			onType: function( str ) {
				str || this.$dropdown_content.removeHighlight();
			},
			render: {
				option: function( item, escape ) {
					return '<div>' +
					       //'<strong>' + escape( item.title ) + '</strong>' +
					       ( ( typeof item.title !== 'undefined' ) ? '<strong>' + escape( item.title ) + '</strong>' : '<strong>' + escape( item.slug ) + '</strong>'  ) +
					       ' (slug: ' + escape( item.slug ) + ')' +
					       '</div>';
				}
			}
		} );

		series = series[ 0 ].selectize;

		series.addOption( bt_admin.series_list );

		var episodes = $( '#bt-editor-episode-slug' ).selectize( {
			create: true,
			valueField: 'slug',
			labelField: 'slug',
			searchField: [ 'title', 'slug', 'release_date', 'publish_status' ],
			options: episode_list,
			onType: function( str ) {
				str || this.$dropdown_content.removeHighlight();
			},
			render: {
				option: function( item, escape ) {

					var html;
					var publishText = '';

					switch ( item.publish_status ) {
						case 0:
							publishText = 'Not Published';
							break;
						case 1:
							publishText = 'Published';
							break;
						case 2:
							publishText = 'Scheduled';
							break;
						default:
							publishText = 'Unknown';
					}

					html = '<div class="episode-item-wrap">' +
					       '<div class="episode-item-title">' +  ( ( typeof item.title !== 'undefined' ) ? '<strong>' + escape( item.title ) + '</strong>' : '<strong>' + escape( item.slug ) + '</strong>'  ) +
					       '<span class="episode-item-slug"> (slug: ' + escape( item.slug ) + ')</span></div>' +
					       '<div class="episode-item-status publish-status-' + item.publish_status + '">' + publishText + '</div>';

					if ( item.publish_status === 1 || item.publish_status === 2 ) {
						html += '<div class="episode-item-dates">' + 'Release Date (Local Time): ' + escape( item.release_date ) + '<br>' +
						        'Release Date (UTC): ' + escape( item.release_date_utc ) +
						        '</div>';
					}

					html += '</div>';

					return html;
				}
			}
		} );

		episodes = episodes[ 0 ].selectize;
	}

})(jQuery);
