<?php
/*
 * Title: Channel Objectives
 * Post Type: channel
 * Order: 10
 */

/*
 * Channel Objectives
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'channel_objectives',
    'label' => __('Channel Objectives'),
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'channel_objective',
            'label' => 'Objective',
            'columns' => 12
        )
    )
));