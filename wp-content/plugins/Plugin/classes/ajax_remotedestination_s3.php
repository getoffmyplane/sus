<?php
if (!empty( $_GET['callback_data'])) {
	$callback_data = $_GET['callback_data'];
} else {
	$callback_data = '';
}
?>

<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ); ?>&callback_data=<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>">
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_destinations" value="Delete" class="button-secondary delete" />
		</div>
	</div>
	<table class="widefat">
		<thead>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Name</th>
				<th>AWS Keys</th>
				<th>Bucket</th>
				<th>Directory</th>
				<th>SSL</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Name</th>
				<th>AWS Keys</th>
				<th>Bucket</th>
				<th>Directory</th>
				<th>SSL</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$file_count = 0;
			foreach ( $this->_options['remote_destinations'] as $destination_id => $destination ) {
				if ( $destination['type'] != 's3' ) {
					continue;
				}
				?>
				<tr class="entry-row alternate">
					<th scope="row" class="check-column"><input type="checkbox" name="destinations[]" class="entries" value="<?php echo $destination_id; ?>" /></th>
					<td>
						<?php echo $destination['title']; ?><br />
							<a href="<?php echo $destination_id; ?>" alt="<?php echo $destination['title'] . ' (Amazon S3)'; ?>" class="pb_backupbuddy_selectdestination">Select this destination</a> |
							<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $destination_id ); ?>&callback_data=<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>">Edit Settings</a>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['accesskey']; ?>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['bucket']; ?>
					</td>
					<td>
						<?php echo $destination['directory']; ?>
					</td>
					<td>
						<?php echo $destination['ssl']; ?>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" name="delete_destinations" value="Delete" class="button-secondary delete" />
		</div>
	</div>
	
	<?php $this->nonce(); ?>
</form>

<br />

<?php
if ( isset( $_GET['edit'] ) ) {
	$options = array_merge( $this->_parent->_s3defaults, (array)$this->_options['remote_destinations'][$_GET['edit']] );
	
	echo '<h3>Edit Destination</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_s3">';
	echo '	<input type="hidden" name="savepoint" value="remote_destinations#' . $_GET['edit'] . '" />';
} else {
	$options = $this->_parent->_s3defaults;
	
	echo '<h3>Add New Destination ' . $this->video( 'njT1ExMgUrk', 'Add a new Amazon S3 destination', false ) . '</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_s3">';
}
?>
	<input type="hidden" name="#type" value="s3" />
	<table class="form-table">
		<tr>
			<td><label for="title">Destination Name <?php $this->tip( 'Name of the new destination to create. This is for your convenience only.' ); ?></label></td>
			<td><input type="text" name="#title" id="title" size="45" maxlength="45" value="<?php echo $options['title']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="accesskey">AWS Access Key <?php $this->tip( '[Example: BSEGHGSDEUOXSQOPGSBE] - Log in to your Amazon S3 AWS Account and navigate to Account: Access Credentials: Security Credentials.' ); ?></label></td>
			<td><input type="text" name="#accesskey" id="accesskey" size="45" maxlength="45" value="<?php echo $options['accesskey']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="secretkey">AWS Secret Key <?php $this->tip( '[Example: GHOIDDWE56SDSAZXMOPR] - Log in to your Amazon S3 AWS Account and navigate to Account: Access Credentials: Security Credentials.' ); echo ' '; $this->video( 'Tp_VkLoBEpw', 'Find your Amazon S3 key' ); ?></label></td>
			<td><input type="password" name="#secretkey" id="secretkey" size="45" maxlength="45" value="<?php echo $options['secretkey']; ?>" /> <a href="https://aws-portal.amazon.com/gp/aws/developer/account/index.html?ie=UTF8&action=access-key" target="_blank" title="Opens a new tab where you can get your Amazon S3 key"><small>Get Key</small></a></td>
		</tr>
		<tr>
			<td><label for="bucket">Bucket Name <?php $this->tip( '[Example: wordpress_backups] - This bucket will be created for you automatically if it does not already exist.' ); ?></label></td>
			<td><input type="text" name="#bucket" id="bucket" size="45" maxlength="45" value="<?php echo $options['bucket']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="directory">Directory (optional) <?php $this->tip( '[Example: backupbuddy] - Directory name to place the backup in within the bucket.' ); echo ' '; $this->video( 'njT1ExMgUrk#20', 'Create an Amazon S3 bucket' ); ?></label></td>
			<td><input type="text" name="#directory" id="directory" size="45" maxlength="250" value="<?php echo $options['directory']; ?>" /></td>
		</tr>
		<tr>
			<td valign="top"><label>Use SSL Encryption <?php $this->tip( '[Default: enabled] - When enabled, all transfers will be encrypted with SSL encryption. Please note that encryption introduces overhead and may slow down the transfer. If Amazon S3 sends are failing try disabling this feature to speed up the process.  Note that 32-bit servers cannot encrypt transfers of 2GB or larger with SSL, causing large file transfers to fail.' ); ?></label></td>
			<td>
				<input type="hidden" name="#ssl" value="0" />
				<input type="checkbox" name="#ssl" id="ssl" value="1" <?php if ( $options['ssl'] == '1' ) { echo 'checked'; } ?> /> <label for="high_security">Enable high security mode</label>
			</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td>
				<input value="Test these settings" class="button-secondary pb_backupbuddy_remotetest" id="pb_backupbuddy_remotetest_s3" type="submit" alt="<?php echo admin_url('admin-ajax.php').'?action=pb_backupbuddy_remotetest&service=s3'; ?>" />
			</td>
		</tr>
		
	</table>
	
	<?php
	if ( isset( $_GET['edit'] ) ) {
		echo '<p class="submit"><input type="submit" name="edit_destination" value="Save Changes" class="button-primary" /></p>';
	} else {
		echo '<p class="submit"><input type="submit" name="add_destination" value="+ Add Destination" class="button-primary" /></p>';
	}
	
	$this->nonce();
	?>
</form>
