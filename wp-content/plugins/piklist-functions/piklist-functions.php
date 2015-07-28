<?php
/*
 * Plugin Name: Piklist Functions
 * Plugin URI: http://www.google.com
 * Description: A plugin that holds the functions that are dependent on Piklist (e.g. custom post types, etc.).
 * Version: 0.1
 * Author: Luke Woods
 * Author URI: http://www.google.com
 * Plugin Type: Piklist
 * License: GPL2
 */

/*
 * Override post statuses for custom post types (to show CRUD instead of WP) -
 * This will be used to conditionally lock and unlock fields to distinguish between, for example,
 * View person and edit person.
 */

add_action('init', 'my_remove_post_type_support', 10);

function my_remove_post_type_support()
{
    remove_post_type_support('channel', 'editor');
    remove_post_type_support('group', 'editor');
    remove_post_type_support('person', 'editor');
    remove_post_type_support('persona', 'editor');
    remove_post_type_support('product-or-service', 'editor');
}