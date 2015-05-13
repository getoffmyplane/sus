<?php
if ( !empty( $_GET['callback_data'] ) ) {
	$callback_data = $_GET['callback_data'];
} else {
	$callback_data = '';
}
?>

<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ); ?>&callback_data=<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>#pb_backupbuddy_tab_ftp">
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
				<th>Address</th>
				<th>Username</th>
				<th>Path</th>
				<th>FTPs</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Name</th>
				<th>Address</th>
				<th>Username</th>
				<th>Path</th>
				<th>FTPs</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$file_count = 0;
			foreach ( $this->_options['remote_destinations'] as $destination_id => $destination ) {
				if ( $destination['type'] != 'ftp' ) {
					continue;
				}
				?>
				<tr class="entry-row alternate">
					<th scope="row" class="check-column"><input type="checkbox" name="destinations[]" class="entries" value="<?php echo $destination_id; ?>" /></th>
					<td>
						<?php echo $destination['title']; ?><br />
							<a href="<?php echo $destination_id; ?>" alt="<?php echo $destination['title'] . ' (FTP)'; ?>" class="pb_backupbuddy_selectdestination">Select this destination</a> |
							<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $destination_id ); ?>&callback_data=<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>#pb_backupbuddy_tab_ftp">Edit Settings</a>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['address']; ?>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['username']; ?>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['path']; ?>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['ftps']; ?>
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
	$options = array_merge( $this->_parent->_ftpdefaults, (array)$this->_options['remote_destinations'][$_GET['edit']] );
	
	echo '<h3>Edit Destination</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_ftp">';
	echo '	<input type="hidden" name="savepoint" value="remote_destinations#' . $_GET['edit'] . '" />';
} else {
	$options = $this->_parent->_ftpdefaults;
	
	echo '<h3>Add New Destination ' . $this->video( 'O2fK6W4tokE', 'Add a new FTP destination', false ) . '</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_ftp">';
}
?>
	<input type="hidden" name="#type" value="ftp" />
	<table class="form-table">
		<tr>
			<td><label for="title">Destination Name<?php $this->tip( 'Name of the new destination to create. This is for your convenience only.' ); ?></label></td>
			<td><input type="text" name="#title" id="title" size="45" maxlength="45" value="<?php echo $options['title']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="address">Server Address<?php $this->tip( '[Example: ftp.foo.com] - FTP server address.  Do not include http:// or ftp:// or any other prefixes.' ); ?></label></td>
			<td><input type="text" name="#address" id="address" size="45" maxlength="45" value="<?php echo $options['address']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="username">Username<?php $this->tip( '[Example: foo] - Username to use when connecting to the FTP server.' ); ?></label></td>
			<td><input type="text" name="#username" id="username" size="45" maxlength="45" value="<?php echo $options['username']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="password">Password<?php $this->tip( '[Example: 1234xyz] - Password to use when connecting to the FTP server.' ); ?></label></td>
			<td><input type="password" name="#password" id="password" size="45" maxlength="45" value="<?php echo $options['password']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="path">Remote Path (optional)<?php $this->tip( '[Example: /public_html/backups] - Remote path to place uploaded files into on the destination FTP server. Make sure this path is correct and that the directory already exists. No trailing slash.' ); echo ' '; $this->video( 'O2fK6W4tokE#43', 'Set a FTP remote directory' ); ?></label></td>
			<td><input type="text" name="#path" id="path" size="45" maxlength="250" value="<?php echo $options['path']; ?>" /></td>
		</tr>
		<tr>
			<td valign="top"><label>Use sFTP Encryption <?php $this->tip( '[Default: disabled] - Select whether this connection is for FTP or FTPs (enabled; FTP over SSL). Note that FTPs is NOT the same as sFTP (FTP over SSH) and is not compatible or equal.' ); ?></label></td>
			<td>
				<input type="hidden" name="#ftps" value="0" />
				<input type="checkbox" name="#ftps" id="ftps" value="1" <?php if ( $options['ftps'] == '1' ) { echo 'checked'; } ?> /> <label for="high_security">Enable high security mode</label>
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
