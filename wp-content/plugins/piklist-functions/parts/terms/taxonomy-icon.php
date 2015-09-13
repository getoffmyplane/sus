<?php
/*
Title: Taxonomy Icon
Description: Add icons to taxonomies
Taxonomy: strategy
Locked: false
New: true
Collapse: false
*/

// Let's create a text box field
piklist('field', array(
    'type' => 'file',
    'field' => 'strategy_icon',
    //'scope' => '',
    'label' => __('Strategy Icon'),
    'description' => __('Add a Strategy Icon'),
    'options' => array
    (
        'modal_title' => __('Add an icon','piklist'),
        'button' => __('Add','piklist')
    )
));
?>