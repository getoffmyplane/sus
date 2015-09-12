<?php
/*
Title: What can we do?
Post Type: persona
Order: 40
*/

// Any field with the scope set to the field name of the upload field will be treated as related
// data to the upload. Below we see we are setting the post_status and post_title, where the
// post_status is pulled dynamically on page load, hence the current status of the content is
// applied. Have fun! ;)
//
// NOTE: If the post_status of an attachment is anything but inherit or private it will NOT be
// shown on the Media page in the admin, but it is in the database and can be found using query_posts
// or get_posts or get_post etc....

/*
 * Solutions field
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'persona_solutions',
    'label' => __('What can we do?'),
    'description' => '...to help our persona achieve their goal(s)...to help our persona overcome their challenge(s)',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'persona_solution',
            'label' => 'Solution',
            'columns' => 12
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));