<?php
/*
 * Title: Activity Feed
 * Post Type: person
 * Context: normal
 * Priority: high
 * Order: 1
 */

piklist('field', array(
    'type' => 'html',
    'label' => 'Business Contact Activity',
    'field' => 'my_field_name', // 'field' is only required for a settings page.
    'description' => 'Allows you to view the current activities and history of the person',
    'value' => '<img src="http://localhost/sus/wp-content/uploads/2015/06/coming-soon.jpg" alt="Coming Soon">'
));