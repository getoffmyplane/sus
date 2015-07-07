<?php
/*
 * Title: Process
 * Post Type: channel
 * Order: 30
 */

/*
 * Channel Levels
 */

echo 'Producer = you';

piklist('field', array(
    'type' => 'group',
    'field' => 'channel_intermediaries',
    'label' => __('Intermediaries'),
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'select',
            'field' => 'channel_intermediary',
            'label' => 'Type',
            'choices' => array(
                'agent' => 'Agent',
                'distributor' => 'Distributor',
                'wholesaler' => 'Wholesaler',
                'retailer' => 'Retailer'
            ),
            'columns' => 12
        ),
        array(
            'type' => 'post-relate',
            'label' => __('Select intermediaries'),
            'description' => 'Select your intermediary(ies)',
            'scope' => 'group',
            'columns' => 12
        )
    )
));


piklist('field', array(
    'type' => 'post-relate',
    'label' => __('Consumers'),
    'description' => 'Link your product to personas or markets',
    'scope' => 'persona',
    'columns' => 12
));