<?php
/*
Title: Problem
Order: 20
Flow: Product
*/
?>

    <h3 class="demo-highlight">
        <?php _e('Your customer personas have the following goals, problems, and triggers:','piklist-demo');?>
        <?php display_persona_goals(); ?>
        <?php _e('Here are some real quotes and common objections:','piklist-demo');?>
        <?php display_persona_quotes(); ?>
    </h3>

<?php

//Problem Insights
piklist('include_meta_boxes', array(
    'piklist_meta_product_problem_insights',
    'piklist_meta_strategy_pane'
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Workflow Tab'
));

//display persona goals
function display_persona_goals()
{
    //get the id of the current post to use in the $args
    $this_post_id = get_the_ID();

    //get related personas
    $related_posts = get_posts(array(
        'post_type' => 'persona' // Set post type you are relating to.
        ,'posts_per_page' => -1
        ,'post_belongs' => $this_post_id
        //,'post_status' => 'publish'
        ,'suppress_filters' => false // This must be set to false
    ));

    if ( is_array($related_posts))
    {
        foreach ($related_posts as $related_post):
            //persona goals
            $persona_goals = get_post_meta($related_post->ID, 'persona_goals', true);
            if (is_array($persona_goals))
            {
                foreach ($persona_goals as $goal_index):
                    if (is_array($goal_index))
                    {
                        foreach ($goal_index as $persona_goal):
                            echo '<p>GOAL: ' . $persona_goal . '</p>';
                        endforeach;
                    }
                endforeach;
            }

            //persona challenges
            $persona_challenges = get_post_meta($related_post->ID, 'persona_challenges', true);
            if (is_array($persona_challenges)) {
                foreach ($persona_challenges as $goal_index):
                    if (is_array($goal_index)) {
                        foreach ($goal_index as $persona_challenge):
                            echo '<p>CHALLENGE: ' . $persona_challenge . '</p>';
                        endforeach;
                    }
                endforeach;
            }

            //persona triggers
            $persona_triggers = get_post_meta($related_post->ID, 'persona_triggers', true);
            if (is_array($persona_triggers)) {
                foreach ($persona_triggers as $goal_index):
                    if (is_array($goal_index)) {
                        foreach ($goal_index as $persona_trigger):
                            echo '<p>TRIGGER: ' . $persona_trigger . '</p>';
                        endforeach;
                    }
                endforeach;
            }
        endforeach;
    }
}

//display persona quotes
function display_persona_quotes()
{
    //get the id of the current post to use in the $args
    $this_post_id = get_the_ID();

    //get related personas
    $related_posts = get_posts(array(
        'post_type' => 'persona' // Set post type you are relating to.
    ,'posts_per_page' => -1
    ,'post_belongs' => $this_post_id
        //,'post_status' => 'publish'
    ,'suppress_filters' => false // This must be set to false
    ));

    if ( is_array($related_posts))
    {
        foreach ($related_posts as $related_post):

            //persona quotes
            $persona_quotes = get_post_meta($related_post->ID, 'persona_quotes', true);
            if (is_array($persona_quotes)) {
                foreach ($persona_quotes as $goal_index):
                    if (is_array($goal_index)) {
                        foreach ($goal_index as $persona_quote):
                            echo '<p>QUOTE: "' . $persona_quote . '"</p>';
                        endforeach;
                    }
                endforeach;
            }

            //persona objections
            $persona_objections = get_post_meta($related_post->ID, 'persona_objections', true);
            if (is_array($persona_objections)) {
                foreach ($persona_objections as $goal_index):
                    if (is_array($goal_index)) {
                        foreach ($goal_index as $persona_objection):
                            echo '<p>OBJECTION: "' . $persona_objection . '"</p>';
                        endforeach;
                    }
                endforeach;
            }

        endforeach;
    }
}

?>

