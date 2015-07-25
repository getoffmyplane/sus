<?php
/*
Title: Customer Insights
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
 * Insights
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'product_insights',
    'label' => __('Customer Insights'),
    'description' => 'What insights do you have about your customer and their problem (the current situation)?',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'textarea',
            'field' => 'product_insight',
            'label' => 'Insight',
            'columns' => 12
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));