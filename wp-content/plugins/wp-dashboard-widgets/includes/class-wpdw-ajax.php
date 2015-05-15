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

		// Update widget
		add_action( 'wp_ajax_wpdw_update_widget', array( $this, 'wpdw_update_widget' ) );
		add_action( 'wp_ajax_wpdw_toggle_widget', array( $this, 'wpdw_toggle_widget' ) );

		// Add / Delete widget
		add_action( 'wp_ajax_wpdw_add_widget', array( $this, 'wpdw_add_widget' ) );
		add_action( 'wp_ajax_wpdw_delete_widget', array( $this, 'wpdw_delete_widget' ) );

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
	 * Toggle widget type, from 'note widget' to 'list widget' or vice versa.
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
			'widget_type'		=> 'resource',
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
	 * Post is trashed and not permanently deleted.
	 *
	 * @since 1.0.0
	 */
	public function wpdw_delete_widget() {

		$post_id = (int) $_POST['post_id'];
		wp_trash_post( $post_id );
		die();

	}


}
