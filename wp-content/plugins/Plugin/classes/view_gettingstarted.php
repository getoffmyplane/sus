<?php
$this->admin_scripts();

// Needed for fancy boxes...
wp_enqueue_style('dashboard');
wp_print_styles('dashboard');
wp_enqueue_script('dashboard');
wp_print_scripts('dashboard');

// If they clicked the button to reset plugin defaults...
if (!empty($_POST['reset_defaults'])) {
	$this->_options = $this->_parent->_defaults;
	$this->_parent->activate();
	$this->_parent->save();
	$this->_parent->alert( 'Plugin settings have been reset to defaults.' );
}
?>

<div class="wrap">
	<div class="postbox-container" style="width:70%;">
		<?php
		$this->title( 'Getting Started with BackupBuddy v' . $this->_parent->_version );
		?>
		
		<br />
		
		BackupBuddy is the all-in-one solution for backups, restoration, and migration.  The single backup ZIP file created can be used
		with the import & migration script to quickly and easily restore your site on the same server or even migrate to a new host
		with different settings.  Whether you're an end user or a developer, this plugin will bring you peace of mind and added safety
		in the event of data loss.  Our goal is keeping the backup, restoration, and migration processes easy, fast, and reliable.
		
		<br /><br />
		
		Throughout the plugin you may hover your mouse over question marks <?php $this->tip( 'This tip provides additional help.' ); ?> for tips or click play icons <?php $this->video( 'x', 'This video would provide detailed information.' ); ?> for video tutorials.
		
		<br /><br />
		
		<p>
			<h3>Backup</h3>
			<ol>
				<li type="disc">
					Perform a <b>Full Backup</b> by clicking `Full Backup` button on the <a href="<?php echo admin_url( "admin.php?page={$this->_var}-backup" ); ?>">Backup</a>
					page. This backs up all files in your WordPress directory (and subdirectories) as well as the database.
					This will capture everything from the Database Only Backup and also all files in the WordPress directory and subdirectories.
					This includes files such as media, plugins, themes, images, and any other files found.
				</li>
				<li type="disc">
					Perform a <b>Database Backup</b> regularly by clicking `Database Backup` button on the
					<a href="<?php echo admin_url( "admin.php?page={$this->_var}-backup" ); ?>">Backup</a>. The database contains posts, pages, comments widget
					content, media titles & descriptions (but not media files), and other WordPress settings. It may be backed up more often without impacting
					your available storage space or server performance as much as a Full Backup.</li>
				<li type="disc"><i>Local backup storage directory: <input size="70" type="text" value="<?php echo str_replace( '\\', '/', $this->_options['backup_directory'] ); ?>"><?php $this->tip(' This is the local directory that backups are stored in. Backup files include random characters in their name for increased security. BackupBuddy must be able to create this directory & write to it.' ); ?></i></li>
			</ol>
			
		</p>
		<br />
		<p>
			<h3>Restoring, Migrating</h3>
			<ol>
				<li type="disc">Upload the backup file and <a href="<?php $this->importbuddy_link(); ?>">importbuddy.php</a> to the root web directory of the destination server.
					<b>Do not install WordPress</b> on the destination server. The importbuddy.php script will restore all files, including WordPress.</li>
				<li type="disc">Create a mySQL database on the destination server. ( <a href="http://anonym.to/?http://pluginbuddy.com/tutorial-create-database-in-cpanel/" target="_new">Tutorial Video & Instructions Here</a> )</li>
				<li type="disc">Navigate to <a href="<?php $this->importbuddy_link(); ?>">importbuddy.php</a> in your web browser on the destination server. If you provided an import password you will be prompted for this password before you may continue.</li>
				<li type="disc">Follow the importing instructions on screen. You will be asked whether you are restoring or migrating.</li>
			</ol>
		</p>
		
		<?php if ( stristr( PHP_OS, 'WIN' ) ) { ?>
			<br />
			<h3>Windows Server Performance Boost</h3>
			Windows servers may be able to significantly boost performance, if the server allows executing .exe files, by adding native Zip compatibility executable files <a href="http://anonym.to/?http://pluginbuddy.com/wp-content/uploads/2010/05/backupbuddy_windows_unzip.zip">available for download here</a>.
			Instructions are provided within the readme.txt in the package.  This package prevents Windows from falling back to Zip compatiblity mode and works for both BackupBuddy and importbuddy.php. This is particularly useful for <a href="http://anonym.to/?http://ithemes.com/codex/page/BackupBuddy:_Local_Development">local development on a Windows machine using a system like XAMPP</a>.
		<?php } ?>
		
		<br /><br />
		
		<br />
		
		<center>
			<div style="background: #D4E4EE; -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px; padding: 15px; text-align: center; width: 395px; line-height: 1.6em;">
				<a title="Click to visit the PluginBuddy Knowledge Base" href="http://anonym.to/?http://ithemes.com/codex/page/PluginBuddy" style="text-decoration: none; color: #000000;">
					<img src="<?php echo $this->_pluginURL; ?>/images/kb.png" alt="" width="70" height="70" style="float: right;" />For documentation &#038; help visit our<br />
					<span style="font-size: 2.8em;">Knowledge Base</span>
					<br />Walkthroughs &middot; Tutorials &middot;  Technical Details
				</a>
			</div>
		</center>
		
		<br style="clear: both;" />
		
		
		
		<h3>Version History</h3>
		<textarea rows="7" cols="70"><?php readfile( $this->_parent->_pluginPath . '/history.txt' ); ?></textarea>
		<br /><br />
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#pluginbuddy_debugtoggle").click(function() {
					jQuery("#pluginbuddy_debugtoggle_div").slideToggle();
				});
			});
		</script>
		
		<a id="pluginbuddy_debugtoggle" class="button secondary-button">Debugging Information</a>
		<div id="pluginbuddy_debugtoggle_div" style="display: none;">
			<h3>Debugging Information</h3>
			<?php
			echo '<textarea rows="7" cols="65">';
			echo 'Plugin Version = '.$this->_name.' '.$this->_parent->_version.' ('.$this->_parent->_var.')'."\n";
			echo 'WordPress Version = '.get_bloginfo("version")."\n";
			echo 'PHP Version = '.phpversion()."\n";
			global $wpdb;
			echo 'DB Version = '.$wpdb->db_version()."\n";
			echo "\n".print_r($this->_options);
			echo '</textarea>';
			?>
			<p>
			<form method="post" action="<?php echo $this->_selfLink; ?>">
				<input type="hidden" name="reset_defaults" value="true" />
				<input type="submit" name="submit" value="Reset Plugin Settings & Defaults" id="reset_defaults" class="button secondary-button" onclick="if ( !confirm('WARNING: This will reset all settings associated with this plugin to their defaults. Are you sure you want to do this?') ) { return false; }" />
			</form>
			</p>
		</div>
		<br /><br /><br />
		<a href="http://anonym.to/?http://pluginbuddy.com" style="text-decoration: none;"><img src="<?php echo $this->_pluginURL; ?>/images/pluginbuddy.png" style="vertical-align: -3px;" /> PluginBuddy.com</a><br /><br />
	</div>
	<div class="postbox-container" style="width:20%; margin-top: 35px; margin-left: 15px;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<div id="breadcrumbssupport" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>Need support?</span></h3>
					<div class="inside">
						<p>See our <a href="http://anonym.to/?http://pluginbuddy.com/tutorials/">tutorials & videos</a> or visit our <a href="http://anonym.to/?http://pluginbuddy.com/support/">support forum</a> for additional information and help.</p>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>