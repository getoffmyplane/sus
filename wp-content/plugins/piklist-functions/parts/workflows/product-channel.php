<?php
/*
Title: Channel
Order: 70
Flow: Product
*/
?>

    <h3 class="demo-highlight">
        <?php _e('Your customer personas have the following goals, problems, and triggers:','piklist-demo');?>
        <?php _e('Here are some real quotes and common objections:','piklist-demo');?>
    </h3>

<?php

piklist('include_meta_boxes', array(
    'piklist_meta_product_who_personas',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

?>