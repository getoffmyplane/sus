<?php
if ( !class_exists( "pluginbuddy_backupbuddy_backup" ) ) {
	class pluginbuddy_backupbuddy_backup {
		var $_errors = array();
		var $_ajax = false;			// True when in AJAX mode.
		
		
		function pluginbuddy_backupbuddy_backup( &$parent ) {
			$this->_parent = &$parent;
			$this->_var = &$parent->_var;
			$this->_name = &$parent->_name;
			$this->_options = &$parent->_options;
			$this->_pluginPath = &$parent->_pluginPath;
			$this->_pluginURL = &$parent->_pluginURL;
			$this->_selfLink = &$parent->_selfLink;
			
			// Load options if they have not been loaded yet...
			if ( empty( $this->_options ) ) {
				$this->_parent->load();
			}
			
			// Load zipbuddy object if it has not been initialized yet...
			if ( !isset( $this->_parent->_zipbuddy ) ) {
				require_once( $this->_pluginPath . '/lib/zipbuddy/zipbuddy.php' );
				$this->_parent->_zipbuddy = new pluginbuddy_zipbuddy( $this->_options['backup_directory'] );
			}
		}
		
		
		function log() {
			$args = func_get_args();
			call_user_func_array( array( $this->_parent, 'log' ) , $args );
		}
		
		
		// $pre_backup	array		FUTURE: Array of function processes to run prior to backing up.
		// $post_backup	array		Array of function processes to run after backing up processes complete.
		// $trigger		string		manual for manual backup, scheduled for schedule-triggered backup.
		function start_backup_process( $type, $trigger = 'manual', $pre_backup = array(), $post_backup = array() ) {
			// Prepare backup directory (need this up high for status logging)
			if ( !file_exists( $this->_options['backup_directory'] ) ) {
				if ( $this->_parent->_parent->mkdir_recursive( $this->_options['backup_directory'] ) === false ) {
					$this->error( 'Unable to create backup storage directory (' . $this->_options['backup_directory'] . ')', '9002' );
					return false;
				}
			}
			if ( !is_writable( $this->_options['backup_directory'] ) ) {
				$this->error( 'Archive directory is not writable. Check your permissions. (' . $this->_options['backup_directory'] . ')', '9016' );
				return false;
			}
			
			
			if ( $this->pre_backup( $type, $trigger, $pre_backup, $post_backup ) === false ) {
				return false;
			}
			
			$this->cron_next_step();
			
			return true;
		}
		
		
		// Calls cron to run the next step of the backup NOW.
		function cron_next_step() {
			$this->log( 'Scheduling Cron for ' . $this->_backup['serial'] . '.' );
			wp_schedule_single_event( time(), $this->_parent->_var . '-cron_process_backup', array( $this->_backup['serial'] ) );
			spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
			update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
		}
		
		
		function process_backup( $serial ) {
			$this->_backup = &$this->_options['backups'][$serial];
			
			$this_process = array_shift( $this->_backup['steps'] );
			$this->_parent->save(); // Save shifted steps array.
			
			$this->status( 'details', 'Starting function `' . $this_process['function'] . '`.' );
			$response = call_user_func_array( array( &$this, $this_process['function'] ), $this_process['args'] );
			if ( $response === false ) {
				$this->status( 'error', 'Failed function `' . $this_process['function'] . '`. Backup terminated.' );
			} else {
				$this->status( 'details', 'Finished function `' . $this_process['function'] . '`.' );
			
				if ( count( $this->_backup['steps'] ) > 0 ) { // Only schedule next step if more steps exist.
					$this->cron_next_step();
				}
			}
		}
		
		
		function pre_backup( $type, $trigger, $pre_backup = array(), $post_backup = array() ) {
			$this->_options['last_backup'] = time();
			$this->_options['edits_since_last'] = 0;
			
			$serial = $this->rand_string( 10 );
			$this->_backup = &$this->_options['backups'][$serial]; // Set reference.
			$this->_backup['serial'] = $serial; // SET SERIAL BEFORE DOING ANYTHING ELSE OR LOGGING WILL NOT WORK.
			
			$this->set_greedy_script_limits();
			
			// Schedule a cleanup of this backup 6 hours from now in case this backup fails. If we get cleaned up before this runs this is okay.
			wp_schedule_single_event( ( time() + ( 6 * 60 * 60 ) ), $this->_parent->_var . '-cron_final_cleanup', array( $this->_backup['serial'] ) );
			
			$this->_backup['type'] = $type;
			$this->_backup['start_time'] = time();
			$this->_backup['updated_time'] = time();
			$this->_backup['status'] = array();
			
			if ( $type == 'full' ) {
				$this->status( 'message', 'Full backup mode.' );
			} elseif ( $type == 'db' ) {
				$this->status( 'message', 'Database only backup mode.' );
			} else {
				$this->status( 'error', 'Unknown backup mode.' );
			}
			$this->status( 'details', 'Performing pre-backup procedures.' );
			
			$siteurl = site_url();
			$siteurl = str_replace( 'http://', '', $siteurl );
			$siteurl = str_replace( 'https://', '', $siteurl );
			$siteurl = str_replace( '/', '_', $siteurl );
			$siteurl = str_replace( '\\', '_', $siteurl );
			$siteurl = str_replace( '.', '_', $siteurl );
			
			$this->_backup['backup_directory'] = $this->_options['backup_directory'];
			$this->_backup['archive_file'] = $this->_options['backup_directory'] . 'backup-' . $siteurl . '-' . str_replace( '-', '_', date( 'Y-m-d' ) ) . '-' . $this->_backup['serial'] . '.zip';
			$this->_backup['trigger'] = $trigger;
			
			$this->status( 'details', 'Backup serial: ' . $this->_backup['serial'] );
			
			if ( $this->_options['force_compatibility'] == '1' ) {
				$this->_backup['force_compatibility'] = true;
			} else {
				$this->_backup['force_compatibility'] = false;
			}
			
			$this->_backup['steps'] = array(
								array(
									'function'	=>	'backup_create_database_dump',
									'args'		=>	array(),
								),
								array(
									'function'	=>	'backup_zip_files',
									'args'		=>	array(),
								),
								array(
									'function'	=>	'post_backup',
									'args'		=>	array(),
								),
							);
							
			$this->_backup['steps'] = array_merge( $pre_backup, $this->_backup['steps'], $post_backup );
			
			unset( $siteurl );
			
			$this->anti_directory_browsing( $this->_backup['backup_directory'] );
			
			// Prepare temporary directory for holding SQL and data file.
			$this->_backup['temp_directory'] = ABSPATH . 'wp-content/uploads/backupbuddy_temp/' . $this->_backup['serial'] . '/';
			if ( !file_exists( $this->_backup['temp_directory'] ) ) {
				if ( $this->_parent->_parent->mkdir_recursive( $this->_backup['temp_directory'] ) === false ) {
					$this->error( 'Unable to create temporary storage directory (' . $this->_backup['temp_directory'] . ')', '9002' );
					return false;
				}
			}
			if ( !is_writable( $this->_backup['temp_directory'] ) ) {
				$this->error( 'Temp data directory is not writable. Check your permissions. (' . $this->_backup['temp_directory'] . ')', '9015' );
				return false;
			}
			$this->anti_directory_browsing( ABSPATH . 'wp-content/uploads/backupbuddy_temp/' );
			
			// Prepare temporary directory for holding ZIP file while it is being generated.
			$this->_backup['temporary_zip_directory'] = $this->_options['backup_directory'] . 'temp_zip_' . $this->_backup['serial'] . '/';
			if ( !file_exists( $this->_backup['temporary_zip_directory'] ) ) {
				if ( $this->_parent->_parent->mkdir_recursive( $this->_backup['temporary_zip_directory'] ) === false ) {
					$this->error( 'Unable to create temporary ZIP storage directory (' . $this->_backup['temporary_zip_directory'] . ')', '9002' );
					return false;
				}
			}
			if ( !is_writable( $this->_backup['temporary_zip_directory'] ) ) {
				$this->error( 'Temp data directory is not writable. Check your permissions. (' . $this->_backup['temporary_zip_directory'] . ')', '9015' );
				return false;
			}
			
			// Generate data file.
			if ( $this->backup_create_dat_file() !== true ) {
				return false;
			}
			
			$this->status( 'details', 'Finished pre-backup procedures.' );
			$this->status( 'action', 'finish_settings' );
			
			// Save $this->_backup into options (it's a reference of course!).
			$this->_parent->save();
			
			return true;
		}
		
		
		/**
		 *	backup_create_dat_file()
		 *
		 *	Generates backupbuddy_dat.php within the temporary directory containing the
		 *	random serial in its name. This file contains a serialized array that has been
		 *	XOR encrypted for security.  The XOR key is backupbuddy_SERIAL where SERIAL
		 *	is the randomized set of characters in the ZIP filename. This file contains
		 *	various information about the source site.
		 *
		 *	@return		null
		 *
		 */
		function backup_create_dat_file() {
			$this->status( 'details', 'Creating import data file.' );
			
			global $wpdb;
			
			$dat_content = array(
				// Backup details.
				'backupbuddy_version'		=> $this->_parent->plugin_info( 'version' ),
				'backup_time'				=> $this->_backup['start_time'],
				'backup_type'				=> $this->_backup['type'],
				'serial'					=> $this->_backup['serial'],
				
				// WordPress details.
				'abspath'					=> ABSPATH,
				'siteurl'					=> site_url(),
				'home'						=> get_option( 'home' ),
				'blogname'					=> get_option( 'blogname' ),
				'blogdescription'			=> get_option( 'blogdescription' ),
				
				// Database details. Possibly sensitive values we will be set
				// several lines down if high security mode is NOT enabled.
				'db_prefix'					=> $wpdb->prefix,
				'db_name'					=> '',
				'db_user'					=> '',
				'db_server'					=> '',
				'db_password'				=> '',
			);
			
			// If high security mode is enabled we will omit the following database details.
			if ( $this->_options['high_security'] != '1' ) { // High security off.
				$dat_content['db_name'] = DB_NAME;
				$dat_content['db_user'] = DB_USER;
				$dat_content['db_server'] = DB_HOST;
				$dat_content['db_password'] = DB_PASSWORD;
			} else { // High security on.
				// TODO: High security mode dat settings.
			}
			
			// If currently using SSL or forcing admin SSL then we will check the hardcoded defined URL to make sure it matches.
			if ( is_ssl() OR ( defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN == true ) ) {
				$dat_content['siteurl'] = get_option('siteurl');
			}
			
			// Serialize .dat file array.
			$dat_content = base64_encode( serialize( $dat_content ) );
			
			
			// Encrypt serialized data with XOR encryption to obscure data.
			
			//require_once( $this->_pluginPath . '/lib/xorcrypt/xorcrypt.php' );
			//$xorcrypt = new xorcrypt();
			//$xorcrypt->set_key( 'backupbuddy_' . $this->_backup['serial'] );
			//$dat_content = $xorcrypt->encrypt( $dat_content );
			
			// Write data to the dat file.
			$dat_file = $this->_backup['temp_directory'] . 'backupbuddy_dat.php';
			if ( false === ( $file_handle = fopen( $dat_file, 'w' ) ) ) {
				$this->error( 'Temp data file is not creatable/writable. Check your permissions. (' . $dat_file . ')', '9017' );
				return false;
			}
			fwrite( $file_handle, "<?php die('Access Denied.'); ?>\n" . $dat_content );
			fclose( $file_handle );
			
			$this->status( 'details', 'Finished creating import data file.' );
			
			return true;
		}
		
		
		function backup_create_database_dump() {
			$this->status( 'action', 'start_database' );
			//sleep(7);
			$this->status( 'message', 'Saving database content to file.' );
			
			$database_file = $this->_backup['temp_directory'] . 'db_1.sql';
			if ( false === ( $file_handle = fopen( $database_file, 'w' ) ) ) {
				$this->error( 'Database file is not creatable/writable. Check your permissions. (' . $database_file . ')', '9018' );
				return false;
			}
			
			// --- START: Database prep & setting up list of tables to backup. ---
			
			global $wpdb;
			mysql_connect( DB_HOST, DB_USER, DB_PASSWORD );
			mysql_select_db( DB_NAME );
			
			$tables = array();
			$result = mysql_query( 'SHOW TABLES' );
			$this->status( 'message', 'Saving database content to file. (' . mysql_num_rows( $result ) . ' tables)' );
			while( $row = mysql_fetch_row( $result ) ) {
				array_push( $tables, $row[0] );
			}
			mysql_free_result( $result ); // Free memory.
			
			// Make $tables only include the tables we want to actually back up.
			$this->_parent->log( 'backup_create_database_dump(): Calculating table inclusion/exclusion.' );
			if ( $this->_options['backup_nonwp_tables'] == '1' ) { // All tables minus excluded ones.
				$this->status( 'message', 'Including non-WordPress tables in backup (if found; based on settings).' );
				foreach( $this->_options['exclude_tables'] as $this_table ) { // Look to exclude any tables.
					if ( false !== ( $table_key = array_search( $this_table, $tables ) ) ) { // Found this table so it needs removed from list.
						unset( $tables[$table_key] );
					}
				}
			} else { // Limit to WordPress prefixed tables plus additionally defined ones.
				$this->status( 'message', 'Only including WordPress tables in backup (unless more explicitly defined).' );
				$temp_tables = array();
				foreach( $tables as $this_table_key => $this_table ) { // Strip out differently prefixed tables into another list temporarily.
					if ( substr( $this_table, 0, strlen( $wpdb->prefix ) ) != $wpdb->prefix ) { // Does not match so pull it out!
						array_push( $temp_tables, $this_table );
						unset( $tables[$this_table_key] );
					}
				}
				// Now $tables only includes WP prefixed tables. $temp_tables has all the rest. We need to see which ones to keep.
				// Set $temp_tables to be values in $temp_tables that are also in the include list.
				$temp_tables = array_intersect( $this->_options['include_tables'], $temp_tables );
				
				// Put the included tables together with the WP prefixed tables.
				$tables = array_merge( $tables, $temp_tables );
				
				// Cleanup.
				unset( $this_table_key );
				unset( $this_table );
				unset( $temp_tables );
			}
			if ( !empty( $this->_options['exclude_tables'] ) ) {
				$this->status( 'details', 'Additional tables are set to be excluded based on settings.' );
			}
			if ( !empty( $this->_options['include_tables'] ) ) {
				$this->status( 'details', 'Additional tables are set to be included based on settings.' );
			}
			
			// --- END: Database prep & setting up list of tables to backup. ---
			
			//$this->_parent->log( 'backup_create_database_dump(): Beginning database dump.' );
			$this->status( 'details', 'Beginning actual SQL dump.' );
			
			$_count = 0;
			$insert_sql = '';
			
			// Iterate through all the tables to backup.
			// TODO: Future ability to break up DB exporting to multiple page loads if needed.
			foreach( $tables as $table_key => $table ) {
				$create_table = mysql_query("SHOW CREATE TABLE `{$table}`");
				
				// Table creation text.
				$create_table_array = mysql_fetch_array( $create_table );
				mysql_free_result( $create_table ); // Free memory.
				$insert_sql .= str_replace( "\n", '', $create_table_array[1] ) . ";\n"; // Remove internal linebreaks; only put one at end.
				unset( $create_table_array );
				
				// Row creation text for all rows within this table.
				$table_query = mysql_query("SELECT * FROM `$table`") or $this->error( 'Unable to read database table ' . $table . '. Your backup will not include data from this table (you may ignore this warning if you do not need this specific data). This is due to the following error: ' . mysql_error(), '9001');
				$num_fields = mysql_num_fields($table_query);
				while ( $fetch_row = mysql_fetch_array( $table_query ) ) {
					$insert_sql .= "INSERT INTO `$table` VALUES(";
					for ( $n=1; $n<=$num_fields; $n++ ) {
						$m = $n - 1;
						$insert_sql .= "'" . mysql_real_escape_string( $fetch_row[$m] ) . "', ";
					}
					$insert_sql = substr( $insert_sql, 0, -2 );
					$insert_sql .= ");\n";
					
					fwrite( $file_handle, $insert_sql );
					$insert_sql = '';
					
					// Help keep HTTP alive.
					$_count++;
					if ($_count >= 400) {
						if ( $this->_ajax === false ) {
							echo ' ';
						}
						flush();
						$_count = 0;
					}
				}
				mysql_free_result( $table_query ); // Free memory.
				
				// Help keep HTTP alive.
				if ( $this->_ajax === false ) {
					echo ' ';
				}
				flush();
				
				//unset( $tables[$table_key] );
			}
			
			fclose( $file_handle );
			unset( $file_handle );
			
			$this->status( 'details', 'Finished actual SQL dump.' );
			$this->status( 'status', 'database_end' );
			$this->status( 'action', 'finish_database' );
			
			return true;
		}
		
		
		function backup_zip_files() {
			$this->status( 'action', 'start_files' );
			//sleep(7);
			
			$excludes = trim( $this->_options['excludes'] );
			$excludes = explode( "\n", $excludes );
			
			//Clean up directory exclusions.
			foreach ( $excludes as $exclude_id => $exclude_value ) {
				if ( empty( $exclude_value ) ) {
					unset( $excludes[$exclude_id] );
				}
			} 
			
			// Add backup archive directory to be excluded.
			array_push( $excludes, ltrim( str_replace( rtrim( ABSPATH, '\\\/' ), '', $this->_options['backup_directory'] ), ' \\/' ) ); // Exclude backup directory with other archives.
			
			/*
			if ( $this->_options['force_compatibility'] == 1 ) {
				$this->status( 'message', 'Forcing compatibility mode (PCLZip) based on settings. This is slower and less reliable.' );
				$this->_parent->_zipbuddy->set_zip_methods( array( 'pclzip' ) );
			}
			*/
			
			if ( $this->_options['compression'] == '1' ) {
				$compression = true;
			} else {
				$compression = false;
			}
			
			if ( $this->_backup['type'] == 'full' ) {
				$directory_to_add = ABSPATH;
			} elseif ( $this->_backup['type'] == 'db' ) {
				$directory_to_add = $this->_backup['temp_directory'];
			} else {
				$this->status( 'error', 'Backup FAILED. Unknown backup type.' );
				$this->status( 'action', 'halt_script' ); // Halt JS on page.
			}
			
			$this->_parent->_zipbuddy->set_status_callback( array( &$this, 'status' ) ); // Set logging callback.
			if ( $this->_parent->_zipbuddy->add_directory_to_zip( $this->_backup['archive_file'], $directory_to_add, $compression, $excludes, $this->_backup['temporary_zip_directory'], $this->_backup['force_compatibility'] ) === true ) {
				$this->status( 'message', 'Backup ZIP file successfully created.' );
				if ( chmod( $this->_backup['archive_file'], 0644) ) {
					$this->status( 'details', 'Chmod to 0644 succeeded.' );
				} else {
					$this->status( 'details', 'Chmod to 0644 failed.' );
				}
			} else {
				$this->status( 'error', 'Backup FAILED. Unable to successfully generate ZIP archive. Error #3382.' );
				$this->status( 'action', 'halt_script' ); // Halt JS on page.
				return false;
			}
			
			return true;
		}
		
		function trim_old_archives() {
			$summed_size = 0;
			
			$file_list = (array) glob( $this->_options['backup_directory'] . 'backup*.zip' );
			foreach( (array) $file_list as $file ) {
				$file_stats = stat( $file );
				$modified_time = $file_stats['mtime'];
				$filename = str_replace( $this->_options['backup_directory'], '', $file ); // Just the file name.
				$files[$modified_time] = array(
													'filename'				=>		$filename,
													'size'					=>		$file_stats['size'],
													'modified'				=>		$modified_time,
												);
				$summed_size += ( $file_stats['size'] / 1048576 ); // MB
			}
			unset( $file_list );
			if ( empty( $files ) ) { // return if no archives (nothing else to do).
				return true;
			} else {
				krsort( $files );
			}
			
			// Limit by number of archives if set. Deletes oldest archives over this limit.
			if ( ( $this->_options['archive_limit'] > 0 ) && ( count( $files ) ) > $this->_options['archive_limit'] ) {
				// Need to trim.
				$i = 0;
				foreach( $files as $file ) {
					$i++;
					if ( $i > $this->_options['archive_limit'] ) {
						$this->status( 'details', 'Deleting old archive `' . $file['filename'] . '` due as it causes archives to exceed total number allowed.' );
						unlink( $this->_options['backup_directory'] . $file['filename'] );
					}
				}
			}
			
			// Limit by number of archives, oldest first if set.
			$files = array_reverse( $files, true ); // Reversed so we delete oldest files first as long as size limit still is surpassed; true = preserve keys.
			if ( ( $this->_options['archive_limit_size'] > 0 ) && ( $summed_size > $this->_options['archive_limit_size'] ) ) {
				// Need to trim.
				foreach( $files as $file ) {
					if ( $summed_size > $this->_options['archive_limit_size'] ) {
						$summed_size = $summed_size - ( $file['size'] / 1048576 );
						$this->status( 'details', 'Deleting old archive `' . $file['filename'] . '` due as it causes archives to exceed total size allowed.' );
						unlink( $this->_options['backup_directory'] . $file['filename'] );
					}
				}
			}
			
			return true;
		}
		
		function post_backup() {
			$this->status( 'message', 'Cleaning up after backup.' );
			
			// Delete temporary data directory.
			if ( file_exists( $this->_backup['temp_directory'] ) ) {
				$this->_parent->delete_directory_recursive( $this->_backup['temp_directory'] );
			}
			// Delete temporary ZIP directory.
			if ( file_exists( $this->_options['backup_directory'] . 'temp_zip_' . $this->_backup['serial'] . '/' ) ) {
				$this->_parent->delete_directory_recursive( $this->_options['backup_directory'] . 'temp_zip_' . $this->_backup['serial'] . '/' );
			}
			
			$this->trim_old_archives(); // Clean up any old excess archives pushing us over defined limits in settings.
			
			$message = 'Backup completed successfully in ' . $this->_parent->format_duration( time() - $this->_backup['start_time'] ) . '. File: ' . basename( $this->_backup['archive_file'] );
			if ( $this->_backup['trigger'] == 'manual' ) {
				$this->_parent->mail_notify_manual( $message );
			} elseif ( $this->_backup['trigger'] == 'scheduled' ) {
				$this->_parent->mail_notify_scheduled( $message );
			} else {
				$this->log( 'Error #4343434. Unknown backup trigger.', 'error' );
			}
			
			// Schedule cleanup (12 hours from now; time for remote transfers) of log status file and data structure.
			wp_schedule_single_event( ( time() + ( 12 * 60 * 60 ) ), $this->_parent->_var . '-cron_final_cleanup', array( $this->_backup['serial'] ) );
			
			$this->status( 'message', 'Finished cleaning up.' );
			$this->status( 'action', 'archive_url^' . site_url() . '/wp-content/uploads/backupbuddy_backups/' . basename( $this->_backup['archive_file'] ) );
			$this->status( 'message', 'Backup completed successfully in ' . $this->_parent->format_duration( time() - $this->_backup['start_time'] ) . '. Done.' );
			$this->status( 'action', 'finish_backup' );
		}
		
		
		function send_remote_destination( $destination_id ) {
			$this->_parent->log( 'Sending file to remote destination.' );
			return $this->_parent->send_remote_destination( $destination_id, $this->_backup['archive_file'] );
		}
		
		function post_remote_delete() {
			$this->_parent->log( 'Deleting local copy of file sent remote.' );
			if ( file_exists( $this->_backup['archive_file'] ) ) {
				unlink( $this->_backup['archive_file'] );
			}
			
			if ( file_exists( $this->_backup['archive_file'] ) ) {
				return false; // Didnt delete.
			} else {
				return true; // Deleted.
			}
		}
		
		
		/**
		 *	anti_directory_browsing()
		 *
		 *	Helps security by attempting to block directory browsing by creating
		 *	both index.htm files and .htaccess files turning browsing off.
		 *
		 *	$directory	string		Full absolute pass to insert anti-directory-browsing files into.
		 *
		 *	@return		null
		 *
		 */
		function anti_directory_browsing( $directory ) {
			if ( !file_exists( $this->_options['backup_directory'] . '/index.htm' ) ) {
				$fh = fopen( $this->_options['backup_directory'] . '/index.htm', 'a' );
				fwrite( $fh, '<html></html>' );
				fclose( $fh );
				unset( $fh );
			}
			if ( !file_exists( $this->_options['backup_directory'] . '/.htaccess' ) ) {
				$fh = fopen( $this->_options['backup_directory'] . '/.htaccess', 'a' );
				fwrite( $fh, "IndexIgnore *\n" );
				fclose( $fh );
				unset( $fh );
			}
			
			return true;
		}
		
		
		/**
		 *	set_greedy_script_limits()
		 *
		 *	Sets greedy script limits to help prevent timeouts, running out of memory, etc.
		 *
		 *	@return		null
		 *
		 */
		function set_greedy_script_limits() {
			$this->status( 'details', 'Setting greedy script limits.' );
			
			// Don't abort script if the client connection is lost/closed
			@ignore_user_abort( true );
			
			// Set socket timeout to 2 hours.
			@ini_set( 'default_socket_timeout', 60 * 60 * 2 );
			
			// Set maximum runtime to 2 hours.
			@set_time_limit( 60 * 60 * 2 );
			
			// Increase the memory limit
			$current_memory_limit = trim( @ini_get( 'memory_limit' ) );
			
			// Make sure a minimum memory limit of 256MB is set.
			if ( preg_match( '/(\d+)(\w*)/', $current_memory_limit, $matches ) ) {
				$current_memory_limit = $matches[1];
				$unit = $matches[2];
				// Up memory limit if currently lower than 256M.
				if ( 'g' !== strtolower( $unit ) ) {
					if ( ( $current_memory_limit < 256 ) || ( 'm' !== strtolower( $unit ) ) )
						@ini_set('memory_limit', '256M');
				}
			} else {
				// Couldn't determine current limit, set to 256M to be safe.
				@ini_set('memory_limit', '256M');
			}
			
			$this->status( 'details', 'Finished greedy script limits.' );
		}
		
		
		function rand_string($length = 32, $chars = 'abcdefghijkmnopqrstuvwxyz1234567890') {
			$chars_length = (strlen($chars) - 1);
			$string = $chars{rand(0, $chars_length)};
			for ($i = 1; $i < $length; $i = strlen($string)) {
				$r = $chars{rand(0, $chars_length)};
				if ($r != $string{$i - 1}) $string .=  $r;
			}
			return $string;
		}
		
		
		/**
		 *	error()
		 *
		 *	Appends error message and optional error code to $this->_errors array
		 *	with optional error code. Also logs to text file via parent file's log()
		 *	function. Passes optional error code.
		 *
		 *	$error		string		Error text.
		 *	$code		integer		Numeric error code number. Used for matching in the codex.
		 *	@return		null
		 *
		 */
		function error( $error, $code = '' ) {
			array_push( $this->_errors, array( $error, $code ) );
			if ( !empty( $code ) ) {
				$error = '(Error #' . $code . ') ' . $error;
			}
			$this->_parent->log( $error, 'error' );
		}
		
		function get_errors() {
			if ( empty( $this->_errors ) ) { // Return if no errors.
				return;
			}
			$error_list = array();
			foreach( $this->_errors as $error ) {
				$this_error = 'Error';
				if ( !empty( $error[1] ) ) {
					$this_error .= ' #' . $error[1];
				}
				$this_error .= ': ' . $error[0] . '<br >';
				
				array_push( $error_list, $this_error );
			}
			
			return implode( ' ', $error_list );
		}
		
		
		/**
		 *	response()
		 *
		 *	Handles sending data to the client. Useful for sending commands, messages, and debugging information to the client.
		 *	Automatically handles the proper formatting for the BB ajax client code if in AJAX mode.
		 *	Parts split with the pipe | deliminator.
		 *
		 *	$type		string		action		Command to tell the client/ajax something internal. Send commands with this.
		 *										Actions split with the hat ^ deliminator.
		 *							message		Normal message to display to the user.
		 *							warning		Same as message but noted as a warning to allow client to bring more attention.
		 *							error		[FATAL ERROR] Same as warning BUT ALSO should instruct client that backup process has HALTED!
		 *							details		Additional details. These may be technical. Use for sending more obscure or
												detailed messages, debugging.
		 *	$message	string					Message or command to send to the client. Not applicable for done or ping types.
		 *	@return		null
		 *
		 */
		function status( $type, $message = '' ) {
			$status = $this->_parent->localize_time( time() ) . '|' . $type . '|' . $message;
			
			$fp = fopen( $this->_options['backup_directory'] . 'temp_status_' . $this->_backup['serial'] . '.txt', 'a' );
			fwrite( $fp, $status . "\n" );
			fclose( $fp );
			
			if ( $this->_ajax !== true ) {
				//echo '(' . $type . ') ' . $message;
			}
			
			if ( $type == 'error' ) {
				$this->log( $message, 'error' );
			} elseif ( $type == 'message' ) {
				$this->log( $message );
			} elseif ( $type == 'details' ) {
				$this->log( $message );
			}
			
			return true;
		}
		
	} // End class
	
	//$pluginbuddy_backupbuddy_backup = new pluginbuddy_backupbuddy_backup( $this );
}
?>