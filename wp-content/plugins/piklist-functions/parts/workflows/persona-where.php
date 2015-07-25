<?php
/*
Title: Where?
Order: 50
Flow: Persona
*/
?>

    <h3 class="demo-highlight">
        <?php _e('The WordPress post editor can be placed anywhere you like, since Piklist treats it like any other field. You can also use multiple editors per page.','piklist-demo');?>
    </h3>

<?php

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