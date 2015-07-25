<?php
/*
 * Title: Design Constraints
 * Post Type: channel
 * Order: 20
 */

/*
 * Channel Objectives
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'channel_design_constraints',
    'label' => __('Design Constraints'),
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'channel_design_constraint',
            'label' => 'Constraint',
            'columns' => 12
        )
    )
));