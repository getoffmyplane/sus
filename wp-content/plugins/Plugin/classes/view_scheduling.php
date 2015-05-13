<?php
$this->admin_scripts();

wp_enqueue_script( 'thickbox' );
wp_print_scripts( 'thickbox' );
wp_print_styles( 'thickbox' );

if ( !empty( $_POST['add_schedule'] ) ) {
	$_POST['#first_run'] = $this->_parent->unlocalize_time( strtotime( $_POST['#first_run'] ) );
	$next_index = end( array_keys( $this->_options['schedules'] ) ) + 1;
	$this->savesettings( 'schedules#' . $next_index );
	wp_schedule_event( $_POST['#first_run'], $_POST['#interval'], 'pb_backupbuddy-cron_scheduled_backup', array( $next_index ) );
} elseif ( !empty( $_POST['edit_schedule'] ) ) {
	$_POST['#first_run'] = $this->_parent->unlocalize_time( strtotime( $_POST['#first_run'] ) );
	
	wp_unschedule_event( $this->_options['schedules'][$_GET['edit']]['first_run'], 'pb_backupbuddy-cron_scheduled_backup', array( (int)$_GET['edit'] ) ); // Remove old schedule.
	$this->savesettings( $_POST['savepoint'] );
	wp_schedule_event( $_POST['#first_run'], $_POST['#interval'], 'pb_backupbuddy-cron_scheduled_backup', array( (int)$_GET['edit'] ) ); // Add new schedule.
}

if ( isset( $_POST['delete_schedules'] ) ) {
	if ( ! empty( $_POST['schedules'] ) && is_array( $_POST['schedules'] ) ) {
		$deleted_groups = '';
		
		foreach ( (array) $_POST['schedules'] as $id ) {
			$deleted_groups .= ' "' . stripslashes( $this->_options['schedules'][$id]['title'] ) . '",';
			wp_unschedule_event( $this->_options['schedules'][$id]['first_run'], 'pb_backupbuddy-cron_scheduled_backup', array( (int)$id ) ); // Remove old schedule.
			unset( $this->_options['schedules'][$id] );
		}
		
		$this->_parent->save();
		$this->alert( 'Deleted schedule(s) ' . trim( $deleted_groups, ',' ) . '.' );
	}
}

?>

<script type="text/javascript">
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data ) {
		jQuery( '#pb_backupbuddy_remotedestinations_list' ).append( destination_title + '<br />' );
		jQuery( '#pb_backupbuddy_remotedestinations' ).val( destination_id + '|' + jQuery( '#pb_backupbuddy_remotedestinations' ).val() );
		jQuery( '#pb_backupbuddy_deleteafter' ).slideDown();
	}
</script>

<div class="wrap">
	<?php $this->title( 'Scheduled Backups' ); ?><br />
	
	
	
	<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo $this->_selfLink; ?>-scheduling">
		<div class="tablenav">
			<div class="alignleft actions">
				<input type="submit" name="delete_schedules" value="Delete" class="button-secondary delete" />
			</div>
		</div>
		<table class="widefat">
			<thead>
				<tr class="thead">
					<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
					<th>Name</th>
					<th>Type</th>
					<th>Interval</th>
					<th>First Run</th>
					<th>Destinations</th>
				</tr>
			</thead>
			<tfoot>
				<tr class="thead">
					<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
					<th>Name</th>
					<th>Type</th>
					<th>Interval</th>
					<th>First Run</th>
					<th>Destinations</th>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$file_count = 0;
				foreach ( $this->_options['schedules'] as $schedule_id => $schedule ) {
					?>
					<tr class="entry-row alternate">
						<th scope="row" class="check-column"><input type="checkbox" name="schedules[]" class="entries" value="<?php echo $schedule_id; ?>" /></th>
						<td>
							<?php echo $schedule['title']; ?>
							<div class="row-actions" style="margin:0; padding:0;">
								<a href="<?php echo $this->_selfLink . '-scheduling&edit=' . $schedule_id; ?>">Edit this schedule</a>
							</div>
						</td>
						<td style="white-space: nowrap;">
							<?php
							if ( $schedule['type'] == 'full' ) {
								echo 'full';
							} elseif ( $schedule['type'] == 'db' ) {
								echo 'database';
							} else {
								echo 'unknown: ' . $schedule['type'];
							}
							?>
						</td>
						<td style="white-space: nowrap;">
							<?php echo $schedule['interval']; ?>
						</td>
						<td>
							<?php echo $this->_parent->format_date( $this->_parent->localize_time( $schedule['first_run'] ) ); ?>
						</td>
						<td>
							<?php
							$destinations = explode( '|', $schedule['remote_destinations'] );
							foreach( $destinations as $destination ) {
								if ( !empty( $destination ) ) {
									echo $this->_options['remote_destinations'][$destination]['title'];
									echo ' (' . $this->_options['remote_destinations'][$destination]['type'] . ')';
								}
							}
							unset( $destinations );
							unset( $destination );
							?>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="alignleft actions">
				<input type="submit" name="delete_schedules" value="Delete" class="button-secondary delete" />
			</div>
			<div style="float: right;"><small><i>Hover over a schedule above for additional options.</i></small></div>
		</div>
		
		<?php $this->nonce(); ?>
	</form>

	<br />
	
	
	
	
	
	<?php
	if ( isset( $_GET['edit'] ) ) {
		$options = array_merge( $this->_parent->_scheduledefaults, (array)$this->_options['schedules'][$_GET['edit']] );
		
		echo '<h3>Edit Schedule</h3>';
		echo '<form method="post" action="' . $this->_selfLink . '-scheduling&edit=' . htmlentities( $_GET['edit'] ) . '">';
		echo '	<input type="hidden" name="savepoint" value="schedules#' . htmlentities( $_GET['edit'] ) . '" />';
	} else {
		$options = $this->_parent->_scheduledefaults;
		
		echo '<h3>Add New Schedule ' . $this->video( 'W6vQtnKM1uk', 'Add a new schedule', false ) . '</h3>';
		echo '<form method="post" action="' . $this->_selfLink . '-scheduling">';
	}
	?>
		
		<table class="form-table">
			<tr><th scope="row"><label for="title">Name for backup schedule <?php $this->tip( 'This is a name for your reference only.' ); ?></label></th>
				<td><input type="text" name="#title" id="title" size="30" maxlength="45" value="<?php echo $options['title']; ?>" /></td>
			</tr>
			<tr><th scope="row"><label for="type">Backup type <?php $this->tip( 'Full backups contain all files (except exclusions) and your database. Database only backups consist of an export of your mysql database; no WordPress files or media. Database backups are typically much smaller and faster to perform and are typically the most quickly changing part of a site.' ); ?></label></th>
				<td>
					<select name="#type">
						<option value="db" <?php if ( $options['interval'] == 'db' ) { echo 'selected'; } ?>>Database Only</option>
						<option value="full" <?php if ( $options['interval'] == 'full' ) { echo 'selected'; } ?>>Full (database + files)</option>
					</select>
				</td>
			</tr>
			<tr><th scope="row"><label for="interval">Backup interval <?php $this->tip( 'Time period between backups.' ); ?></label></th>
				<td>
					<select name="#interval">
						<option value="monthly" <?php if ( $options['interval'] == 'monthly' ) { echo 'selected'; } ?>>Monthly</option>
						<option value="twicemonthly" <?php if ( $options['interval'] == 'twicemonthly' ) { echo 'selected'; } ?>>Twice Monthly</option>
						<option value="weekly" <?php if ( $options['interval'] == 'weekly' ) { echo 'selected'; } ?>>Weekly</option>
						<option value="daily" <?php if ( $options['interval'] == 'daily' ) { echo 'selected'; } ?>>Daily</option>
						<option value="hourly" <?php if ( $options['interval'] == 'hourly' ) { echo 'selected'; } ?>>Hourly</option>
					</select>
				</td>
			</tr>
			<tr><th scope="row"><label for="name">Date/time of first run <?php $this->tip( 'IMPORTANT: For scheduled events to be occur someone (or you) must visit this site on or after the scheduled time. If no one visits your site for a long period of time some backup events may not be triggered.' ); ?></label></th>
				<td>
					<input type="text" name="#first_run" id="first_run" size="30" maxlength="45" value="<?php if ( !empty( $options['first_run'] ) ) { echo date('m/d/Y h:i a', $options['first_run'] ); } else { echo date('m/d/Y h:i a', time() + ( ( get_option( 'gmt_offset' ) * 3600 ) + 86400 ) ); } ?>" /> Currently <code><?php echo date( 'm/d/Y h:i a ' . get_option( 'gmt_offset' ), time() + ( get_option( 'gmt_offset' ) * 3600 ) ); ?> UTC</code> based on <a href="<?php echo admin_url( 'options-general.php' ); ?>">WordPress settings</a>.
					<br />
					<small>mm/dd/yyyy hh:mm [am/pm]</small>
				</td>
			</tr>
			<tr><th scope="row"><label for="send_ftp">Remote backup destination<?php $this->tip( 'Automatically sends generated backups to a remote destination such as Amazon S3, Rackspace Cloudsites, Email, or FTP.' ); ?></label></th>
				<td>
					<span id="pb_backupbuddy_remotedestinations_list">
						<?php
						$remote_destinations = explode( '|', $options['remote_destinations'] );
						foreach( $remote_destinations as $destination ) {
							if ( !empty( $destination ) ) {
								echo $this->_options['remote_destinations'][$destination]['title'];
								echo '<br />';
							}
						}
						?>
					</span>
					<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination'; ?>&#038;TB_iframe=1&#038;width=640&#038;height=400" class="thickbox button secondary-button" style="margin-top: 3px;">+ Add Remote Destination</a>
					
					<input type="hidden" name="#remote_destinations" id="pb_backupbuddy_remotedestinations" value="<?php echo $options['remote_destinations']; ?>" />
					
					<div id="pb_backupbuddy_deleteafter" style="<?php if ( !isset( $_GET['edit'] ) ) { echo 'display: none; '; } ?>background-color: #EAF2FA; border: 1px solid #E3E3E3; width: 250px; padding: 10px; margin: 5px; margin-left: 0px;">
						<input type="hidden" name="#delete_after" value="0" />
						<input type="checkbox" name="#delete_after" id="delete_after" value="1" <?php if ( $options['delete_after'] == '1' ) { echo 'checked'; } ?> /> <label for="delete_after">Delete local copy after remote send <?php $this->tip( '[Default: disabled] - When enabled the local copy of the backup will be deleted after the file has been sent to the remote destination(s).' ); ?></label>
					</div>
				</td>
			</tr>
		</table>
		
		<?php
		if ( isset( $_GET['edit'] ) ) {
			echo '<p class="submit"><input type="submit" name="edit_schedule" value="Save Changes" class="button-primary" /></p>';
		} else {
			echo '<p class="submit"><input type="submit" name="add_schedule" value="+ Add Schedule" class="button-primary" /></p>';
		}
		
		$this->nonce();
		?>
		<br />
	</form>
	
	<br /><br />
	<div style="color: #AFAFAF; width: 793px; text-align: center;">
		Due to the way schedules are triggered in WordPress someone must visit your site<br />
		for scheduled backups to occur. If there are no visits, some schedules may not be triggered.
	</div>
	<br /><br />
	
</div>
