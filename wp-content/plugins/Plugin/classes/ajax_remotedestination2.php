<?php
wp_enqueue_script( 'jquery-ui-tabs' );
wp_print_scripts( 'jquery-ui-tabs' );
$this->admin_scripts();
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#pluginbuddy-tabs").tabs();
		
		jQuery('.pb_backupbuddy_selectdestination').click(function(e) {
			var win = window.dialogArguments || opener || parent || top;
			win.pb_backupbuddy_selectdestination( jQuery(this).attr( 'href' ), jQuery(this).attr( 'alt' ), '<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>' );
			win.tb_remove();
			return false;
		});
	});
</script>

<br />

<div id="pluginbuddy-tabs">
	<ul>
		<li><a href="#pb_backupbuddy_tab_s3"><span>Amazon S3</span></a></li>
		<li><a href="#pb_backupbuddy_tab_rackspace"><span>Rackspace Cloudfiles</span></a></li>
		<li><a href="#pb_backupbuddy_tab_ftp"><span>FTP</span></a></li>
		<li><a href="#pb_backupbuddy_tab_email"><span>Email</span></a></li>
	</ul>
	<br />
	<?php
	if ( isset( $_POST['delete_destinations'] ) ) {
		if ( ! empty( $_POST['destinations'] ) && is_array( $_POST['destinations'] ) ) {
			$deleted_groups = '';
			
			foreach ( (array) $_POST['destinations'] as $id ) {
				$deleted_groups .= ' "' . stripslashes( $this->_options['remote_destinations'][$id]['title'] ) . '",';
				unset( $this->_options['remote_destinations'][$id] );
				
				// Remove this destination from all schedules using it.
				foreach( $this->_options['schedules'] as $schedule_id => $schedule ) {
					$remote_list = '';
					$trimmed_destination = false;
					
					$remote_destinations = explode( '|', $schedule['remote_destinations'] );
					foreach( $remote_destinations as $remote_destination ) {
						if ( $remote_destination == $id ) {
							$trimmed_destination = true;
						} else {
							$remote_list .= $remote_destination . '|';
						}
					}
					
					if ( $trimmed_destination === true ) {
						$this->_options['schedules'][$schedule_id]['remote_destinations'] = $remote_list;
					}
				}
			}
			
			$this->_parent->save();
			$this->alert( 'Deleted destination(s) ' . trim( $deleted_groups, ',' ) . '.' );
		}
	}
	
	if ( !empty( $_GET['edit'] ) ) {
		$edit_id = $_GET['edit'];
	} else {
		$edit_id = '';
	}

	if ( !empty( $_POST['add_destination'] ) ) {
		$next_index = end( array_keys( $this->_options['remote_destinations'] ) ) + 1;
		$this->savesettings( 'remote_destinations#' . $next_index );
	} elseif ( !empty( $_POST['edit_destination'] ) ) {
		$this->savesettings( $_POST['savepoint'] );
	}
?>

	<div class="tabs-borderwrap" id="editbox" style="position: relative; height: 100%; -moz-border-radius-topleft: 0px; -webkit-border-top-left-radius: 0px;">
		<div id="pb_backupbuddy_tab_s3">
			<?php require_once( $this->_pluginPath . '/classes/ajax_remotedestination_s3.php' ); ?>
		</div>
		<div id="pb_backupbuddy_tab_rackspace">
			<?php require_once( $this->_pluginPath . '/classes/ajax_remotedestination_rackspace.php' ); ?>
		</div>
		<div id="pb_backupbuddy_tab_ftp">
			<?php require_once( $this->_pluginPath . '/classes/ajax_remotedestination_ftp.php' ); ?>
		</div>
		<div id="pb_backupbuddy_tab_email">
			<?php require_once( $this->_pluginPath . '/classes/ajax_remotedestination_email.php' ); ?>
		</div>
	</div>
</div>