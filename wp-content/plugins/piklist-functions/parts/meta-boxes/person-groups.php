<?php
/*
 * Title: Groups & Markets
 * Post Type: person
 */

piklist('field', array(
    'type' => 'post-relate',
    'field' => 'group_memberships',
    'label' => __('Group Memberships'),
    'description' => 'Add this person to groups such as businesses, social groups, markets, etc. Use + / - to add or remove groups',
    'scope' => 'group'
));