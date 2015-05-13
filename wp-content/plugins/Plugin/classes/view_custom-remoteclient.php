<?php
	if ( !empty( $this->_options['remote_destinations'][$_GET['destination_id']] ) ) {
		$destination = &$this->_options['remote_destinations'][$_GET['destination_id']];
	} else {
		echo 'Error #438934894349. Invalid destination ID.';
	}
	
	if ( $destination['type'] == 's3' ) {
		require( 'view_custom-remoteclient-s3.php' );
	} elseif ( $destination['type'] == 'rackspace' ) {
		require( 'view_custom-remoteclient-rackspace.php' );
	} elseif ( $destination['type'] == 'ftp' ) {
		require( 'view_custom-remoteclient-ftp.php');
	} else {
		echo 'Sorry, a remote client is not available for this destination at this time.';
	}
?>