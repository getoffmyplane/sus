<?php
/*
Title: Solution
Order: 50
Flow: Product
*/
?>

    <h3 class="demo-highlight">
        <?php _e('Your customer personas have the following goals, problems, and triggers:','piklist-demo');?>
        <?php _e('Here are some real quotes and common objections:','piklist-demo');?>
        <?php _e('Include simplified solution description, detailed solution, unfair advantage, competition ','piklist-demo');?>
    </h3>

<?php

piklist('include_meta_boxes', array(
    'piklist_meta_product_solution_hypothesis',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

?>