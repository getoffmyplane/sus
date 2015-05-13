<?php
/**
 *
 * Plugin Name: BackupBuddy
 * Plugin URI: http://anonym.to/?http://pluginbuddy.com/backupbuddy/
 * Description: Backup - Restore - Migrate. Backs up files, settings, and content for a complete snapshot of your site. Allows migration to a new host or URL.
 * Version: 2.0.2
 * Author: Dustin Bolton
 * Author URI: http://anonym.to/?http://dustinbolton.com/
 *
 * Installation:
 * 
 * 1. Download and unzip the latest release zip file.
 * 2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 * 3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 * 4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 * 
 * Usage:
 * 
 * 1. Navigate to the new plugin menu in the Wordpress Administration Panel.
 *
 */


if (!class_exists("pluginbuddy_backupbuddy")) {
	class pluginbuddy_backupbuddy {
		var $_version = '2.0.2';									// Deprecated variable. Use $this->plugin_info( 'version' ).
		var $_url = 'http://anonym.to/?http://pluginbuddy.com/backupbuddy/';			// Deprecated variable. Purchase URL. Use $this->plugin_info( 'url' ).
		var $_var = 'pluginbuddy_backupbuddy';						// Deprecared variable. Use $this->_slug.
		
		var $_wp_minimum = '2.9.0';
		var $_php_minimum = '5.2';
		
		var $_slug = 'pluginbuddy_backupbuddy';						// Format: pluginbuddy-pluginnamehere. All lowecase, no dashes.
		var $_name = 'BackupBuddy';									// Pretty plugin name. Only used for display so any format is valid.
		var $_series = '';											// Series name if applicable.
		var $_timestamp = 'M j, Y g:iA';							// PHP timestamp format.
		var $_defaults = array(
			'data_version'				=>		'2',				// Data structure version. Added BB 2.0 to ease updating.
			'import_password'			=>		'',					// Importbuddy password.
			'backup_reminders'			=>		1,					// Todo: High security mode.
			'edits_since_last'			=>		0,					// Number of post/page edits since the last backup began.
			'last_backup'				=>		0,					// Timestamp of when last backup started.
			'compression'				=>		1,					// Zip compression.
			'force_compatibility'		=>		0,					// Force compatibility mode even if normal is detected.
			'backup_nonwp_tables'		=>		0,					// Backup tables not prefixed with the WP prefix.
			'include_tables'			=>		array(),			// Additional tables to include.
			'exclude_tables'			=>		array(),			// Tables to exclude.
			'integrity_check'			=>		1,					// Zip file integrity check on the backup listing.
			'schedules'					=>		array(),			// Array of scheduled schedules.
			'log_level'					=>		'1',				// Valid options: 0 = none, 1 = errors only, 2 = errors + warnings, 3 = debugging (all kinds of actions)
			'excludes'					=>		'',					// Newline deliminated list of directories to exclude from the backup.
			'backup_reminders'			=>		1,					// Whether or not to show reminders to backup on post/page edits & on the WP upgrade page.
			'high_security'				=>		0,					// TODO: Future feature. Strip mysql password & admin user password. Prompt on import.
			
			'archive_limit'				=>		0,					// Maximum number of archives to storage. Deletes oldest if exceeded.
			'archive_limit_size'		=>		0,					// Maximum size of all archives to store. Deletes oldest if exeeded.
			
			'email_notify_scheduled'	=>		'',
			'email_notify_manual'		=>		'',
			'email_notify_error'		=>		'',
			
			'backups'					=>		array(),			// Array of currently ocurring backups.
			'remote_destinations'		=>		array(),			// Array of remote destinations (S3, Rackspace, email, ftp, etc)
			'backup_file_integrity'		=>		array(),
			'role_access'				=>		'administrator',
		);
		
		var $_scheduledefaults = array(
			'title'						=>		'',
			'type'						=>		'db',
			'interval'					=>		'monthly',
			'first_run'					=>		'',
			'delete_after'				=>		0,
			'remote_destinations'		=>		'',
		);
		
		var $_s3defaults = array(
			'title'			=>		'',
			'accesskey'		=>		'',
			'secretkey'		=>		'',
			'bucket'		=>		'',
			'directory'		=>		'',
			'ssl'			=>		1,
		);
		
		var $_rackspacedefaults = array(
			'title'			=>		'',
			'username'		=>		'',
			'api_key'		=>		'',
			'container'		=>		'',
		);
			
		var $_emaildefaults = array(
			'title'			=>		'',
			'email'			=>		'',
		);
			
		var $_ftpdefaults = array(
			'title'			=>		'',
			'address'		=>		'',
			'username'		=>		'',
			'password'		=>		'',
			'path'			=>		'',
			'ftps'			=>		0,
		);
		
		
		// Default constructor. This is run when the plugin first runs.
		function pluginbuddy_backupbuddy() {
			$this->_pluginPath = dirname( __FILE__ );
			$this->_pluginRelativePath = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', $this->_pluginPath ) ), '\\\/' );
			$this->_pluginURL = site_url() . '/' . $this->_pluginRelativePath;
			if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) { $this->_pluginURL = str_replace( 'http://', 'https://', $this->_pluginURL ); }
			$this->_selfLink = array_shift( explode( '?', $_SERVER['REQUEST_URI'] ) ) . '?page=' . $this->_var;
			
			// This must always be available to trigger, even outside admin.
			add_action( $this->_var . '-cron_process_backup', array( &$this, 'cron_process_backup' ), 10, 1 );
			add_action( $this->_var . '-cron_final_cleanup', array( &$this, 'cron_final_cleanup' ), 10, 1 );
			add_action( $this->_var . '-cron_s3_copy', array( &$this, 'cron_process_s3_copy' ), 10, 5 );
			add_action( $this->_var . '-cron_rackspace_copy', array( &$this, 'cron_process_rackspace_copy' ), 10, 5 );
			add_action( $this->_var . '-cron_ftp_copy', array( &$this, 'cron_process_ftp_copy' ), 10, 5 );
			add_action( 'pb_backupbuddy-cron_remotesend', array( &$this, 'cron_remotesend' ), 10, 2 );
			add_action( 'pb_backupbuddy-cron_scheduled_backup', array( &$this, 'cron_process_scheduled_backup' ), '', 5 );
			
			add_filter( 'cron_schedules', array( &$this, 'filter_cron_add_schedules' ) );
			
			if ( is_admin() ) { // Admin Dashboard
				$this->load();
				
				if ( empty( $this->_options['backup_directory'] ) ) {
					$this->activate();
				}
				
				require_once( $this->_pluginPath . '/classes/admin.php' );
				$pluginbuddy_backupbuddy_admin = new pluginbuddy_backupbuddy_admin( $this );
				//require_once( $this->_pluginPath . '/lib/updater/updater.php' );
				register_activation_hook( __FILE__, array( &$this, 'activate' ) ); // Run some code when plugin is activated in dashboard.
				
				add_action( 'wp_dashboard_setup', array( &$this, 'action_wp_dashboard_setup' ) ); // Dashboard Stats
				if ( $this->_options['backup_reminders'] == '1' ) { 
					add_action( 'load-update-core.php', array( &$this, 'action_update_notice' ) );
					add_action( 'post_updated_messages', array( &$this, 'action_post_updated_messages' ) );
				}
				
				// Ajax Actions
				add_action( 'wp_ajax_pb_backupbuddy', array( &$this, 'ajax' ) ); // Directory listing for exluding
				add_action( 'wp_ajax_pb_backupbuddy_remotedestination', array( &$this, 'ajax_remotedestination' ) ); // Directory listing for exluding
				add_action( 'wp_ajax_pb_backupbuddy_remotetest', array( &$this, 'ajax_remotetest' ) ); // Test S3, etc.
				add_action( 'wp_ajax_pb_backupbuddy_filetree', array( &$this, 'ajax_filetree' ) ); // Directory listing for exluding
				add_action( 'wp_ajax_pb_backupbuddy_icicletree', array( &$this, 'ajax_icicletree' ) ); // Directory listing for exluding
				add_action( 'wp_ajax_pb_backupbuddy_remotesend', array( &$this, 'ajax_remotesend' ) ); // Remote send offsite (manually).
				add_action( 'wp_ajax_pb_backupbuddy_md5hash', array( &$this, 'ajax_md5hash' ) ); // Generate a MD5 hash for file verification.
				add_action( 'wp_ajax_backupbuddy_importbuddy', array( &$this, 'ajax_importbuddy' ) ); // Direct downloading of importbuddy.php using ajax portal.
				
				add_filter( 'plugin_row_meta', array( &$this, 'filter_plugin_row_meta' ), 10, 2 );
			}
		}
		
		// Name, title, description, author, authoruri, version, pluginuri OR url, textdomain, domainpath, network
		function plugin_info( $type ) {
			if ( empty( $this->_info ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				$this->_info = array_change_key_case( get_plugin_data( __FILE__, false, false ), CASE_LOWER );
				$this->_info['url'] = $this->_info['pluginuri'];
			}
			if ( !empty( $this->_info[$type] ) ) {
				return $this->_info[$type];
			} else {
				return 'UNKNOWN_VAR_354-' . $type;
			}
		}
		
		
		/**
		 *	alert()
		 *
		 *	Displays a message to the user at the top of the page when in the dashboard.
		 *
		 *	$message		string		Message you want to display to the user.
		 *	$error			boolean		OPTIONAL! true indicates this alert is an error and displays as red. Default: false
		 *	$error_code		int			OPTIONAL! Error code number to use in linking in the wiki for easy reference.
		 */
		function alert( $message, $error = false, $error_code = '' ) {
			$log_error = false;
			
			echo '<div id="message" class="';
			if ( $error == false ) {
				echo 'updated fade';
			} else {
				echo 'error';
				$log_error = true;
			}
			if ( $error_code != '' ) {
				$message .= '<p><a href="http://anonym.to/?http://ithemes.com/codex/page/' . $this->_name . ':_Error_Codes#' . $error_code . '" target="_new"><i>' . $this->_name . ' Error Code ' . $error_code . ' - Click for more details.</i></a></p>';
				$log_error = true;
			}
			if ( $log_error === true ) {
				$this->log( $message . ' Error Code: ' . $error_code, 'error' );
			}
			echo '"><p><strong>'.$message.'</strong></p></div>';
		}
		
		
		/**
		 *	tip()
		 *
		 *	Displays a message to the user when they hover over the question mark. Gracefully falls back to normal tooltip.
		 *	HTML is supposed within tooltips.
		 *
		 *	$message		string		Actual message to show to user.
		 *	$title			string		Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
		 *	$echo_tip		boolean		Whether to echo the tip (default; true), or return the tip (false). (optional)
		 */
		function tip( $message, $title = '', $echo_tip = true ) {
			$tip = ' <a class="pluginbuddy_tip" title="' . $title . ' - ' . $message . '"><img src="' . $this->_pluginURL . '/images/pluginbuddy_tip.png" alt="(?)" /></a>';
			if ( $echo_tip === true ) {
				echo $tip;
			} else {
				return $tip;
			}
		}
		
		
		/**
		 *	video()
		 *
		 *	Displays a message to the user when they hover over the question mark. Gracefully falls back to normal tooltip.
		 *	HTML is supposed within tooltips.
		 *
		 *	$video_key		string		YouTube video key from the URL ?v=VIDEO_KEY_HERE
		 *	$title			string		Title of message to show to user. This is displayed at top of tip in bigger letters. Default is blank. (optional)
		 *	$echo_tip		boolean		Whether to echo the tip (default; true), or return the tip (false). (optional)
		 */
		function video( $video_key, $title = '', $echo_tip = true ) {
			global $wp_scripts;
			if ( !in_array( 'thickbox', $wp_scripts->done ) ) {
				wp_enqueue_script( 'thickbox' );
				wp_print_scripts( 'thickbox' );
				wp_print_styles( 'thickbox' );
			}
			
			if ( strstr( $video_key, '#' ) ) {
				$video = explode( '#', $video_key );
				$video[1] = '&start=' . $video[1];
			} else {
				$video[0] = $video_key;
				$video[1] = '';
			}
			
			$tip = '<a href="http://www.youtube.com/embed/' . urlencode( $video[0] ) . '?autoplay=1' . $video[1] . '&TB_iframe=1&width=640&height=400" class="thickbox pluginbuddy_tip" title="Video Tutorial - ' . $title . '"><img src="' . $this->_pluginURL . '/images/pluginbuddy_play.png" alt="(video)" /></a>';
			if ( $echo_tip === true ) {
				echo $tip;
			} else {
				return $tip;
			}
		}
		
		
		/**
		 *	log()
		 *
		 *	Logs to a text file depending on settings.
		 *	0 = none, 1 = errors only, 2 = errors + warnings, 3 = debugging (all kinds of actions)
		 *
		 *	$text	string			Text to log.
		 *	$log_type	string		Valid options: error, warning, all (default so may be omitted).
		 *
		 */
		function log( $text, $log_type = 'all' ) {
			$write = false;
			
			if ( !isset( $this->_options['log_level'] ) ) {
				$this->load();
			}
			
			if ( $this->_options['log_level'] == 0 ) { // No logging.
				return;
			} elseif ( $this->_options['log_level'] == 1 ) { // Errors only.
				if ( $log_type == 'error' ) {
					$write = true;
				}
			} elseif ( $this->_options['log_level'] == 2 ) { // Errors and warnings only.
				if ( ( $log_type == 'error' ) || ( $log_type == 'warning' ) ) {
					$write = true;
				}
			} elseif ( $this->_options['log_level'] == 3 ) { // Log all; Errors, warnings, actions, notes, etc.
				$write = true;
			}
			
			if ( $write === true ) {
				$fh = fopen( WP_CONTENT_DIR . '/uploads/' . $this->_var . '.txt', 'a');
				fwrite( $fh, '[' . date( $this->_timestamp . ' ' . get_option( 'gmt_offset' ), time() + (get_option( 'gmt_offset' )*3600) ) . '-' . $log_type . '] ' . $text . "\n" );
				fclose( $fh );
			}
		}
		
		
		function load() {
			$this->_options=get_option($this->_var);
			$options = array_merge( $this->_defaults, (array)$this->_options );
			
			if ( $options !== $this->_options ) {
				// Defaults existed that werent already in the options so we need to update their settings to include some new options.
				$this->_options = $options;
				$this->save();
			}
			
			return true;
		}
		
		
		function save() {
			add_option($this->_var, $this->_options, '', 'no'); // 'No' prevents autoload if we wont always need the data loaded.
			update_option($this->_var, $this->_options);
			return true;
		}
		
		
		/**
		 * activate()
		 *
		 * Run on plugin activation. Handles upgrades.
		 *
		 */
		function activate() {
			// Migrate storage location for settings from pre-2.0 to post-2.0 location.
			$upgrade_options = get_option( 'ithemes-backupbuddy' );
			if ( $upgrade_options != false ) {
				$this->_options = $upgrade_options;
				
				$this->_options['email_notify_error'] = $this->_options['email'];
				if ( $this->_options['email_notify_manual'] == 1 ) {
					$this->_options['email_notify_manual'] = $this->_options['email'];
				}
				if ( $this->_options['email_notify_scheduled'] == 1 ) {
					$this->_options['email_notify_scheduled'] = $this->_options['email'];
				}
				unset( $this->_options['email'] );
				
				$this->_options['archive_limit'] = $this->_options['zip_limit'];
				unset( $this->_options['zip_limit'] );
				
				$this->_options['import_password'] = $this->_options['password'];
				if ( $this->_options['import_password'] == '#PASSWORD#' ) {
					$this->_options['import_password'] = '';
				}
				unset( $this->_options['password'] );
				
				if ( is_array( $this->_options['excludes'] ) ) {
					$this->_options['excludes'] = explode( "\n", $this->_options['excludes'] );
				}
				
				$this->_options['last_backup'] = $this->_options['last_run'];
				unset( $this->_options['last_run'] );
				
				// FTP.
				if ( !empty( $this->_options['ftp_server'] ) ) {
					$this->_options['remote_destinations'][0] = array(
																	'title'			=>		'FTP',
																	'address'		=>		$this->_options['ftp_server'],
																	'username'		=>		$this->_options['ftp_user'],
																	'password'		=>		$this->_options['ftp_pass'],
																	'path'			=>		$this->_options['ftp_path'],
																	'type'			=>		'ftp',
																);
					if ( $this->_options['ftp_type'] == 'ftp' ) {
						$this->_options['remote_destinations'][0]['ftps'] = 0;
					} else {
						$this->_options['remote_destinations'][0]['ftps'] = 1;
					}
				}
				
				// Amazon S3.
				if ( !empty( $this->_options['aws_bucket'] ) ) {
					$this->_options['remote_destinations'][1] = array(
																	'title'			=>		'S3',
																	'accesskey'		=>		$this->_options['aws_accesskey'],
																	'secretkey'		=>		$this->_options['aws_secretkey'],
																	'bucket'		=>		$this->_options['aws_bucket'],
																	'directory'		=>		$this->_options['aws_directory'],
																	'ssl'			=>		$this->_options['aws_ssl'],
																	'type'			=>		's3',
																);
				}
				
				// Email destination.
				if ( !empty( $this->_options['email'] ) ) {
					$this->_options['remote_destinations'][2] = array(
																	'title'			=>		'Email',
																	'email'			=>		$this->_options['email'],
																);
				}
				
				// Handle migrating scheduled remote destinations.
				foreach( $this->_options['schedules'] as $schedule_id => $schedule ) {
					$this->_options['schedules'][$schedule_id]['title'] = $this->_options['schedules'][$schedule_id]['name'];
					unset( $this->_options['schedules'][$schedule_id]['name'] );
					
					$this->_options['schedules'][$schedule_id]['remote_destinations'] = '';
					if ( $schedule['remote_send'] == 'ftp' ) {
						$this->_options['schedules'][$schedule_id]['remote_destinations'] .= '0|';
					}
					if ( $schedule['remote_send'] == 'aws' ) {
						$this->_options['schedules'][$schedule_id]['remote_destinations'] .= '1|';
					}
					if ( $schedule['remote_send'] == 'email' ) {
						$this->_options['schedules'][$schedule_id]['remote_destinations'] .= '2|';
					}
				}
				
				delete_option( 'ithemes-backupbuddy' );
			}
			
			// TODO: Find a better way to do this since we cant handle non-default content dir.
			//$this->_options['backup_directory'] = WP_CONTENT_DIR . '/uploads/backupbuddy_backups/';
			$this->_options['backup_directory'] = ABSPATH . 'wp-content/uploads/backupbuddy_backups/';
			$this->save();
		}
		
		
		// Displays helpful info in a dashboard box. Called from callback within our function action_wp_dashboard_setup().
		function dashboard_stats() {
			echo '<style type="text/css">';
			echo '	.pb_fancy {';
			echo '		font-family: Georgia, "Times New Roman", "Bitstream Charter", Times, serif;';
			echo '		font-size: 18px;';
			echo '		color: #21759B;';
			echo '	}';
			echo '</style>';
			
			echo '<div>';
			
			$files = (array) glob( $this->_options['backup_directory'] . 'backup*.zip' );
			array_multisort( array_map( 'filemtime', $files ), SORT_NUMERIC, SORT_DESC, $files );
			
			echo 'You currently have <span class="pb_fancy"><a href="admin.php?page=pluginbuddy_backupbuddy-backup">' . count( $files ) . '</a></span> stored backups.';
			if ( $this->_options['last_backup'] == 0 ) {
				echo ' You have no created any backups.';
			} else {
				echo ' Your most recent backup was <span class="pb_fancy"><a href="admin.php?page=pluginbuddy_backupbuddy-backup">' . $this->time_ago( $this->_options['last_backup'] ) . '</a></span> ago.';
			}
			echo ' There have been <span class="pb_fancy"><a href="admin.php?page=pluginbuddy_backupbuddy-backup">' . $this->_options['edits_since_last'] . '</a></span> post/page modifications since your last backup.';
			echo ' <span class="pb_fancy"><a href="admin.php?page=pluginbuddy_backupbuddy-backup">Go create a backup!</a></span>';
			
			echo '</div>';
		}
		
		
		// Displays a friendly backup reminder on post/page edits (if enabled).
		function action_post_updated_messages( $messages ) {
			$this->_options['edits_since_last']++;
			$this->save();
			
			$backup_message = ' | <a href="admin.php?page=pluginbuddy_backupbuddy-backup&run_backup=full">Full Backup</a> | <a href="admin.php?page=pluginbuddy_backupbuddy-backup&run_backup=db">Database Backup</a>';
			
			$reminder_posts = array(); // empty array to store customized post messages array
			$reminder_pages = array(); // empty array to store customized page messages array
			$others = array(); // An empty array to store the array for custom post types
			foreach ( $messages['post'] as $num => $message ) {
				$message .= $backup_message;
				if ( $num == 0 ) {
				$message = ''; // The first element in the messages['post'] array is always empty
				}
				array_push( $reminder_posts, $message ); // Insert/copy the modified message value to the last element of reminder array
			}
			$reminder_posts = array( 'post' => $reminder_posts ); // Apply the post key to the first dimension of messages array
			foreach ( $messages['page'] as $num => $message ) {
				$message .= $backup_message;
				if ( $num == 0 ) {
					$message = ''; // The first element in the messages['page'] array is always empty
				}
				array_push( $reminder_pages, $message ); // Insert/copy the modified message value to the last element of reminder array
			}
			$reminder_pages = array( 'page' => $reminder_pages ); // Apply the page key to the first dimension of messages array
			$reminder = array_merge( $reminder_posts, $reminder_pages );
			foreach ( $messages as $type => $message ) {
				if ( ( $type == 'post' ) || ( $type == 'page' ) ) { // Skip the post key since it is already defined
					continue;
				}
				$others[$type] = $message; // Since message is an array, this statement forms 2D array
			}
			$reminder = array_merge( $reminder, $others ); // Merge the arrays in the others array with reminder array in order to form an appropriate format for messages array
			
			return $reminder;
		}
		
		
		function action_wp_dashboard_setup() {
			if ( current_user_can( 'switch_themes' ) ) {
				wp_add_dashboard_widget( 'pb_backupbuddy', 'BackupBuddy',  array( &$this, 'dashboard_stats' ) );
			}
		}
		
		// Admin update page notice buffering setup.
		function action_update_notice() {
			ob_start( array( &$this, 'update_notice_dump' ) );
			add_action( 'admin_footer', create_function( '', 'ob_end_flush();' ) );
		}
		
		
		// Admin update page notice actual screen output.
		function update_notice_dump( $text = '' ) {
			return str_replace( '<h2>WordPress Updates</h2>', '<h2>WordPress Updates</h2><div id="message" class="updated fade"><p><img src="' . $this->_pluginURL . '/images/pluginbuddy.png" style="vertical-align: -3px;" /> <a href="admin.php?page=pluginbuddy_backupbuddy-backup" target="_new" style="text-decoration: none;">Remember to back up your site with BackupBuddy before upgrading!</a></p></div>', $text );
		}
		
		
		function ajax() {
			if ( $_GET['actionb'] == 'backup_status' ) {
				// Make sure the serial exists.
				if ( empty( $this->_options['backups'][$_POST['serial']] ) ) {
					echo '!' . $this->localize_time( time() ) . '|error|Error #5445589. Invalid backup serial (' . htmlentities( $_POST['serial'] ) . '). Fatal error.' . "\n";
					echo '!' . $this->localize_time( time() ) . '|action|halt_script' . "\n";
				} else {
					// Return the status information since last retrieval.
					$return_status = $this->localize_time( time() ) . "|ping\n";
					
					// FILE SIZES FOR STATUS UPDATES---------------------
					$temporary_zip_directory = $this->_options['backup_directory'] . 'temp_zip_' . $_POST['serial'] . '/';
					if ( file_exists( $temporary_zip_directory ) ) { // Temp zip file.
						$directory = opendir( $temporary_zip_directory );
						while( $file = readdir( $directory ) ) {
							if ( ( $file != '.' ) && ( $file != '..' ) ) {
								$stats = stat( $temporary_zip_directory . $file );
								$return_status .= '!' . $this->localize_time( time() ) . '|details|Temporary ZIP file size: ' . $this->format_size( $stats['size'] ) . "\n";;
								$return_status .= '!' . $this->localize_time( time() ) . '|action|archive_size^' . $this->format_size( $stats['size'] ) . "\n";
							}
						}
						closedir( $directory );
						unset( $directory );
					}
					if( file_exists( $this->_options['backups'][$_POST['serial']]['archive_file'] ) ) { // Final zip file.
						$stats = stat( $this->_options['backups'][$_POST['serial']]['archive_file'] );
						$return_status .= '!' . $this->localize_time( time() ) . '|details|Final ZIP file size: ' . $this->format_size( $stats['size'] ) . "\n";;
						$return_status .= '!' . $this->localize_time( time() ) . '|action|archive_size^' . $this->format_size( $stats['size'] ) . "\n";
					}
					// END FILE SIZES FOR STATUS UPDATES-----------------
					
					$status_file = $this->_options['backup_directory'] . 'temp_status_' . $_POST['serial'] . '.txt';
					if ( file_exists( $status_file ) ) {
						$file_array = file( $status_file );
						
						foreach( $file_array as $status_line ) {
							$return_status .= '!' . $status_line . "\n";
						}
						
						// Reset messages.
						file_put_contents( $this->_options['backup_directory'] . 'temp_status_' . $_POST['serial'] . '.txt', '' );
					}
					
					// Return messages.
					echo $return_status;
				}
				
				
				
				//require_once( $this->_pluginPath . '/classes/backup.php' );
				//$pluginbuddy_backupbuddy_backup = new pluginbuddy_backupbuddy_backup( $this );
				//$data = unserialize( base64_decode( $_POST['data'] ) );
				/*
				if ( $pluginbuddy_backupbuddy_backup->ajax_process_backup( $data ) === false ) {
					echo $pluginbuddy_backupbuddy_backup->get_errors();
				}
				*/
			} elseif ( $_GET['actionb'] == 'process_backup' ) {
				
				
				
			}
			die();
		}
		
		
		// Handle manually sending remote destination files in the background cron process.
		function cron_remotesend( $destination_id, $file ) {
			$this->load();
			$this->log( 'Launching remote send.' );
			$this->send_remote_destination( $destination_id, $file );
		}
		
		
		function cron_process_backup( $serial = 'blank' ) {
			$this->log( 'Processing cron backup...' );
			
			require_once( $this->_pluginPath . '/classes/backup.php' );
			$pluginbuddy_backupbuddy_backup = new pluginbuddy_backupbuddy_backup( $this );
			$pluginbuddy_backupbuddy_backup->process_backup( $serial );
		}
		
		
		function cron_process_scheduled_backup( $cron_id ) {
			$this->load();
			$this->log( 'cron_process_scheduled_backup: ' . $cron_id );
			
			if ( is_array( $this->_options['schedules'][$cron_id] ) ) {
				require_once( $this->_pluginPath . '/classes/backup.php' );
				$backup = new pluginbuddy_backupbuddy_backup( $this );
				
				// If any remote destinations are set then add these to the steps to perform after the backup.
				$post_backup_steps = array();
				$destinations = explode( '|', $this->_options['schedules'][$cron_id]['remote_destinations'] );
				foreach( $destinations as $destination ) {
					array_push( $post_backup_steps, array(
														'function'		=>		'send_remote_destination',
														'args'			=>		array( $destination ),
													)
								);
				}
				
				if ( $this->_options['schedules'][$cron_id]['delete_after'] == '1' ) {
					array_push( $post_backup_steps, array(
													'function'		=>		'post_remote_delete',
													'args'			=>		array(),
												)
							);
				}
				
				if ( $backup->start_backup_process( $this->_options['schedules'][$cron_id]['type'], 'scheduled', array(), $post_backup_steps ) !== true ) {
					error_log( 'FAILURE #4455484589 IN BACKUPBUDDY.' );
					echo 'Error #4564658344443: Backup failure';
					echo $backup->get_errors();
				}
			}
			$this->log( 'Finished cron_process_scheduled_backup.' );
		}
		
		// Copy S3 backup to local backup directory
		function cron_process_s3_copy( $s3file, $accesskey, $secretkey, $bucket, $directory ) {
			require_once( $this->_pluginPath . '/lib/s3/s3.php');
			$s3 = new S3( $accesskey, $secretkey);
			$s3->getObject($bucket, $directory . $s3file, ABSPATH . 'wp-content/uploads/backupbuddy_backups/' . $s3file );
		}
		
		// Copy Rackspace backup to local backup directory
		function cron_process_rackspace_copy( $rs_backup, $rs_username, $rs_api_key, $rs_container, $rs_path ) {
			require_once( $this->_pluginPath . '/lib/rackspace/cloudfiles.php' );
			$auth = new CF_Authentication( $rs_username, $rs_api_key );
			$auth->authenticate();
			$conn = new CF_Connection( $auth );

			// Set container
			$container = $conn->get_container( $rs_container );
			
			// Get file from Rackspace
			$rsfile = $container->get_object( $rs_backup );
			$fso = fopen( ABSPATH . 'wp-content/uploads/backupbuddy_backups/' . $rs_backup, 'w' );
			$rsfile->stream($fso);
			fclose($fso);
		}
		
		
		// Copy FTP backup to local backup directory
		function cron_process_ftp_copy( $backup, $ftp_server, $ftp_username, $ftp_password, $ftp_directory ) {
			// connect to server
			$conn_id = ftp_connect( $ftp_server ) or die( 'Could not connect to ' . $ftp_server );
			// login with username and password
			$login_result = ftp_login( $conn_id, $ftp_username, $ftp_password );
		
			// try to download $server_file and save to $local_file
			$local_file = ABSPATH . 'wp-content/uploads/backupbuddy_backups/' . $backup;
			if ( ftp_get( $conn_id, $local_file, $ftp_directory . $backup, FTP_BINARY ) ) {
			    echo "Successfully written to $local_file\n";
			} else {
			    echo "There was a problem\n";
			}
		
			// close this connection
			ftp_close( $conn_id );
		}
		
		
		// Cleanup final remaining bits post backup. Handled here so log file can be accessed by AJAX temporarily after backup.
		// Also called when finished_backup action is seen being sent to AJAX signalling we can clear it NOW since AJAX is done.
		// Also pre_backup() of backup.php schedules this 6 hours in the future of the backup in case of failure.
		function cron_final_cleanup( $serial ) {
			$this->log( 'cron_final_cleanup of ' . $serial );
			
			// Delete temporary data directory.
			if ( file_exists( $this->_options['backups'][$serial]['temp_directory'] ) ) {
				$this->delete_directory_recursive( $this->_options['backups'][$serial]['temp_directory'] );
			}
			
			// Delete temporary zip directory.
			if ( file_exists( $this->_options['backups'][$serial]['temporary_zip_directory'] ) ) {
				$this->delete_directory_recursive( $this->_options['backups'][$serial]['temporary_zip_directory'] );
			}
			
			// Delete status log text file.
			if ( file_exists( $this->_options['backup_directory'] . 'temp_status_' . $serial . '.txt' ) ) {
				unlink( $this->_options['backup_directory'] . 'temp_status_' . $serial. '.txt' );
			}
			
			// Cleaning up internal data structure.
			if ( !empty( $this->_options['backups'][$serial] ) && is_array( $this->_options['backups'][$serial] ) ) {
				unset( $this->_options['backups'][$serial] );
				$this->save();
			}
		}
		
		
		function send_remote_destination( $destination_id, $file ) {
			$destination = &$this->_options['remote_destinations'][$destination_id];
			
			if ( $destination['type'] == 's3' ) {
				$response = $this->remote_send_s3( $destination['accesskey'], $destination['secretkey'], $destination['bucket'], $destination['directory'], $destination['ssl'], $file );
			} elseif ( $destination['type'] == 'rackspace' ) {
				$response = $this->remote_send_rackspace( $destination['username'], $destination['api_key'], $destination['container'], $file );
			} elseif ( $destination['type'] == 'email' ) {
				$response = $this->remote_send_email( $destination['email'], $file );
			} elseif ( $destination['type'] == 'ftp' ) {
				$response = $this->remote_send_ftp( $destination['address'], $destination['username'], $destination['password'], $destination['path'], $destination['ftps'], $file );
			} else {
				return false; // Invalid destination.
			}
			
			return $response;
		}
		
		
		// Generates an MD5 file hash for file integrity verification.
		function ajax_md5hash() {
			$this->ajax_header( true );
			echo '<h2>MD5 Checksum Hash</h2>';
			echo 'This is a string of characters that uniquely represents this file.  If this file is in any way manipulated then this string of characters will change.  This allows you to later verify that the file is intact and uncorrupted.  For instance you may verify the file after uploading it to a new location by making sure the MD5 checksum matches.<br /><br />';
			echo '<b>MD5 Checksum:</b><input type="text" size="40" value="' . md5_file( $this->_options['backup_directory'] . $_GET['file'] ) . '" />';
			$this->ajax_footer();
			die();
		}
		
		
		function ajax_importbuddy() {
			$output = file_get_contents( dirname( __FILE__ ) . '/importbuddy.php' );
			if ( isset( $_GET['pass'] ) && !empty( $_GET['pass'] ) ) {
				$output = preg_replace('/#PASSWORD#/', $_GET['pass'], $output, 1 ); // Only replaces first instance.
			}
			$output = preg_replace('/#VERSION#/', $this->_version, $output, 1 ); // Only replaces first instance.
			
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: text/plain; name=importbuddy.php' );
			header( 'Content-Disposition: attachment; filename=importbuddy.php' );
			header( 'Expires: 0' );
			header( 'Content-Length: ' . strlen( $output ) );
			
			//ob_clean();
			flush();
			
			//str_replace( readfile( dirname( __FILE__ ) . '/importbuddy.php' ) );
			
			echo $output;
			
			die();
		}
		
		
		function ajax_remotedestination() {
			
			
			$this->ajax_header( true );
			
			require_once( $this->_pluginPath . '/classes/admin.php' );
			$pluginbuddy_backupbuddy_admin = new pluginbuddy_backupbuddy_admin( $this );

			$pluginbuddy_backupbuddy_admin->ajax_remotedestination();
			
			
			
			
			
			
			
			//$pluginbuddy_backupbuddy_admin->ajax_remotedestination();
			
			$this->ajax_footer();
			die();
		}
		
		function ajax_remotetest() {
			if ( $_POST['#type'] == 's3' ) {
				if ( true === ( $response = $this->test_s3( $_POST['#accesskey'], $_POST['#secretkey'], $_POST['#bucket'], $_POST['#directory'], $_POST['#ssl'] ) ) ) {
					echo 'Test completed successfully.';
				} else {
					echo 'Failure; ' . $response;
				}
			} elseif ( $_POST['#type'] == 'rackspace' ) {
				if ( true === ( $response = $this->test_rackspace( $_POST['#username'], $_POST['#api_key'], $_POST['#container'] ) ) ) {
					echo 'Test completed successfully.';
				} else {
					echo 'Failure; ' . $response;
				}
			} elseif ( $_POST['#type'] == 'ftp' ) {
				if ( $_POST['#ftps'] == '0' ) {
					$ftp_type = 'ftp';
				} else {
					$ftp_type = 'ftps';
				}
				if ( true === ( $response = $this->test_ftp( $_POST['#address'], $_POST['#username'], $_POST['#password'], $_POST['#path'], $ftp_type ) ) ) {
					echo 'Test completed successfully.';
				} else {
					echo 'Failure; ' . $response;
				}
			} else {
				echo 'Error #4343489. There is not an automated test available for this service at this time.';
			}
			
			die();
		}
		
		function ajax_header( $js = false ) {
			//echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			//echo '<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US">';
			echo '<head>';
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<title>PluginBuddy</title>';
			wp_print_styles( 'global' );
			wp_print_styles( 'wp-admin' );
			wp_print_styles( 'colors-fresh' );
			wp_print_styles( 'colors-fresh' );
			
			echo '<link rel="stylesheet" href="' . $this->_pluginURL . '/css/admin.css" type="text/css" media="all" />';
			
			if ( $js === true ) {
				wp_enqueue_script( 'jquery' );
				wp_print_scripts( 'jquery' );
			}
			
			echo '<div style="padding: 5px;">';
		}
		
		function ajax_footer() {
			echo '</div>';
			echo '</head>';
			echo '</html>';
		}
		
		function ajax_filetree() {
			$root = ABSPATH;
			$_POST['dir'] = urldecode( $_POST['dir'] );
			if( file_exists( $root . $_POST['dir'] ) ) {
				$files = scandir( $root . $_POST['dir'] );
				natcasesort( $files );
				if( count( $files ) > 2 ) { /* The 2 accounts for . and .. */
					echo '<ul class="jqueryFileTree" style="display: none;">';
					foreach( $files as $file ) {
						if( file_exists( $root . $_POST['dir'] . $file ) && ( $file != '.' ) && ( $file != '..' ) && ( is_dir( $root . $_POST['dir'] . $file ) ) ) {
							echo '<li class="directory collapsed"><a href="#" rel="' . htmlentities($_POST['dir'] . $file) . '/">' . htmlentities($file) . ' <img src="' . $this->_pluginURL . '/images/bullet_delete.png" style="vertical-align: -3px;" /></a></li>';
						}
					}
					echo '</ul>';
				} else {
					echo '<ul class="jqueryFileTree" style="display: none;">';
					echo '<li><a href="#" rel="' . htmlentities( $_POST['dir'] . 'NONE' ) . '"><i>Empty Directory ...</i></a></li>';
					echo '</ul>';
				}
			} else {
				echo 'Error #1127555. Unable to read site root.';
			}
			
			die();
		}
		
		
		function ajax_remotesend() {
			wp_schedule_single_event( time(), 'pb_backupbuddy-cron_remotesend', array( $_POST['destination_id'], $this->_options['backup_directory'] . $_POST['file'] ) );
			spawn_cron( time() + 150 ); // Adds > 60 seconds to get around once per minute cron running limit.
			update_option( '_transient_doing_cron', 0 ); // Prevent cron-blocking for next item.
			
			echo 1;
			die();
		}
		
		
		function ajax_icicletree() {
		
		
			//die('monkey');
			$response = $this->build_icicle( ABSPATH, ABSPATH, '', -1 );
			echo $response[0];
			die();
		}
		
		// $max_depth	int		Maximum depth of tree to display.  Npte that deeper depths are still traversed for size calculations.
		function build_icicle( $dir, $base, $icicle_json, $max_depth = 10, $depth_count = 0, $is_root = true ) {
			$bg_color = '005282';
			
			$depth_count++;
			$bg_color = dechex( hexdec( $bg_color ) - ( $depth_count * 15 ) );
			
			$icicle_json = '{' . "\n";
			
			$dir_name = $dir;
			$dir_name = str_replace( ABSPATH, '', $dir );
			$dir_name = str_replace( '\\', '/', $dir_name );
			
			
			
			/*
			if ( ( $max_depth-1 > 0 ) || ( $max_depth == -1 ) ) {
				if ( $max_depth > 0 ) {
					$max_depth = $max_depth - 1;
				}
			*/
						
				$dir_size = 0;
				$sub = opendir( $dir );
				$has_children = false;
				while( $file = readdir( $sub ) ) {
					if ( ( $file == '.' ) || ( $file == '..' ) ) {
						// Do nothing.
					} elseif ( is_dir( $dir . '/' . $file ) ) {
						
						$dir_array = '';
						$response = $this->build_icicle( $dir . '/' . $file, $base, $dir_array, $max_depth, $depth_count, false );
						if ( ( $max_depth-1 > 0 ) || ( $max_depth == -1 ) ) { // Only adds to the visual tree if depth isnt exceeded.
							if ( $max_depth > 0 ) {
								$max_depth = $max_depth - 1;
							}
							
							if ( $has_children === false ) { // first loop add children section
								$icicle_json .= '"children": [' . "\n";
							} else {
								$icicle_json .= ',';
							}
							$icicle_json .= $response[0];
							
							$has_children = true;
						}
						$dir_size += $response[1];
						unset( $response );
						
						//$dir_array[ str_replace( $base, '', $dir . '/' . $file ) ] = ( $this_size / 1048576 );
						unset( $file );
						
						
					} else {
						$stats = stat( $dir . '/' . $file );
						$dir_size += $stats['size'];
						unset( $file );
					}
				}
				closedir( $sub );
				unset( $sub );
				
				if ( $has_children === true ) {
					$icicle_json .= ' ]' . "\n";
				}
				
			//}
			
			if ( $has_children === true ) {
				$icicle_json .= ',';
			}
			
			/*						if ( $dir_size == '' ) {
							$dir_size = 0;
						}
*/

			$icicle_json .= '"id": "node_' . str_replace( '/', ':', $dir_name ) . ': ^' . str_replace( ' ', '~', $this->format_size( $dir_size ) ) . '"' . "\n";
			//$icicle_json .= '"id": "node_' . str_replace( '/', ':', $dir_name ) . '"' . "\n";
			
			$dir_name = str_replace( '/', '', strrchr( $dir_name, '/' ) );
			if ( $dir_name == '' ) { // Set root to be /.
				$dir_name = '/';
			}
			$icicle_json .= ', "name": "' . $dir_name . ' (' . $this->format_size( $dir_size ) . ')"' . "\n";

			
			$icicle_json .= ',"data": { "$dim": ' . ( $dir_size + 10 ) . ', "$color": "#' . str_pad( $bg_color, 6, '0', STR_PAD_LEFT ) . '" }' . "\n";
			$icicle_json .= '}';
			
			if ( $is_root !== true ) {
				//$icicle_json .= ',x';
			}
			
			return array( $icicle_json, $dir_size );
		}
		
		function remote_send_s3( $accesskey, $secretkey, $bucket, $directory = '', $ssl, $file ) {
			$this->log( 'Starting Amazon S3 transfer.' );
			
			require_once(dirname( __FILE__ ).'/lib/s3/s3.php');
			$s3 = new S3( $accesskey, $secretkey );
			
			if ( $ssl != '1' ) {
				S3::$useSSL = false;
			}
			
			$this->log( 'About to put bucket to Amazon S3 cron.' );
			$s3->putBucket( $bucket, S3::ACL_PUBLIC_READ );
			$this->log( 'About to put object (the file) to Amazon S3 cron.' );
			if ( !empty( $directory ) ) {
				$directory = $directory . '/';
			}
			if ( $s3->putObject( S3::inputFile( $file ), $bucket, $directory . basename( $file ), S3::ACL_PRIVATE) ) {
				$this->log( 'SUCCESS sending to Amazon S3!' );
				
				return true;
			} else {
				$this->mail_error( 'ERROR #9002! Failed sending file to Amazon S3.' );
				$this->log( 'FAILURE sending to Amazon S3!', 'error' );
				
				return false;
			}
		}
		
		
		function remote_send_rackspace( $rs_username, $rs_api_key, $rs_container, $rs_file ) {
			$this->log( 'Starting Rackspace transfer.' );
			
			$rs_file = basename( $rs_file );
			
			require_once( $this->_pluginPath . '/lib/rackspace/cloudfiles.php' );
			$auth = new CF_Authentication( $rs_username, $rs_api_key );
			$auth->authenticate();
			$conn = new CF_Connection( $auth );

			// Set container
			$container = $conn->get_container($rs_container);
			
			$this->log( 'About to put object (the file) to Rackspace cron.' );
			
			// Create backup file
			$testbackup = $container->create_object( $rs_file );
			if ( $testbackup->load_from_filename( ABSPATH . 'wp-content/uploads/backupbuddy_backups/' . $rs_file ) ) {	
				return true;
			} else {
				$this->mail_error( 'ERROR #9002-rs! Failed sending file to Rackspace.' );
				$this->log( 'FAILURE sending to Rackspace!', 'error' );
				
				return false;
			}
		}
		
		
		function remote_send_ftp( $server, $username, $password, $path, $ftps, $file ) {
			$this->log( 'Starting remote send to FTP.' );
			
			if ( $ftps == '1' ) {
				if ( function_exists( 'ftp_ssl_connect' ) ) {
					$conn_id = ftp_ssl_connect( $server );
					if ( $conn_id === false ) {
						$this->log( 'Unable to connect to FTPS  (check address/FTPS support).', 'error' );
						return false;
					} else {
						$this->log( 'Connected to FTPs.' );
					}
				} else {
					$this->log( 'Your web server doesnt support FTPS in PHP.', 'error' );
					return false;
				}
			} else {
				if ( function_exists( 'ftp_connect' ) ) {
					$conn_id = ftp_connect( $server );
					if ( $conn_id === false ) {
						$this->log( 'ERROR: Unable to connect to FTP (check address).', 'error' );
						return false;
					} else {
						$this->log( 'Connected to FTP.' );
					}
				} else {
					$this->log( 'Your web server doesnt support FTP in PHP.', 'error' );
					return false;
				}
			}
			
			$login_result = @ftp_login( $conn_id, $username, $password );
			if ( $login_result === false ) {
				$this->mail_notice( 'ERROR #9011 ( http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#9011 ).  FTP/FTPs login failed on scheduled FTP.' );
				return false;
			} else {
				$this->log( 'Logged in. Sending backup via FTP/FTPs ...' );
			}
			
			$upload = ftp_put( $conn_id, $path . '/' . basename( $file ), $file, FTP_BINARY );
			if ( $upload === false ) {
				$this->mail_notice( 'ERROR #9012 ( http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#9012 ).  FTP/FTPs file upload failed. Check file permissions & disk quota.' );
			} else {
				$this->log( 'Done uploading backup file to FTP/FTPs.' );
			}
			ftp_close( $conn_id );
			
			return true;
		}
		
		
		function remote_send_email( $email, $file ) {
			$this->log( 'Sending remote email.' );
			$headers = 'From: BackupBuddy <' . get_option('admin_email') . '>' . "\r\n\\";
			wp_mail( $email, 'BackupBuddy Backup', 'BackupBuddy backup for ' . site_url(), $headers, $file );
			$this->log( 'Sent remote email.' );
		}
		
		
		function test_s3( $accesskey, $secretkey, $bucket, $directory = '', $ssl ) {
			if ( empty( $accesskey ) || empty( $secretkey ) || empty( $bucket ) ) {
				return 'Missing one or more required fields.';
			}
			
			require_once( dirname( __FILE__ ) . '/lib/s3/s3.php' );
			$s3 = new S3( $accesskey, $secretkey );
			
			if ( $this->_options['aws_ssl'] != '1' ) {
				S3::$useSSL = false;
			}
			
			if ( $s3->getBucketLocation( $bucket ) === false ) { // Easy way to see if bucket already exists.
				$s3->putBucket( $bucket, S3::ACL_PUBLIC_READ );
			}
			
			if ( !empty( $directory ) ) {
				$directory = $directory . '/';
			}
			if ( $s3->putObject( 'Upload test for BackupBuddy for Amazon S3', $bucket, $directory . 'backupbuddy.txt', S3::ACL_PRIVATE) ) {
				// Success... just delete temp test file later...
			} else {
				return 'Unable to upload. Verify your keys, bucket name, and account permissions.';
			}
			
			if ( ! S3::deleteObject( $bucket, $directory . '/backupbuddy.txt' ) ) {
				return 'Partial success. Could not delete temp file.';
			}
			
			return true; // Success!
		}
		
		function test_rackspace( $rs_username, $rs_api_key, $rs_container ) {
			if ( empty( $rs_username ) || empty( $rs_api_key ) || empty( $rs_container ) ) {
				return 'Missing one or more required fields.';
			}
			require_once($this->_pluginPath . '/lib/rackspace/cloudfiles.php');
			$auth = new CF_Authentication( $rs_username, $rs_api_key );
			if ( !$auth->authenticate() ) {
				return 'Unable to authenticate. Verify your username/api key.';
			}

			$conn = new CF_Connection( $auth );

			// Set container
			if ( false === ( $container = @$conn->get_container( $rs_container ) ) ) {
				return 'Invalid container. You must create it first.';
			}
			// Create test file
			$testbackup = @$container->create_object( 'backupbuddytest.txt' );
			if ( !$testbackup->load_from_filename( $this->_pluginPath . '/readme.txt') ) {
				return 'BackupBuddy was not able to write the test file.';
			}
			
			// Delete test file from Rackspace
			if ( !$container->delete_object( 'backupbuddytest.txt' ) ) {
				return 'Unable to delete file from container.';
			}
			
			return true; // Success
		}

		function test_ftp( $server, $username, $password, $path, $type = 'ftp' ) {
			if ( ( $server == '' ) || ( $username == '' ) || ( $password == '' ) ) {
				return 'Missing required input.';
			}
			
			if ( $type == 'ftp' ) {
				$conn_id = @ftp_connect( $server );
				if ( $conn_id === false ) {
					return 'Unable to connect to FTP (check address).';
				}
			} else {
				if ( function_exists( 'ftp_ssl_connect' ) ) {
					$conn_id = @ftp_ssl_connect( $server );
					if ( $conn_id === false ) {
						return 'Destination server does not support FTPS?';
					}
				} else {
					return 'Your web server doesnt support FTPS.';
				}
			}
			
			$login_result = @ftp_login( $conn_id, $username, $password );
			
			if ( ( !$conn_id ) || ( !$login_result ) ) {
			   return 'Unable to login. Bad user/pass.';
			} else {
				$tmp = tmpfile(); // Write tempory text file to stream.
				fwrite( $tmp, 'Upload test for BackupBuddy' );
				rewind( $tmp );
				$upload = @ftp_fput( $conn_id, $path . '/backupbuddy.txt', $tmp, FTP_BINARY );
				fclose( $tmp );
				
				if ( !$upload ) {
					return 'Failure uploading. Check path & permissions.';
				} else {
					ftp_delete( $conn_id, $path . '/backupbuddy.txt' );
				}
			}
			@ftp_close($conn_id);
			
			return true; // Success if we got this far.
		}
		
		
		function mail_error( $message ) {
			$this->load();
			$email = $this->_options['email_notify_error'];
			if ( !empty( $email ) ) {
				wp_mail( $email, "BackupBuddy Status - Error", "An error occurred with BackupBuddy on " . date(DATE_RFC822) . " for the site ". site_url() . ".  The error is displayed below:\r\n\r\n".$message, 'From: '.$email."\r\n".'Reply-To: '.get_option('admin_email')."\r\n");
			}
		}
		
		function mail_notify_manual( $message ) {
			$this->load();
			$email = $this->_options['email_notify_manual'];
			if ( !empty( $email ) ) {
				wp_mail( $email, "BackupBuddy Status - Manual Backup", "A manual backup occurred with BackupBuddy on " . date(DATE_RFC822) . " for the site ". site_url() . ".  The notice is displayed below:\r\n\r\n".$message, 'From: '.$email."\r\n".'Reply-To: '.get_option('admin_email')."\r\n");
			}
		}
		
		function mail_notify_scheduled( $message ) {
			$this->load();
			$email = $this->_options['email_notify_scheduled'];
			if ( !empty( $email ) ) {
				wp_mail( $email, "BackupBuddy Status - Scheduled Backup", "A scheduled backup occurred with BackupBuddy on " . date(DATE_RFC822) . " for the site ". site_url() . ".  The notice is displayed below:\r\n\r\n".$message, 'From: '.$email."\r\n".'Reply-To: '.get_option('admin_email')."\r\n");
			}
		}
		
		
		function dir_size( $dir, $base, &$dir_array ) {
			if( !is_dir( $dir ) ) {
				return 0;
			}
			
			$ret = 0;
			$sub = opendir( $dir );
			while( $file = readdir( $sub ) ) {
				if ( ( $file == '.' ) || ( $file == '..' ) ) {
					// Do nothing.
				} elseif ( is_dir( $dir . '/' . $file ) ) {
					$this_size = $this->dir_size( $dir . '/' . $file, $base, $dir_array );
					$dir_array[ str_replace( $base, '', $dir . '/' . $file ) ] = ( $this_size / 1048576 );
					$ret += $this_size;
					unset( $file );
				} else {
					$stats = stat( $dir . '/' . $file );
					$ret += $stats['size'];
					unset( $file );
				}
			}
			closedir( $sub );
			unset( $sub );
			return $ret;
		}
		
		
		function filter_plugin_row_meta( $plugin_meta, $plugin_file ) {
			if ( strstr( $plugin_file, strtolower( $this->_name ) ) ) {
				$plugin_meta[2] = '<a title="Visit plugin site" href="http://pluginbuddy.com/backupbuddy/">Visit PluginBuddy.com</a>';
				return $plugin_meta;
			} else {
				return $plugin_meta;
			}
		}
		
		function filter_cron_add_schedules( $schedules = array() ) {
			$schedules['weekly'] = array( 'interval' => 604800, 'display' => 'Once Weekly' );
			$schedules['twicemonthly'] = array( 'interval' => 1296000, 'display' => 'Twice Monthly' );
			$schedules['monthly'] = array( 'interval' => 2592000, 'display' => 'Once Monthly' );
			return $schedules;
		}
		
		/**
		 * versions_confirm()
		 *
		 * Check the version of an item and compare it to the minimum requirements BackupBuddy requires.
		 *
		 * $type		string		Optional. If left blank '' then all tests will be performed. Valid values: wordpress, php, ''.
		 * $notify		boolean		Optional. Whether or not to alert to the screen (and throw error to log) of a version issue.\
		 * @return		boolean		True if the selected type is a bad version
		 *
		 */
		function versions_confirm( $type = '', $notify = false ) {
			$bad_version = false;
			
			if ( ( $type == 'wordpress' ) || ( $type == '' ) ) {
				global $wp_version;
				if ( version_compare( $wp_version, $this->_wp_minimum, '<=' ) ) {
					if ( $notify === true ) {
						$this->alert( 'ERROR: ' . $this->_name . ' requires WordPress version ' . $this->_wp_minimum . ' or higher. You may experience unexpected behavior or complete failure in this environment. Please consider upgrading WordPress.' );
						$this->log( 'Unsupported WordPress Version: ' . $wp_version , 'error' );
					}
					$bad_version = true;
				}
			}
			if ( ( $type == 'php' ) || ( $type == '' ) ) {
				if ( version_compare( PHP_VERSION, $this->_php_minimum, '<=' ) ) {
					if ( $notify === true ) {
						$this->alert( 'ERROR: ' . $this->_name . ' requires PHP version ' . PHP_VERSION . ' or higher. You may experience unexpected behavior or complete failure in this environment. Please consider upgrading PHP.' );
						$this->log( 'Unsupported PHP Version: ' . PHP_VERSION , 'error' );
					}
					$bad_version = true;
				}
			}
			
			return $bad_version;
		}
		
		
		function format_size( $size ) {
			$sizes = array( ' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
			if ( $size == 0 ) {
				return( 'empty' );
			} else {
				return ( round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), $i > 1 ? 2 : 0) . $sizes[$i] );
			}
		}
		
		
		function format_date( $timestamp ) {
			return date( $this->_timestamp, $timestamp );
		}
		
		
		function localize_time( $timestamp ) {
			return $timestamp + ( get_option( 'gmt_offset' ) * 3600 );
		}
		
		function unlocalize_time( $timestamp ) {
			return $timestamp - ( get_option( 'gmt_offset' ) * 3600 );
		}
		
		
		// Accepts NON-localized timestamps.
		function time_ago( $timestamp ) {
			//return human_time_diff( $this->localize_time( $timestamp ), $this->localize_time( time() ) );
			return human_time_diff( $timestamp, time() );
		}
		
		// Human readable duration. Ex: 5 hours, 4 minutes, 43 seconds.
		function format_duration( $seconds ) {
			$ret = '';
			
			// Hours
			$hours = intval( intval( $seconds ) / 3600 );
			if( $hours > 0 ) {
				$ret .= "$hours hours ";
			}
			
			// Minutes
			$minutes = bcmod( ( intval( $seconds ) / 60 ), 60 );
			if( $hours > 0 || $minutes > 0 ) {
				$ret .= "$minutes minutes ";
			}
			
			// Seconds
			$seconds = bcmod( intval( $seconds ), 60 );
			$ret .= "$seconds seconds";
			
			return $ret;
		}
		
		/*
		Deletes a directory recursively including any files or subdirectories.
		*/
		function delete_directory_recursive( $directory ) {
			$directory = preg_replace( '|[/\\\\]+$|', '', $directory );
			
			$files = glob( $directory . '/*', GLOB_MARK );
			
			foreach( $files as $file ) {
				if( '/' === substr( $file, -1 ) )
					$this->rmdir_recursive( $file );
				else
					unlink( $file );
			}
			
			if ( is_dir( $directory ) ) rmdir( $directory );
			
			if ( is_dir( $directory ) )
				return false;
			return true;
		}
		
		
		
		/**
		 *	mkdir_recursive()
		 *
		 *	Recursively creates the directories needed to generate a full directory path.
		 *
		 *	$path		string		Full absolute path to generate.
		 *	@return		null
		 *
		 */
		function mkdir_recursive( $path ) {
			if ( empty( $path ) ) { // prevent infinite loop on bad path
				return;
			}
			is_dir( dirname( $path ) ) || $this->mkdir_recursive( dirname( $path ) );
			return is_dir( $path ) || mkdir( $path );
		}
		
		
	} // End class
	
	$pluginbuddy_backupbuddy = new pluginbuddy_backupbuddy();
}



?>