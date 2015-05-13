<?php
$this->admin_scripts();

// Used for drag & drop / collapsing boxes.
wp_enqueue_style('dashboard');
wp_print_styles('dashboard');
wp_enqueue_script('dashboard');
wp_print_scripts('dashboard');
?>
<div class="wrap">
<?php
if ( isset( $_GET['run_backup'] ) ) {
	require_once( 'view_backup-run_backup-perform.php' );
} else {
	require_once( 'view_backup-run_backup-home.php' );
}
?>
</div>

<br /><br />