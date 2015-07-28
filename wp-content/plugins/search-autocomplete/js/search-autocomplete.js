(function($) {
	$(function() {
		var options = $.extend({
			'fieldName': '#s',
			'maxRows': 10,
			'minLength': 4
		}, SearchAutocomplete);

		options.fieldName = $('<div />').html(options.fieldName).text();

		$(options.fieldName).autocomplete({
			source: function( request, response ) {
			    $.ajax({
			        url: options.ajaxurl,
			        dataType: "json",
			        data: {
			        	action: 'autocompleteCallback',
			            term: this.term
			        },
			        success: function( data ) {
			            response( $.map( data.results, function( item ) {
			                return {
			                	label: item.title,
			                	value: item.title,
			                	url: item.url
			                }
			            }));
			        },
			        error: function(jqXHR, textStatus, errorThrown) {
			        	console.log(jqXHR, textStatus, errorThrown);
			        }
			    });
			},
			delay: options.delay,
			minLength: options.minLength,
			autoFocus: ( options.autoFocus === 'true' ),
			search: function(event, ui) {
				$(event.currentTarget).addClass('sa_searching');
			},
			create: function() {
			},
			select: function( event, ui ) {
				if ( ui.item.url !== '#' ) {
					/*
					below command will follow the link in the search-autocomplete drop down when the user clicks on it.
					We don't want that behaviour so commenting out.
					*/

                    //location = ui.item.url;
					var name = ui.item.value;
                    var url = ui.item.url;
                    //alert(name + ' ' + url);

                    var post_id 	= $( this ).closest( ".postbox" ).attr( 'id' );
                    alert(post_id);

                    /*var resource_item 	= {
                        initialHTML: '<div class="resource-item"><div class="dashicons dashicons-menu wpdw-widget-sortable"></div><span class="resource-item-content" contenteditable="false">',
                        url: '<a class="wp-colorbox-iframe" href="'+ new_resource_url +'">',
                        userInput: $( this ).val(),
                        closingHTML: '</a></span><div class="delete-item dashicons dashicons-no-alt"></div></div>',

                        combined: function () {
                            return this.initialHTML + this.url + this.userInput + this.closingHTML;
                        }
                    };

                    $( '#' + post_id + ' div.wp-dashboard-widget' ).append( resource_item.combined() );
                    $( this ).val( '' ); // Clear 'add item' field
                    $( this ).trigger( 'widget-sortable' );
                    $( this ).trigger( 'wpdw-update', this );
                    */

                    //$( document.activeElement ).text(resource_name);
                    //$( document.activeElement ).attr(href=url);

                } else {
					return true;
				}
			},
			open: function(event, ui) {
				var acData = $(this).data('uiAutocomplete');
				acData
						.menu
						.element
						.find('a')
						.each(function () {
							var $self = $( this ),
								keywords = $.trim( acData.term ).split( ' ' ).join('|');
							$self.html($self.text().replace(new RegExp("(" + keywords + ")", "gi"), '<span class="sa-found-text">$1</span>'));
						});
				$(event.target).removeClass('sa_searching');
			},
			close: function() {
			}
		});
	});
})(jQuery);