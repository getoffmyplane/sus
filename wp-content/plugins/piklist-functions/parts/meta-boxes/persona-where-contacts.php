<?php
/*
 * Title: Link to your contacts
 * Post Type: persona
 */

piklist('field', array(
    'type' => 'post-relate',
    'field' => 'contacts',
    'label' => __('Link to Contacts'),
    'description' => 'Link your persona to your real, live contacts. Use + / - to add or remove contacts',
    'scope' => 'person'
));