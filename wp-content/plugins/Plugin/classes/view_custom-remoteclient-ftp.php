<?php
	$this->title( 'FTP' );

	// FTP connection information
	$ftp_server = $destination['address'];
	$ftp_username = $destination['username'];
	$ftp_password = $destination['password'];
	$ftp_directory = $destination['path'];
	if ( !empty( $ftp_directory ) ) {
		$ftp_directory = $ftp_directory . '/';
	}
	
	// Delete ftp backups
	if ( !empty( $_POST['delete_file'] ) ) {
		$delete_count = 0;
		if ( !empty( $_POST['files'] ) && is_array( $_POST['files'] ) ) {
			// connect to server
			$conn_id = ftp_connect( $ftp_server ) or die( 'Could not connect to ' . $ftp_server );
			// login with username and password
			$login_result = ftp_login( $conn_id, $ftp_username, $ftp_password );
		
			// loop through and delete ftp backup files
			foreach ( $_POST['files'] as $backup ) {
				// try to delete backup
				if ( ftp_delete( $conn_id, $ftp_directory . $backup ) ) {
					$delete_count++;
				}
			}
		
			// close this connection
			ftp_close( $conn_id );
		}
		if ( $delete_count > 0 ) {
			$this->alert( 'Deleted ' . $delete_count . ' file(s).' );
		} else {
			$this->alert( 'No backups were deleted.');	
		}
	}

	// Copy ftp backups to the local backup files
	if ( !empty( $_GET['copy_file'] ) ) {
		$this->alert( 'The remote file is now being copied to your <a href="' . $this->_selfLink . '-backup">local backups</a>.' );
		$this->log( 'Scheduling Cron for creating ftp copy.' );
		wp_schedule_single_event( time(), $this->_parent->_var . '-cron_ftp_copy', array( $_GET['copy_file'], $ftp_server, $ftp_username, $ftp_password, $ftp_directory) );
		spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
		update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
	}
	
	// Retrieve listing of backups
	// Connect to server
	$conn_id = ftp_connect( $ftp_server ) or die( 'Could not connect to ' . $ftp_server );
		
		// Login with username and password
		$login_result = ftp_login( $conn_id, $ftp_username, $ftp_password );
		
		// Get contents of the current directory
		$contents = ftp_nlist( $conn_id, $ftp_directory );
		
		// Create array of backups and sizes
		$backups = array();
		foreach ( $contents as $backup ) {
			// check if file is backup
			$pos = strpos( $backup, 'backup-' );
			if ( $pos !== FALSE ) {
				$backups[$backup] = ftp_size( $conn_id, $ftp_directory . $backup );
			}
		}
		
	// close this connection
	ftp_close( $conn_id );
	
	
	echo '<h3>Editing ' . $destination['title'] . ' (' . $destination['type'] . ')</h3>';
?>
<div style="max-width: 950px;">
<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo $this->_selfLink . '&custom=' . $_GET['custom'] . '&destination_id=' . $_GET['destination_id'];?>">
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="Delete from FTP" class="button-secondary delete" />
		</div>
	</div>
	<table class="widefat">
		<thead>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Backup File</th>
				<!-- <th>Last Modified <img src="<?php echo $this->_pluginURL; ?>/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first" /></th> -->
				<th>File Size</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Backup File</th>
				<!-- <th>Last Modified &darr;</th> -->
				<th>File Size</th>
				<th>Actions</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			// List FTP backups
			if ( empty( $backups ) ) {
				echo '<tr><td colspan="5" style="text-align: center;"><i>This directory does not have any backups.</i></td></tr>';
			} else {
				$file_count = 0;
				foreach ( (array)$backups as $backup => $size ) {
					$file_count++;
					?>
					<tr class="entry-row alternate">
						<th scope="row" class="check-column"><input type="checkbox" name="files[]" class="entries" value="<?php echo $backup; ?>" /></th>
						<td>
							<?php
								echo $backup;
							?>
						</td>
						<td style="white-space: nowrap;">
							<?php echo $this->_parent->format_size( $size ); ?>
						</td>
						<td>
							<?php echo '<a href="' . $this->_selfLink . '&custom=' . $_GET['custom'] . '&destination_id=' . $_GET['destination_id'] . '&#38;copy_file=' . $backup . '">Copy to local</a>'; ?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="Delete from FTP" class="button-secondary delete" />
		</div>
	</div>
	
	<?php $this->nonce(); ?>
</form><br />
</div>
