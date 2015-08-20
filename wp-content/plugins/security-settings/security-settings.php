<?php
/**
 * Plugin Name: Security Settings
 * Description: This plugin adds ensures that users can see only the posts that they've authored.
 * Version: 1.0.0
 * Author: Luke Woods
 * License: GPL2
 */

add_filter( 'pre_get_posts', 'see_own_posts_only' );
//add_filter( 'pre_get_posts', 'if_opened_in_cbox_hide_admin_bar_and_menu');

function see_own_posts_only($query)
{
    global $user_level;

//    if (!current_user_can('edit_others_posts')) {
    global $user_ID;

    if ( in_array ( $query->get('post_type'), array('widget', 'person', 'group', 'product-or-service', 'log', 'channel', 'persona') ) )
    {
        $query->set('author', $user_ID);
        return;
    }
    //$query->set('post_type', array( 'person', 'group', 'product-or-service', 'channel', 'persona'));

    unset($user_ID);
//    }

    unset($user_level);
    return $query;
}

function if_opened_in_cbox_hide_admin_bar_and_menu($query)
{
    if($_SESSION['open_in_cb'] == 1)
    {
        add_filter('show_admin_bar', '__return_false');
//        // for the admin page
//        remove_action('admin_footer', 'wp_admin_bar_render', 1000);
//        // for the front-end
//        remove_action('wp_footer', 'wp_admin_bar_render', 1000);
//
//        // css override for the admin page
//        function unload_admin_bar_style_backend() {
//            echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
//        }
//        add_filter('admin_head','unload_admin_bar_style_backend');
//
//        // css override for the frontend
//        function unload_admin_bar_style_frontend() {
//            echo '<style type="text/css" media="screen">
//				html { margin-top: 0px !important; }
//				* html body { margin-top: 0px !important; }
//				</style>';
//        }
//        add_filter('wp_head','unload_admin_bar_style_frontend', 99);
//
//        // clear session variable
//        //unset($_SESSION['open_in_cb']);

    }
}

//allow checkbox tag past WP KSES & TinyMCE
// They currently get stripped for authors which breaks the to-do list widget

add_action( 'init', function () {
    global $allowedtags;

    $allowedtags['input'] = array(
        'type' => array()
    );
});

/**
 * Add to extended_valid_elements for TinyMCE
 *
 * @param $init assoc. array of TinyMCE options
 * @return $init the changed assoc. array
 */
function my_change_mce_options( $init ) {
    // Command separated string of extended elements
    $ext = '*[*]';

    // Add to extended_valid_elements if it alreay exists
    if ( isset( $init['extended_valid_elements'] ) ) {
        $init['extended_valid_elements'] .= ',' . $ext;
    } else {
        $init['extended_valid_elements'] = $ext;
    }

    // Super important: return $init!
    return $init;
}

add_filter('tiny_mce_before_init', 'my_change_mce_options');

add_action( 'init', function() {
    kses_remove_filters();
    //echo allowed_tags();
});

//add_action( 'init', 'allow_checkboxes_for_authors' );
//
//function allow_checkboxes_for_authors()
//{
//    global $allowedposttags;
//
//    $tags = array( 'input' );
//    $new_attributes = array( 'contenteditable' => array() );
//
//    foreach ( $tags as $tag )
//    {
//        if ( isset( $allowedposttags[ $tag ] ) && is_array( $allowedposttags[ $tag ] ) )
//            $allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $new_attributes );
//    }
//}
//
//add_filter('tiny_mce_before_init', 'allow_checkboxes_for_authors_filter_tiny_mce_before_init');
//function allow_checkboxes_for_authors_filter_tiny_mce_before_init( $options ) {
//
//    if ( ! isset( $options['extended_valid_elements'] ) ) {
//        $options['extended_valid_elements'] = '';
//    } else {
//        $options['extended_valid_elements'] .= ',';
//    }
//
//    if ( ! isset( $options['custom_elements'] ) ) {
//        $options['custom_elements'] = '';
//    } else {
//        $options['custom_elements'] .= ',';
//    }
//
//    $options['extended_valid_elements'] .= 'input';
//    $options['custom_elements']         .= 'input';
//    return $options;
//}