<?php
/*
Title: Activity Feed
Order: 10
Flow: Person
Default: true
*/

piklist('include_meta_boxes', array(
    'piklist_meta_person_activity_feed',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

?>