		<div class='wp-dashboard-widget-wrap list-widget' data-widget-type='list' data-color-text='<?php echo $widget_meta['color_text']; ?>' data-widget-color='<?php echo $widget_meta['color']; ?>'>

			<div class='wp-dashboard-widget'>
				<?php echo $content; ?>
			</div>

			<div class='wp-dashboard-widget-options'>
				<div class='dashicons dashicons-plus wpdw-add-item'></div>
				<input type='text' name='list_item' class='add-list-item' data-id='<?php echo $widget->ID; ?>' placeholder='<?php _e( 'To Do', 'wp-dashboard-widgets' ); ?>'>
				<span class='status'></span>
				<div class='wpdw-extra'>
					<span class='wpdw-option-visibility'>
						<?php
						if ( 'private' == $widget_meta['visibility'] && $widget_meta ) :
							$status['icon']			= 'dashicons-admin-users';
							$status['title']		= __( 'Just me', 'wp-dashboard-widgets' );
							$status['visibility']	= 'private';
						else :
							$status['icon']			= 'dashicons-groups';
							$status['title']		= __( 'Everyone', 'wp-dashboard-widgets' );
							$status['visibility']	= 'public';
						endif; ?>

						<span class='wpdw-toggle-visibility' title='<?php _e( 'Visibility:', 'wp-dashboard-widgets' ); ?> <?php echo $status['title']; ?>' data-visibility='<?php echo $status['visibility']; ?>'>
							<div class='wpdw-visibility visibility-publish dashicons <?php echo $status['icon']; ?>'></div>
						</span>

						<span class='wpdw-color-widget' title='<?php _e( 'Give me a color!', 'wp-dashboard-widgets' ); ?>'>
							<span class='wpdw-color-palette'>

								<?php foreach ( $colors as $name => $color ) : ?>
									<span class='color color-<?php echo $name;?>' data-select-color-text='<?php echo $name; ?>'	data-select-color='<?php echo $color; ?>' style='background-color: <?php echo $color; ?>'></span>
								<?php endforeach; ?>

							</span>
							<div class='dashicons dashicons-art wpdw-widget-color'></div>
						</span>

						<span title='<?php _e( 'Add resource(s)', 'wp-dashboard-widgets'); ?>'>
							<div class='wpdw-widget-type dashicons dashicons-archive'></div>
						</span>

						<span class='wpdw-add-widget' title='<?php _e( 'Add a new widget', 'wp-dashboard-widgets' ); ?>'>
							<div class='dashicons dashicons-plus'></div>
						</span>


						<span style='float: right; margin-right: 10px;' class='wpdw-delete-widget' title='<?php _e( 'Delete widget', 'wp-dashboard-widgets' ); ?>'>
							<div class='dashicons dashicons-trash'></div>
						</span>

					</span>
				</div>
			</div>

		</div>
