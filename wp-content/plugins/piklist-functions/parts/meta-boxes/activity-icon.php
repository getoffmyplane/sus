<?php
/*
 * Title: Activity Icon
 * Post Type: activity
 * Order: 20
 */

piklist('field', array(
    'type' => 'file',
    'field' => 'activity_icon',
    //'scope' => '',
    'label' => __('Activity Icon'),
    'description' => __('Add an Activity Icon'),
    'options' => array
    (
        'modal_title' => __('Add an icon','piklist'),
        'button' => __('Add','piklist')
    )
));

?>