<?php
$this->title( 'Amazon S3' );
		
// S3 information
$aws_accesskey = $destination['accesskey'];
$aws_secretkey = $destination['secretkey'];
$aws_bucket = $destination['bucket'];
$aws_directory = $destination['directory'];
if ( !empty( $aws_directory ) ) {
	$aws_directory = $aws_directory . '/';
}

require_once( $this->_pluginPath . '/lib/s3/s3.php' );
$s3 = new S3( $aws_accesskey, $aws_secretkey);

// Delete S3 backups
if ( !empty( $_POST['delete_file'] ) ) {
	$delete_count = 0;
	if ( !empty( $_POST['files'] ) && is_array( $_POST['files'] ) ) {
		// loop through and delete s3 files
		foreach ( $_POST['files'] as $s3file ) {
			$delete_count++;
			// Delete S3 file
			$s3->deleteObject($aws_bucket, $s3file);
		}
	}
	if ( $delete_count > 0 ) {
		$this->alert( 'Deleted ' . $delete_count . ' file(s).' );
	}
}

// Copy S3 backups to the local backup files
if ( !empty( $_GET['copy_file'] ) ) {
	$this->alert( 'The remote file is now being copied to your <a href="' . $this->_selfLink . '-backup">local backups</a>.' );
	$this->log( 'Scheduling Cron for creating s3 copy.' );
	wp_schedule_single_event( time(), $this->_parent->_var . '-cron_s3_copy', array( $_GET['copy_file'], $aws_accesskey, $aws_secretkey, $aws_bucket, $aws_directory) );
	spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
	update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
}

echo '<h3>Editing ' . $destination['title'] . ' (' . $destination['type'] . ')</h3>';
?>
<div style="max-width: 950px;">
<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo $this->_selfLink . '&custom=' . $_GET['custom'] . '&destination_id=' . $_GET['destination_id'];?>">
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="Delete from S3" class="button-secondary delete" />
		</div>
	</div>
	<table class="widefat">
		<thead>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Backup File</th>
				<th>Last Modified <img src="<?php echo $this->_pluginURL; ?>/images/sort_down.png" style="vertical-align: 0px;" title="Sorted most recent first" /></th>
				<th>File Size</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Backup File</th>
				<th>Last Modified &darr;</th>
				<th>File Size</th>
				<th>Actions</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			// List s3 backups
			$results = $s3->getBucket( $aws_bucket);
			
			if ( empty( $results ) ) {
				echo '<tr><td colspan="5" style="text-align: center;"><i>You have not created any S3 backups yet.</i></td></tr>';
			} else {
				$file_count = 0;
				foreach ( (array) $results as $rekey => $reval ) {
					// check if file is backup
					$pos = strpos( $rekey, $aws_directory . 'backup-' );
					if ( $pos !== FALSE ) {
						$file_count++;
						?>
						<tr class="entry-row alternate">
							<th scope="row" class="check-column"><input type="checkbox" name="files[]" class="entries" value="<?php echo $rekey; ?>" /></th>
							<td>
								<?php
									$bubup = str_replace( $aws_directory . 'backup-', 'backup-', $rekey );
									echo $bubup;
								?>
							</td>
							<td style="white-space: nowrap;">
								<?php
									echo $this->_parent->format_date( $this->_parent->localize_time( $results[$rekey]['time'] ) );
									echo '<br /><span style="color: #AFAFAF;">(' . $this->_parent->time_ago( $results[$rekey]['time'] ) . ' ago)</span>';
								?>
							</td>
							<td style="white-space: nowrap;">
								<?php echo $this->_parent->format_size( $results[$rekey]['size'] ); ?>
							</td>
							<td>
								<?php echo '<a href="' . $this->_selfLink . '&custom=' . $_GET['custom'] . '&destination_id=' . $_GET['destination_id'] . '&#38;copy_file=' . $bubup . '">Copy to local</a>'; ?>
							</td>
						</tr>
						<?php
					}
				}
			}
			?>
		</tbody>
	</table>
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_file" value="Delete from S3" class="button-secondary delete" />
		</div>
	</div>
	
	<?php $this->nonce(); ?>
</form><br />
</div>