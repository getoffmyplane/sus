<?php
/*
Title: Messaging
Post Type: persona
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
        <?php _e('Your linked product has the following benefits and features','piklist-demo');?>
    </h3>

<?php

/*
 * Marketing Message
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'persona_marketing_messages',
    'label' => __('Marketing Message'),
    'description' => 'How should you describe your solution to your persona?',
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'persona_marketing_message',
            'columns' => 12
        )
    )
));

/*
 * Elevator Pitch (Unique Value Proposition)
 */

piklist('field', array(
    'type' => 'group',
    'field' => 'persona_elevator_pitches',
    'label' => __('Elevator Pitch'),
    'description' => 'Sell your persona on your solution!',
    'fields' => array(
        array(
            'type' => 'text',
            'field' => 'persona_elevator_pitch',
            'columns' => 12
        )
    )
));

piklist('shared/code-locater', array(
    'location' => __FILE__
,'type' => 'Meta Box'
));