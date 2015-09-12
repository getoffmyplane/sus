<?php
/*
Title: Where?
Order: 50
Flow: Persona
*/

piklist('include_meta_boxes', array(
    'piklist_meta_persona_where_markets',
    'piklist_meta_persona_where_contacts',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

?>