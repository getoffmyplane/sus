jQuery( document ).ready( function($) {

	var loading_icon 	= '<span class="saving-icon"><img src="images/wpspin_light.gif"/> saving...</span>';
	var saved_icon 		= '<span class="saved-icon"><div class="dashicons dashicons-yes"></div> saved!</span>';

    // Add resource item
    $( 'body, .resource-item-content' ).on( 'keydown', '.add-resource-item', function( e ) {

        if( e.keyCode == 13 && $( this ).val() != '' ) {

            var post_id 	= $( this ).closest( ".postbox" ).attr( 'id' );
            var resource_item 	= '<div class="resource-item"><div class="dashicons dashicons-menu wpdw-widget-sortable"></div><span class="resource-item-content" contenteditable="true">' + $( this ).val() + '</span><div class="delete-item dashicons dashicons-no-alt"></div></div>';

            $( '#' + post_id + ' div.wp-dashboard-widget' ).append( resource_item );
            $( this ).val( '' ); // Clear 'add item' field
            $( this ).trigger( 'widget-sortable' );

            $( this ).trigger( 'wpdw-update', this );

        }

    });


    // Delete resource item
    $( 'body' ).on( 'click', '.delete-item', function() {

        var post_id = $( this ).closest( ".postbox" ).attr( 'id' );

        $( this ).parent( '.resource-item' ).remove();
        $( 'body' ).trigger( 'wpdw-update', ['', post_id]  );

    });

    // Add note item
    $( 'body, .note-item-content' ).on( 'keydown', '.add-note-item', function( e ) {

        if( e.keyCode == 13 && $( this ).val() != '' ) {

            var post_id 	= $( this ).closest( ".postbox" ).attr( 'id' );
            var note_item 	= '<div class="note-item"><div class="dashicons dashicons-menu wpdw-widget-sortable"></div><span class="note-item-content" contenteditable="true">' + $( this ).val() + '</span><div class="delete-item dashicons dashicons-no-alt"></div></div>';

            $( '#' + post_id + ' div.wp-dashboard-widget' ).append( note_item );
            $( this ).val( '' ); // Clear 'add item' field
            $( this ).trigger( 'widget-sortable' );

            $( this ).trigger( 'wpdw-update', this );

        }

    });


    // Delete note item
    $( 'body' ).on( 'click', '.delete-item', function() {

        var post_id = $( this ).closest( ".postbox" ).attr( 'id' );

        $( this ).parent( '.note-item' ).remove();
        $( 'body' ).trigger( 'wpdw-update', ['', post_id]  );

    });

	// Add todo item
	$( 'body, .list-item-content' ).on( 'keydown', '.add-list-item', function( e ) {

		if( e.keyCode == 13 && $( this ).val() != '' ) {

			var post_id 	= $( this ).closest( ".postbox" ).attr( 'id' );
			var list_item 	= '<div class="list-item"><div class="dashicons dashicons-menu wpdw-widget-sortable"></div><input type="checkbox"><span class="list-item-content" contenteditable="true">' + $( this ).val() + '</span><div class="delete-item dashicons dashicons-no-alt"></div></div>';

			$( '#' + post_id + ' div.wp-dashboard-widget' ).append( list_item );
			$( this ).val( '' ); // Clear 'add item' field
			$( this ).trigger( 'widget-sortable' );

			$( this ).trigger( 'wpdw-update', this );

		}

	});


	// Delete todo item
	$( 'body' ).on( 'click', '.delete-item', function() {

		var post_id = $( this ).closest( ".postbox" ).attr( 'id' );

		$( this ).parent( '.list-item' ).remove();
		$( 'body' ).trigger( 'wpdw-update', ['', post_id]  );

	});


	// Toggle visibility
	$( 'body' ).on( 'click', '.wpdw-visibility', function() {

		$( this ).toggleClass( 'dashicons-admin-users dashicons-groups' );

		var visibility = $( this ).parent().attr( 'data-visibility' );
		if ( 'public' == visibility ) {
			$( this ).parent( '.wpdw-toggle-visibility' ).attr( 'data-visibility', 'private' );
			$( this ).parent( '.wpdw-toggle-visibility' ).attr( 'title', 'Visibility: Just me' );
		} else {
			$( this ).parent( '.wpdw-toggle-visibility' ).attr( 'data-visibility', 'public' );
			$( this ).parent( '.wpdw-toggle-visibility' ).attr( 'title', 'Visibility: Everyone' );
		}

		$( this ).trigger( 'wpdw-update', this );

	});


	// Toggle widget type
	$( 'body' ).on( 'click', '.wpdw-widget-type', function() {

		$( this ).toggleClass( 'dashicons-list-view dashicons-welcome-write-blog' );

		var widget_type = $( this ).closest( '[data-widget-type]' ).attr( 'data-widget-type' );
		if ( widget_type == 'note' ) {
			$( this ).closest( '[data-widget-type]' ).attr( 'data-widget-type', 'list' );
		} else if ( widget_type == 'list' )  {
            $( this ).closest( '[data-widget-type]' ).attr( 'data-widget-type', 'resource' );
        } else {
			$( this ).closest( '[data-widget-type]' ).attr( 'data-widget-type', 'note' );
		}

		var data = {
			action: 	'wpdw_toggle_widget',
			post_id: 	$( this ).closest( ".postbox" ).attr( 'id' ).replace( 'widget_', '' ),
			widget_type:	( widget_type == 'note' ? 'list' : widget_type == 'list' ? 'resource' : 'note' )
		};

		$.post( ajaxurl, data, function( response ) {
			$( '#widget_' + data.post_id + ' .inside' ).html( response ).trigger( 'widget-sortable' );;
		});

		$( this ).trigger( 'wpdw-update', this );

	});


	// Update widget trigger
	$( 'body' ).on( 'wpdw-update', function( event, t, post_id ) {

		if ( t != '' ) {
			post_id = $( t ).closest( ".postbox" ).attr( 'id' );
		}

		if ( ! post_id ) {
			return;
		}

		$( '#' + post_id + ' .hndle .status' ).html( loading_icon );
		var data = {
			action: 			'wpdw_update_widget',
			post_id: 			post_id.replace( 'widget_', '' ),
			post_content: 		$( '#' + post_id + ' div.wp-dashboard-widget' ).html(),
			post_title: 		$( '#' + post_id + ' > h3 .wpdw-title' ).html(),
			widget_visibility:	$( '#' + post_id + ' [data-visibility]' ).attr( 'data-visibility' ),
			widget_color_text:	$( '#' + post_id + ' [data-color-text]' ).attr( 'data-color-text' ),
			widget_color:			$( '#' + post_id + ' [data-widget-color]' ).attr( 'data-widget-color' ),
			widget_type:			$( '#' + post_id + ' [data-widget-type]' ).attr( 'data-widget-type' )
		};

		$.post( ajaxurl, data, function( response ) {
			$( '#' + post_id + ' .hndle .status' ).html( saved_icon );
			$( '#' + post_id + ' .hndle .status *' ).fadeOut( 1000, function() { $( this ).html( '' ) });
		});

	});


	// Delete widget
	$( 'body' ).on( 'click', '.wpdw-delete-widget', function() {

		var post_id = $( this ).closest( ".postbox" ).attr( 'id' );

		$( '#' + post_id ).fadeOut( 500, function() { $( this ).remove() } );

		var data = {
			action: 'wpdw_delete_widget',
			post_id: post_id.replace( 'widget_', '' )
		};

		$.post( ajaxurl, data, function( response ) {

		});

	});


	// Add widget
	$( 'body' ).on( 'click', '.wpdw-add-widget, #add_widget-hide', function() {

		var data = { action: 'wpdw_add_widget' };

		$.post( ajaxurl, data, function( response ) {
			response = jQuery.parseJSON( response );
			jQuery( '#postbox-container-1 #normal-sortables' ).append( response.widget );
			jQuery('body, html').animate({ scrollTop: $( "#widget_" + response.post_id ).offset().top - 50 }, 750); // scroll down
			jQuery( '#widget_' + response.post_id + ' .add-resource-item' ).focus();
		});


		// Stop scrollTop animation on user scroll
		$( 'html, body' ).bind("scroll mousedown DOMMouseScroll mousewheel keyup", function( e ){
			if ( e.which > 0 || e.type === "mousedown" || e.type === "mousewheel") {
				$( 'html, body' ).stop().unbind('scroll mousedown DOMMouseScroll mousewheel keyup');
			}
		});

	});

	// Change color
	$( 'body' ).on( 'click', '.color', function() {

		// Set variables
		var color = $( this ).attr( 'data-select-color' );
		var color_text = $( this ).attr( 'data-select-color-text' );

		// Preview
		$( this ).closest( '.postbox' ).css( 'background-color', color );
		$( this ).closest( '.wp-dashboard-widget-wrap' ).attr( 'data-color-text', color_text );

		// Set saving attributes
		$( this ).closest( '[data-widget-color]' ).attr( 'data-widget-color', color );
		$( this ).closest( '[data-color-text]' ).attr( 'data-color-text', color_text );

		// Update widget
		$( this ).trigger( 'wpdw-update', this );
	});

    // Edit/update resource widget
    $( 'body' ).on( 'blur', '.resource-item-content, [contenteditable=true]', function() {
        $( this ).trigger( 'wpdw-update', this );
    });

    // Save on enter (resource widget)
    $( 'body' ).on( 'keydown', '[data-widget-type=resource], .wpdw-title, .resource-item-content', function( e ) {
        if ( e.keyCode == 13 ) {
            $( this ).trigger( 'wpdw-update', this );
            $( this ).blur();
            return false;
        }
    });

    // Edit/update note widget
    $( 'body' ).on( 'blur', '.note-item-content, [contenteditable=true]', function() {
        $( this ).trigger( 'wpdw-update', this );
    });

    // Save on enter (note widget)
    $( 'body' ).on( 'keydown', '[data-widget-type=note], .wpdw-title, .note-item-content', function( e ) {
        if ( e.keyCode == 13 ) {
            $( this ).trigger( 'wpdw-update', this );
            $( this ).blur();
            return false;
        }
    });

    /* Save on CMD|CTRL + enter (note widget)
    $( 'body' ).on( 'keydown', '[data-widget-type=note] .wp-dashboard-widget', function( e ) {
        if ( e.keyCode == 13 && ( e.ctrlKey || e.metaKey ) ) {
            $( this ).trigger( 'wpdw-update', this );
            $( this ).blur();
            return false;
        }
    });*/

	// Edit/update list widget
	$( 'body' ).on( 'blur', '.list-item-content, [contenteditable=true]', function() {
  		$( this ).trigger( 'wpdw-update', this );
	});

	// Save on enter (list widget)
	$( 'body' ).on( 'keydown', '[data-widget-type=list], .wpdw-title, .list-item-content', function( e ) {
	    if ( e.keyCode == 13 ) {
      		$( this ).trigger( 'wpdw-update', this );
      		$( this ).blur();
			return false;
		}
	});

	// Edit title
	$( 'body, .postbox h3' ).on( 'click', '.wpdw-edit-title', function( e ) {
		$( this ).prev().focus();
		document.execCommand( 'selectAll', false, null );
		e.stopPropagation();
	});


	// Widget checkbox toggle
	$( 'input[type=checkbox]' ).change( function() {
	    if( this.checked ) {
	        $( this ).attr( 'checked', 'checked' );
	    } else {
		    $( this ).removeAttr( 'checked' );
	    }
  		$( this ).trigger( 'wpdw-update', this );
    });


    // Make list sortable
    $( 'body' ).on( 'widget-sortable', function() {
		$( '.wp-dashboard-widget' ).sortable({
			handle: '.wpdw-widget-sortable',
			update: function( event, ui ) {
				$( this ).trigger( 'wpdw-update', this );
			},
			axis: 'y'
		});
	})
	.trigger( 'widget-sortable' );


	// Open link box when hovering a link
	$( '.wp-dashboard-widget-wrap a' ).hover( function() {

		var url = $( this ).attr( 'href' );
		$( this ).append( '<span class="link-hover" contenteditable="false"><a href="' + url + '" target="_blank" contenteditable="false">Open link</a></span>' );

	}, function() {

		$( '.link-hover' ).remove();

	});

	// Prevent background color and other style from copying from one widget to the other
	$( 'body' ).on('paste', '[contenteditable]', function (e) {
		e.preventDefault();
		var text = (e.originalEvent || e).clipboardData.getData('text/plain');
		document.execCommand('insertText', false, text);
	});

});
