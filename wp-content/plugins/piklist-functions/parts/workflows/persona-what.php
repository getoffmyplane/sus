<?php
/*
Title: What?
Order: 20
Flow: Persona
*/

piklist('include_meta_boxes', array(
    'piklist_meta_persona_what_goals',
    'piklist_meta_persona_what_challenges',
    'piklist_meta_persona_what_triggers',
    'piklist_meta_persona_what_solutions',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

?>