<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Widget_Post_Type.
 *
 * Register and handle post type registration.
 *
 * @class		Widget_Post_Type
 * @version		1.0.0
 * @package		WP Dashboard Widgets
 * @author		Jeroen Sormani
 */
class Widget_Post_Type {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register post type
		add_action( 'init', array( $this, 'register_post_type' ) );

	 }


	/**
	 * Register post type.
	 *
	 * Register and set settings for post type 'widget'.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$labels = array(
			'name'					=> __( 'Widgets', 'wp-dashboard-widgets' ),
			'singular_name'			=> __( 'Widget', 'wp-dashboard-widgets' ),
			'add_new'				=> __( 'Add New', 'wp-dashboard-widgets' ),
			'add_new_item'			=> __( 'Add New Widget', 'wp-dashboard-widgets' ),
			'edit_item'				=> __( 'Edit Widget', 'wp-dashboard-widgets' ),
			'new_item'				=> __( 'New Widget', 'wp-dashboard-widgets' ),
			'view_item'				=> __( 'View Widget', 'wp-dashboard-widgets' ),
			'search_items'			=> __( 'Search Widgets', 'wp-dashboard-widgets' ),
			'not_found'				=> __( 'No Widgets', 'wp-dashboard-widgets' ),
			'not_found_in_trash'	=> __( 'No Widgets found in Trash', 'wp-dashboard-widgets' ),
		);

		register_post_type( 'widget', array(
			'label'					=> 'widget',
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'capability_type'		=> 'post',
			'map_meta_cap'			=> true,
			'rewrite'				=> array( 'slug' => 'widgets' ),
			'_builtin'				=> false,
			'query_var'				=> true,
			'supports'				=> array( 'title', 'editor' ),
			'labels'				=> $labels,
		) );

	}

}

// Backwards compatibility
$GLOBALS['wpdw_post_type'] = WP_Dashboard_Widgets()->post_type;
