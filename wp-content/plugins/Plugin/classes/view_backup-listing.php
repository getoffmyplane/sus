<?php
wp_enqueue_script( 'thickbox' );
wp_print_scripts( 'thickbox' );
wp_print_styles( 'thickbox' );
// Handles resizing thickbox.
if ( !wp_script_is( 'media-upload' ) ) {
	wp_enqueue_script( 'media-upload' );
	wp_print_scripts( 'media-upload' );
}

if ( !empty( $_POST['delete_file'] ) ) {
	if ( !empty( $_POST['files'] ) && is_array( $_POST['files'] ) ) {
			$delete_count = 0;
			foreach( (array) $_POST['files'] as $id ) {
				//if ($id != '') {
				if ( file_exists( $this->_options['backup_directory'] . $id ) ) {
					@unlink( $this->_options['backup_directory'] . $id );
					if ( file_exists( $this->_options['backup_directory'] . $id ) ) {
						$this->alert( 'Unable to delete file `' . $id . '`.', 'error' );
					} else {
						$delete_count++;
						
						// Clear any cached file integrity.
						unset( $this->_options['backup_file_integrity'][$id] );
						$this->_parent->save();
					}
				}
				//}
			}
		}
		if ( $delete_count > 0 ) {
			$this->alert( 'Deleted ' . $delete_count . ' files.' );
		}
}

/*
echo '<pre>';
print_r( $this->_options );
echo '</pre>';
*/


// Clear out backup file integrity cache for deleted backups.
$update_options = false;
foreach( $this->_options['backup_file_integrity'] as $backup_file => $backup_file_info ) {
	if ( !file_exists( $this->_options['backup_directory'] . $backup_file ) ) {
		unset( $this->_options['backup_file_integrity'][$backup_file] );
		$update_options = true;
	}
}
if ( $update_options === true ) {
	$this->_parent->save();
}
unset( $backup_file_info );
unset( $backup_file );
unset( $update_options );


$integrity_checked = false;
$file_list = (array) glob( $this->_options['backup_directory'] . 'backup*.zip' );
foreach( (array) $file_list as $file ) {
	$file_stats = stat( $file );
	$modified_time = $file_stats['mtime'];
	
	$filename = str_replace( $this->_options['backup_directory'], '', $file ); // Just the file name.
	
	if ( !empty( $_GET['reset_integrity'] ) ) {
		unset( $this->_options['backup_file_integrity'][$_GET['reset_integrity']] );
		$this->_parent->save();
	}
	
	if ( !empty( $this->_options['backup_file_integrity'][$filename] ) ) {
		$integrity_status = $this->_options['backup_file_integrity'][$filename]['status'];
		$integrity_scantime = $this->_options['backup_file_integrity'][$filename]['scantime'];
		$integrity_description = $this->_options['backup_file_integrity'][$filename]['description'];
		$backup_type = $this->_options['backup_file_integrity'][$filename]['backup_type'];
	} else {
		if ( $this->_options['integrity_check'] == '0' ) { // Integrity checking disabled.
			$integrity_status = 'unknown';
			$integrity_scantime = 0;
			$integrity_description = 'Integrity checking disabled based on settings. This file has not been verified.';
			$backup_type = 'unknown';
		} else { // Do integrity checking.
			$integrity_checked = true;
			$found_dat = false;
			$found_sql = false;
			$found_wpc = false;
			$backup_type = '';
			
			$zip_id = strrpos($file,'-')+1;
			$zip_id = substr( $file, $zip_id, strlen($file)-$zip_id-4 );
			
			// Check for DAT file.
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'wp-content/uploads/backupbuddy_temp/' . $zip_id . '/backupbuddy_dat.php' ) === true ) { // Post 2.0 full backup
				$found_dat = true;
				$backup_type = 'full';
			}
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'wp-content/uploads/temp_' . $zip_id . '/backupbuddy_dat.php' ) === true ) { // Pre 2.0 full backup
				$found_dat = true;
				$backup_type = 'full';
			}
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'backupbuddy_dat.php' ) === true ) { // DB backup
				$found_dat = true;
				$backup_type = 'db';
			}
			
			// Check for DB SQL file.
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'wp-content/uploads/backupbuddy_temp/' . $zip_id . '/db_1.sql' ) === true ) { // post 2.0 full backup
				$found_sql = true;
				$backup_type = 'full';
			}
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'wp-content/uploads/temp_' . $zip_id . '/db.sql' ) === true ) { // pre 2.0 full backup
				$found_sql = true;
				$backup_type = 'full';
			}
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'db_1.sql' ) === true ) { // db only backup 2.0+
				$found_sql = true;
				$backup_type = 'db';
			}
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'db.sql' ) === true ) { // db only backup pre-2.0
				$found_sql = true;
				$backup_type = 'db';
			}
			
			// Check for WordPress config file.
			if ( $this->_parent->_zipbuddy->file_exists( $file, 'wp-config.php' ) === true ) {
				$found_wpc = true;
				$backup_type = 'full';
			}
			
			// Calculate status from results.
			$integrity_status = 'pass';
			$integrity_description = '';
			$integrity_zipresult_details = implode( '<br />', $this->_parent->_zipbuddy->_status );
			
			if ( $found_dat !== true ) {
				$integrity_status = 'fail';
				$integrity_description .= 'Missing .dat file.<br />';
			}
			if ( $found_sql !== true ) {
				$integrity_status = 'fail';
				$integrity_description .= 'Missing DB SQL file.<br />';
			}
			if ( ($backup_type == 'full' ) && ( $found_wpc !== true ) ) {
				$integrity_status = 'fail';
				$integrity_description .= 'Missing WP config file.<br />';
			}
			
			$integrity_scantime = time();
			if ( $integrity_status == 'pass' ) { // If a passing file, dont bother storing the description (saves space)
				$integrity_description = '';
			} else {
				$integrity_description .= '<br />Technical Details:<br />' . $integrity_zipresult_details;
			}
			
			// Cache integrity status.
			$this->_options['backup_file_integrity'][$filename]['status'] = $integrity_status;
			$this->_options['backup_file_integrity'][$filename]['scantime'] = $integrity_scantime;
			$this->_options['backup_file_integrity'][$filename]['description'] = $integrity_description;
			$this->_options['backup_file_integrity'][$filename]['backup_type'] = $backup_type;
		}
	}
	
	$files[ $modified_time ] = array(
										'filename'				=>		$filename,
										'backup_type'			=>		$backup_type,
										'integrity_status'		=>		$integrity_status,
										'integrity_scantime'	=>		$integrity_scantime,
										'integrity_description'	=>		$integrity_description,
										'size'					=>		$file_stats['size'],
										'modified'				=>		$modified_time,
									);
	
	$this->_parent->_zipbuddy->clear_status();
}
if ( !empty( $files ) ) {
	krsort( $files );
}

// Save updated integrity data if a check was performed.
if ( $integrity_checked === true ) {
	$this->_parent->save();
}



$tip_backup_file = 'Files include random characters in their name for increased security. Verify that write permissions are available for this directory. Backup files are stored in ' . str_replace( '\\', '/', $this->_options['backup_directory'] );









require_once( $this->_pluginPath . '/classes/backup.php' );
$pluginbuddy_backupbuddy_backup = new pluginbuddy_backupbuddy_backup( $this );
$pluginbuddy_backupbuddy_backup->trim_old_archives();




?>






<script type="text/javascript">
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data ) {
		if ( callback_data != '' ) {
			jQuery.post( '<?php echo admin_url('admin-ajax.php'); ?>?action=pb_backupbuddy_remotesend', { destination_id: destination_id, destination_title: destination_title, file: callback_data }, 
				function(data) {
					if ( data != '1' ) {
						alert('Error starting remote send: ' + data );
					} else {
						alert( 'Your file has been scheduled to be sent now. It should arrive shortly.' );
					}
				}
			);
		} else {
			window.location.href = '<?php echo $this->_selfLink; ?>&custom=remoteclient&destination_id=' + destination_id;
		}
	}
</script>








<div style="max-width: 950px;">
	<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo $this->_selfLink; ?>-backup">
		<div class="tablenav">
			<div class="alignleft actions">
				<input type="submit" name="delete_file" value="Delete" class="button-secondary delete" />
			</div>
		</div>
		<table class="widefat">
			<thead>
				<tr class="thead">
					<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
					<th>Backup File <?php $this->tip( $tip_backup_file ); ?></th>
					<th>Last Modified <img src="<?php echo $this->_pluginURL; ?>/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first" /></th>
					<th>File Size</th>
					<th>Status <?php $this->tip( 'Backups are checked to verify that they are valid BackupBuddy backups and contain all of the key backup components needed to restore. Backups may display as invalid until they are completed. Click the refresh icon to re-verify the archive.' ); ?></th>
					<th>Type</th>
				</tr>
			</thead>
			<tfoot>
				<tr class="thead">
					<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
					<th>Backup File <?php $this->tip( $tip_backup_file ); ?></th>
					<th>Last Modified <img src="<?php echo $this->_pluginURL; ?>/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first" /></th>
					<th>File Size</th>
					<th>Status <?php $this->tip( 'Backups are checked to verify that they are valid BackupBuddy backups and contain all of the key backup components needed to restore. Backups may display as invalid until they are completed. Click the refresh icon to re-verify the archive.' ); ?></th>
					<th>Type</th>
				</tr>
			</tfoot>
			<tbody>
				<?php
				if ( empty( $files ) ) {
					echo '<tr><td colspan="6" style="text-align: center;"><i>You have not created any backups yet.</i></td></tr>';
				} else {
					$file_count = 0;
					foreach ( (array) $files as $file ) {
						$file_count++;
						?>
						<tr class="entry-row alternate">
							<th scope="row" class="check-column"><input type="checkbox" name="files[]" class="entries" value="<?php echo $file[ 'filename' ]; ?>" /></th>
							<td>
								<?php echo '<a href="'. site_url() . '/wp-content/uploads/backupbuddy_backups/' . $file['filename'] . '" title="Click to download this backup.">' . $file['filename'] . '</a>' ?>
								<div class="row-actions" style="margin:0; padding:0;">
									<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&callback_data=' . urlencode( $file['filename'] ); ?>&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox" style="margin-top: 3px;" title="Send this file to a remote destination such as Amazon S3, Email, FTP, or Rackspace Cloud.">Send file offsite</a> |
									<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_md5hash&file=' . urlencode( $file['filename'] ); ?>&#038;TB_iframe=1&#038;width=640&#038;height=821" class="thickbox" style="margin-top: 3px;" title="Allows you to later verify the backup file has been unchanged.">MD5 Checksum</a>
								</div>
							</td>
							<td style="white-space: nowrap;">
								<?php
									echo $this->_parent->format_date( $this->_parent->localize_time( $file['modified'] ) );
									echo '<br /><span style="color: #AFAFAF;">(' . $this->_parent->time_ago( $file['modified'] ) . ' ago)</span>';
								?>
							</td>
							<td style="white-space: nowrap;">
								<?php echo $this->_parent->format_size( $file['size'] ); ?>
							</td>
							<td>
								<?php
								echo '<span title="Last checked: ' . $this->_parent->format_date( $this->_parent->localize_time( $file['integrity_scantime'] ) ) . '">';
								if ( $file['integrity_status'] == 'pass' ) {
									echo 'Good';
								} elseif ( $file['integrity_status'] == 'unknown' ) {
									echo 'Unknown';
								} else {
									echo '<span style="color: red;">Bad</span>';
									$this->tip( $file['integrity_description'], 'Integrity Check Failure' );
								}
								echo '</span> <a href="' . $this->_selfLink . '-backup&reset_integrity=' . urlencode( $file['filename'] ) . '" title="Refresh backup integrity status for this file"><img src="' . $this->_pluginURL . '/images/refresh_gray.gif" style="vertical-align: -1px;" /></a>'; ?>
							</td>
							<td>
								<?php
								if ( $file['backup_type'] == 'full' ) {
									echo 'Full';
								} elseif( $file['backup_type'] == 'db' ) {
									echo 'Database';
								} else {
									echo 'Unknown';
								}
								?>
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
				<input type="submit" name="delete_file" value="Delete" class="button-secondary delete" />
			</div>
			<div style="float: right;"><small><i>Hover over a file above for additional options.</i></small></div>
		</div>
		
		<?php $this->nonce(); ?>
	</form><br />
</div>


<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination'; ?>&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox button secondary-button" style="margin-top: 3px;" title="Select a destination within to manage archives within the destination">Manage Remote Destinations & Archives</a>
<span style="color: #AFAFAF;">
	&nbsp;&nbsp;&nbsp;
	Manage Amazon S3, Rackspace Cloudfiles, Email, and FTP.
</span>