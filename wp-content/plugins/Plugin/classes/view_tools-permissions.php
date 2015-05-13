<?php
$tests = array();


$this_test = array(
				'title'			=>		'/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-includes/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-includes/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-includes/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-includes/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-admin/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-admin/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-admin/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-admin/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-admin/js/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-admin/js/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-admin/js/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-admin/js/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-content/themes/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/themes/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-content/themes/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/themes/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-content/plugins/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/plugins/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-content/plugins/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/plugins/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-content/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-content/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-content/uploads/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/uploads/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-content/uploads/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-content/uploads/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );


$this_test = array(
				'title'			=>		'/wp-includes/',
				'suggestion'	=>		'<= 755',
				'value'			=>		substr( sprintf( '%o', fileperms( ABSPATH . '/wp-includes/' ) ), -4 ),
			);
if ( !fileperms( ABSPATH . '/wp-includes/' ) || substr( sprintf( '%o', fileperms( ABSPATH . '/wp-includes/' ) ), -4 ) > 755 ) {
	$this_test['status'] = 'warn';
} else {
	$this_test['status'] = 'success';
}
array_push( $tests, $this_test );

?>

<table class="widefat">
	<thead>
		<tr class="thead">
			<th>Relative Path</th>
			<th>Suggestion</th>
			<th>Value</th>
			<th>Status</th>
		</tr>
	</thead>
	<tfoot>
		<tr class="thead">
			<th>Relative Path</th>
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