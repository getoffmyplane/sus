<?php
if ( !empty( $_GET['callback_data'] ) ) {
	$callback_data = $_GET['callback_data'];
} else {
	$callback_data = '';
}
?>

<form id="posts-filter" enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ); ?>&callback_data=<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>#pb_backupbuddy_tab_rackspace">
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
				<th>Username</th>
				<th>Container</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Name</th>
				<th>Username</th>
				<th>Container</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$file_count = 0;
			foreach ( $this->_options['remote_destinations'] as $destination_id => $destination ) {
				if ( $destination['type'] != 'rackspace' ) {
					continue;
				}
				?>
				<tr class="entry-row alternate">
					<th scope="row" class="check-column"><input type="checkbox" name="destinations[]" class="entries" value="<?php echo $destination_id; ?>" /></th>
					<td>
						<?php echo $destination['title']; ?><br />
							<a href="<?php echo $destination_id; ?>" alt="<?php echo $destination['title'] . ' (Rackspace)'; ?>" class="pb_backupbuddy_selectdestination">Select this destination</a> |
							<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $destination_id ); ?>&callback_data=<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>#pb_backupbuddy_tab_rackspace">Edit Settings</a>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['username']; ?>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['container']; ?>
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
	$options = array_merge( $this->_parent->_rackspacedefaults, (array)$this->_options['remote_destinations'][$_GET['edit']] );
	
	echo '<h3>Edit Destination</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_rackspace">';
	echo '	<input type="hidden" name="savepoint" value="remote_destinations#' . $_GET['edit'] . '" />';
} else {
	$options = $this->_parent->_rackspacedefaults;
	
	echo '<h3>Add New Destination ' . $this->video( 'lfTs_GtAp1I', 'Add a new Rackspace destination', false ) . '</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_rackspace">';
}
?>
	<input type="hidden" name="#type" value="rackspace" />
	<table class="form-table">
		<tr>
			<td><label for="title">Destination Name <?php $this->tip( 'Name of the new destination to create. This is for your convenience only.' ); ?></label></td>
			<td><input type="text" name="#title" id="title" size="45" maxlength="45" value="<?php echo $options['title']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="username">Username <?php $this->tip( '[Example: badger] - Your Rackspace Cloudfiles username.' ); ?></label></td>
			<td><input type="text" name="#username" id="username" size="45" maxlength="45" value="<?php echo $options['username']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="api_key">API Key <?php $this->tip( '[Example: 9032jk09jkdspo9sd32jds9swd039dwe] - Log in to your Rackspace Cloudfiles Account and navigate to Your Account: API Access' ); echo ' '; $this->video( 'lfTs_GtAp1I#14s', 'Get your Rackspace Cloudfiles API key' ); ?></label></td>
			<td><input type="password" name="#api_key" id="api_key" size="45" maxlength="45" value="<?php echo $options['api_key']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="container">Container <?php $this->tip( '[Example: wordpress_backups] - This container will NOT be created for you automatically if it does not already exist. Please create it first.' ); echo ' '; $this->video( 'lfTs_GtAp1I#26', 'Create a container from the Rackspace Cloudfiles panel' ); ?></label></td>
			<td><input type="text" name="#container" id="container" size="45" maxlength="45" value="<?php echo $options['container']; ?>" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input value="Test these settings" class="button-secondary pb_backupbuddy_remotetest" id="pb_backupbuddy_remotetest_rackspace" type="submit" alt="<?php echo admin_url('admin-ajax.php').'?action=pb_backupbuddy_remotetest&service=rackspace'; ?>" />
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
