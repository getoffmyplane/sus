<?php
/*
 * Title: Assign Persona to Group(s)
 * Post Type: persona
 */

//Relate to groups

piklist('field', array(
    'type' => 'post-relate',
    'field' => 'group_memberships',
    'label' => __('Groups'),
    'description' => 'Add this person to groups such as markets, etc.',
    'scope' => 'group'
));