<?php
/**
 * Plugin Name: Security Settings
 * Description: This plugin adds ensures that users can see only the posts that they've authored.
 * Version: 1.0.0
 * Author: Luke Woods
 * License: GPL2
 */

add_filter( 'pre_get_posts', 'see_own_posts_only' );

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