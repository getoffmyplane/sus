<?php
/*
 * Plugin Name:		WP Dashboard Widgets
 * Plugin URI:		http://www.startupsite.com
 * Donate link:		http://www.jeroensormani.com/donate/
 * Description:		Allows users to create custom widgets to: link to resources, act as post-it widgets, act as to-do lists.
 * Version:			0.1
 * Author:			Luke
 * Author URI:		http://www.startupsite.com/
 * Text Domain:		wp-dashboard-widgets
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! is_admin() ) return; // Only load plugin when user is in admin

/**
 * Class WP_Dashboard_Widgets.
 *
 * Main WPDW class initializes the plugin.
 *
 * @class		WP_Dashboard_Widgets
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class WP_Dashboard_Widgets {


	/**
	 * Plugin version number
	 *
	 * @since 1.0.3
	 * @var string $version Plugin version number.
	 */
	public $version = '1.0.5';


	/**
	 * Plugin file.
	 *
	 * @since 1.0.0
	 * @var string $file Plugin file path.
	 */
	public $file = __FILE__;


	/**
	 * Instace of WP_Dashboard_Widget.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The instance of WP_Dashboard_Widgets.
	 */
	private static $instance;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Init plugin parts
		$this->init();

		$this->hooks();

	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 *
	 * @return object Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;

	}


	/**
	 * Init.
	 *
	 * Initiate plugin parts.
	 *
	 * @since 1.0.5
	 */
	public function init() {

		/**
		 * Post type class.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-widget-post-type.php';
		$this->post_type = new Widget_Post_Type();

		/**
		 * AJAX class.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpdw-ajax.php';
		$this->ajax = new WPDW_Ajax();

	}


	/**
	 * Hooks.
	 *
	 * Init actions and filters.
	 *
	 * @since 1.0.5
	 */
	public function hooks() {

		// Add dashboard widget
		add_action( 'wp_dashboard_setup', array( $this, 'wpdw_init_dashboard_widget' ) );

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'wpdw_admin_enqueue_scripts' ) );

		// Make URLs clickable
		add_action( 'wpdw_content', array( $this, 'wpdw_clickable_url' ) );

		// Add widget button
		add_filter( 'manage_dashboard_columns', array( $this, 'wpdw_dashboard_columns' ) );

		// Load textdomain
		load_plugin_textdomain( 'wp-dashboard-widgets', false, basename( dirname( __FILE__ ) ) . '/languages' );

    }

	/**
	 * Enqueue scripts.
	 *
	 * Enqueue Stylesheet and multiple javascripts.
	 *
	 * @since 1.0.0
	 */
	public function wpdw_admin_enqueue_scripts() {

		// Javascript
		wp_enqueue_script( 'wpdw_admin_js', plugin_dir_url( __FILE__ ) . 'assets/js/wpdw_admin.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version );

        // jQuery BlockUI plugin (prevent user interaction during Ajax calls
        wp_enqueue_script( 'jquery_block_ui', plugin_dir_url ( __FILE__) . 'assets/js/jquery.blockUI.js' );

		// Stylesheet
		wp_enqueue_style( 'wpdw_admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/wpdw_admin.css', array( 'dashicons' ), $this->version );

	}


	/**
	 * Get widgets.
	 *
	 * Returns all posts from DB with post type 'widget'.
	 *
	 * @since 1.0.0
	 *
	 * @return Array List of all published widgets.
	 */
	public function wpdw_get_widgets() {

		$widgets = get_posts( array( 'posts_per_page' => '-1', 'post_type' => 'widget' ) );

		return apply_filters( 'wpdw_widgets', $widgets );

	}


	/**
	 * Widget meta.
	 *
	 * Return widget meta selected by widget id.
	 *
	 * @since 1.0.0
	 *
	 * @param	int		$widget_id	ID of the widget.
	 * @return	array				Widget meta.
	 */
	public static function wpdw_get_widget_meta( $widget_id ) {

		$widget_meta = get_post_meta( $widget_id, '_widget', true );

		if ( ! isset( $widget_meta['widget_type'] ) )	{ $widget_meta['widget_type']	= 'resource'; }
		if ( ! isset( $widget_meta['color'] ) )		{ $widget_meta['color']		= '#ffffff'; }
		if ( ! isset( $widget_meta['visibility'] ) )	{ $widget_meta['visibility']	= 'public'; }
		if ( ! isset( $widget_meta['color_text'] ) )	{ $widget_meta['color_text']	= 'white'; }

		return apply_filters( 'wpdw_widget_meta', $widget_meta );

	}


	/**
	 * Initialize dashboard widgets.
	 *
	 * Get all widgets and initialize dashboard widgets.
	 *
	 * @since 1.0.0
	 */
	public function wpdw_init_dashboard_widget() {

		$widgets = $this->wpdw_get_widgets();

		foreach ( $widgets as $widget ) :

			$widget_meta	= $this->wpdw_get_widget_meta( $widget->ID );
			$user		= wp_get_current_user();

			// Skip if private
			if ( 'private' == $widget_meta['visibility'] && $user->ID != $widget->post_author ) :
				continue;
			endif;

			// Add widget
			wp_add_dashboard_widget(
				'widget_' . $widget->ID,
				'<span contenteditable="true" class="wpdw-title">' . $widget->post_title . '</span><div class="wpdw-edit-title dashicons dashicons-edit"></div><span class="status"></span>',
				array( $this, 'wpdw_render_dashboard_widget' ),
				'',
				$widget
			);

		endforeach;

	}


	/**
	 * Render dashboard widget.
	 *
	 * Load data and render the widget with the right colors.
	 *
	 * @since 1.0.0
	 *
	 * @param object	$post Post object.
	 * @param array		$args Extra arguments.
	 */
	public function wpdw_render_dashboard_widget( $post, $args ) {

		$widget		= $args['args'];
		$widget_meta	= $this->wpdw_get_widget_meta( $widget->ID );
		$content	= apply_filters( 'wpdw_content', $widget->post_content );
		$colors		= apply_filters( 'wpdw_colors', array(
			'white'		=> '#fff',
			'red'		=> '#f7846a',
			'orange'	=> '#ffbd22',
			'yellow'	=> '#eeee22',
			'green'		=> '#bbe535',
			'blue'		=> '#66ccdd',
			'black'		=> '#777777',
		) );

		// Inline styling required for widget depending colors.
		?><style>
			#widget_<?php echo $widget->ID; ?> { background-color: <?php echo $widget_meta['color']; ?>; }
			#widget_<?php echo $widget->ID; ?> .hndle { border: none; }
		</style><?php

		if ( $widget_meta['widget_type'] == 'note' ) :
			require plugin_dir_path( __FILE__ ) . 'includes/templates/note.php';
        elseif ( $widget_meta['widget_type'] == 'resource') :
            require plugin_dir_path( __FILE__ ) . 'includes/templates/resource-widget.php';
		else :
			require plugin_dir_path( __FILE__ ) . 'includes/templates/todo-list.php';
		endif;

	}


	/**
	 * Clickable URL.
	 *
	 * Filter widget content to make links clickable.
	 *
	 * @since 1.0.0
	 *
	 * @param	string	$content	Original content.
	 * @return	string				Edited content.
	 */
	public function wpdw_clickable_url( $content ) {

		return make_clickable( $content );

	}


	/**
	 * Add button.
	 *
	 * Adds a 'Add widget' button to the 'Screen Options' tab.
	 * Triggered via jQuery.
	 *
	 * @since 1.0.0
	 *
	 * @global	object	$current_screen	Information about current screen.
	 *
	 * @param	array	$columns	Array of columns within the screen options tab.
	 * @return	array				Array of columns within the screen options tab.
	 */
	public function wpdw_dashboard_columns( $columns ) {

		global $current_screen;

		if ( $current_screen->id ) :
			$columns['add_widget'] = __( 'New Widget', 'wp-dashboard-widgets' );
		endif;
		return $columns;

	}
}

/**
 * The main function responsible for returning the WP_Dashboard_Widgets object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php WP_Dashboard_Widgets()->method_name(); ?>
 *
 * @since 1.0.0
 *
 * @return object WP_Dashboard_Widgets class object.
 */
if ( ! function_exists( 'WP_Dashboard_Widgets' ) ) :

	function WP_Dashboard_Widgets() {
		return WP_Dashboard_Widgets::instance();
	}

endif;

WP_Dashboard_Widgets();


// Backwards compatibility
$GLOBALS['wp_dashboard_widgets'] = WP_Dashboard_Widgets();
