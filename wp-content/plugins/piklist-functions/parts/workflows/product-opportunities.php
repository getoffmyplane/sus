<?php
/*
Title: Opportunities
Order: 30
Flow: Product
*/
?>

    <h3 class="demo-highlight">
        <?php _e('Your insights into the current situation:','piklist-demo');?>
        <?php display_insights(); ?>
    </h3>

<?php

piklist('include_meta_boxes', array(
    'piklist_meta_product_opportunities_opportunities'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

//display insights
function display_insights()
{
    //get the id of the current post to use in the $args
    $this_post_id = get_the_ID();
    $product_insights = get_post_meta($this_post_id, 'product_insights', true);

    if (is_array($product_insights))
    {
        foreach ($product_insights as $goal_index):
            if (is_array($goal_index))
            {
                foreach ($goal_index as $product_insight):
                    echo '<p>INSIGHT: ' . $product_insight . '</p>';
                endforeach;
            }
        endforeach;
    }

}

?>