<?php
$this->admin_scripts();

wp_enqueue_script( 'thickbox' );
wp_print_scripts( 'thickbox' );
wp_print_styles( 'thickbox' );

// for Directory Exclusion:
wp_enqueue_script( $this->_var . '_filetree', $this->_pluginURL . '/js/filetree.js' );
wp_print_scripts( $this->_var . '_filetree' );
echo '<link rel="stylesheet" href="'.$this->_pluginURL . '/css/filetree.css" type="text/css" media="all" />';

// Used for drag & drop / collapsing boxes.
wp_enqueue_style('dashboard');
wp_print_styles('dashboard');
wp_enqueue_script('dashboard');
//wp_print_scripts('dashboard'); BREAKS VIDEO THICKBOX when play button is in the collaspible top.

if ( !empty( $_POST['save'] ) ) {
	$this->savesettings();
}
?>


<style type="text/css">
	.form-table tr > td:first-child {
		width: 300px;
	}
</style>


<?php // The following section is for directory exclusion. ?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#exlude_dirs').fileTree({ root: '/', multiFolder: false, script: '<?php echo admin_url('admin-ajax.php').'?action=pb_backupbuddy_filetree'; ?>' }, function(file) {
				//alert('file:'+file);
			}, function(directory) {
				if ( ( directory == '/wp-content/' ) || ( directory == '/wp-content/uploads/' ) || ( directory == '/wp-content/uploads/backupbuddy_backups/' ) ) {
					alert( 'You cannot exclude /wp-content/ or /wp-content/uploads/.  However, you may exclude subdirectories within these. The backupbuddy_backups directory is also automatically excluded and cannot be added to exclusion list.' );
				} else {
					jQuery('#exclude_dirs').val( directory + "\n" + jQuery('#exclude_dirs').val() );
				}
				
			});
		});
		
		function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data ) {
			window.location.href = '<?php echo $this->_selfLink; ?>&custom=remoteclient&destination_id=' + destination_id;
		}
	</script>
<style type="text/css">
	/* Core Styles */
	.jqueryFileTree LI.directory { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/directory.png') left top no-repeat; }
	.jqueryFileTree LI.expanded { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/folder_open.png') left top no-repeat; }
	.jqueryFileTree LI.file { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/file.png') left top no-repeat; }
	.jqueryFileTree LI.wait { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/spinner.gif') left top no-repeat; }
	/* File Extensions*/
	.jqueryFileTree LI.ext_3gp { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/film.png') left top no-repeat; }
	.jqueryFileTree LI.ext_afp { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_afpa { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_asp { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_aspx { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_avi { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/film.png') left top no-repeat; }
	.jqueryFileTree LI.ext_bat { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/application.png') left top no-repeat; }
	.jqueryFileTree LI.ext_bmp { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_c { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_cfm { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_cgi { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_com { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/application.png') left top no-repeat; }
	.jqueryFileTree LI.ext_cpp { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_css { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/css.png') left top no-repeat; }
	.jqueryFileTree LI.ext_doc { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/doc.png') left top no-repeat; }
	.jqueryFileTree LI.ext_exe { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/application.png') left top no-repeat; }
	.jqueryFileTree LI.ext_gif { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_fla { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/flash.png') left top no-repeat; }
	.jqueryFileTree LI.ext_h { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_htm { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/html.png') left top no-repeat; }
	.jqueryFileTree LI.ext_html { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/html.png') left top no-repeat; }
	.jqueryFileTree LI.ext_jar { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/java.png') left top no-repeat; }
	.jqueryFileTree LI.ext_jpg { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_jpeg { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_js { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/script.png') left top no-repeat; }
	.jqueryFileTree LI.ext_lasso { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_log { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/txt.png') left top no-repeat; }
	.jqueryFileTree LI.ext_m4p { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/music.png') left top no-repeat; }
	.jqueryFileTree LI.ext_mov { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/film.png') left top no-repeat; }
	.jqueryFileTree LI.ext_mp3 { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/music.png') left top no-repeat; }
	.jqueryFileTree LI.ext_mp4 { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/film.png') left top no-repeat; }
	.jqueryFileTree LI.ext_mpg { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/film.png') left top no-repeat; }
	.jqueryFileTree LI.ext_mpeg { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/film.png') left top no-repeat; }
	.jqueryFileTree LI.ext_ogg { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/music.png') left top no-repeat; }
	.jqueryFileTree LI.ext_pcx { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_pdf { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/pdf.png') left top no-repeat; }
	.jqueryFileTree LI.ext_php { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/php.png') left top no-repeat; }
	.jqueryFileTree LI.ext_png { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_ppt { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/ppt.png') left top no-repeat; }
	.jqueryFileTree LI.ext_psd { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/psd.png') left top no-repeat; }
	.jqueryFileTree LI.ext_pl { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/script.png') left top no-repeat; }
	.jqueryFileTree LI.ext_py { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/script.png') left top no-repeat; }
	.jqueryFileTree LI.ext_rb { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/ruby.png') left top no-repeat; }
	.jqueryFileTree LI.ext_rbx { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/ruby.png') left top no-repeat; }
	.jqueryFileTree LI.ext_rhtml { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/ruby.png') left top no-repeat; }
	.jqueryFileTree LI.ext_rpm { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/linux.png') left top no-repeat; }
	.jqueryFileTree LI.ext_ruby { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/ruby.png') left top no-repeat; }
	.jqueryFileTree LI.ext_sql { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/db.png') left top no-repeat; }
	.jqueryFileTree LI.ext_swf { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/flash.png') left top no-repeat; }
	.jqueryFileTree LI.ext_tif { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_tiff { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/picture.png') left top no-repeat; }
	.jqueryFileTree LI.ext_txt { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/txt.png') left top no-repeat; }
	.jqueryFileTree LI.ext_vb { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_wav { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/music.png') left top no-repeat; }
	.jqueryFileTree LI.ext_wmv { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/film.png') left top no-repeat; }
	.jqueryFileTree LI.ext_xls { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/xls.png') left top no-repeat; }
	.jqueryFileTree LI.ext_xml { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/code.png') left top no-repeat; }
	.jqueryFileTree LI.ext_zip { background: url('<?php echo $this->_pluginURL; ?>/images/filetree/zip.png') left top no-repeat; }
</style>


<div class="wrap">
	<?php $this->title( 'Settings' ); ?><br />
	<form method="post" action="<?php echo $this->_selfLink; ?>-settings">
		<?php // Ex. for saving in a group you might do something like: $this->_options['groups'][$_GET['group_id']['settings'] which would be: ['groups'][$_GET['group_id']['settings'] ?>
		<input type="hidden" name="savepoint" value="" />
		
		
		
		
		
		
		<div class="postbox-container" style="width: 80%; min-width: 750px;">
			<div class="metabox-holder">
				<div class="meta-box-sortables">
					
					
					<div id="breadcrumbslike" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>Email Notifications <?php $this->video( 'E37G9X6eNpg#8', 'Email Notifications Tutorial' ); ?></span></h3>
						<div class="inside">
							<table class="form-table">
								<tr>
									<td><label for="email_notify_scheduled">Scheduled backup completion recipients <?php $this->tip( 'Email address(es) to send notifications to upon scheduled backup completion.' ); ?></label></td>
									<td><input type="text" name="#email_notify_scheduled" id="email_notify_scheduled" size="30" maxlength="45" value="<?php echo $this->_options['email_notify_scheduled']; ?>" /></td>
								</tr>
								<tr>
									<td><label for="email_notify_manual">Manual backup completion recipients <?php $this->tip( 'Email address(es) to send notifications to upon manually triggered backup completion.' ); ?></label></td>
									<td><input type="text" name="#email_notify_manual" id="email_notify_manual" size="30" maxlength="45" value="<?php echo $this->_options['email_notify_manual']; ?>" /></td>
								</tr>
								<tr>
									<td><label for="email_notify_error">Backup failure/error recipients  <?php $this->tip( 'Email address(es) to send notifications to upon encountering any errors or problems.' ); ?></label></td>
									<td><input type="text" name="#email_notify_error" id="email_notify_error" size="30" maxlength="45" value="<?php echo $this->_options['email_notify_error']; ?>" /></td>
								</tr>
							</table>
						</div>
					</div>
					
					
					<div id="breadcrumbslike" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>General Options <?php $this->video( 'E37G9X6eNpg#26', 'General Options Tutorial' ); ?></span></h3>
						<div class="inside">
							<table class="form-table">
								<tr>
									<td><label for="import_password">Import password (optional) <?php $this->tip( '[Example: myp@ssw0rD] - Optional password to be required to launch the importbuddy import/migration script. This prevents unauthorized users from being able to view your backup archive listing on the destination server during Step 1 or proceed with an unauthorized import.' ); ?></label></td>
									<td><input type="password" name="#import_password" id="import_password" size="30" maxlength="45" value="<?php echo $this->_options['import_password']; ?>" /></td>
								</tr>
								<tr>
									<td valign="top"><label>Backup reminders <?php $this->tip( '[Default: enabled] - When enabled links will be displayed upon post or page edits and during WordPress upgrades to remind and allow rapid backing up after modifications or before upgrading.' ); ?></label></td>
									<td>
										<input type="hidden" name="#backup_reminders" value="0" />
										<input type="checkbox" name="#backup_reminders" id="backup_reminders" value="1" <?php if ( $this->_options['backup_reminders'] == '1' ) { echo 'checked'; } ?> /> <label for="high_security">Enable backup reminders</label>
									</td>
								</tr>
								<tr>
									<td valign="top"><label>Compatibility options <?php $this->tip( 'Various options to aid in making BackupBuddy work in less than ideal server environments. These are useful for working around problems. You may be directed by support to modify these if you encounter problems.' ); ?>  <?php $this->video( 'Tp_VkLoBEpw', 'Compatibility Options Details' ); ?></label></td>
									<td>
										<input type="hidden" name="#compression" value="0" />
										<input type="checkbox" name="#compression" id="compression" value="1" <?php if ( $this->_options['compression'] == '1' ) { echo 'checked'; } ?> /> <label for="compression">Enable ZIP compression <?php $this->tip( '[Default: enabled] - ZIP compression decreases file sizes of stored backups. If you are encountering timeouts due to the script running too long, disabling compression may allow the process to complete faster.' ); ?></label>
										<br />
										<input type="hidden" name="#force_compatibility" value="0" />
										<input type="checkbox" name="#force_compatibility" id="force_compatibility" value="1" <?php if ( $this->_options['force_compatibility'] == '1' ) { echo 'checked'; } ?> /> <label for="force_compatibility">Force compatibility mode backups <?php $this->tip( '[Default: disabled] - Under normal circumstances compatibility mode is automatically entered as needed without user intervention. However under some server configurations the native backup system is unavailable but is incorrectly reported as functioning by the server.  Forcing compatibility may fix problems in this situation by bypassing the native backup system check entirely.' ); ?></label>
										<br />
										<input type="hidden" name="#backup_nonwp_tables" value="0" />
										<input type="checkbox" name="#backup_nonwp_tables" id="backup_nonwp_tables" value="1" <?php if ( $this->_options['backup_nonwp_tables'] == '1' ) { echo 'checked'; } ?> /> <label for="backup_nonwp_tables">Backup non-WordPress database tables <?php $this->tip( '[Default: disabled] - Checking this box will result in ALL tables and data in the database being backed up, even database content not related to WordPress, its content, or plugins (based on prefix).  This is useful if you have other software installed on your hosting that stores data in your database.' ); ?></label>
										<br />
										<input type="hidden" name="#integrity_check" value="0" />
										<input type="checkbox" name="#integrity_check" id="integrity_check" value="1" <?php if ( $this->_options['integrity_check'] == '1' ) { echo 'checked'; } ?> /> <label for="integrity_check">Perform integrity check on backup files <?php $this->tip( '[Default: enabled] - By default each backup file is checked for integrity and completion the first time it is viewed on the Backup page.  On some server configurations this may cause memory problems as the integrity checking process is intensive.  If you are experiencing out of memory errors on the Backup file listing, you can uncheck this to disable this feature.' ); ?></label>
									</td>
								</tr>
								<tr>
									<td><label for="log_level">Logging level <?php $this->tip( '[Default: Errors Only] - This option controls how much activity is logged for records or debugging. Logs saved in ' . WP_CONTENT_DIR . '/uploads/backupbuddy.txt' ); ?></label></td>
									<td>
										<select name="#log_level">
											<option value="0" <?php if ( $this->_options['log_level'] == '0' ) { echo 'selected'; } ?>>None</option>
											<option value="1" <?php if ( $this->_options['log_level'] == '1' ) { echo 'selected'; } ?>>Errors Only</option>
											<option value="2" <?php if ( $this->_options['log_level'] == '2' ) { echo 'selected'; } ?>>Errors & Warnings</option>
											<option value="3" <?php if ( $this->_options['log_level'] == '3' ) { echo 'selected'; } ?>>Everything (debug mode)</option>
										</select>
									</td>
								</tr>
								<?php
								/*
								<tr>
									<td><label for="role_access">Plugin access limits <?php $this->tip( '[Default: administrator] - Determine which level of users are allowed to have access to all of BackupBuddy\'s features. WARNING: This will allow other roles access to configure BackupBuddy & download your backup files which may release sensitive information and be a security risk. Use caution.' ); ?></label></td>
									<td>
										<select name="#role_access">
											<option value="administrator" <?php if ( $this->_options['role_access'] == 'administrator' ) { echo 'selected'; } ?>>Administrator</option>
											<option value="editor" <?php if ( $this->_options['role_access'] == 'editor' ) { echo 'selected'; } ?>>Editor (use caution)</option>
											<option value="author" <?php if ( $this->_options['role_access'] == 'author' ) { echo 'selected'; } ?>>Author (use caution)</option>
											<option value="contributer" <?php if ( $this->_options['role_access'] == 'contributer' ) { echo 'selected'; } ?>>Contributer (use caution)</option>
											<option value="subscriber" <?php if ( $this->_options['role_access'] == 'subscriber' ) { echo 'selected'; } ?>>Subscriber (use caution)</option>
										</select>
									</td>
								</tr>
								*/
								?>
							</table>
						</div>
					</div>
					
					
					<div id="breadcrumbslike" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>Archive Storage Limits  <?php $this->video( 'E37G9X6eNpg#176', 'Archive Storage Limits Tutorial' ); ?></span></h3>
						<div class="inside">
							<table class="form-table">
								<tr>
									<td><label for="archive_limit">Maximum number of archived backups <?php $this->tip( '[Example: 10] - Maximum number of archived backups to store. The oldest backup(s) will be removed if this limit is exceeded. Set to zero for no limit. You can either limit the total backups or by type.  Set unused limits to zero (0).' ); ?></label></td>
									<td>
										<input type="text" name="#archive_limit" id="archive_limit" size="2" maxlength="6" value="<?php echo $this->_options['archive_limit']; ?>" style="text-align: right;" /> total
									</td>
								</tr>
								<tr>
									<td><label for="archive_limit_size">Maximum size of archived backups <?php $this->tip( '[Example: 350] - Maximum size (in MB) to allow your total archives to reach. Any new backups created after this limit is met will result in your oldest backup(s) being deleted to make room for the newer ones.' ); ?></label></td>
									<td>
										<input type="text" name="#archive_limit_size" id="archive_limit_size" size="2" maxlength="6" value="<?php echo $this->_options['archive_limit_size']; ?>" style="text-align: right;" /> MB total
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					
					<div id="breadcrumbslike" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>Remote Offsite Storage / Destinations  <?php $this->video( 'E37G9X6eNpg#262', 'Remote Offsite Management / Remote Clients Tutorial' ); ?></span></h3>
						<div class="inside">
							<a href="<?php echo admin_url('admin-ajax.php') . '?action=pb_backupbuddy_remotedestination'; ?>&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox button secondary-button" style="margin-top: 3px;" title="Select a destination within to manage archives within the destination">Manage Remote Destinations & Archives</a>
							<span style="color: #AFAFAF;">
								&nbsp;&nbsp;&nbsp;
								Manage Amazon S3, Rackspace Cloudfiles, Email, and FTP.
							</span>
						</div>
					</div>
					
					
					<div id="breadcrumbslike" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>Backup Exclusions  <?php $this->video( 'E37G9X6eNpg#294', 'Backup Directory Excluding Tutorial' ); ?></span></h3>
						<div class="inside">
							<table class="form-table">
								<tr>
									<td>
										Click directory to navigate to it or <img src="<?php echo $this->_pluginURL; ?>/images/bullet_delete.png" style="vertical-align: -3px;" />to exclude it. <?php $this->tip( 'Click on a directory name to navigate directories. Click the red minus sign to the right of a directory to place it in the exclusion list. /wp-content/ and /wp-content/uploads/ cannot be excluded.' ); ?><br />
										<div id="exlude_dirs" class="jQueryOuterTree">
										</div>
										<small>Available if server does not require compatibility mode. <?php $this->tip( 'If you receive notifications that your server is entering compatibility mode or that native zip functionality is unavailable then this feature will not be available due to technical limitations of the compatibility mode.  Ask your host to correct the problems causing compatibility mode or move to a new server.' ); ?></small>
									</td>
									<td>
										Excluded directories (path relative to root <?php $this->tip( 'List paths relative to root to be excluded from backups.  You may use the directory selector to the left to easily exclude directories by ctrl+clicking them.  Paths are relative to root. Ex: /wp-content/uploads/junk/' ); ?>)<br />
										<textarea name="#excludes" wrap="off" rows="4" cols="35" maxlength="9000" id="exclude_dirs"><?php echo $this->_options['excludes']; ?></textarea>
										<br /><small>List one path per line. Remove a line to remove exclusion.</small>
									</td>
								</tr>
								
							</table>
						</div>
					</div>
					
					
					
					
					
				</div>
			</div>
		</div>

		
		
		<p class="submit clear"><input type="submit" name="save" value="Save Settings" class="button-primary" id="save" /></p>
		<?php $this->nonce(); ?>
		<br />
	</form>
</div>
