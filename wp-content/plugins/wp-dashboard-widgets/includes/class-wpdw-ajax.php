<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPDW_Ajax.
 *
 * Class to handle all AJAX calls.
 *
 * @class		WPDW_Ajax
 * @version		1.0.0
 * @package		WP Dashboard Widgets
 * @author		Jeroen Sormani
 */
class WPDW_Ajax {


	/**
	 * Constructor.
	 *
	 * Add ajax actions.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// start & destroy sessions
        add_action( 'init', array( $this, 'start_user_session'));
        add_action( 'wp_logout', array( $this, 'end_user_session' ));
        add_action( 'wp_login', array( $this, 'end_user_session' ));

		// Update widget
		add_action( 'wp_ajax_wpdw_update_widget', array( $this, 'wpdw_update_widget' ) );
		add_action( 'wp_ajax_wpdw_toggle_widget', array( $this, 'wpdw_toggle_widget' ) );

		// Add / Delete widget
		add_action( 'wp_ajax_wpdw_add_widget', array( $this, 'wpdw_add_widget' ) );
		add_action( 'wp_ajax_wpdw_delete_widget', array( $this, 'wpdw_delete_widget' ) );

        // Pass resource name between Dashboard widget and "New [Resource]" screen
        add_action( 'wp_ajax_post_title_return_url', array($this, 'set_session_variables_posted_from_jquery'));
        add_filter( 'default_title', array($this, 'widget_title_to_resource'));

        // Pass resource name and URL back to dashboard widget
        add_action('publish_persona', array($this, 'set_resource_title_and_url_for_widget'),10,2);
        add_action('publish_person', array($this, 'set_resource_title_and_url_for_widget'),10,2);
        add_action('publish_group', array($this, 'set_resource_title_and_url_for_widget'),10,2);
        add_action('publish_product-or-service', array($this, 'set_resource_title_and_url_for_widget'),10,2);
        add_action('publish_channel', array($this, 'set_resource_title_and_url_for_widget'),10,2);
        add_action('publish_log', array($this, 'set_resource_title_and_url_for_widget'),10,2);
        add_action('wp_ajax_resource_title_and_url_to_widget', array($this,'resource_title_and_url_to_widget'));

        // Pages opened in colourbox hooks
        add_action('wp_ajax_set_opened_in_cb_session_variable_to_true', array($this, 'set_opened_in_cb_session_variable_to_true'));

        // Strategy pane function hooks
        add_action('wp_ajax_log_current_activity_step_to_user_meta', array($this,'log_current_activity_step_to_user_meta'));
    }

    /*
     * start_user_session
     *
     * This will create a session to store the resource name and url so that they can be passed between the admin pages and the dashboard.
     * It hooks into an action that takes place after WordPress is loaded, but before your code needs the session
     */

    function start_user_session() {
        if (!session_id()) {
            session_start();
        }
    }

    /*
     * end_user_session
     *
     * The data stored in the session doesn't go away when the user logs out or logs into a different account.
     * For that, you need to destroy the session, and, of course, that requires a couple more hooks.
     * The following code will destroy the session in these cases
     */

    function end_user_session() {
        session_destroy();
    }

	/**
	 * Update widget.
	 *
	 * Update widget + meta when the jQuery update trigger is triggered.
	 *
	 * @since 1.0.0
	 */
	 public function wpdw_update_widget() {

		$post = array(
			'ID'			=> $_POST['post_id'],
			'post_title'	=> $_POST['post_title'],
			'post_content'	=> $_POST['post_content'],
		);

		wp_update_post( $post );

		$widget_meta = array(
			'color'			=> $_POST['widget_color'],
			'color_text'	=> $_POST['widget_color_text'],
			'visibility'	=> $_POST['widget_visibility'],
			'widget_type'		=> $_POST['widget_type'],
		);
		update_post_meta( $_POST['post_id'], '_widget', $widget_meta );

		die();

	}


	/**
	 * Toggle widget.
	 *
	 * Toggle widget type, from 'note widget' to 'resource' to 'to do list widget' or vice versa.
	 *
	 * @since 1.0.0
	 */
	public function wpdw_toggle_widget() {

		$widget		= get_post( $_POST['post_id'] );
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
		$widget_meta = WP_Dashboard_Widgets::wpdw_get_widget_meta( $widget->ID );

		?>
		<style>
			#widget_<?php echo $widget->ID; ?> { background-color: <?php echo $widget_meta['color']; ?>; }
			#widget_<?php echo $widget->ID; ?> .hndle { border: none; }
		</style>
		<?php
		if ( $_POST['widget_type'] == 'note' ) :
			require plugin_dir_path( __FILE__ ) . 'templates/note.php';
        elseif ( $_POST['widget_type'] == 'resource') :
            require plugin_dir_path( __FILE__ ) . 'templates/resource-widget.php';
		else :
			require plugin_dir_path( __FILE__ ) . 'templates/todo-list.php';
		endif;

		die();

	}


	/**
	 * Add new widget.
	 *
	 * Create a new widget, return two variables (post ID | widget content) to jQuery through json_encode.
	 * To set the new widget type, make sure it is located in the ELSE part of the IF statement at the bottom of this function (since there is no assigned type, new widgets default to ELSE)
     *
	 * @since 1.0.0
	 */
	public function wpdw_add_widget() {

		$args = array(
			'post_status'	=> 'publish',
			'post_type'		=> 'widget',
			'post_title'	=> __( 'New widget', 'wp-dashboard-widgets' ),
		);
		$post_id = wp_insert_post( $args );

		$widget		= (object) array( 'ID' => $post_id, 'post_content' => '' );
		$widget_meta = apply_filters( 'wpdw_new_widget_meta', array(
			'color'			=> '#ffffff',
			'color_text'	=> 'white',
			'visibility'	=> 'Everyone',
			'widget_type'		=> 'note',
		) );
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
		$widget_meta = apply_filters( 'wpdw_new_widget_meta', $widget_meta );
		update_post_meta( $post_id, '_widget', $widget_meta );

		ob_start(); ?>

		<div id='widget_<?php echo $post_id; ?>' class='postbox'>
			<div class='handlediv' title='Click to toggle'><br></div>
			<h3 class="hndle">
				<span>
					<span contenteditable="true" class="wpdw-title"><?php _e( 'New Widget', 'wp-dashboard-widgets' ); ?></span>
					<div class="wpdw-edit-title dashicons dashicons-edit"></div>
					<span class="status"></span>
				</span>
			</h3>

			<div class='inside'>

			<style>
				#widget_<?php echo $post_id; ?> { background-color: <?php echo $widget_meta['color']; ?>; }
				#widget_<?php echo $post_id; ?> .hndle { border: none; }
			</style>

                <?php if ( 'note' == $widget_meta['widget_type'] ) :
                    require plugin_dir_path( __FILE__ ) . 'templates/note.php';
                elseif ( $_POST['widget_type'] == 'list') :
                    require plugin_dir_path( __FILE__ ) . 'templates/todo-list.php';
                else :
                    require plugin_dir_path( __FILE__ ) . 'templates/resource-widget.php';
                endif; ?>

			</div> <!-- .inside -->
		</div> <!-- .postbox -->

		<?php
		$return['widget']		= ob_get_clean();
		$return['post_id']	= $post_id;

		echo json_encode( $return );

		die();

	}


	/**
	 * Delete widget.
	 *
	 * Post is binned and not permanently deleted.
	 *
	 * @since 1.0.0
	 */
	public function wpdw_delete_widget() {

		$post_id = (int) $_POST['post_id'];
		wp_trash_post( $post_id );
		die();

	}

    /*
     * Set session variables to pass between "New [Resource]" page and Dashboard widget (e.g. resource name, url, etc.)
     */

    public function set_session_variables_posted_from_jquery()
    {
        $_SESSION["url"] = $_POST['url'];
        $_SESSION["resource_name"] = $_POST['res_name'];
        $_SESSION["post_id"] = $_POST['post_id'];
        $_SESSION["selection"] = $_POST['sel'];

        //die("Hello World");
        //die ("Selection was ".$_SESSION["selection"]." URL=".$_SESSION["url"]." Resource Name=".$_SESSION["resource_name"]." ID=".$_SESSION['post_id']);
    }

    /*
     * Progress:
     * Name resource
     * Post resource name
     * Click link
     * Select resource to create
     * New resource name == post resource name
     * Populate resource details
     * Save
     * Post resource url
     * Close lightbox
     * Widget resource link == post resource url <-- YOU ARE HERE
     * Clear session

     */

    function widget_title_to_resource()
    {
        return $_SESSION["resource_name"];
    }

    function set_resource_title_and_url_for_widget($ID, $post)
    {
        $_SESSION['resource_name'] = get_the_title($ID);
        //$_SESSION['url'] = get_permalink($ID);
        $_SESSION['url'] = get_edit_post_link($ID,'');
        //die (" URL=".$_SESSION["url"]." Resource Name=".$_SESSION["resource_name"]);
    }
/*
    function resource_title_and_url_to_widget()
    {
        $url_set = $_SESSION['url'];
        $resource_name_set = $_SESSION['resource_name'];
        //CLEAR SESSION SHOULD GO HERE
        die("<div class='resource-item'><div class='dashicons dashicons-menu wpdw-widget-sortable'></div><span class='resource-item-content' contenteditable='false'><a class='wp-colorbox-iframe' href=".$url_set.">".$resource_name_set."</a></span><div class='delete-item dashicons dashicons-no-alt'></div></div>");
    }
*/
    function resource_title_and_url_to_widget()
    {
        $response['url'] = $_SESSION['url'];
        $response['resource_name']= $_SESSION['resource_name'];
        $response['post_id']= $_SESSION['post_id'];

        //CLEAR SESSION SHOULD GO HERE
        echo json_encode($response);
        exit;
    }

    /*
     * Catcher function for if page opened in cbox, don't show admin bar and left-menu (in wpdw_admin.js)
     */

    function set_opened_in_cb_session_variable_to_true()
    {
        //set session variable to true if opening in colorbox. A wp filter is in the security settings plugin to listen for this (and unset the session variable when done)
        $_SESSION['open_in_cb'] = 1;
    }

    /*
     * Strategy pane - remember which strategy step the user is on regardless of what page they're on
     */

    function log_current_activity_step_to_user_meta()
    {
        //get current user's id
        $user_id = get_current_user_id();
        //key = column name in user_meta table to check
        $key = 'current_activity_step_id';
        //get currently running activity step
        $current_activity_step = $_POST['current_activity_step'];
        //set user meta data (for persistent running strategy) in database
        update_user_meta($user_id, $key, $current_activity_step);
    }

}


