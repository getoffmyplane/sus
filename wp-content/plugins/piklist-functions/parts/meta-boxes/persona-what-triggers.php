<?php
/*
Title: Triggers
Post Type: persona
Order: 30
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
 * Triggers field
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'persona_triggers',
    'label' => __('Triggers'),
    'description' => 'What event would cause your persona to require your product or service? 1 or 2 triggers is ideal, try to keep them down to 5 or less',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'textarea',
            'field' => 'persona_trigger',
            'label' => 'Trigger',
            'columns' => 12
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));