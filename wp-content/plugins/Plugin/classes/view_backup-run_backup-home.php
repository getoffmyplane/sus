<?php $this->title( 'Backup, Restore, Migrate' ); ?>

<style> 
	.graybutton {
		background: url(<?php echo $this->_pluginURL; ?>/images/buttons/grays2.png) top repeat-x;
		width: 158px;
		height: 138px;
		display: block;
		float: left;
		-moz-border-radius: 6px;
		border-radius: 6px;
		border: 1px solid #c9c9c9;
	}
	.graybutton:hover {
		background: url(<?php echo $this->_pluginURL; ?>/images/buttons/grays2.png) bottom repeat-x;
		border: 1px solid #aaaaaa;
	}
	.graybutton:active {
		background: url(<?php echo $this->_pluginURL; ?>/images/buttons/grays2.png) bottom repeat-x;
		border: 1px solid transparent;
	}
	.leftround {
		-moz-border-radius: 4px 0 0 4px;
		border-radius: 4px 0 0 4px;
		border-right: 1px solid #c9c9c9;
	}
	.rightround {
		-moz-border-radius: 0 4px 4px 0;
		border-radius: 0 4px 4px 0;
	}
	.dbonlyicon {
		background: url(<?php echo $this->_pluginURL; ?>/images/buttons/dbonly-icon.png);
		width: 60px;
		height: 60px;
		margin: 15px 0 0 50px;
		display: block;
		float: left;
	}
	.allcontenticon {
		background: url(<?php echo $this->_pluginURL; ?>/images/buttons/allcontent-icon.png);
		width: 60px;
		height: 60px;
		margin: 15px 0 0 50px;
		display: block;
		float: left;
	}
	.restoremigrateicon {
		background: url(<?php echo $this->_pluginURL; ?>/images/buttons/restoremigrate-icon.png);
		width: 60px;
		height: 60px;
		margin: 15px 0 0 50px;
		display: block;
		float: left;
	}
	.bbbutton-text {
		font-family: Georgia, Times, serif;
		font-size: 18px;
		font-style: italic;
		width: 158px;
		text-align: center;
		
		/* line-height: 60px; */
		padding-top: 13px;
		
		color: #666666;
		text-shadow: 1px 1px 1px #ffffff;
		clear: both;
	}
	.bbbutton-smalltext {
		font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
		font-size: 9px;
		font-style: normal;
		text-shadow: 0;
		padding-top: 3px;
	}
</style>

<p>Select a backup option from the buttons below to begin:</p>
<br />


<div>
	<a href="<?php echo $this->_selfLink; ?>-backup&run_backup=db" style="text-decoration: none;" title="Backs up the database only. This backup is fast and creates a small backup file. Includes pages, posts, comments, etc. Files such as media are not included.">
		<div class="graybutton leftround">
			<div class="dbonlyicon"></div>
			<div class="bbbutton-text">
				Database Only<br />
				<div class="bbbutton-smalltext">back up content only</div>
			</div>
		</div>
	</a>
	<a href="<?php echo $this->_selfLink; ?>-backup&run_backup=full" style="text-decoration: none;" title="Backs up everything including the database, settings, themes, plugins, files, media, etc. This backup takes more time and can create a large file.">
		<div class="graybutton rightround">
			<div class="allcontenticon"></div>
			<div class="bbbutton-text">
				Full Backup<br />
				<div class="bbbutton-smalltext">back up everything</div>
			</div>
		</div>
	</a>
	<a href="<?php
		$this->importbuddy_link();
	?>" style="text-decoration: none;" title="Download the import & migration script, importbuddy.php">
		<div class="graybutton" style="margin-left: 30px">
			<div class="restoremigrateicon"></div>
			<div class="bbbutton-text">
				Restore/Migrate<br />
				<div class="bbbutton-smalltext">restore or move your site</div>
			</div>
		</div>
	</a>
</div>


<br style="clear: both;" /><br />

<?php
echo '<h3>Backup Archives (local)</h3>';
require_once( 'view_backup-listing.php' );

echo '<br /><br />';
?>


