<?php
/*
Title: Hypothesis
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

// Show top idea from brainstorm for reference

/*
 * Description - what it is - concise description
 */

piklist('field', array(
    'type' => 'textarea',
    'label' => __('Product Description'),
    'field' => 'product_description',
    'columns' => 12
    )
);

/*
 * Design - sketch, storyboard
 */

piklist('field', array(
    'type' => 'group',
    'label' => 'Product Design',
    'description' => 'Detail or upload your product design. Detail what it will look like and how it will work',
    'fields' => array(
        array(
            'type' => 'editor',
            'field' => 'product_design_user_input',
            'columns' => 10
        ),
        array(
            'type' => 'file',
            'field' => 'product_design_uploaded_file',
            'scope' => 'post_meta',
            'label' => 'Upload design(s)',
            'options' => array(
                'modal_title' => __('Upload Design(s)', 'piklist'),
                'button' => __('Upload', 'piklist')
            ),
            'columns' => 2
        )
    )
));

/*
 * Build - Features & timeline
 * The user should list out the features of their product.
 * Then they should add a timeline & skills required to deliver for each one (thus turning each feature into a milestone).
 */

piklist('field', array(
        'type' => 'text',
        'label' => __('Product Features'),
        'field' => 'product_features',
        'add_more' => 'true',
        'columns' => 12
    )
);

/*
 * Release criteria - what must the product be able to do in order for it to be acceptable to release
 */

piklist('field', array(
        'type' => 'text',
        'label' => __('Release Criteria'),
        'field' => 'product_release_criteria',
        'add_more' => 'true',
        'columns' => 12
    )
);

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));

/*
 * Hypothesis - this is the idea you wish to use
 */

//$product_hypothesis_connected_ideas = display_product_hypothesis_connected_ideas();
//
//piklist('field', array
//    (
//        'type' => 'select',
//        'field' => 'product_hypothesis',
//        'label' => __('Select your favourite idea to base your product or service on'),
//        'choices' => $product_hypothesis_connected_ideas,
//        'columns' => 12
//    )
//);

//display product hypothesis connected ideas in select field
//function display_product_hypothesis_connected_ideas()
//{
//    //get the id of the current post to use in the $args
//    $this_post_id = get_the_ID();
//    $product_ideas = get_post_meta($this_post_id, 'product_ideas', true);
//
//    if (is_array($product_ideas))
//    {
//        foreach ($product_ideas as $goal_index):
//            if (is_array($goal_index))
//            {
//                foreach ($goal_index as $product_idea):
//                endforeach;
//            }
//        endforeach;
//    }
//
//    print_r($product_ideas);
//    //print_r($goal_index);
//    //print_r($product_idea);
//    return $product_idea;
//}