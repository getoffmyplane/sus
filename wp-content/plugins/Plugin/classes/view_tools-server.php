<?php
	$tests = array();
	
	
	// PHP VERSION
	$this_test = array(
					'title'			=>		'PHP Version',
					'suggestion'	=>		'>= ' . $this->_parent->_php_minimum,
					'value'			=>		phpversion(),
				);
	if ( version_compare( PHP_VERSION, $this->_parent->_php_minimum, '<=' ) ) {
		$this_test['status'] = 'fail';
	} else {
		$this_test['status'] = 'success';
	}
	array_push( $tests, $this_test );
	
	
	// PHP max_execution_time
	$this_test = array(
					'title'			=>		'PHP max_execution_time',
					'suggestion'	=>		'>= ' . '30 (seconds)',
					'value'			=>		ini_get( 'max_execution_time' ),
				);
	if ( ini_get( 'max_execution_time' ) < 30 ) {
		$this_test['status'] = 'warning';
	} else {
		$this_test['status'] = 'success';
	}
	array_push( $tests, $this_test );
	
	
	// WORDPRESS VERSION
	global $wp_version;
	$this_test = array(
					'title'			=>		'WordPress Version',
					'suggestion'	=>		'>= ' . $this->_parent->_wp_minimum,
					'value'			=>		$wp_version,
				);
	if ( version_compare( $wp_version, $this->_parent->_wp_minimum, '<=' ) ) {
		$this_test['status'] = 'fail';
	} else {
		$this_test['status'] = 'success';
	}
	array_push( $tests, $this_test );
	
	
	// MYSQL VERSION
	global $wpdb;
	$this_test = array(
					'title'			=>		'MySQL Version',
					'suggestion'	=>		'>= 5.0.15',
					'value'			=>		$wpdb->db_version(),
				);
	if ( version_compare( $wpdb->db_version(), '5.0.15', '<=' ) ) {
		$this_test['status'] = 'fail';
	} else {
		$this_test['status'] = 'success';
	}
	array_push( $tests, $this_test );
	
	
	// ZIP METHODS
	if ( !file_exists( $this->_options['backup_directory'] ) ) {
		if ( $this->_parent->mkdir_recursive( $this->_options['backup_directory'] ) === false ) {
			$this->error( 'Unable to create backup storage directory (' . $this->_options['backup_directory'] . ')', '9002' );
			return false;
		}
	}
			
	require_once( $this->_pluginPath . '/lib/zipbuddy/zipbuddy.php' );
	$this->_zipbuddy = new pluginbuddy_zipbuddy( $this->_options['backup_directory'] );
	$this_test = array(
					'title'			=>		'Zip Methods',
					'suggestion'	=>		'exec (best) > ziparchive > pclzip (worst)',
					'value'			=>		implode( ', ', $this->_zipbuddy->_zip_methods ),
				);
	if ( in_array( 'exec', $this->_zipbuddy->_zip_methods ) ) {
		$this_test['status'] = 'success';
	} else {
		$this_test['status'] = 'warn';
	}
	array_push( $tests, $this_test );
	
	
	// ADDHANDLER HTACCESS CHECK
	$this_test = array(
					'title'			=>		'AddHandler in .htaccess',
					'suggestion'	=>		'host dependant',
				);
	if ( file_exists( ABSPATH . '.htaccess' ) ) {
		$htaccess_contents = file_get_contents( ABSPATH . '.htaccess' );
		if ( !stristr( $htaccess_contents, 'addhandler' ) ) {
			$this_test['status'] = 'success';
			$this_test['value'] = 'n/a';
		} else {
			$this_test['status'] = 'warn';
			$this_test['value'] = 'exists';
		}
		unset( $htaccess_contents );
	} else {
		$this_test['status'] = 'success';
		$this_test['value'] = 'n/a';
	}
	array_push( $tests, $this_test );
	
?>


<table class="widefat">
	<thead>
		<tr class="thead">
			<th>Parameter</th>
			<th>Suggestion</th>
			<th>Value</th>
			<th>Status</th>
		</tr>
	</thead>
	<tfoot>
		<tr class="thead">
			<th>Parameter</th>
			<th>Suggestion</th>
			<th>Value</th>
			<th>Status</th>
		</tr>
	</tfoot>
	<tbody>
		<?php
		foreach( $tests as $this_test ) {
			echo '<tr class="entry-row alternate">';
			echo '	<td>' . $this_test['title'] . '</td>';
			echo '	<td>' . $this_test['suggestion'] . '</td>';
			echo '	<td>' . $this_test['value'] . '</td>';
			echo '	<td>' . $this_test['status'] . '</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>