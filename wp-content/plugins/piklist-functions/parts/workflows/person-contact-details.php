<?php
/*
Title: Contact Details
Order: 20
Flow: Person
*/

piklist('include_meta_boxes', array(
    'piklist_meta_person_contact_details',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

?>