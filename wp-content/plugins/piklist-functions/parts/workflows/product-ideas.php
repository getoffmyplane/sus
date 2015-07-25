<?php
/*
Title: Ideas
Order: 40
Flow: Product
*/
?>

    <h3 class="demo-highlight">
        <?php _e('Opportunities identifed:','piklist-demo');?>
        <?php display_opportunities(); ?>
    </h3>

<?php

piklist('include_meta_boxes', array(
    'piklist_meta_product_ideas_ideas',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

//display insights
function display_opportunities()
{
    //get the id of the current post to use in the $args
    $this_post_id = get_the_ID();
    $product_opportunities = get_post_meta($this_post_id, 'product_opportunities', true);

    if (is_array($product_opportunities))
    {
        foreach ($product_opportunities as $goal_index):
            if (is_array($goal_index))
            {
                foreach ($goal_index as $product_opportunity):
                    echo '<p>OPPORTUNITY: ' . $product_opportunity . '</p>';
                endforeach;
            }
        endforeach;
    }

}

?>