<?php

add_action('wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('admin_enqueue_scripts', 'admin_css_overrides');

function admin_css_overrides() {
    wp_enqueue_style( 'admin-style',  get_stylesheet_directory_uri() . '/admin-style.css' );
}

?>