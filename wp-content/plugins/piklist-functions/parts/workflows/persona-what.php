<?php
/*
Title: What?
Order: 20
Flow: Persona
*/
?>

    <h3 class="demo-highlight">
        <?php _e('The WordPress post editor can be placed anywhere you like, since Piklist treats it like any other field. You can also use multiple editors per page.','piklist-demo');?>
    </h3>

<?php

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