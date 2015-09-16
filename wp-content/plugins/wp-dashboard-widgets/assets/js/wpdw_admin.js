jQuery( document ).ready( function($) {

    /*
     Dashboard functions
     */

    var loading_icon = '<span class="saving-icon"><img src="images/wpspin_light.gif"/> saving...</span>';
    var saved_icon = '<span class="saved-icon"><div class="dashicons dashicons-yes"></div> saved!</span>';
    var new_resource_url = '../?page_id=118';

    // Add resource item
    $('body, .resource-item-content').on('keydown', '.add-resource-item', function (e) {

        if (e.keyCode == 13 && $(this).val() != '') {

            var post_id = $(this).closest(".postbox").attr('id');
            var resource_item = {
                initialHTML: '<div class="resource-item"><div class="dashicons dashicons-menu wpdw-widget-sortable"></div><span class="resource-item-content" contenteditable="false">',
                url: '<a class="wp-colorbox-iframe unlinked" href="' + new_resource_url + '">',
                userInput: $(this).val(),
                closingHTML: '</a></span><div class="delete-item dashicons dashicons-no-alt"></div></div>',

                combined: function () {
                    return this.initialHTML + this.url + this.userInput + this.closingHTML;
                }
            };

            $('#' + post_id + ' div.wp-dashboard-widget').append(resource_item.combined());
            $(".wp-colorbox-iframe").colorbox({iframe: true, width: "80%", height: "80%"});

            // Pass resource name from widget to resource (JQuery -> PHP)
            $('.wp-dashboard-widget-wrap a').on('click', function (event) {

                event.preventDefault();

                //get resource url for isexistingresource or newresource comparison test
                var resource_url = $(this).attr('href');
                //get resource name that user typed
                var resource_name = $(this).text();
                //get widget id
                var post_id = $(this).closest(".postbox").attr('id');

                //check if resource has not been linked to an existing resource already
                //if ( resource_url == new_resource_url ) {
                //post variables to PHP action
                var data = {
                    action: 'post_title_return_url',
                    url: resource_url,
                    res_name: resource_name,
                    post_id: post_id
                };

                $.post(ajaxurl, data, function (response) {
                    //alert('The server responded: ' + response);
                });
                //}
            })

            // Get resource name and url from php and update widget resource name (PHP -> JQuery)
            $(document).on('cbox_closed', function () {

                /*$( document.activeElement ).closest('div[class="postbox"]').block(
                 {
                 message: '<h1>processing...</h1>',
                 css: {border: '3px solid #a00'}
                 }
                 );*/

                var set_data = {
                    action: 'resource_title_and_url_to_widget',
                    url: '',
                    resource_name: '',
                    post_id: ''
                }

                $.post(ajaxurl, set_data, function (response) {
                    var resource_att = $.parseJSON(response); 
                    var url = resource_att.url;
                    var resource_name = resource_att.resource_name;

                    $(document.activeElement).text(resource_name);
                    $(document.activeElement).attr({
                        href: url
                    });
                    if ($(document.activeElement).href != new_resource_url) {
                        $(document.activeElement).removeClass('unlinked');
                        $(document.activeElement).addClass('linked');
                    }
                    //$( this ).val( '' ); // Clear 'add item' field
                    $(document.activeElement).trigger('widget-sortable');
                    $(document.activeElement).trigger('wpdw-update', this);
                })

                //$( document.activeElement ).closest('div[class="postbox"]').unblock();

            })

            $(this).val(''); // Clear 'add item' field
            $(this).trigger('widget-sortable');

            $(this).trigger('wpdw-update', this);
        }

    });


    // Delete resource item
    $('body').on('click', '.delete-item', function () {

        var post_id = $(this).closest(".postbox").attr('id');

        $(this).parent('.resource-item').remove();
        $('body').trigger('wpdw-update', ['', post_id]);

    });

    // Add note item
    $('body, .note-item-content').on('keydown', '.add-note-item', function (e) {

        if (e.keyCode == 13 && $(this).val() != '') {

            var post_id = $(this).closest(".postbox").attr('id');
            var note_item = '<div class="note-item"><div class="dashicons dashicons-menu wpdw-widget-sortable"></div><span class="note-item-content" contenteditable="true">' + $(this).val() + '</span><div class="delete-item dashicons dashicons-no-alt"></div></div>';

            $('#' + post_id + ' div.wp-dashboard-widget').append(note_item);
            $(this).val(''); // Clear 'add item' field
            $(this).trigger('widget-sortable');

            $(this).trigger('wpdw-update', this);

        }
    });

    // Add line break within note items (CTRL + Enter)
    $('body, .add-note-item').on('keydown', '.add-note-item', function (e) {
        if (e.keyCode == 13) {
            var content = $(this).val;
            // Add line break
            out.appendChild(content("br"));
        }
    });

    /*DELETE ME
     $( 'body' ).on( 'keydown', '[data-widget-type=note] .wp-dashboard-widget', function( e ) {
     if ( e.keyCode == 13 && ( e.ctrlKey || e.metaKey ) ) {
     $( this ).trigger( 'wpdw-update', this );
     $( this ).blur();
     return false;
     */


    // Delete note item
    $('body').on('click', '.delete-item', function () {

        var post_id = $(this).closest(".postbox").attr('id');

        $(this).parent('.note-item').remove();
        $('body').trigger('wpdw-update', ['', post_id]);

    });

    // Add todo item
    $('body, .list-item-content').on('keydown', '.add-list-item', function (e) {

        if (e.keyCode == 13 && $(this).val() != '') {

            var post_id = $(this).closest(".postbox").attr('id');
            var list_item = '<div class="list-item"><div class="dashicons dashicons-menu wpdw-widget-sortable"></div><input type="checkbox"><span class="list-item-content" contenteditable="true">' + $(this).val() + '</span><div class="delete-item dashicons dashicons-no-alt"></div></div>';

            $('#' + post_id + ' div.wp-dashboard-widget').append(list_item);
            $(this).val(''); // Clear 'add item' field
            $(this).trigger('widget-sortable');

            $(this).trigger('wpdw-update', this);

        }

    });


    // Delete todo item
    $('body').on('click', '.delete-item', function () {

        var post_id = $(this).closest(".postbox").attr('id');

        $(this).parent('.list-item').remove();
        $('body').trigger('wpdw-update', ['', post_id]);

    });


    // Toggle visibility
    $('body').on('click', '.wpdw-visibility', function () {

        $(this).toggleClass('dashicons-admin-users dashicons-groups');

        var visibility = $(this).parent().attr('data-visibility');
        if ('public' == visibility) {
            $(this).parent('.wpdw-toggle-visibility').attr('data-visibility', 'private');
            $(this).parent('.wpdw-toggle-visibility').attr('title', 'Visibility: Just me');
        } else {
            $(this).parent('.wpdw-toggle-visibility').attr('data-visibility', 'public');
            $(this).parent('.wpdw-toggle-visibility').attr('title', 'Visibility: Everyone');
        }

        $(this).trigger('wpdw-update', this);

    });


    // Toggle widget type
    $('body').on('click', '.wpdw-widget-type', function () {

        $(this).toggleClass('dashicons-list-view dashicons-welcome-write-blog');

        var widget_type = $(this).closest('[data-widget-type]').attr('data-widget-type');
        if (widget_type == 'note') {
            $(this).closest('[data-widget-type]').attr('data-widget-type', 'list');
        } else if (widget_type == 'list') {
            $(this).closest('[data-widget-type]').attr('data-widget-type', 'resource');
        } else {
            $(this).closest('[data-widget-type]').attr('data-widget-type', 'note');
        }

        var data = {
            action: 'wpdw_toggle_widget',
            post_id: $(this).closest(".postbox").attr('id').replace('widget_', ''),
            widget_type: ( widget_type == 'note' ? 'list' : widget_type == 'list' ? 'resource' : 'note' )
        };

        $.post(ajaxurl, data, function (response) {
            $('#widget_' + data.post_id + ' .inside').html(response).trigger('widget-sortable');
            ;
        });

        $(this).trigger('wpdw-update', this);

    });


    // Update widget trigger
    $('body').on('wpdw-update', function (event, t, post_id) {

        if (t != '') {
            post_id = $(t).closest(".postbox").attr('id');
        }

        if (!post_id) {
            return;
        }

        $('#' + post_id).block(
            {
                message: 'Processing...'
            }
        );
        $('#' + post_id + ' .hndle .status').html(loading_icon);
        var data = {
            action: 'wpdw_update_widget',
            post_id: post_id.replace('widget_', ''),
            post_content: $('#' + post_id + ' div.wp-dashboard-widget').html(),
            post_title: $('#' + post_id + ' > h3 .wpdw-title').html(),
            widget_visibility: $('#' + post_id + ' [data-visibility]').attr('data-visibility'),
            widget_color_text: $('#' + post_id + ' [data-color-text]').attr('data-color-text'),
            widget_color: $('#' + post_id + ' [data-widget-color]').attr('data-widget-color'),
            widget_type: $('#' + post_id + ' [data-widget-type]').attr('data-widget-type')
        };

        $.post(ajaxurl, data, function (response) {
            $('#' + post_id + ' .hndle .status').html(saved_icon);
            $('#' + post_id + ' .hndle .status *').fadeOut(1000, function () {
                $(this).html('')
            });
            $('#' + post_id).unblock();
        });

    });


    // Delete widget
    $('body').on('click', '.wpdw-delete-widget', function () {

        var post_id = $(this).closest(".postbox").attr('id');

        $('#' + post_id).fadeOut(500, function () {
            $(this).remove()
        });

        var data = {
            action: 'wpdw_delete_widget',
            post_id: post_id.replace('widget_', '')
        };

        $.post(ajaxurl, data, function (response) {

        });

    });


    // Add widget
    $('body').on('click', '.wpdw-add-widget, #add_widget-hide', function () {

        var data = {action: 'wpdw_add_widget'};

        $.post(ajaxurl, data, function (response) {
            response = jQuery.parseJSON(response);
            jQuery('#postbox-container-1 #normal-sortables').append(response.widget);
            jQuery('body, html').animate({scrollTop: $("#widget_" + response.post_id).offset().top - 50}, 750); // scroll down
            jQuery('#widget_' + response.post_id + ' .add-resource-item').focus();
        });


        // Stop scrollTop animation on user scroll
        $('html, body').bind("scroll mousedown DOMMouseScroll mousewheel keyup", function (e) {
            if (e.which > 0 || e.type === "mousedown" || e.type === "mousewheel") {
                $('html, body').stop().unbind('scroll mousedown DOMMouseScroll mousewheel keyup');
            }
        });

    });

    // Change color
    $('body').on('click', '.color', function () {

        // Set variables
        var color = $(this).attr('data-select-color');
        var color_text = $(this).attr('data-select-color-text');

        // Preview
        $(this).closest('.postbox').css('background-color', color);
        $(this).closest('.wp-dashboard-widget-wrap').attr('data-color-text', color_text);

        // Set saving attributes
        $(this).closest('[data-widget-color]').attr('data-widget-color', color);
        $(this).closest('[data-color-text]').attr('data-color-text', color_text);

        // Update widget
        $(this).trigger('wpdw-update', this);
    });

    // Edit/update resource widget
    $('body').on('blur', '.resource-item-content, [contenteditable=true]', function () {
        $(this).trigger('wpdw-update', this);
    });

    // Save on enter (resource widget)
    $('body').on('keydown', '[data-widget-type=resource], .wpdw-title, .resource-item-content', function (e) {
        if (e.keyCode == 13) {
            $(this).trigger('wpdw-update', this);
            $(this).blur();
            return false;
        }
    });

    // Edit/update note widget
    $('body').on('blur', '.note-item-content, [contenteditable=true]', function () {
        $(this).trigger('wpdw-update', this);
    });

    // Save on ctrl & enter (note widget)
    $('body').on('keydown', '[data-widget-type=note], .wpdw-title, .note-item-content', function (e) {
        if (e.keyCode == 13 && ( e.ctrlKey || e.metaKey )) {
            $(this).trigger('wpdw-update', this);
            $(this).blur();
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
    $('body').on('blur', '.list-item-content, [contenteditable=true]', function () {
        $(this).trigger('wpdw-update', this);
    });

    // Save on enter (list widget)
    $('body').on('keydown', '[data-widget-type=list], .wpdw-title, .list-item-content', function (e) {
        if (e.keyCode == 13) {
            $(this).trigger('wpdw-update', this);
            $(this).blur();
            return false;
        }
    });

    // Edit title
    $('body, .postbox h3').on('click', '.wpdw-edit-title', function (e) {
        $(this).prev().focus();
        document.execCommand('selectAll', false, null);
        e.stopPropagation();
    });


    // Widget checkbox toggle
    $('input[type=checkbox]').change(function () {
        if (this.checked) {
            $(this).attr('checked', 'checked');
        } else {
            $(this).removeAttr('checked');
        }
        $(this).trigger('wpdw-update', this);
    });


    // Make list sortable
    $('body').on('widget-sortable', function () {
        $('.wp-dashboard-widget').sortable({
            handle: '.wpdw-widget-sortable',
            update: function (event, ui) {
                $(this).trigger('wpdw-update', this);
            },
            axis: 'y'
        });
    })
        .trigger('widget-sortable');


    /* Open link box when hovering a link
     $( '.wp-dashboard-widget-wrap a' ).hover( function() {

     var url = $( this ).attr( 'href' );
     $( this ).append( '<span class="link-hover" contenteditable="false"><a href="' + url + '">Link to Resource</a></span>' );
     //<a class="wp-colorbox-iframe" href="http://localhost/sus/wp-admin/post-new.php"{"Test"}>' + $( this ).val() + '"</a>

     }, function() {

     $( '.link-hover' ).remove();

     });*/

    // Pass resource name from widget to resource (JQuery -> PHP)
    $('.wp-dashboard-widget-wrap a').on('click', function (event) {

        event.preventDefault();

        //get resource url for isexistingresource or newresource comparison test
        var resource_url = $(this).attr('href');
        //get resource name that user typed
        var resource_name = $(this).text();
        //get widget id
        var post_id = $(this).closest(".postbox").attr('id');

        //check if resource has not been linked to an existing resource already
        //if ( resource_url == new_resource_url ) {
        //post variables to PHP action
        var data = {
            action: 'post_title_return_url',
            url: resource_url,
            res_name: resource_name,
            post_id: post_id
        };

        $.post(ajaxurl, data, function (response) {
            //alert('The server responded: ' + response);
        });
        //}
    })

    // Get resource name and url from php and update widget resource name (PHP -> JQuery)
    //$(document).on('cbox_closed', function () {
    //
    //    /*$( document.activeElement ).closest('div[class="postbox"]').block(
    //     {
    //     message: '<h1>processing...</h1>',
    //     css: {border: '3px solid #a00'}
    //     }
    //     );*/
    //
    //    var set_data = {
    //        action: 'resource_title_and_url_to_widget',
    //        url: '',
    //        resource_name: '',
    //        post_id: ''
    //    }
    //
    //    $.post(ajaxurl, set_data, function (response) {
    //        var resource_att = $.parseJSON(response);
    //        var url = resource_att.url;
    //        var resource_name = resource_att.resource_name;
    //
    //        $(document.activeElement).text(resource_name);
    //        $(document.activeElement).attr({
    //            href: url
    //        });
    //
    //        if ($(document.activeElement).href != new_resource_url) {
    //            $(document.activeElement).removeClass('unlinked');
    //            $(document.activeElement).addClass('linked');
    //        }
    //
    //        //$( this ).val( '' ); // Clear 'add item' field
    //        $(document.activeElement).trigger('widget-sortable');
    //        $(document.activeElement).trigger('wpdw-update', this);
    //    })
    //
    //    //$( document.activeElement ).closest('div[class="postbox"]').unblock();
    //
    //})

    // Prevent background color and other style from copying from one widget to the other
    $('body').on('paste', '[contenteditable]', function (e) {
        e.preventDefault();
        var text = (e.originalEvent || e).clipboardData.getData('text/plain');
        document.execCommand('insertText', false, text);
    });

    /*

     //obsolete - replaced with filter in class-wpdw-ajax.php
     New Resource (post-type) page

     $( function () {
     $('#post_title').val('Test');
     })
     //<input type="text" name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off">
     */

    //$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//    //Strategy pane
//
//    $(".strategy-list-item").click(function () {
//
//        //get id of strategy to pass to ajax
//        var sid = $(this).attr('id');
//        alert(sid);
//
//        //pass to ajax
//        var set_strategy_data = {
//            action: 'set_strategy_and_restart',
//            sid: sid
//        }
//
//        $.post(ajaxurl, set_strategy_data, function (response) {
//            var returned_sid = $.parseJSON(response);
//            var sid = returned_sid.sid;
//            alert (sid);
//        });
//    });

    /*
    Strategy pane
     */

    /*
    Onclick the activity title, show or hide the activity content
     */

    $(document).ready(function(){
        $('.activity-header-container').click(function(){
            //show / hide element after activity-title (activity-content)
            $(this).next().toggle();

            //pass clicked activity-step to php to store as user meta (so that it's persistently shown)
            var c_a_s = $(this).attr('id');

            //toggle display of other activities and change icon to back arrow
            if($(this).next().is(':visible'))
            {
                //bold activity title text
                $(this).css(
                    {
                        'font-weight': 'bold'
                    }
                );

                //hide other activities (non-running)
                $(this).parent().siblings().css(
                    {
                        display: 'none'
                    }
                );
                //get original activity url (so that you can restore it on activity close)
                original_image_url = $(this).find('.activity-icon').find('img').attr('src');
                //$(this).find('.activity-icon').find('img').attr('src','http://startupsite.duckdns.org/wp-content/uploads/2015/09/34141513-back-modern-flat-icon-with-long-shadow-e1441082124992.jpg');
                $(this).find('.activity-icon').find('img').attr('src','http://startupsite.duckdns.org/wp-content/uploads/2015/09/34141513-back-modern-flat-icon-with-long-shadow-e1441082124992.jpg');

                //show pagination
                $(this).next().child().find('.pagination').css(
                    {
                        display: 'none'
                    }
                )
            } else {
                //unbold activity title text
                $(this).css(
                    {
                        'font-weight': 'normal'
                    }
                );
                $(this).parent().siblings().css(
                    {
                        display: 'inline-block'
                    }
                );
                $(this).find('.activity-icon').find('img').attr('src', original_image_url);

                $(this).nextAll().css(
                    {
                        display: 'none'
                    }
                )
            }

            //alert(c_a_s);

            var data = {
                action: 'log_current_activity_step_to_user_meta',
                current_activity_step: c_a_s
            };

            $.post(ajaxurl, data, function (response) {
                var resp = response;
            });

        });
    });

    /*
    Pagination controls
    */

    $(document).ready(function(){
        $('.previous-page').click(function(){
            //show previous page hide current page
            $(this).parent().parent().prev().css(
                {
                    display: 'block'
                }
            );
            $(this).parent().parent().prev().find('div').css(
                {
                    display: 'block'
                }
            )

            // hide current page
            $(this).parent().parent().css(
                {
                    display: 'none'
                }
            );
            $(this).parent().parent().find('div').css(
                {
                    display: 'none'
                }
            )

            ////pass clicked activity-step to php to store as user meta (so that it's persistently shown)
            //var c_a_s = $(this).attr('id');
            //
            ////alert(c_a_s);
            //
            //var data = {
            //    action: 'log_current_activity_step_to_user_meta',
            //    current_activity_step: c_a_s
            //};
            //
            //$.post(ajaxurl, data, function (response) {
            //    var resp = response;
            //});

        });
    });

    $(document).ready(function(){
        $('.next-page').click(function(){
            //show next page
            $(this).parent().parent().next().css(
                {
                    display: 'block'
                }
            );
            $(this).parent().parent().next().find('div').css(
                {
                    display: 'block'
                }
            )

            // hide current page
            $(this).parent().parent().css(
                {
                    display: 'none'
                }
            );
            $(this).parent().parent().find('div').animate(
                {
                    display: 'none'
                }
            )


            ////pass clicked activity-step to php to store as user meta (so that it's persistently shown)
            //var c_a_s = $(this).attr('id');
            //
            ////alert(c_a_s);
            //
            //var data = {
            //    action: 'log_current_activity_step_to_user_meta',
            //    current_activity_step: c_a_s
            //};
            //
            //$.post(ajaxurl, data, function (response) {
            //    var resp = response;
            //});

        });
    });



    // If page opened in cbox, don't show admin bar and left-menu
    $(document).on('cbox_complete', function () {
        //hide toolbar
        $("iframe").contents().find("#wpadminbar").css("display","none");
        //hide left-menu
        $("iframe").contents().find("#adminmenuback").css("display","none");
        $("iframe").contents().find("#adminmenuwrap").css("display","none");
        //move content left & up to fill gaps left by menus
        $("iframe").contents().find("html.wp-toolbar").css("padding-top","0px");
        $("iframe").contents().find("#wpcontent").css("margin-left","0px");

        //add id to colorbox for frameready to check against
        //cboxName = ($("iframe").attr('name'));
        //window.iframe_name = window.frameElement;
    })

    $(document).ready(function(){
        if (self != top) {
            //hide toolbar
            $("#wpadminbar").css("display","none");
            //hide left-menu
            $("#adminmenuback").css("display","none");
            $("#adminmenuwrap").css("display","none");
            //move content left & up to fill gaps left by menus
            $("html.wp-toolbar").css("padding-top","0px");
            $("#wpcontent").css("margin-left","0px");
        }
    });

});
//

