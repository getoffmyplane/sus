<?php
/*
Title: Ideas
Post Type: product-or-service
Order: 10
*/

// Any field with the scope set to the field name of the upload field will be treated as related
// data to the upload. Below we see we are setting the post_status and post_title, where the
// post_status is pulled dynamically on page load, hence the current status of the content is
// applied. Have fun! ;)
//
// NOTE: If the post_status of an attachment is anything but inherit or private it will NOT be
// shown on the Media page in the admin, but it is in the database and can be found using query_posts
// or get_posts or get_post etc....
?>

    <h3 class="demo-highlight">
        <?php _e('Piklist comes standard with two upload fields: Basic and Media. The Media field works just like the standard WordPress media field, while the Basic uploader is great for simple forms.','piklist-demo');?>
        <?php _e('The metabox "look" can be removed to provide a different look.','piklist-demo');?>
    </h3>

<?php

/*
 * Ideas
 */

$product_idea_connected_choices = display_product_idea_connected_choices();
$display_product_idea_goal_relevancy_choices = display_product_idea_goal_relevancy_choices();

piklist('field', array(
    'type' => 'group',
    'field' => 'product_ideas',
    'label' => __('Ideas'),
    'description' => 'Brainstorm ideas that could take advantage of the opportunities that have been identified.',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'textarea',
            'field' => 'product_idea',
            'label' => 'Idea',
            'columns' => 12
        ),
        array(
            'type' => 'range',
            'field' => 'product_idea_novel',
            'label' => 'How novel is your idea?',
            'conditions' => array(
                array(
                    'field' => 'evaluate_idea_bool',
                    'value' => 'no',
                    'compare' => '=='
                )
            ),
            'columns' => 12
        ),
        array(
            'type' => 'range',
            'field' => 'product_idea_understandability',
            'label' => 'How easy is your idea to understand by someone who is not steeped in the process or the topic?',
            'conditions' => array(
                array(
                    'field' => 'evaluate_idea_bool',
                    'value' => 'no',
                    'compare' => '=='
                )
            ),
            'columns' => 12
        ),
        array(
            'type' => 'checkbox',
            'field' => 'product_idea_connected',
            'label' => 'Your idea should meet a real need and solve a real problem - select:',
            'choices' => $product_idea_connected_choices,
            'conditions' => array(
                array(
                    'field' => 'evaluate_idea_bool',
                    'value' => 'no',
                    'compare' => '=='
                )
            ),
            'columns' => 12
        ),
        array(
            'type' => 'checkbox',
            'field' => 'product_idea_goal_relevancy',
            'label' => 'Your idea should be relevant to the original challenge(s) - select a relevant challenge:',
            'choices' => $display_product_idea_goal_relevancy_choices,
            'conditions' => array(
                array(
                    'field' => 'evaluate_idea_bool',
                    'value' => 'no',
                    'compare' => '=='
                )
            ),
            'columns' => 12
        )

    )
));

piklist('field', array(
    'type' => 'radio',
    'field' => 'evaluate_idea_bool',
    'label' => __('Are you ready to evaluate your ideas?'),
    'choices' => array(
            'yes' => 'Yes',
            'no' => 'No'
    )/*,
    'value' => 'no'*/

));


piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));

//display product idea connected choices
function display_product_idea_connected_choices()
{
    //get the id of the current post to use in the $args
    $this_post_id = get_the_ID();

    //get related personas
    $related_posts = get_posts(array(
        'post_type' => 'persona', // Set post type you are relating to.
        'posts_per_page' => -1,
        'post_belongs' => $this_post_id,
        //,'post_status' => 'publish',
        'suppress_filters' => false // This must be set to false
    ));

    if ( is_array($related_posts))
    {
        foreach ($related_posts as $related_post):

            //persona challenges
            $persona_challenges = get_post_meta($related_post->ID, 'persona_challenges', true);
            if (is_array($persona_challenges)) {
                foreach ($persona_challenges as $goal_index):
                endforeach;
            }

        endforeach;
    }
    return $goal_index;
}

//display product idea goal relevancy choices
function display_product_idea_goal_relevancy_choices()
{
    //get the id of the current post to use in the $args
    $this_post_id = get_the_ID();

    //get related personas
    $related_posts = get_posts(array(
        'post_type' => 'persona', // Set post type you are relating to.
        'posts_per_page' => -1,
        'post_belongs' => $this_post_id,
        //,'post_status' => 'publish',
        'suppress_filters' => false // This must be set to false
    ));

    if ( is_array($related_posts))
    {
        foreach ($related_posts as $related_post):

            //persona goals
            $persona_goals = get_post_meta($related_post->ID, 'persona_goals', true);
            if (is_array($persona_goals)) {
                foreach ($persona_goals as $goal_index):
                endforeach;
            }

        endforeach;
    }
    return $goal_index;
}

?>

