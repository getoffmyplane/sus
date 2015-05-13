<?php
if ( !empty( $_GET['callback_data'] ) ) {
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
				<th>Email</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries" /></th>
				<th>Name</th>
				<th>Email</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$file_count = 0;
			foreach ( $this->_options['remote_destinations'] as $destination_id => $destination ) {
				if ( $destination['type'] != 'email' ) {
					continue;
				}
				?>
				<tr class="entry-row alternate">
					<th scope="row" class="check-column"><input type="checkbox" name="destinations[]" class="entries" value="<?php echo $destination_id; ?>" /></th>
					<td>
						<?php echo $destination['title']; ?><br />
							<a href="<?php echo $destination_id; ?>" alt="<?php echo $destination['title'] . ' (Email)'; ?>" class="pb_backupbuddy_selectdestination">Select this destination</a> |
							<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $destination_id ); ?>&callback_data=<?php if ( !empty( $_GET['callback_data'] ) ) { echo $_GET['callback_data']; } ?>">Edit Settings</a>
					</td>
					<td style="white-space: nowrap;">
						<?php echo $destination['email']; ?>
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
	$options = array_merge( $this->_parent->_emaildefaults, (array)$this->_options['remote_destinations'][$_GET['edit']] );
	
	echo '<h3>Edit Destination</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_email">';
	echo '	<input type="hidden" name="savepoint" value="remote_destinations#' . $_GET['edit'] . '" />';
} else {
	$options = $this->_parent->_emaildefaults;
	
	echo '<h3>Add New Destination ' . $this->video( 'Tp_VkLoBEpw', 'Add a new Email destination', false ) . '</h3>';
	echo '<form method="post" action="' . admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination&edit=' . htmlentities( $edit_id ) . '&callback_data=' . $callback_data . '#pb_backupbuddy_tab_email">';
}
?>
	<input type="hidden" name="#type" value="email" />
	<table class="form-table">
		<tr>
			<td><label for="title">Destination Name<?php $this->tip( 'Name of the new destination to create. This is for your convenience only.' ); ?></label></td>
			<td><input type="text" name="#title" id="title" size="45" maxlength="45" value="<?php echo $options['title']; ?>" /></td>
		</tr>
		<tr>
			<td><label for="email">Email<?php $this->tip( '[Example: your@email.com] - Email address for this destination.' ); ?></label></td>
			<td><input type="text" name="#email" id="email" size="45" maxlength="45" value="<?php echo $options['email']; ?>" /></td>
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