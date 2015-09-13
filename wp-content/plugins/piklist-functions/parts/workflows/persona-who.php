<?php
/*
Title: Who?
Order: 10
Flow: Persona
Default: true
*/

piklist('include_meta_boxes', array(
    'piklist_meta_persona_who_background',
    'piklist_meta_persona_who_demographics',
    'piklist_meta_persona_who_skills',
    'piklist_meta_persona_who_identifiers',
    'piklist_meta_persona_who_employment',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

?>